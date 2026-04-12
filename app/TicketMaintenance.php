<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketMaintenance extends Model
{
    protected $table = 'tickets_maintenance';

    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'maison_id', 'chambre_id', 'locataire_id',
        'titre', 'description', 'categorie', 'priorite', 'statut',
        'prestataire_id', 'cout_intervention', 'imputation',
        'date_ouverture', 'date_resolution', 'date_cloture',
        'created_by', 'delete_at',
    ];

    protected $dates = ['date_ouverture', 'date_resolution', 'date_cloture', 'delete_at'];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function maison()      { return $this->belongsTo(Maison::class,      'maison_id'); }
    public function chambre()     { return $this->belongsTo(Chambre::class,     'chambre_id'); }
    public function locataire()   { return $this->belongsTo(Locataire::class,   'locataire_id'); }
    public function prestataire() { return $this->belongsTo(Prestataire::class, 'prestataire_id'); }
    public function createdBy()   { return $this->belongsTo(User::class,        'created_by'); }

    public function historiques()
    {
        return $this->hasMany(TicketHistorique::class, 'ticket_id')->orderByDesc('created_at');
    }

    // ── Accesseurs ────────────────────────────────────────────────────────────

    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'nouveau'  => '<span class="badge bg-label-secondary">Nouveau</span>',
            'en_cours' => '<span class="badge bg-label-warning">En cours</span>',
            'resolu'   => '<span class="badge bg-label-info">Résolu</span>',
            'cloture'  => '<span class="badge bg-label-success">Clôturé</span>',
            default    => '<span class="badge bg-label-secondary">' . $this->statut . '</span>',
        };
    }

    public function getStatutLabelAttribute()
    {
        return match($this->statut) {
            'nouveau'  => 'Nouveau',
            'en_cours' => 'En cours',
            'resolu'   => 'Résolu',
            'cloture'  => 'Clôturé',
            default    => $this->statut,
        };
    }

    public function getPrioriteBadgeAttribute()
    {
        return match($this->priorite) {
            'basse'   => '<span class="badge bg-label-secondary">Basse</span>',
            'normale' => '<span class="badge bg-label-primary">Normale</span>',
            'haute'   => '<span class="badge bg-label-warning">Haute</span>',
            'urgente' => '<span class="badge bg-danger">Urgente</span>',
            default   => '<span class="badge bg-label-secondary">' . $this->priorite . '</span>',
        };
    }

    public function getPrioriteLabelAttribute()
    {
        return match($this->priorite) {
            'basse'   => 'Basse',
            'normale' => 'Normale',
            'haute'   => 'Haute',
            'urgente' => 'Urgente',
            default   => $this->priorite,
        };
    }

    public function getCategorieLabelAttribute()
    {
        return Prestataire::SPECIALITES[$this->categorie]['label'] ?? ucfirst($this->categorie);
    }

    public function getCategorieIconAttribute()
    {
        return Prestataire::SPECIALITES[$this->categorie]['icon'] ?? 'bx bx-wrench';
    }

    // ── Workflow : statut suivant ─────────────────────────────────────────────

    public function statutSuivant()
    {
        return match($this->statut) {
            'nouveau'  => 'en_cours',
            'en_cours' => 'resolu',
            'resolu'   => 'cloture',
            default    => null,
        };
    }

    public function labelBoutonStatut()
    {
        return match($this->statut) {
            'nouveau'  => 'Prendre en charge',
            'en_cours' => 'Marquer résolu',
            'resolu'   => 'Clôturer',
            default    => null,
        };
    }
}
