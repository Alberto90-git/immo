<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;

    public function __construct(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function build()
    {
        $mail = $this->subject("Lokativ - Facture d'abonnement")
                    ->view('mail.subscriptionInvoice');

        // Vérifier que le contenu PDF existe et n'est pas vide
        if (!empty($this->invoiceData['pdf_content'])) {
            // Sauvegarder dans un fichier temporaire pour plus de fiabilité
            $tempFile = tempnam(sys_get_temp_dir(), 'invoice_') . '.pdf';
            file_put_contents($tempFile, $this->invoiceData['pdf_content']);

            if (file_exists($tempFile) && filesize($tempFile) > 0) {
                $mail->attach($tempFile, [
                    'as' => 'Facture_Lokativ_' . date('Ymd') . '.pdf',
                    'mime' => 'application/pdf',
                ]);

                Log::info('SubscriptionInvoiceMail: PDF attaché avec succès', [
                    'taille' => filesize($tempFile),
                    'email' => $this->invoiceData['user']['email'] ?? 'inconnu',
                ]);
            } else {
                Log::error('SubscriptionInvoiceMail: Le fichier temporaire PDF est vide ou inexistant', [
                    'email' => $this->invoiceData['user']['email'] ?? 'inconnu',
                ]);
            }
        } else {
            Log::warning('SubscriptionInvoiceMail: Aucun contenu PDF fourni, email envoyé sans pièce jointe', [
                'email' => $this->invoiceData['user']['email'] ?? 'inconnu',
            ]);
        }

        return $mail;
    }
}
