<?php

namespace App\Http\Controllers;

use App\PlatformConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlatformConfigController extends Controller
{
    /**
     * Retourne la config publique du prestataire de paiement actif.
     * Utilisée par la welcome page et la page "Mon abonnement".
     */
    public function publicConfig()
    {
        $config = PlatformConfig::getConfig();

        $paymentEnabled  = $config->isOperational();
        $paymentProvider = $config->getActiveProvider();
        $paymentPublicKey = $paymentEnabled ? $config->getActivePublicKey() : null;
        $paymentSandbox  = $config->getActiveSandbox();

        return response()->json([
            // Champs agnostiques (nouveaux)
            'payment_enabled'    => $paymentEnabled,
            'payment_provider'   => $paymentProvider,
            'payment_public_key' => $paymentPublicKey,
            'payment_sandbox'    => $paymentSandbox,
            // Alias KKiaPay conservés pour compatibilité
            'kkiapay_enabled'    => $config->isKkiapayActive() && $paymentEnabled,
            'kkiapay_public_key' => $config->isKkiapayActive() ? $config->kkiapay_public_key : null,
            'kkiapay_sandbox'    => (bool) $config->kkiapay_sandbox,
        ]);
    }

    /**
     * Page dédiée Super Admin : configuration du prestataire de paiement.
     */
    public function configPage()
    {
        if (!Auth::user()->can('config-paiement')) {
            abort(403);
        }

        $paymentConfig         = \App\PlatformConfig::getConfig();
        $activePaymentProvider = $paymentConfig->getActiveProvider();
        $activePaymentSandbox  = $paymentConfig->getActiveSandbox();
        $paymentCfgData        = [
            'kkiapay_public_key'  => $paymentConfig->kkiapay_public_key ?? '',
            'kkiapay_sandbox'     => (bool) $paymentConfig->kkiapay_sandbox,
            'has_kkiapay_private' => !empty($paymentConfig->kkiapay_private_key),
            'has_kkiapay_secret'  => !empty($paymentConfig->kkiapay_secret_key),
            'fedapay_public_key'  => $paymentConfig->fedapay_public_key ?? '',
            'fedapay_sandbox'     => (bool) ($paymentConfig->fedapay_sandbox ?? true),
            'has_fedapay_secret'  => !empty($paymentConfig->fedapay_secret_key),
        ];

        return view('super-admin.config_paiement', compact(
            'activePaymentProvider',
            'activePaymentSandbox',
            'paymentCfgData'
        ));
    }

    /**
     * Retourne la config complète pour la page d'administration (Super Admin).
     */
    public function getAdminConfig()
    {
        if (!Auth::user()->can('config-paiement')) {
            return response()->json(['status' => false, 'message' => 'Accès refusé.'], 403);
        }

        $row = DB::table('platform_configs')->orderBy('id')->first();

        if (!$row) {
            return response()->json(['status' => false, 'message' => 'Configuration introuvable.'], 500);
        }

        return response()->json([
            'status'                  => true,
            'active_payment_provider' => $row->active_payment_provider ?? 'none',
            // KKiaPay
            'kkiapay_public_key'      => $row->kkiapay_public_key,
            'kkiapay_sandbox'         => (bool) $row->kkiapay_sandbox,
            'kkiapay_enabled'         => (bool) $row->kkiapay_enabled,
            'has_kkiapay_private_key' => !empty($row->kkiapay_private_key),
            'has_kkiapay_secret_key'  => !empty($row->kkiapay_secret_key),
            // FedaPay
            'fedapay_public_key'      => $row->fedapay_public_key,
            'fedapay_sandbox'         => (bool) ($row->fedapay_sandbox ?? true),
            'has_fedapay_secret_key'  => !empty($row->fedapay_secret_key),
        ]);
    }

    /**
     * Met à jour la configuration des prestataires de paiement (Super Admin uniquement).
     */
    public function update(Request $request)
    {
        if (!Auth::user()->can('config-paiement')) {
            return response()->json(['status' => false, 'message' => 'Accès refusé.'], 403);
        }

        $request->validate([
            'active_payment_provider' => 'required|in:none,kkiapay,fedapay',
            // KKiaPay
            'kkiapay_public_key'      => 'nullable|string|max:255',
            'kkiapay_private_key'     => 'nullable|string|max:255',
            'kkiapay_secret_key'      => 'nullable|string|max:255',
            'kkiapay_sandbox'         => 'nullable',
            // FedaPay
            'fedapay_public_key'      => 'nullable|string|max:255',
            'fedapay_secret_key'      => 'nullable|string|max:255',
            'fedapay_sandbox'         => 'nullable',
        ]);

        $activeProvider = $request->input('active_payment_provider');

        // Conversion booléenne sandbox KKiaPay
        $rawKkSandbox = $request->input('kkiapay_sandbox');
        $kkSandbox = ($rawKkSandbox === true || $rawKkSandbox === 1 || $rawKkSandbox === '1' || $rawKkSandbox === 'true') ? 1 : 0;

        // Conversion booléenne sandbox FedaPay
        $rawFpSandbox = $request->input('fedapay_sandbox');
        $fpSandbox = ($rawFpSandbox === true || $rawFpSandbox === 1 || $rawFpSandbox === '1' || $rawFpSandbox === 'true') ? 1 : 0;

        // Sync kkiapay_enabled avec active_payment_provider
        $kkEnabled = ($activeProvider === 'kkiapay') ? 1 : 0;

        $updateData = [
            'active_payment_provider' => $activeProvider,
            'kkiapay_enabled'         => $kkEnabled,
            'kkiapay_sandbox'         => $kkSandbox,
            'fedapay_sandbox'         => $fpSandbox,
            'updated_at'              => now(),
        ];

        // Clés KKiaPay : ne mettre à jour que si fournies
        if ($request->filled('kkiapay_public_key')) {
            $updateData['kkiapay_public_key'] = trim($request->input('kkiapay_public_key'));
        }
        if ($request->filled('kkiapay_private_key')) {
            $updateData['kkiapay_private_key'] = trim($request->input('kkiapay_private_key'));
        }
        if ($request->filled('kkiapay_secret_key')) {
            $updateData['kkiapay_secret_key'] = trim($request->input('kkiapay_secret_key'));
        }

        // Clés FedaPay : ne mettre à jour que si fournies
        if ($request->filled('fedapay_public_key')) {
            $updateData['fedapay_public_key'] = trim($request->input('fedapay_public_key'));
        }
        if ($request->filled('fedapay_secret_key')) {
            $updateData['fedapay_secret_key'] = trim($request->input('fedapay_secret_key'));
        }

        $exists = DB::table('platform_configs')->count() > 0;

        if ($exists) {
            DB::table('platform_configs')->update($updateData);
        } else {
            DB::table('platform_configs')->insert(array_merge($updateData, ['created_at' => now()]));
        }

        Log::info('Config prestataire de paiement mise à jour', [
            'active_provider' => $activeProvider,
            'user'            => Auth::user()->email,
        ]);

        $saved = DB::table('platform_configs')->orderBy('id')->first();

        return response()->json([
            'status'  => true,
            'message' => 'Configuration mise à jour avec succès.',
            'debug'   => [
                'active_payment_provider' => $saved->active_payment_provider,
                'kkiapay_enabled'         => (bool) $saved->kkiapay_enabled,
            ],
        ]);
    }
}
