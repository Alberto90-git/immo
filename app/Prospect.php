<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'nom', 'prenom', 'telephone', 'email',
        'maison_id', 'chambre_id', 'message', 'note', 'statut', 'source', 'agent_id', 'delete_at',
    ];

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function visites()
    {
        return $this->hasMany(VisiteProspect::class, 'prospect_id');
    }

    public function preReservations()
    {
        return $this->hasMany(PreReservation::class, 'prospect_id');
    }

    public function preReservationActive()
    {
        return $this->hasOne(PreReservation::class, 'prospect_id')->where('statut', 'active');
    }

    public function getStatutLabel(): string
    {
        return [
            'demande'          => __('pages.prospect_statut_demande'),
            'visite_planifiee' => __('pages.prospect_statut_visite_planifiee'),
            'visite_effectuee' => __('pages.prospect_statut_visite_effectuee'),
            'dossier'          => __('pages.prospect_statut_dossier'),
            'converti'         => __('pages.prospect_statut_converti'),
            'annule'           => __('pages.prospect_statut_annule'),
        ][$this->statut ?? 'demande'] ?? $this->statut;
    }

    public function getStatutBadgeClass(): string
    {
        return [
            'demande'          => 'bg-label-info',
            'visite_planifiee' => 'bg-label-warning',
            'visite_effectuee' => 'bg-label-primary',
            'dossier'          => 'bg-label-secondary',
            'converti'         => 'bg-label-success',
            'annule'           => 'bg-label-danger',
        ][$this->statut ?? 'demande'] ?? 'bg-label-secondary';
    }

    public function getNomComplet(): string
    {
        return trim($this->nom . ' ' . ($this->prenom ?? ''));
    }
}
