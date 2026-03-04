<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Direction;
use App\User;
use App\Annexe;
use App\Notifications\SubscriptionExpirationNotification;
use App\Mail\SubscriptionExpirationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckSubscriptionExpiration extends Command
{
    protected $signature = 'subscription:check-expiration';
    protected $description = 'Vérifie les abonnements proches de l\'expiration et envoie des notifications';

    public function handle()
    {
        $today = Carbon::today();

        $thresholds = [
            7 => '7_jours',
            3 => '3_jours',
            1 => '1_jour',
            0 => 'jour_j',
        ];

        // Directions avec abonnement actif ou essai
        $directions = Direction::whereNotNull('abonnement_fin')
            ->whereIn('statut_abonnement', ['actif', 'essai'])
            ->get();

        $notified = 0;

        foreach ($directions as $direction) {
            $daysRemaining = $today->diffInDays(Carbon::parse($direction->abonnement_fin), false);

            // Vérifier chaque seuil (du plus éloigné au plus proche)
            foreach ($thresholds as $days => $key) {
                if ($daysRemaining == $days && $direction->derniere_notification_abonnement !== $key) {
                    $this->sendNotifications($direction, $days, $key);
                    $direction->derniere_notification_abonnement = $key;
                    $direction->save();
                    $notified++;
                    $this->info("Notification envoyée: {$direction->designation} ({$key})");
                    break;
                }
            }
        }

        // Abonnements expirés
        $expiredDirections = Direction::whereNotNull('abonnement_fin')
            ->where('abonnement_fin', '<', $today)
            ->whereIn('statut_abonnement', ['actif', 'essai'])
            ->get();

        $blocked = 0;

        foreach ($expiredDirections as $direction) {
            $direction->statut_abonnement = 'expire';

            if ($direction->derniere_notification_abonnement !== 'expire') {
                $daysAfter = $today->diffInDays(Carbon::parse($direction->abonnement_fin), false);
                $this->sendNotifications($direction, $daysAfter, 'expire');
                $direction->derniere_notification_abonnement = 'expire';
                $notified++;
                $this->info("Expiration marquée: {$direction->designation}");
            }

            $direction->save();

            // Bloquer tous les utilisateurs de l'entreprise
            User::where('iddirection_ref', $direction->iddirection)
                ->whereNull('blocage_entreprise')
                ->update(['blocage_entreprise' => Carbon::now()]);

            // Bloquer toutes les annexes de l'entreprise
            Annexe::where('iddirection_ref', $direction->iddirection)
                ->whereNull('blocage_annexe')
                ->update(['blocage_annexe' => Carbon::now()]);

            $blocked++;
            $this->info("Compte bloqué: {$direction->designation}");
        }

        $this->info("Vérification terminée. {$notified} notification(s) envoyée(s). {$blocked} entreprise(s) bloquée(s).");
        return 0;
    }

    private function sendNotifications(Direction $direction, int $daysRemaining, string $thresholdKey)
    {
        // Récupérer tous les utilisateurs de l'entreprise
        $users = User::where('iddirection_ref', $direction->iddirection)
            ->whereNull('status')
            ->get();

        // Notification database + mail pour chaque utilisateur
        foreach ($users as $user) {
            try {
                $user->notify(new SubscriptionExpirationNotification($direction, $daysRemaining, $thresholdKey));
            } catch (\Exception $e) {
                Log::error("Erreur notification user {$user->id}: " . $e->getMessage());
            }
        }

        // Email à l'adresse de l'entreprise (si différente des users)
        if ($direction->email) {
            $alreadySent = $users->contains(function ($user) use ($direction) {
                return $user->email === $direction->email;
            });

            if (!$alreadySent) {
                try {
                    $mailData = $this->buildMailData($direction, $daysRemaining, $thresholdKey);
                    Mail::to($direction->email)->send(new SubscriptionExpirationMail($mailData));
                } catch (\Exception $e) {
                    Log::error("Erreur email direction {$direction->iddirection}: " . $e->getMessage());
                }
            }
        }
    }

    private function buildMailData(Direction $direction, int $daysRemaining, string $thresholdKey): array
    {
        return [
            'entreprise' => $direction->designation,
            'plan_nom' => $direction->plan ? $direction->plan->nom : 'N/A',
            'date_expiration' => $direction->abonnement_fin
                ? Carbon::parse($direction->abonnement_fin)->format('d/m/Y')
                : 'N/A',
            'jours_restants' => $daysRemaining,
            'destinataire_nom' => $direction->designation,
        ];
    }
}
