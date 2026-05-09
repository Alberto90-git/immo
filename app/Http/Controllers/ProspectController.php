<?php

namespace App\Http\Controllers;

use App\Prospect;
use App\VisiteProspect;
use App\PreReservation;
use App\Maison;
use App\Chambre;
use App\Locataire;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProspectController extends Controller
{
    // ── Pipeline ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $dirId      = Auth::user()->iddirection_ref;
        $annexeId   = get_active_annexe_id();

        $query = Prospect::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->when($annexeId, fn($q) => $q->where('idannexe_ref', $annexeId))
            ->when(!Gate::allows('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->with(['maison', 'chambre', 'preReservationActive'])
            ->orderByDesc('updated_at');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nom', 'ilike', "%$s%")
                  ->orWhere('prenom', 'ilike', "%$s%")
                  ->orWhere('telephone', 'ilike', "%$s%");
            });
        }

        $prospects = $query->get();

        // Grouper par statut pour le kanban
        $pipeline = $prospects->groupBy('statut');

        // Chambres libres (non occupées + pas de pré-réservation active)
        $preReservChambresIds = PreReservation::where('iddirection_ref', $dirId)
            ->where('statut', 'active')
            ->pluck('chambre_id')
            ->toArray();

        $chambresLibres = Chambre::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->where('etat', false)
            ->whereNotIn('id', $preReservChambresIds)
            ->when($annexeId, fn($q) => $q->where('idannexe_ref', $annexeId))
            ->when(!Gate::allows('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->with('maison')
            ->get();

        $maisons = Maison::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->when($annexeId, fn($q) => $q->where('idannexe_ref', $annexeId))
            ->when(!Gate::allows('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->get();

        $agents = User::where('iddirection_ref', $dirId)->get();

        $totalFraisVisites = VisiteProspect::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->sum('frais_visite');

        return view('prospects.index', compact(
            'prospects', 'pipeline', 'chambresLibres', 'maisons', 'agents', 'totalFraisVisites'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'       => 'required|string|max:100',
            'prenom'    => 'nullable|string|max:100',
            'telephone' => 'required|string|max:20',
            'email'     => 'nullable|email|max:150',
            'maison_id' => 'nullable|integer',
            'chambre_id'=> 'nullable|integer',
            'note'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $dirId    = Auth::user()->iddirection_ref;
        $annexeId = get_active_annexe_id() ?? Auth::user()->idannexe_ref;

        $prospect = Prospect::create([
            'iddirection_ref' => $dirId,
            'idannexe_ref'    => $annexeId,
            'nom'             => $request->nom,
            'prenom'          => $request->prenom,
            'telephone'       => $request->telephone,
            'email'           => $request->email,
            'maison_id'       => $request->maison_id ?: null,
            'chambre_id'      => $request->chambre_id ?: null,
            'note'            => $request->note,
            'statut'          => 'demande',
            'source'          => 'agent',
            'agent_id'        => Auth::id(),
        ]);

        activity()->performedOn($prospect)
            ->causedBy(Auth::user())
            ->log('Ajout prospect ' . $prospect->getNomComplet() . ' par ' . Auth::user()->nom);

        return response()->json([
            'status'  => true,
            'message' => __('messages.prospect_created'),
            'data'    => $prospect->load(['maison', 'chambre']),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'        => 'required|integer',
            'nom'       => 'required|string|max:100',
            'prenom'    => 'nullable|string|max:100',
            'telephone' => 'required|string|max:20',
            'email'     => 'nullable|email|max:150',
            'maison_id' => 'nullable|integer',
            'chambre_id'=> 'nullable|integer',
            'note'      => 'nullable|string',
            'statut'    => 'required|in:demande,visite_planifiee,visite_effectuee,dossier,converti,annule',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $prospect = Prospect::where('id', $request->id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('delete_at')
            ->firstOrFail();

        $prospect->update([
            'nom'        => $request->nom,
            'prenom'     => $request->prenom,
            'telephone'  => $request->telephone,
            'email'      => $request->email,
            'maison_id'  => $request->maison_id ?: null,
            'chambre_id' => $request->chambre_id ?: null,
            'note'       => $request->note,
            'statut'     => $request->statut,
        ]);

        activity()->performedOn($prospect)
            ->causedBy(Auth::user())
            ->log('Modification prospect ' . $prospect->getNomComplet());

        return response()->json([
            'status'  => true,
            'message' => __('messages.prospect_updated'),
            'data'    => $prospect->fresh(['maison', 'chambre']),
        ]);
    }

    public function updateStatut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required|integer',
            'statut' => 'required|in:demande,visite_planifiee,visite_effectuee,dossier,converti,annule',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $prospect = Prospect::where('id', $request->id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('delete_at')
            ->firstOrFail();

        $prospect->statut = $request->statut;
        $prospect->save();

        return response()->json([
            'status'      => true,
            'message'     => __('messages.prospect_statut_updated'),
            'statut'      => $prospect->statut,
            'statut_label'=> $prospect->getStatutLabel(),
            'badge_class' => $prospect->getStatutBadgeClass(),
        ]);
    }

    public function destroy(Request $request)
    {
        $prospect = Prospect::where('id', $request->id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('delete_at')
            ->firstOrFail();

        $prospect->update(['delete_at' => Carbon::now()]);

        activity()->performedOn($prospect)
            ->causedBy(Auth::user())
            ->log('Suppression prospect ' . $prospect->getNomComplet());

        return response()->json(['status' => true, 'message' => __('messages.prospect_deleted')]);
    }

    // ── Visites ─────────────────────────────────────────────────────────────

    public function storeVisite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prospect_id'  => 'required|integer',
            'chambre_id'   => 'nullable|integer',
            'agent_id'     => 'nullable|integer',
            'date_visite'  => 'required|date',
            'frais_visite' => 'nullable|numeric|min:0',
            'note'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $dirId = Auth::user()->iddirection_ref;

        $prospect = Prospect::where('id', $request->prospect_id)
            ->where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->firstOrFail();

        $visite = VisiteProspect::create([
            'iddirection_ref' => $dirId,
            'idannexe_ref'    => $prospect->idannexe_ref,
            'prospect_id'     => $prospect->id,
            'chambre_id'      => $request->chambre_id ?: null,
            'agent_id'        => $request->agent_id ?: Auth::id(),
            'date_visite'     => $request->date_visite,
            'statut'          => 'planifiee',
            'frais_visite'    => $request->frais_visite ?? 0,
            'note'            => $request->note,
        ]);

        // Avancer le statut du prospect
        if ($prospect->statut === 'demande') {
            $prospect->statut = 'visite_planifiee';
            $prospect->save();
        }

        activity()->performedOn($visite)
            ->causedBy(Auth::user())
            ->log('Visite planifiée pour ' . $prospect->getNomComplet());

        return response()->json([
            'status'  => true,
            'message' => __('messages.visite_created'),
            'data'    => $visite->load('chambre'),
        ]);
    }

    public function updateVisiteStatut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required|integer',
            'statut' => 'required|in:planifiee,effectuee,annulee',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $visite = VisiteProspect::where('id', $request->id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('delete_at')
            ->firstOrFail();

        $visite->statut = $request->statut;
        $visite->save();

        if ($request->statut === 'effectuee') {
            $prospect = $visite->prospect;
            if ($prospect && in_array($prospect->statut, ['demande', 'visite_planifiee'])) {
                $prospect->statut = 'visite_effectuee';
                $prospect->save();
            }
        }

        return response()->json(['status' => true, 'message' => __('messages.visite_updated')]);
    }

    // ── Pré-réservation ──────────────────────────────────────────────────────

    public function storePreReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prospect_id' => 'required|integer',
            'chambre_id'  => 'required|integer',
            'date_debut'  => 'required|date',
            'date_fin'    => 'required|date|after:date_debut',
            'note'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $dirId = Auth::user()->iddirection_ref;

        // Vérifier que la chambre est libre
        $existingActive = PreReservation::where('chambre_id', $request->chambre_id)
            ->where('iddirection_ref', $dirId)
            ->where('statut', 'active')
            ->whereNull('delete_at')
            ->exists();

        if ($existingActive) {
            return response()->json(['status' => false, 'message' => __('messages.prereserv_already_reserved')]);
        }

        $chambre = Chambre::where('id', $request->chambre_id)
            ->where('iddirection_ref', $dirId)
            ->where('etat', false)
            ->first();

        if (!$chambre) {
            return response()->json(['status' => false, 'message' => __('messages.prereserv_room_occupied')]);
        }

        $prospect = Prospect::where('id', $request->prospect_id)
            ->where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->firstOrFail();

        $preResa = PreReservation::create([
            'iddirection_ref' => $dirId,
            'idannexe_ref'    => $prospect->idannexe_ref,
            'prospect_id'     => $prospect->id,
            'chambre_id'      => $request->chambre_id,
            'date_debut'      => $request->date_debut,
            'date_fin'        => $request->date_fin,
            'statut'          => 'active',
            'note'            => $request->note,
        ]);

        activity()->performedOn($preResa)
            ->causedBy(Auth::user())
            ->log('Pré-réservation chambre ' . $chambre->numero_chambre . ' pour ' . $prospect->getNomComplet());

        return response()->json([
            'status'  => true,
            'message' => __('messages.prereserv_created'),
        ]);
    }

    public function annulerPreReservation(Request $request)
    {
        $preResa = PreReservation::where('id', $request->id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->firstOrFail();

        $preResa->statut = 'annulee';
        $preResa->save();

        return response()->json(['status' => true, 'message' => __('messages.prereserv_cancelled')]);
    }

    // ── Conversion prospect → locataire ─────────────────────────────────────

    public function convertir(Request $request, $id)
    {
        $id = decrypt_id($id); abort_if(!$id, 404);
        $prospect = Prospect::where('id', $id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('delete_at')
            ->firstOrFail();

        // Marquer la pré-réservation comme convertie
        PreReservation::where('prospect_id', $id)
            ->where('statut', 'active')
            ->update(['statut' => 'convertie']);

        $prospect->statut = 'converti';
        $prospect->save();

        // Passer les données en session pour pré-remplir le formulaire locataire
        session(['prospect_prefill' => [
            'nom'        => $prospect->nom,
            'prenom'     => $prospect->prenom,
            'telephone'  => $prospect->telephone,
            'email'      => $prospect->email,
            'maison_id'  => $prospect->maison_id,
            'chambre_id' => $prospect->chambre_id,
        ]]);

        activity()->performedOn($prospect)
            ->causedBy(Auth::user())
            ->log('Conversion prospect → locataire : ' . $prospect->getNomComplet());

        return redirect()->route('get_locataireView')
            ->with('success', __('messages.prospect_converted') . ' ' . $prospect->getNomComplet() . '.');
    }

    // ── Calendrier des disponibilités (auth) ────────────────────────────────

    public function calendrier()
    {
        $dirId    = Auth::user()->iddirection_ref;
        $annexeId = get_active_annexe_id();

        $preReservChambresIds = PreReservation::where('iddirection_ref', $dirId)
            ->where('statut', 'active')
            ->pluck('chambre_id')
            ->toArray();

        $maisons = Maison::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->when($annexeId, fn($q) => $q->where('idannexe_ref', $annexeId))
            ->when(!Gate::allows('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->with(['chambres' => function ($q) use ($preReservChambresIds) {
                $q->whereNull('delete_at')->with('locataire');
            }])
            ->get()
            ->map(function ($maison) use ($preReservChambresIds) {
                $maison->chambres->each(function ($chambre) use ($preReservChambresIds) {
                    $chambre->pre_reservee = in_array($chambre->id, $preReservChambresIds);
                });
                return $maison;
            });

        // Visites planifiées pour FullCalendar
        $visites = VisiteProspect::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->where('statut', 'planifiee')
            ->where('date_visite', '>=', Carbon::now()->subDays(7))
            ->with('prospect')
            ->get()
            ->map(fn($v) => [
                'id'    => $v->id,
                'title' => '🏠 ' . ($v->prospect->getNomComplet() ?? ''),
                'start' => $v->date_visite->toIso8601String(),
                'color' => '#f59e0b',
                'url'   => '#',
            ]);

        return view('prospects.calendrier', compact('maisons', 'visites'));
    }

    // ── Agenda des visites (auth) ────────────────────────────────────────────

    public function agenda()
    {
        $dirId    = Auth::user()->iddirection_ref;
        $annexeId = get_active_annexe_id();

        $visites = VisiteProspect::where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->when($annexeId, fn($q) => $q->where('idannexe_ref', $annexeId))
            ->when(!Gate::allows('Is_admin'), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->with(['prospect', 'chambre'])
            ->orderBy('date_visite', 'desc')
            ->get();

        $totalFrais = $visites->sum('frais_visite');

        // JSON pour FullCalendar
        $events = $visites->map(fn($v) => [
            'id'    => $v->id,
            'title' => ($v->prospect->getNomComplet() ?? '?') . ($v->chambre ? ' – ' . $v->chambre->numero_chambre : ''),
            'start' => $v->date_visite->toIso8601String(),
            'color' => match($v->statut) {
                'planifiee' => '#f59e0b',
                'effectuee' => '#22c55e',
                'annulee'   => '#ef4444',
                default     => '#6b7280',
            },
        ]);

        return view('prospects.agenda', compact('visites', 'totalFrais', 'events'));
    }

    // ── Formulaire public (demande de location) ──────────────────────────────

    public function storePublic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'           => 'required|string|max:100',
            'prenom'        => 'nullable|string|max:100',
            'telephone'     => 'required|string|max:20',
            'email'         => 'nullable|email|max:150',
            'maison_id'     => 'nullable|integer',
            'message'       => 'nullable|string|max:1000',
            'direction_ref' => 'required|exists:directions,iddirection',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Prospect::create([
            'iddirection_ref' => $request->direction_ref,
            'idannexe_ref'    => null,
            'nom'             => $request->nom,
            'prenom'          => $request->prenom,
            'telephone'       => $request->telephone,
            'email'           => $request->email,
            'maison_id'       => $request->maison_id ?: null,
            'message'         => $request->message,
            'statut'          => 'demande',
            'source'          => 'public',
        ]);

        return back()->with('prospect_success', __('messages.prospect_public_success'));
    }

    // ── API : chambres d'une maison pour les selects ────────────────────────

    public function getChambresMaison(Request $request)
    {
        $dirId = Auth::user()->iddirection_ref;

        $preReservChambresIds = PreReservation::where('iddirection_ref', $dirId)
            ->where('statut', 'active')
            ->pluck('chambre_id')
            ->toArray();

        $chambres = Chambre::where('maison_id', $request->maison_id)
            ->where('iddirection_ref', $dirId)
            ->whereNull('delete_at')
            ->where('etat', false)
            ->whereNotIn('id', $preReservChambresIds)
            ->get(['id', 'numero_chambre', 'type_chambre', 'prix_chambre']);

        return response()->json(['status' => true, 'data' => $chambres]);
    }
}
