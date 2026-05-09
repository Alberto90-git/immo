<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisiteProspect extends Model
{
    protected $table = 'visites_prospects';

    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'prospect_id', 'chambre_id',
        'agent_id', 'date_visite', 'statut', 'frais_visite', 'note', 'delete_at',
    ];

    protected $casts = [
        'date_visite'  => 'datetime',
        'frais_visite' => 'decimal:2',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function agent()
    {
        return $this->belongsTo(\App\User::class, 'agent_id');
    }

    public function getStatutLabel(): string
    {
        return [
            'planifiee' => __('pages.visite_statut_planifiee'),
            'effectuee' => __('pages.visite_statut_effectuee'),
            'annulee'   => __('pages.visite_statut_annulee'),
        ][$this->statut ?? 'planifiee'] ?? $this->statut;
    }

    public function getStatutBadgeClass(): string
    {
        return [
            'planifiee' => 'bg-warning text-dark',
            'effectuee' => 'bg-success',
            'annulee'   => 'bg-danger',
        ][$this->statut ?? 'planifiee'] ?? 'bg-secondary';
    }
}
