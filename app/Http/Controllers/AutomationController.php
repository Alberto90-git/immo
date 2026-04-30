<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\AutomationRule;
use App\AutomationLog;
use App\Locataire;
use App\MessagingRate;
use App\MessagingTransaction;
use App\PlatformConfig;
use App\Services\KkiapayService;
use App\Services\FedapayService;

class AutomationController extends Controller
{
    private function directionId(): int
    {
        return (int) Auth::user()->iddirection_ref;
    }

    // ── Page principale (index + règles + logs) ───────────────────────────────

    public function index()
    {
        // Protected by route middleware can:gestion-automation

        $rules = AutomationRule::where('iddirection_ref', $this->directionId())
            ->orderBy('type')
            ->get();

        $logs = AutomationLog::where('iddirection_ref', $this->directionId())
            ->orderByDesc('envoye_le')
            ->limit(200)
            ->get();

        $messagingRates = MessagingRate::orderBy('country_name')->get();
        $platformConfig = PlatformConfig::getConfig();

        return view('automation.index', compact('rules', 'logs', 'messagingRates', 'platformConfig'));
    }

    // ── Créer / mettre à jour une règle ──────────────────────────────────────

    public function store(Request $request)
    {
        // Protected by route middleware can:gestion-automation

        $data = $request->validate([
            'type'                     => 'required|in:rappel_loyer,escalade_impaye,preavis_bail,rapport_mensuel,renouvellement',
            'actif'                    => 'nullable|boolean',
            'heure_envoi'              => 'required|regex:/^\d{2}:\d{2}$/',
            'canal'                    => 'required|in:email,sms,whatsapp,email_sms,email_whatsapp,tous',
            'jours_declenchement'      => 'nullable|string',
            'delai_escalade_sms'       => 'nullable|integer|min:1',
            'delai_escalade_whatsapp'  => 'nullable|integer|min:1',
            'mois_avant_echeance'      => 'nullable|integer|min:1',
            'jour_du_mois'             => 'nullable|integer|min:1|max:28',
            'transaction_id'           => 'nullable|string',
            'country_code'             => 'nullable|string|max:5',
            'recipient_count'          => 'nullable|integer|min:0',
        ]);

        // ── Vérification paiement SMS si canal inclut SMS ─────────────────────
        $canalInclusSms = in_array($data['canal'], ['sms', 'email_sms', 'tous']);
        if ($canalInclusSms) {
            $cfg = PlatformConfig::getConfig();
            if ($cfg->isOperational()) {
                $txnId = $request->input('transaction_id');
                if (!$txnId) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Un paiement est requis pour activer une règle avec envoi SMS. Veuillez calculer le coût et payer.',
                    ]);
                }
                $payCheck = $this->verifierPaiement($txnId);
                if (!$payCheck['success']) {
                    return response()->json(['status' => false, 'message' => $payCheck['message']]);
                }

                // Logger la transaction de paiement
                $countryCode    = strtoupper($request->input('country_code', 'BJ'));
                $recipientCount = (int) $request->input('recipient_count', 0);
                $rate           = MessagingRate::getForCountry($countryCode);
                MessagingTransaction::create([
                    'iddirection_ref'        => $this->directionId(),
                    'channel'                => 'sms',
                    'recipient_count'        => $recipientCount,
                    'unit_cost'              => $rate ? $rate->sms_unit_cost : 0,
                    'total_amount'           => $rate ? $rate->sms_unit_cost * $recipientCount : 0,
                    'currency'               => $rate ? $rate->currency : 'XOF',
                    'country_code'           => $countryCode,
                    'payment_provider'       => $cfg->getActiveProvider(),
                    'payment_transaction_id' => $txnId,
                    'payment_status'         => 'paid',
                    'messages_sent'          => false,
                ]);
            }
        }

        // Transformer jours_declenchement "−5,0,3" → [-5, 0, 3]
        if (!empty($data['jours_declenchement'])) {
            $data['jours_declenchement'] = array_map(
                fn($v) => (int) trim($v),
                explode(',', $data['jours_declenchement'])
            );
        } else {
            $data['jours_declenchement'] = null;
        }

        $data['actif']           = !empty($request->actif);
        $data['iddirection_ref'] = $this->directionId();

        // Retirer les champs de paiement des données à persister
        unset($data['transaction_id'], $data['country_code'], $data['recipient_count']);

        // Upsert : une seule règle par type par direction
        $rule = AutomationRule::updateOrCreate(
            ['iddirection_ref' => $this->directionId(), 'type' => $data['type']],
            $data
        );

        return response()->json([
            'status'  => true,
            'message' => 'Règle enregistrée.',
            'rule'    => $this->ruleToArray($rule),
        ]);
    }

    // ── Nombre de locataires actifs (pour devis SMS) ──────────────────────────

    public function countActifs()
    {
        // Protected by route middleware can:gestion-automation
        try {
            $count = Locataire::where('iddirection_ref', $this->directionId())
                ->where('status', true)->whereNull('delete_at')
                ->count();

            return response()->json(['status' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // ── Activer / désactiver ──────────────────────────────────────────────────

    public function toggleActif(Request $request)
    {
        // Protected by route middleware can:gestion-automation

        $rule = AutomationRule::where('id', $request->id)
            ->where('iddirection_ref', $this->directionId())
            ->firstOrFail();

        $rule->update(['actif' => !$rule->actif]);

        return response()->json([
            'status'  => true,
            'message' => $rule->actif ? 'Règle activée.' : 'Règle désactivée.',
            'actif'   => $rule->actif,
        ]);
    }

    // ── Supprimer une règle ───────────────────────────────────────────────────

    public function destroy(Request $request)
    {
        // Protected by route middleware can:gestion-automation

        $rule = AutomationRule::where('id', $request->id)
            ->where('iddirection_ref', $this->directionId())
            ->firstOrFail();

        $rule->delete();

        return response()->json(['status' => true, 'message' => 'Règle supprimée.']);
    }

    // ── Historique (AJAX) ─────────────────────────────────────────────────────

    public function logs(Request $request)
    {
        // Protected by route middleware can:gestion-automation

        $query = AutomationLog::where('iddirection_ref', $this->directionId())
            ->with('locataire', 'proprietaire')
            ->orderByDesc('envoye_le');

        if ($request->filled('type'))   $query->where('type', $request->type);
        if ($request->filled('canal'))  $query->where('canal', $request->canal);
        if ($request->filled('statut')) $query->where('statut', $request->statut);
        if ($request->filled('depuis')) $query->whereDate('envoye_le', '>=', $request->depuis);

        $logs = $query->limit(300)->get()->map(fn($l) => [
            'id'           => $l->id,
            'type'         => $l->type,
            'canal'        => $l->canal,
            'canal_badge'  => $l->canal_badge,
            'statut'       => $l->statut,
            'statut_badge' => $l->statut_badge,
            'destinataire' => $l->destinataire,
            'reference'    => $l->reference,
            'message'      => $l->message,
            'envoye_le'    => $l->envoye_le?->format('d/m/Y H:i'),
            'locataire'    => $l->locataire ? "{$l->locataire->prenom} {$l->locataire->nom}" : ($l->proprietaire ? "{$l->proprietaire->prenom} {$l->proprietaire->nom}" : '–'),
        ]);

        return response()->json(['status' => true, 'logs' => $logs]);
    }

    // ── Opt-out locataire ─────────────────────────────────────────────────────

    public function toggleOptOut(Request $request)
    {
        // Protected by route middleware can:gestion-automation

        $locataire = Locataire::where('id', $request->id)
            ->where('iddirection_ref', $this->directionId())
            ->firstOrFail();

        $locataire->update(['automation_opt_out' => !$locataire->automation_opt_out]);

        return response()->json([
            'status'    => true,
            'opt_out'   => $locataire->automation_opt_out,
            'message'   => $locataire->automation_opt_out
                ? 'Locataire exclu des automatisations.'
                : 'Locataire réintégré aux automatisations.',
        ]);
    }

    // ── Liste locataires avec opt-out ─────────────────────────────────────────

    public function locataires()
    {
        // Protected by route middleware can:gestion-automation

        // Vérifier si la colonne automation_opt_out existe (migration peut ne pas être jouée)
        $hasOptOutColumn = Schema::hasColumn('locataires', 'automation_opt_out');

        try {
            $query = Locataire::where('iddirection_ref', $this->directionId())
                ->where('status', true)->whereNull('delete_at')
                ->orderBy('nom');

            // N'inclure automation_opt_out dans le SELECT que si la colonne existe
            if ($hasOptOutColumn) {
                $query->select('id', 'nom', 'prenom', 'telephone', 'email', 'automation_opt_out');
            } else {
                $query->select('id', 'nom', 'prenom', 'telephone', 'email');
            }

            $locataires = $query->get()->map(fn($l) => [
                'id'        => $l->id,
                'nom'       => "{$l->prenom} {$l->nom}",
                'telephone' => $l->telephone ?? '–',
                'email'     => $l->email ?? '–',
                'opt_out'   => $hasOptOutColumn ? (bool) $l->automation_opt_out : false,
            ]);

            return response()->json(['status' => true, 'locataires' => $locataires]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function verifierPaiement(string $txnId): array
    {
        $cfg      = PlatformConfig::getConfig();
        $provider = $cfg->getActiveProvider();

        if ($provider === 'kkiapay') {
            $svc    = new KkiapayService($cfg->kkiapay_public_key, $cfg->kkiapay_private_key, $cfg->kkiapay_secret_key, (bool) $cfg->kkiapay_sandbox);
            $result = $svc->verifyTransaction($txnId);
        } elseif ($provider === 'fedapay') {
            $svc    = new FedapayService($cfg->fedapay_secret_key, (bool) $cfg->fedapay_sandbox);
            $result = $svc->verifyTransaction($txnId);
        } else {
            return ['success' => false, 'message' => 'Aucun prestataire de paiement configuré.'];
        }

        if (!$result['success']) {
            return ['success' => false, 'message' => 'Paiement non vérifié ou invalide.'];
        }

        return ['success' => true, 'amount' => $result['amount']];
    }

    private function ruleToArray(AutomationRule $rule): array
    {
        return [
            'id'                      => $rule->id,
            'type'                    => $rule->type,
            'type_label'              => $rule->type_label,
            'actif'                   => $rule->actif,
            'heure_envoi'             => $rule->heure_envoi,
            'canal'                   => $rule->canal,
            'canal_label'             => $rule->canal_label,
            'jours_declenchement'     => $rule->jours_declenchement,
            'delai_escalade_sms'      => $rule->delai_escalade_sms,
            'delai_escalade_whatsapp' => $rule->delai_escalade_whatsapp,
            'mois_avant_echeance'     => $rule->mois_avant_echeance,
            'jour_du_mois'            => $rule->jour_du_mois,
        ];
    }
}
