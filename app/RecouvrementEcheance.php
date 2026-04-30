<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecouvrementEcheance extends Model
{
    protected $table = 'recouvrement_echeances';

    protected $fillable = [
        'dossier_id', 'numero', 'montant',
        'date_echeance', 'statut', 'date_paiement',
    ];

    protected $casts = [
        'date_echeance'  => 'date',
        'date_paiement'  => 'date',
        'montant'        => 'decimal:2',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function dossier()
    {
        return $this->belongsTo(RecouvrementDossier::class, 'dossier_id');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return [
            'en_attente' => 'En attente',
            'paye'       => 'Payé',
            'en_retard'  => 'En retard',
        ][$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeAttribute(): string
    {
        $classes = [
            'en_attente' => 'bg-secondary',
            'paye'       => 'bg-success',
            'en_retard'  => 'bg-danger',
        ];
        $class = $classes[$this->statut] ?? 'bg-secondary';
        return '<span class="badge ' . $class . '">' . $this->statut_label . '</span>';
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->statut !== 'paye' && $this->date_echeance->isPast();
    }
}
