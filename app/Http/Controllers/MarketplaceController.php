<?php

namespace App\Http\Controllers;

use App\Publicite;
use App\Direction;
use App\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    /**
     * Page principale : liste + filtres + carte
     */
    public function index(Request $request)
    {
        $query = Publicite::whereNull('delete_at')
            ->whereNotNull('published_at');

        // Filtres
        if ($request->filled('ville')) {
            $query->where('ville', 'ilike', '%' . $request->ville . '%');
        }
        if ($request->filled('quartier')) {
            $query->where('quartier', 'ilike', '%' . $request->quartier . '%');
        }
        if ($request->filled('type_bien')) {
            $query->where('type_bien', $request->type_bien);
        }
        if ($request->filled('budget_min')) {
            $query->whereRaw("CAST(price AS NUMERIC) >= ?", [(int) $request->budget_min]);
        }
        if ($request->filled('budget_max')) {
            $query->whereRaw("CAST(price AS NUMERIC) <= ?", [(int) $request->budget_max]);
        }
        if ($request->filled('superficie_min')) {
            $query->whereRaw("CAST(\"Superficie\" AS NUMERIC) >= ?", [(int) $request->superficie_min]);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('localisation', 'ilike', "%$q%")
                    ->orWhere('description', 'ilike', "%$q%")
                    ->orWhere('ville', 'ilike', "%$q%")
                    ->orWhere('quartier', 'ilike', "%$q%");
            });
        }

        // Sponsorisées en premier
        $query->orderByRaw("CASE WHEN is_sponsored = true AND sponsored_until >= NOW() THEN 0 ELSE 1 END")
              ->orderBy('published_at', 'desc');

        $annonces   = $query->paginate(12)->withQueryString();

        // Pour la carte : toutes les annonces avec coordonnées (sans pagination)
        $forMap = Publicite::whereNull('delete_at')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('id','slug','localisation','ville','type_bien','price','image_url','lat','lng','iddirection_ref')
            ->get();

        // Villes distinctes pour autocomplétion
        $villes = Publicite::whereNull('delete_at')
            ->whereNotNull('ville')
            ->distinct()
            ->pluck('ville')
            ->sort()
            ->values();

        // Carte devise par direction (évite les N+1)
        $directionIds = $annonces->pluck('iddirection_ref')->merge($forMap->pluck('iddirection_ref'))->unique()->filter();
        $deviseMap    = Parametre::whereIn('iddirection_ref', $directionIds)
            ->pluck('devise', 'iddirection_ref')
            ->toArray();

        return view('marketplace.index', compact('annonces', 'forMap', 'villes', 'deviseMap'));
    }

    /**
     * Fiche annonce détaillée
     */
    public function show(string $slug)
    {
        $annonce = Publicite::whereNull('delete_at')
            ->where('slug', $slug)
            ->firstOrFail();

        // Annonces similaires (même type / même ville)
        $similaires = Publicite::whereNull('delete_at')
            ->whereNotNull('published_at')
            ->where('id', '!=', $annonce->id)
            ->where(function ($q) use ($annonce) {
                $q->where('type_bien', $annonce->type_bien)
                  ->orWhere('ville', $annonce->ville);
            })
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        // Devise par direction
        $directionIds = collect([$annonce->iddirection_ref])->merge($similaires->pluck('iddirection_ref'))->unique()->filter();
        $deviseMap    = Parametre::whereIn('iddirection_ref', $directionIds)
            ->pluck('devise', 'iddirection_ref')
            ->toArray();

        return view('marketplace.show', compact('annonce', 'similaires', 'deviseMap'));
    }

    /**
     * Formulaire de contact (POST AJAX)
     */
    public function contact(Request $request, string $slug)
    {
        $annonce = Publicite::whereNull('delete_at')
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'nom'     => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'required|string|max:1000',
            'tel'     => 'nullable|string|max:30',
        ]);

        try {
            // Récupérer l'email de la direction
            $direction  = Direction::find($annonce->iddirection_ref);
            $parametre  = Parametre::where('iddirection_ref', $annonce->iddirection_ref)->first();
            $emailCible = ($parametre->email_envoi ?? null) ?: ($direction->email ?? null);

            if ($emailCible) {
                $deviseCode = \App\Parametre::where('iddirection_ref', $annonce->iddirection_ref)
                    ->value('devise') ?? 'XOF';
                Mail::send('marketplace.mail_contact', [
                    'annonce'   => $annonce,
                    'nomCont'   => $request->nom,
                    'emailCont' => $request->email,
                    'telCont'   => $request->tel,
                    'msgCont'   => $request->message,
                    'deviseCode'=> $deviseCode,
                ], function ($m) use ($emailCible, $annonce, $request) {
                    $m->to($emailCible)
                      ->replyTo($request->email, $request->nom)
                      ->subject('Contact depuis le Marketplace — ' . $annonce->localisation);
                });
            }

            return response()->json(['status' => true, 'message' => 'Votre message a bien été envoyé.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Erreur lors de l\'envoi. Réessayez.'], 500);
        }
    }

    /**
     * AJAX : résultats carte (GeoJSON)
     */
    public function mapData(Request $request)
    {
        $annonces = Publicite::whereNull('delete_at')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->whereNotNull('published_at')
            ->get(['id','slug','localisation','ville','type_bien','price','image_url','lat','lng']);

        $mapDeviseMap = Parametre::whereIn('iddirection_ref', $annonces->pluck('iddirection_ref')->unique()->filter())
            ->pluck('devise', 'iddirection_ref')
            ->toArray();

        $features = $annonces->map(function ($a) use ($mapDeviseMap) {
            return [
                'type' => 'Feature',
                'geometry' => ['type' => 'Point', 'coordinates' => [(float)$a->lng, (float)$a->lat]],
                'properties' => [
                    'id'           => $a->id,
                    'slug'         => $a->slug,
                    'localisation' => $a->localisation,
                    'ville'        => $a->ville,
                    'type_bien'    => $a->type_bien,
                    'price'        => $a->price,
                    'devise'       => $mapDeviseMap[$a->iddirection_ref] ?? 'XOF',
                    'image'        => $a->image_url ? asset('storage/' . $a->image_url) : null,
                    'url'          => route('marketplace.show', $a->slug),
                ],
            ];
        });

        return response()->json(['type' => 'FeatureCollection', 'features' => $features]);
    }
}
