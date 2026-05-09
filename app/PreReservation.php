<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PreReservation extends Model
{
    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'prospect_id', 'chambre_id',
        'date_debut', 'date_fin', 'statut', 'note', 'delete_at',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function isExpired(): bool
    {
        return $this->date_fin && $this->date_fin->isPast() && $this->statut === 'active';
    }

    public function getStatutLabel(): string
    {
        return [
            'active'    => __('pages.prereserv_statut_active'),
            'expiree'   => __('pages.prereserv_statut_expiree'),
            'annulee'   => __('pages.prereserv_statut_annulee'),
            'convertie' => __('pages.prereserv_statut_convertie'),
        ][$this->statut ?? 'active'] ?? $this->statut;
    }
}
