<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\TicketMaintenance;
use App\TicketHistorique;
use App\Prestataire;
use App\Maison;
use App\Chambre;
use App\Locataire;
use Carbon\Carbon;

class TicketMaintenanceController extends Controller
{
    // ─── Liste ────────────────────────────────────────────────────────────────

    public function index()
    {
        $idannexe_ref = get_active_annexe_id();

        $tickets = TicketMaintenance::with(['maison', 'chambre', 'locataire', 'prestataire'])
            ->whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderByDesc('created_at')
            ->get();

        // Données pour le formulaire de création
        $maisons = Maison::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderBy('nom_maison')->get();

        $prestataires = Prestataire::whereNull('delete_at')
            ->where('actif', true)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->orderBy('nom')->get();

        // Stats
        $stats = [
            'total'    => $tickets->count(),
            'nouveau'  => $tickets->where('statut', 'nouveau')->count(),
            'en_cours' => $tickets->where('statut', 'en_cours')->count(),
            'urgents'  => $tickets->where('priorite', 'urgente')->whereIn('statut', ['nouveau', 'en_cours'])->count(),
        ];

        return view('maintenance.index', compact('tickets', 'maisons', 'prestataires', 'stats'));
    }

    // ─── Création ─────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre'        => 'required|string|max:255',
            'maison_id'    => 'required|integer',
            'categorie'    => 'required|in:plomberie,electricite,serrurerie,menuiserie,climatisation,maconnerie,peinture,autre',
            'priorite'     => 'required|in:basse,normale,haute,urgente',
            'date_ouverture'=> 'required|date',
        ], [
            'titre.required'         => 'Le titre du ticket est obligatoire.',
            'maison_id.required'     => 'Veuillez sélectionner un logement.',
            'categorie.required'     => 'La catégorie est obligatoire.',
            'priorite.required'      => 'La priorité est obligatoire.',
            'date_ouverture.required'=> 'La date d\'ouverture est obligatoire.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()]);
        }

        $idannexe_ref = get_active_annexe_id() ?? Auth::user()->idannexe_ref;

        $ticket = TicketMaintenance::create([
            'iddirection_ref' => Auth::user()->iddirection_ref,
            'idannexe_ref'    => $idannexe_ref,
            'maison_id'       => $request->maison_id,
            'chambre_id'      => $request->chambre_id ?: null,
            'locataire_id'    => $request->locataire_id ?: null,
            'titre'           => $request->titre,
            'description'     => $request->description,
            'categorie'       => $request->categorie,
            'priorite'        => $request->priorite,
            'statut'          => 'nouveau',
            'date_ouverture'  => $request->date_ouverture,
            'created_by'      => Auth::id(),
        ]);

        // Historique ouverture
        TicketHistorique::create([
            'ticket_id'   => $ticket->id,
            'statut_avant'=> null,
            'statut_apres'=> 'ouvert',
            'commentaire' => 'Ticket créé.',
            'user_id'     => Auth::id(),
        ]);

        $ticket->load(['maison', 'chambre']);

        return response()->json([
            'status'  => true,
            'message' => 'Ticket créé avec succès.',
            'ticket'  => [
                'id'             => $ticket->id,
                'titre'          => $ticket->titre,
                'description'    => $ticket->description,
                'statut'         => $ticket->statut,
                'priorite'       => $ticket->priorite,
                'priorite_badge' => $ticket->priorite_badge,
                'statut_badge'   => $ticket->statut_badge,
                'categorie_label'=> $ticket->categorie_label,
                'categorie_icon' => $ticket->categorie_icon,
                'maison_nom'     => $ticket->maison->nom_maison ?? '–',
                'chambre_numero' => $ticket->chambre->numero_chambre ?? null,
                'date_ouverture' => $ticket->date_ouverture->format('d/m/Y'),
                'show_url'       => route('maintenance.show', $ticket->id),
            ],
        ]);
    }

    // ─── Détail ───────────────────────────────────────────────────────────────

    public function show($id)
    {
        $ticket = TicketMaintenance::with([
            'maison', 'chambre', 'locataire', 'prestataire',
            'historiques.user', 'createdBy',
        ])
        ->whereNull('delete_at')
        ->where('iddirection_ref', Auth::user()->iddirection_ref)
        ->findOrFail($id);

        $prestataires = Prestataire::whereNull('delete_at')
            ->where('actif', true)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->orderBy('nom')->get();

        return view('maintenance.show', compact('ticket', 'prestataires'));
    }

    // ─── Changer statut (AJAX) ────────────────────────────────────────────────

    public function changerStatut(Request $request)
    {
        $ticket = TicketMaintenance::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $suivant = $ticket->statutSuivant();
        if (!$suivant) {
            return response()->json(['status' => false, 'message' => 'Ce ticket est déjà clôturé.']);
        }

        $avant  = $ticket->statut;
        $dates  = [];

        if ($suivant === 'en_cours') {
            $dates = [];
        } elseif ($suivant === 'resolu') {
            $dates = ['date_resolution' => Carbon::today()];
        } elseif ($suivant === 'cloture') {
            $dates = ['date_cloture' => Carbon::today()];
            if ($request->cout_intervention) {
                $dates['cout_intervention'] = (float) $request->cout_intervention;
                $dates['imputation']        = $request->imputation ?? 'agence';
            }
        }

        $ticket->update(array_merge(['statut' => $suivant], $dates));

        TicketHistorique::create([
            'ticket_id'   => $ticket->id,
            'statut_avant'=> $avant,
            'statut_apres'=> $suivant,
            'commentaire' => $request->commentaire ?? null,
            'user_id'     => Auth::id(),
        ]);

        // Notification locataire si assigné
        $this->notifierLocataire($ticket, $suivant);

        return response()->json([
            'status'       => true,
            'message'      => 'Statut mis à jour : ' . $ticket->fresh()->statut_label,
            'nouveau_statut' => $suivant,
            'statut_badge' => $ticket->fresh()->statut_badge,
        ]);
    }

    // ─── Affecter prestataire (AJAX) ──────────────────────────────────────────

    public function affecter(Request $request)
    {
        $ticket = TicketMaintenance::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $ticket->update(['prestataire_id' => $request->prestataire_id ?: null]);

        $nomPrestataire = $ticket->fresh()->prestataire->nom ?? '–';

        TicketHistorique::create([
            'ticket_id'   => $ticket->id,
            'statut_avant'=> $ticket->statut,
            'statut_apres'=> $ticket->statut,
            'commentaire' => $request->prestataire_id
                ? 'Prestataire affecté : ' . $nomPrestataire
                : 'Prestataire retiré.',
            'user_id'     => Auth::id(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => $request->prestataire_id
                ? 'Prestataire ' . $nomPrestataire . ' affecté.'
                : 'Prestataire retiré.',
            'nom_prestataire' => $nomPrestataire,
        ]);
    }

    // ─── Suppression (soft delete) ────────────────────────────────────────────

    public function destroy(Request $request)
    {
        $ticket = TicketMaintenance::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $ticket->update(['delete_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Ticket supprimé.']);
    }

    // ─── Chambres d'une maison (AJAX) ────────────────────────────────────────

    public function chambresParMaison(Request $request)
    {
        $chambres = Chambre::where('maison_id', $request->maison_id)
            ->whereNull('delete_at')
            ->orderBy('numero_chambre')
            ->get(['id', 'numero_chambre']);

        return response()->json(['status' => true, 'chambres' => $chambres]);
    }

    // ─── Locataire d'une chambre (AJAX) ──────────────────────────────────────

    public function locataireParChambre(Request $request)
    {
        $locataire = Locataire::whereNull('delete_at')
            ->where('chambre_id', $request->chambre_id)
            ->where('status', true)
            ->first(['id', 'nom', 'prenom', 'telephone', 'email']);

        return response()->json(['status' => (bool) $locataire, 'locataire' => $locataire]);
    }

    // ─── Notification locataire interne ──────────────────────────────────────

    private function notifierLocataire(TicketMaintenance $ticket, string $nouveauStatut)
    {
        if (!$ticket->locataire) return;

        $messages = [
            'en_cours' => "Votre signalement \"{$ticket->titre}\" est en cours de traitement.",
            'resolu'   => "Votre signalement \"{$ticket->titre}\" a été résolu.",
            'cloture'  => "Votre signalement \"{$ticket->titre}\" est clôturé.",
        ];

        $message = $messages[$nouveauStatut] ?? null;
        if (!$message) return;

        // Email
        if (!empty($ticket->locataire->email)) {
            try {
                Mail::raw($message, function ($m) use ($ticket) {
                    $m->to($ticket->locataire->email, $ticket->locataire->nom)
                      ->subject('Mise à jour de votre signalement – ' . config('app.name'));
                });
            } catch (\Exception $e) {}
        }

        // SMS (Africa's Talking)
        if (!empty($ticket->locataire->telephone)) {
            try {
                $sms = app(\App\Services\AfricasTalkingService::class);
                $sms->envoyerSMS($ticket->locataire->telephone, $message);
            } catch (\Exception $e) {}
        }
    }
}
