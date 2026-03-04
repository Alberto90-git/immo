<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array $data [
     *   'destinataire_nom'       => string,
     *   'destinataire_email'     => string,
     *   'type_document_label'    => string,
     *   'message_personnalise'   => string,
     *   'pdf_content'            => string (binaire),
     *   'pdf_filename'           => string,
     *   'agence_nom'             => string,
     *   'email_envoi'            => string (from),
     * ]
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        $mail = $this->subject($this->data['agence_nom'] . ' - ' . $this->data['type_document_label'])
                     ->view('mail.documentEnvoi');

        // Override from address if configured
        if (!empty($this->data['email_envoi'])) {
            $mail->from($this->data['email_envoi'], $this->data['agence_nom']);
        }

        if (!empty($this->data['pdf_content'])) {
            $tempFile = tempnam(sys_get_temp_dir(), 'doc_') . '.pdf';
            file_put_contents($tempFile, $this->data['pdf_content']);

            if (file_exists($tempFile) && filesize($tempFile) > 0) {
                $mail->attach($tempFile, [
                    'as'   => $this->data['pdf_filename'],
                    'mime' => 'application/pdf',
                ]);

                Log::info('DocumentMail: PDF attaché', [
                    'taille' => filesize($tempFile),
                    'email'  => $this->data['destinataire_email'],
                ]);
            } else {
                Log::error('DocumentMail: fichier temp PDF vide', [
                    'email' => $this->data['destinataire_email'],
                ]);
            }
        }

        return $mail;
    }
}
