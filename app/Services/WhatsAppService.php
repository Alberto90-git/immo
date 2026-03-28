<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WhatsAppService
{
    protected function serviceUrl(): string
    {
        return env('WHATSAPP_SERVICE_URL', 'http://127.0.0.1:5050');
    }

    /**
     * Envoie un PDF via le service WhatsApp natif local (Flask).
     *
     * @param string $pdfContent  Contenu binaire du PDF
     * @param string $filename    Nom du fichier
     * @param string $telephone   Numéro destinataire
     * @param string $caption     Message accompagnant le fichier
     * @return array ['success', 'message', 'temp_path']
     */
    public function envoyerPdf(
        string $pdfContent,
        string $filename,
        string $telephone,
        string $caption
    ): array {
        try {
            $response = Http::timeout(60)->post($this->serviceUrl() . '/send', [
                'telephone'  => $this->formaterNumero($telephone),
                'caption'    => $caption,
                'pdf_base64' => base64_encode($pdfContent),
                'filename'   => $filename,
            ]);

            $result = $response->json();

            return [
                'success'   => (bool) ($result['status'] ?? false),
                'message'   => $result['message'] ?? 'Erreur inconnue',
                'temp_path' => null,
            ];

        } catch (\Exception $e) {
            return [
                'success'   => false,
                'message'   => 'Service WhatsApp indisponible : ' . $e->getMessage(),
                'temp_path' => null,
            ];
        }
    }

    /**
     * Vérifie si le service WhatsApp local est connecté.
     */
    public function estConnecte(): bool
    {
        try {
            $resp = Http::timeout(5)->get($this->serviceUrl() . '/status');
            return ($resp->json()['status'] ?? '') === 'connected';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Envoie un message texte (sans PDF) via le service WhatsApp natif local.
     *
     * @param string $telephone  Numéro destinataire
     * @param string $message    Contenu du message
     * @return array ['success', 'message']
     */
    public function envoyerTexte(string $telephone, string $message): array
    {
        try {
            $response = Http::timeout(60)->post($this->serviceUrl() . '/send-text', [
                'telephone' => $this->formaterNumero($telephone),
                'message'   => $message,
            ]);

            $result = $response->json();

            return [
                'success' => (bool) ($result['status'] ?? false),
                'message' => $result['message'] ?? 'Erreur inconnue',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Service WhatsApp indisponible : ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Compatibilité ascendante – nettoyage des anciens fichiers temp.
     * Conservé pour le scheduler mais n'a plus d'effet.
     *
     * @return int
     */
    public function nettoyerFichiersTemp(): int
    {
        $count = 0;
        if (Storage::exists('public/temp')) {
            $files = Storage::files('public/temp');
            foreach ($files as $file) {
                if ((time() - Storage::lastModified($file)) > 86400) {
                    Storage::delete($file);
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * Normalise un numéro de téléphone béninois en format international.
     */
    public function formaterNumero(string $tel): string
    {
        $tel = preg_replace('/[\s\-\(\)]/', '', $tel);

        if (str_starts_with($tel, '+')) {
            return $tel;
        }
        if (str_starts_with($tel, '00')) {
            return '+' . substr($tel, 2);
        }
        if (str_starts_with($tel, '229')) {
            return '+' . $tel;
        }
        if (strlen($tel) === 8) {
            return '+229' . $tel;
        }

        return $tel;
    }
}
