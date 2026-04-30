<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    protected $fillable = [
        'automation_rule_id',
        'iddirection_ref',
        'locataire_id',
        'proprietaire_id',
        'type',
        'canal',
        'statut',
        'message',
        'destinataire',
        'reference',
        'envoye_le',
    ];

    protected $dates = ['envoye_le'];

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class);
    }

    public function rule()
    {
        return $this->belongsTo(AutomationRule::class, 'automation_rule_id');
    }

    public function getStatutBadgeAttribute(): string
    {
        return match ($this->statut) {
            'succes' => '<span class="badge bg-success">Succès</span>',
            'echec'  => '<span class="badge bg-danger">Échec</span>',
            'ignore' => '<span class="badge bg-secondary">Ignoré</span>',
            default  => '<span class="badge bg-secondary">' . $this->statut . '</span>',
        };
    }

    public function getCanalBadgeAttribute(): string
    {
        return match ($this->canal) {
            'email'    => '<span class="badge bg-label-info">Email</span>',
            'sms'      => '<span class="badge bg-label-warning">SMS</span>',
            'whatsapp' => '<span class="badge bg-label-success">WhatsApp</span>',
            default    => '<span class="badge bg-label-secondary">' . $this->canal . '</span>',
        };
    }
}
