<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Direction;
use App\Mail\SubscriptionExpirationMail;

class SubscriptionExpirationNotification extends Notification
{
    use Queueable;

    protected $direction;
    protected $daysRemaining;
    protected $thresholdKey;

    public function __construct(Direction $direction, int $daysRemaining, string $thresholdKey)
    {
        $this->direction = $direction;
        $this->daysRemaining = $daysRemaining;
        $this->thresholdKey = $thresholdKey;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $mailData = [
            'entreprise' => $this->direction->designation,
            'plan_nom' => $this->direction->plan ? $this->direction->plan->nom : 'N/A',
            'date_expiration' => $this->direction->abonnement_fin
                ? \Carbon\Carbon::parse($this->direction->abonnement_fin)->format('d/m/Y')
                : 'N/A',
            'jours_restants' => $this->daysRemaining,
            'destinataire_nom' => $notifiable->nom . ' ' . $notifiable->prenom,
        ];

        return (new SubscriptionExpirationMail($mailData))->to($notifiable->email);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'subscription_expiration',
            'threshold' => $this->thresholdKey,
            'direction_id' => $this->direction->iddirection,
            'plan_nom' => $this->direction->plan ? $this->direction->plan->nom : 'N/A',
            'date_expiration' => $this->direction->abonnement_fin
                ? \Carbon\Carbon::parse($this->direction->abonnement_fin)->format('d/m/Y')
                : 'N/A',
            'jours_restants' => $this->daysRemaining,
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
        ];
    }

    private function getTitle(): string
    {
        switch ($this->thresholdKey) {
            case '7_jours':
                return 'Abonnement expire dans 7 jours';
            case '3_jours':
                return 'Abonnement expire dans 3 jours';
            case '1_jour':
                return 'Abonnement expire demain';
            case 'jour_j':
                return "Abonnement expire aujourd'hui";
            case 'expire':
                return 'Abonnement expiré';
            default:
                return 'Notification abonnement';
        }
    }

    private function getMessage(): string
    {
        $plan = $this->direction->plan ? $this->direction->plan->nom : '';
        $date = $this->direction->abonnement_fin
            ? \Carbon\Carbon::parse($this->direction->abonnement_fin)->format('d/m/Y')
            : '';

        switch ($this->thresholdKey) {
            case '7_jours':
                return "Votre abonnement {$plan} expire le {$date}. Pensez au renouvellement.";
            case '3_jours':
                return "Votre abonnement {$plan} expire dans 3 jours ({$date}).";
            case '1_jour':
                return "Votre abonnement {$plan} expire demain ({$date}). Renouvelez maintenant.";
            case 'jour_j':
                return "Votre abonnement {$plan} expire aujourd'hui. Renouvelez pour éviter l'interruption.";
            case 'expire':
                return "Votre abonnement {$plan} a expiré. Renouvelez pour rétablir l'accès complet.";
            default:
                return "Notification concernant votre abonnement {$plan}.";
        }
    }

    private function getIcon(): string
    {
        if ($this->thresholdKey === 'expire' || $this->thresholdKey === 'jour_j') {
            return 'bx-error-circle';
        }
        return 'bx-time-five';
    }

    private function getColor(): string
    {
        if (in_array($this->thresholdKey, ['expire', 'jour_j', '1_jour'])) {
            return 'danger';
        }
        return 'warning';
    }
}
