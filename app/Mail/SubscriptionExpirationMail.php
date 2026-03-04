<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpirationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notificationData;

    public function __construct(array $notificationData)
    {
        $this->notificationData = $notificationData;
    }

    public function build()
    {
        $days = $this->notificationData['jours_restants'];

        if ($days < 0) {
            $subject = "Lokativ - Votre abonnement a expiré";
        } elseif ($days == 0) {
            $subject = "Lokativ - Votre abonnement expire aujourd'hui";
        } elseif ($days == 1) {
            $subject = "Lokativ - Votre abonnement expire demain";
        } else {
            $subject = "Lokativ - Votre abonnement expire dans {$days} jours";
        }

        return $this->subject($subject)
                    ->view('mail.subscriptionExpiration');
    }
}
