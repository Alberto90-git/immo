<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $plans = Plan::whereNull('delete_at')->orderBy('prix_annuel')->get();
        return view('super-admin.config_plans', compact('plans'));
    }

    /**
     * Met à jour un plan individuel (appelé en AJAX)
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idplan'               => 'required|exists:plans,idplan',
            'nom'                  => 'required|string|max:100',
            'description'          => 'nullable|string|max:500',
            'max_maisons'          => 'nullable|integer|min:0',
            'max_annexes'          => 'nullable|integer|min:0',
            'max_envois_email'     => 'nullable|integer|min:0',
            'max_envois_whatsapp'  => 'nullable|integer|min:0',
            'max_rappels_loyer'    => 'nullable|integer|min:0',
            'max_preavis'          => 'nullable|integer|min:0',
            'max_publicites'       => 'nullable|integer|min:0',
            'prix_annuel'          => 'required|numeric|min:0',
            'prix_mensuel'         => 'required|numeric|min:0',
            'is_active'            => 'required|boolean',
            'sms_enabled'          => 'required|boolean',
            'whatsapp_enabled'     => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ], 422);
        }

        $plan = Plan::find($request->idplan);

        $plan->update([
            'nom'                  => $request->nom,
            'description'          => $request->description,
            'max_maisons'          => $request->max_maisons !== '' ? $request->max_maisons : null,
            'max_annexes'          => $request->max_annexes !== '' ? $request->max_annexes : null,
            'max_envois_email'     => $request->max_envois_email !== '' ? $request->max_envois_email : null,
            'max_envois_whatsapp'  => $request->max_envois_whatsapp !== '' ? $request->max_envois_whatsapp : null,
            'max_rappels_loyer'    => $request->max_rappels_loyer !== '' ? $request->max_rappels_loyer : null,
            'max_preavis'          => $request->max_preavis !== '' ? $request->max_preavis : null,
            'max_publicites'       => $request->max_publicites !== '' ? $request->max_publicites : null,
            'prix_annuel'          => $request->prix_annuel,
            'prix_mensuel'         => $request->prix_mensuel,
            'is_active'            => (bool) $request->is_active,
            'sms_enabled'          => (bool) $request->sms_enabled,
            'whatsapp_enabled'     => (bool) $request->whatsapp_enabled,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Plan « {$plan->nom} » mis à jour.",
        ]);
    }

    /**
     * Ajoute un nouveau plan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'                  => 'required|string|max:100',
            'code'                 => 'required|string|max:50|unique:plans,code',
            'description'          => 'nullable|string|max:500',
            'max_maisons'          => 'nullable|integer|min:0',
            'max_annexes'          => 'nullable|integer|min:0',
            'max_envois_email'     => 'nullable|integer|min:0',
            'max_envois_whatsapp'  => 'nullable|integer|min:0',
            'max_rappels_loyer'    => 'nullable|integer|min:0',
            'max_preavis'          => 'nullable|integer|min:0',
            'max_publicites'       => 'nullable|integer|min:0',
            'prix_annuel'          => 'required|numeric|min:0',
            'prix_mensuel'         => 'required|numeric|min:0',
            'is_active'            => 'required|boolean',
            'sms_enabled'          => 'required|boolean',
            'whatsapp_enabled'     => 'required|boolean',
        ], [
            'code.unique' => 'Ce code de plan existe déjà.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ], 422);
        }

        $plan = Plan::create([
            'nom'                  => $request->nom,
            'code'                 => strtolower($request->code),
            'description'          => $request->description,
            'max_maisons'          => $request->max_maisons !== '' ? $request->max_maisons : null,
            'max_annexes'          => $request->max_annexes !== '' ? $request->max_annexes : null,
            'max_envois_email'     => $request->max_envois_email !== '' ? $request->max_envois_email : null,
            'max_envois_whatsapp'  => $request->max_envois_whatsapp !== '' ? $request->max_envois_whatsapp : null,
            'max_rappels_loyer'    => $request->max_rappels_loyer !== '' ? $request->max_rappels_loyer : null,
            'max_preavis'          => $request->max_preavis !== '' ? $request->max_preavis : null,
            'max_publicites'       => $request->max_publicites !== '' ? $request->max_publicites : null,
            'prix_annuel'          => $request->prix_annuel,
            'prix_mensuel'         => $request->prix_mensuel,
            'is_active'            => (bool) $request->is_active,
            'sms_enabled'          => (bool) $request->sms_enabled,
            'whatsapp_enabled'     => (bool) $request->whatsapp_enabled,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Plan « {$plan->nom} » créé.",
            'plan'    => $plan,
        ]);
    }

    /**
     * Supprime (soft delete) un plan
     */
    public function destroy(Request $request)
    {
        $plan = Plan::find($request->idplan);
        if (!$plan) {
            return response()->json(['status' => false, 'message' => 'Plan introuvable.'], 404);
        }
        $plan->update(['delete_at' => now(), 'is_active' => false]);

        return response()->json(['status' => true, 'message' => "Plan « {$plan->nom} » supprimé."]);
    }
}
