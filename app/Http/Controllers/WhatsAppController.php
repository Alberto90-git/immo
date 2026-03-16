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
        // Vérifier si le service Flask est déjà en cours d'exécution
        $serviceRunning = false;
        try {
            Http::timeout(3)->get($this->serviceUrl() . '/status');
            $serviceRunning = true;
        } catch (\Exception $e) {
            $serviceRunning = false;
        }

        // Si le service n'est pas lancé, démarrer start.bat en arrière-plan
        if (!$serviceRunning) {
            $bat = base_path('scripts' . DIRECTORY_SEPARATOR . 'whatsapp' . DIRECTORY_SEPARATOR . 'start.bat');

            if (!file_exists($bat)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Fichier start.bat introuvable : ' . $bat,
                ]);
            }

            try {
                // Lancement détaché : la fenêtre s'ouvre en arrière-plan sans bloquer PHP
                $batEscaped = str_replace('/', '\\', $bat);
                $workdir    = str_replace('/', '\\', dirname($bat));
                $cmd = 'cmd /c start /B "" "' . $batEscaped . '"';
                $descriptors = [['pipe','r'], ['pipe','w'], ['pipe','w']];
                $proc = proc_open($cmd, $descriptors, $pipes, $workdir);
                if (is_resource($proc)) {
                    foreach ($pipes as $pipe) { fclose($pipe); }
                    proc_close($proc);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Impossible de lancer le service WhatsApp : ' . $e->getMessage(),
                ]);
            }

            // Retourner un statut "starting" : le frontend va poller /status puis relancer /connect
            return response()->json([
                'status'   => 'starting',
                'message'  => 'Service WhatsApp en cours de démarrage, veuillez patienter…',
            ]);
        }

        // Service déjà actif → demande de connexion directe
        try {
            $resp = Http::timeout(15)->post($this->serviceUrl() . '/connect');
            return response()->json($resp->json());
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Le service WhatsApp ne répond pas.',
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
