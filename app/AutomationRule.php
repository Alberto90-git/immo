<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationRule extends Model
{
    protected $fillable = [
        'iddirection_ref',
        'idannexe_ref',
        'type',
        'actif',
        'heure_envoi',
        'jours_declenchement',
        'canal',
        'delai_escalade_sms',
        'delai_escalade_whatsapp',
        'mois_avant_echeance',
        'jour_du_mois',
    ];

    protected $casts = [
        'jours_declenchement' => 'array',
        'actif'               => 'boolean',
    ];

    // Labels lisibles
    public static $typeLabels = [
        'rappel_loyer'    => 'Rappel de loyer',
        'escalade_impaye' => 'Escalade d\'impayé',
        'preavis_bail'    => 'Préavis de fin de bail',
        'rapport_mensuel' => 'Rapport mensuel propriétaire',
        'renouvellement'  => 'Renouvellement de contrat',
    ];

    public static $canalLabels = [
        'email'           => 'Email uniquement',
        'sms'             => 'SMS uniquement',
        'whatsapp'        => 'WhatsApp uniquement',
        'email_sms'       => 'Email + SMS',
        'email_whatsapp'  => 'Email + WhatsApp',
        'tous'            => 'Tous les canaux',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type] ?? $this->type;
    }

    public function getCanalLabelAttribute(): string
    {
        return self::$canalLabels[$this->canal] ?? $this->canal;
    }

    public function getCanaux(): array
    {
        return match ($this->canal) {
            'email'          => ['email'],
            'sms'            => ['sms'],
            'whatsapp'       => ['whatsapp'],
            'email_sms'      => ['email', 'sms'],
            'email_whatsapp' => ['email', 'whatsapp'],
            'tous'           => ['email', 'sms', 'whatsapp'],
            default          => ['email'],
        };
    }
}
