<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketHistorique extends Model
{
    protected $table = 'ticket_historiques';

    protected $fillable = ['ticket_id', 'statut_avant', 'statut_apres', 'commentaire', 'user_id'];

    public function ticket() { 
        return $this->belongsTo(TicketMaintenance::class, 'ticket_id'); 
    }
    
    public function user() {
         return $this->belongsTo(User::class, 'user_id'); 
    }

    public function getStatutApresLabelAttribute()
    {
        return match($this->statut_apres) {
            'nouveau'  => 'Nouveau',
            'en_cours' => 'En cours',
            'resolu'   => 'Résolu',
            'cloture'  => 'Clôturé',
            default    => $this->statut_apres,
        };
    }

    public function getStatutApresBadgeAttribute()
    {
        return match($this->statut_apres) {
            'nouveau'  => '<span class="badge bg-label-secondary">Nouveau</span>',
            'en_cours' => '<span class="badge bg-label-warning">En cours</span>',
            'resolu'   => '<span class="badge bg-label-info">Résolu</span>',
            'cloture'  => '<span class="badge bg-label-success">Clôturé</span>',
            'ouvert'   => '<span class="badge bg-label-primary">Ouvert</span>',
            default    => '<span class="badge bg-label-secondary">' . $this->statut_apres . '</span>',
        };
    }
}
