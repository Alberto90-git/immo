<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class WhatsAppService
{
    protected AfricasTalkingService $at;

    public function __construct()
    {
        $this->at = new AfricasTalkingService();
    }

    public function estConnecte(): bool
    {
        return $this->at->whatsappConnecte();
    }

    /**
     * Envoie un PDF via WhatsApp (AT) : stockage temporaire + lien dans le message.
     */
    public function envoyerPdf(
        string $pdfContent,
        string $filename,
        string $telephone,
        string $caption
    ): array {
        if (!$this->estConnecte()) {
            return [
                'success'   => false,
                'message'   => 'WhatsApp non configuré. Renseignez le Username, l\'API Key et le Product ID WhatsApp dans Paramétrage > Communication.',
                'temp_path' => null,
            ];
        }

        $tempPath  = 'public/temp/' . uniqid('doc_') . '_' . $filename;
        Storage::put($tempPath, $pdfContent);
        $fullUrl   = url(Storage::url($tempPath));

        $message = "{$caption}\n\nTéléchargez votre document : {$fullUrl}";
        $result  = $this->at->envoyerWhatsApp($telephone, $message);
        $result['temp_path'] = $tempPath;

        return $result;
    }

    /**
     * Envoie un message texte via WhatsApp (AT).
     */
    public function envoyerTexte(string $telephone, string $message): array
    {
        return $this->at->envoyerWhatsApp($telephone, $message);
    }

    /**
     * Nettoyage des fichiers PDF temporaires de plus de 24h.
     */
    public function nettoyerFichiersTemp(): int
    {
        $count = 0;
        if (Storage::exists('public/temp')) {
            foreach (Storage::files('public/temp') as $file) {
                if ((time() - Storage::lastModified($file)) > 86400) {
                    Storage::delete($file);
                    $count++;
                }
            }
        }
        return $count;
    }

    public function formaterNumero(string $tel): string
    {
        return $this->at->formaterNumero($tel);
    }
}
