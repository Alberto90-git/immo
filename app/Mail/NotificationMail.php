<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array $data [
     *   'type_notification'    => 'rappel_loyer' | 'preavis',
     *   'destinataire_nom'     => string,
     *   'destinataire_email'   => string,
     *   'sujet'                => string,
     *   'mois_courant'         => string,       // pour rappel_loyer
     *   'montant_loyer'        => string,       // pour rappel_loyer
     *   'logement'             => string,
     *   'date_fin_bail'        => string|null,  // pour preavis
     *   'message_personnalise' => string,
     *   'agence_nom'           => string,
     *   'email_envoi'          => string,
     * ]
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): self
    {
        $template = $this->data['type_notification'] === 'preavis'
            ? 'mail.notificationPreavis'
            : 'mail.notificationRappelLoyer';

        $mail = $this->subject($this->data['sujet'])
                     ->view($template);

        if (!empty($this->data['email_envoi'])) {
            $mail->from($this->data['email_envoi'], $this->data['agence_nom']);
        }

        return $mail;
    }
}
