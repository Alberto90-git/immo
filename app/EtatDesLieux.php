<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtatDesLieux extends Model
{
    protected $table = 'etats_des_lieux';

    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'locataire_id', 'maison_id', 'chambre_id',
        'type', 'date_etat', 'etat_entree_id', 'statut', 'notes_generales',
        'retenue_caution', 'signature_token', 'signe_locataire', 'signe_locataire_at',
        'signature_locataire_image',
        'signe_agent', 'signe_agent_at', 'created_by', 'delete_at',
    ];

    protected $dates = ['date_etat', 'signe_locataire_at', 'signe_agent_at', 'delete_at'];

    public function pieces()
    {
        return $this->hasMany(EtatDesLieuxPiece::class, 'etat_des_lieux_id')->orderBy('ordre');
    }

    public function photos()
    {
        return $this->hasMany(EtatDesLieuxPhoto::class, 'etat_des_lieux_id');
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function etatEntree()
    {
        return $this->belongsTo(EtatDesLieux::class, 'etat_entree_id');
    }

    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'brouillon' => '<span class="badge bg-secondary">Brouillon</span>',
            'finalise'  => '<span class="badge bg-primary">Finalisé</span>',
            'signe'     => '<span class="badge bg-success">Signé</span>',
            default     => '<span class="badge bg-secondary">' . $this->statut . '</span>',
        };
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'entree' ? 'État d\'entrée' : 'État de sortie';
    }

    public function getTotalRetenueAttribute()
    {
        return $this->pieces->sum(fn($p) => $p->elements->sum('montant_retenue'));
    }
}
