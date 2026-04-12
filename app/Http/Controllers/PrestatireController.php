<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Prestataire;

class PrestatireController extends Controller
{
    // ─── Liste (vue) ──────────────────────────────────────────────────────────

    public function index()
    {
        $idannexe_ref = get_active_annexe_id();

        $prestataires = Prestataire::withCount(['tickets as tickets_actifs' => function ($q) {
                $q->whereNull('delete_at')->whereNotIn('statut', ['cloture']);
            }])
            ->whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderBy('nom')
            ->get();

        return view('maintenance.prestataires', compact('prestataires'));
    }

    // ─── Créer (AJAX) ─────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'        => 'required|string|max:255',
            'specialite' => 'required|in:plomberie,electricite,serrurerie,menuiserie,climatisation,maconnerie,peinture,autre',
            'telephone'  => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:255',
        ], [
            'nom.required'        => 'Le nom du prestataire est obligatoire.',
            'specialite.required' => 'La spécialité est obligatoire.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()]);
        }

        $idannexe_ref = get_active_annexe_id() ?? Auth::user()->idannexe_ref;

        $prestataire = Prestataire::create([
            'iddirection_ref' => Auth::user()->iddirection_ref,
            'idannexe_ref'    => $idannexe_ref,
            'nom'             => $request->nom,
            'specialite'      => $request->specialite,
            'telephone'       => $request->telephone,
            'email'           => $request->email,
            'adresse'         => $request->adresse,
            'actif'           => true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Prestataire ajouté avec succès.',
            'prestataire' => [
                'id'              => $prestataire->id,
                'nom'             => $prestataire->nom,
                'specialite_badge'=> $prestataire->specialite_badge,
                'specialite_icon' => $prestataire->specialite_icon,
                'telephone'       => $prestataire->telephone ?? '–',
                'email'           => $prestataire->email ?? '–',
                'adresse'         => $prestataire->adresse ?? '–',
            ],
        ]);
    }

    // ─── Modifier (AJAX) ──────────────────────────────────────────────────────

    public function update(Request $request)
    {
        $prestataire = Prestataire::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $validator = Validator::make($request->all(), [
            'nom'        => 'required|string|max:255',
            'specialite' => 'required|in:plomberie,electricite,serrurerie,menuiserie,climatisation,maconnerie,peinture,autre',
            'telephone'  => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()->toArray()]);
        }

        $prestataire->update([
            'nom'        => $request->nom,
            'specialite' => $request->specialite,
            'telephone'  => $request->telephone,
            'email'      => $request->email,
            'adresse'    => $request->adresse,
            'actif'      => (bool) $request->actif,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Prestataire modifié.',
            'prestataire' => [
                'id'              => $prestataire->id,
                'nom'             => $prestataire->nom,
                'specialite_badge'=> $prestataire->specialite_badge,
                'specialite_icon' => $prestataire->specialite_icon,
                'specialite'      => $prestataire->specialite,
                'telephone'       => $prestataire->telephone ?? '–',
                'email'           => $prestataire->email ?? '–',
                'adresse'         => $prestataire->adresse ?? '–',
                'actif'           => $prestataire->actif,
            ],
        ]);
    }

    // ─── Supprimer (AJAX) ─────────────────────────────────────────────────────

    public function destroy(Request $request)
    {
        $prestataire = Prestataire::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $prestataire->update(['delete_at' => now()]);

        return response()->json(['status' => true, 'message' => 'Prestataire supprimé.']);
    }
}
