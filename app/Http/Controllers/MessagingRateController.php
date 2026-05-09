<?php

namespace App\Http\Controllers;

use App\MessagingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessagingRateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * GET super-admin/messaging-rates → vue avec toutes les rates
     */
    public function index()
    {
        if (!Auth::user()->can('config-paiement')) {
            abort(403);
        }
        $rates = MessagingRate::orderBy('country_name')->get();
        return view('super-admin.messaging_rates', compact('rates'));
    }

    /**
     * POST super-admin/messaging-rates → créer
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('config-paiement')) {
            return response()->json(['status' => false, 'message' => 'Accès refusé.'], 403);
        }

        $v = Validator::make($request->all(), [
            'country_code'       => 'required|string|max:5|unique:messaging_rates,country_code',
            'country_name'       => 'required|string|max:100',
            'sms_unit_cost'      => 'required|numeric|min:0',
            'whatsapp_unit_cost' => 'required|numeric|min:0',
            'currency'           => 'required|string|max:10',
        ]);

        if ($v->fails()) {
            return response()->json(['status' => false, 'message' => implode(' ', $v->errors()->all())]);
        }

        $rate = MessagingRate::create($request->only([
            'country_code', 'country_name', 'sms_unit_cost', 'whatsapp_unit_cost', 'currency',
        ]));

        return response()->json(['status' => true, 'message' => 'Tarif créé.', 'rate' => $rate]);
    }

    /**
     * PUT super-admin/messaging-rates/{id} → modifier
     */
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('config-paiement')) {
            return response()->json(['status' => false, 'message' => 'Accès refusé.'], 403);
        }
        $id = decrypt_id($id); abort_if(!$id, 404);
        $rate = MessagingRate::findOrFail($id);

        $v = Validator::make($request->all(), [
            'country_name'       => 'required|string|max:100',
            'sms_unit_cost'      => 'required|numeric|min:0',
            'whatsapp_unit_cost' => 'required|numeric|min:0',
            'currency'           => 'required|string|max:10',
            'is_default'         => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            return response()->json(['status' => false, 'message' => implode(' ', $v->errors()->all())]);
        }

        if ($request->boolean('is_default')) {
            MessagingRate::where('id', '!=', $id)->update(['is_default' => false]);
        }

        $rate->update($request->only(['country_name', 'sms_unit_cost', 'whatsapp_unit_cost', 'currency'])
            + ['is_default' => $request->boolean('is_default')]);

        return response()->json(['status' => true, 'message' => 'Tarif mis à jour.', 'rate' => $rate->fresh()]);
    }

    /**
     * DELETE super-admin/messaging-rates/{id} → supprimer
     */
    public function destroy($id)
    {
        if (!Auth::user()->can('config-paiement')) {
            return response()->json(['status' => false, 'message' => 'Accès refusé.'], 403);
        }
        $id = decrypt_id($id); abort_if(!$id, 404);
        MessagingRate::findOrFail($id)->delete();

        return response()->json(['status' => true, 'message' => 'Tarif supprimé.']);
    }

    /**
     * GET /messaging/quote?channel=sms&count=5&country_code=BJ → calcul coût
     */
    public function quote(Request $request)
    {
        $channel     = $request->input('channel'); // sms ou whatsapp
        $count       = (int) $request->input('count', 1);
        $countryCode = strtoupper($request->input('country_code', 'BJ'));

        $rate = MessagingRate::getForCountry($countryCode);
        if (!$rate) {
            return response()->json(['status' => false, 'message' => 'Tarif non configuré pour ce pays.']);
        }

        $unitCost = $channel === 'whatsapp' ? (float) $rate->whatsapp_unit_cost : (float) $rate->sms_unit_cost;
        $total    = $unitCost * $count;

        return response()->json([
            'status'       => true,
            'unit_cost'    => $unitCost,
            'total'        => $total,
            'currency'     => $rate->currency,
            'country_name' => $rate->country_name,
            'count'        => $count,
            'channel'      => $channel,
        ]);
    }
}
