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
        $pdfContent  = $this->data['pdf_content']  ?? null;
        $pdfFilename = $this->data['pdf_filename']  ?? 'document.pdf';

        $mail = $this->subject($this->data['agence_nom'] . ' - ' . $this->data['type_document_label'])
                     ->view('mail.documentEnvoi');

        if (!empty($this->data['email_envoi'])) {
            $mail->from($this->data['email_envoi'], $this->data['agence_nom']);
        }

        if (!empty($pdfContent)) {
            $mail->withSwiftMessage(function ($message) use ($pdfContent, $pdfFilename) {
                $attachment = new \Swift_Attachment($pdfContent, $pdfFilename, 'application/pdf');
                $message->attach($attachment);
            });

            Log::info('DocumentMail: PDF attaché via withSwiftMessage', [
                'taille'  => strlen($pdfContent),
                'fichier' => $pdfFilename,
                'email'   => $this->data['destinataire_email'],
            ]);
        }

        return $mail;
    }
}
