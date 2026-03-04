<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function serviceUrl(): string
    {
        return env('WHATSAPP_SERVICE_URL', 'http://127.0.0.1:5050');
    }

    public function status()
    {
        try {
            $resp = Http::timeout(5)->get($this->serviceUrl() . '/status');
            return response()->json($resp->json());
        } catch (\Exception $e) {
            return response()->json(['status' => 'disconnected', 'error' => 'Service indisponible']);
        }
    }

    public function qr()
    {
        try {
            $resp = Http::timeout(5)->get($this->serviceUrl() . '/qr');
            return response()->json($resp->json());
        } catch (\Exception $e) {
            return response()->json(['status' => 'disconnected', 'qr' => null]);
        }
    }

    /**
     * Proxy l'image PNG du screenshot WhatsApp Web directement au navigateur.
     * Appelé avec un timestamp pour éviter le cache : /whatsapp/qr-image?t=xxx
     */
    public function qrImage()
    {
        try {
            $resp = Http::timeout(10)->get($this->serviceUrl() . '/qr-image');
            if ($resp->successful()) {
                return response($resp->body(), 200, [
                    'Content-Type'  => 'image/png',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma'        => 'no-cache',
                    'Expires'       => '0',
                ]);
            }
            abort(404);
        } catch (\Exception $e) {
            abort(503);
        }
    }

    public function connect(Request $request)
    {
        try {
            $resp = Http::timeout(15)->post($this->serviceUrl() . '/connect');
            return response()->json($resp->json());
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Impossible de démarrer le service WhatsApp. Vérifiez que scripts/whatsapp/start.bat est en cours d\'exécution.',
            ]);
        }
    }

    public function disconnect(Request $request)
    {
        try {
            $resp = Http::timeout(15)->post($this->serviceUrl() . '/disconnect');
            return response()->json($resp->json());
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Service WhatsApp indisponible.']);
        }
    }
}
