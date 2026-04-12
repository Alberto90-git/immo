<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtatDesLieuxElement extends Model
{
    protected $table = 'etat_des_lieux_elements';

    protected $fillable = [
        'piece_id', 'element', 'etat', 'description', 'degradation_detectee', 'montant_retenue',
    ];

    protected $casts = ['degradation_detectee' => 'boolean'];

    public function piece()
    {
        return $this->belongsTo(EtatDesLieuxPiece::class, 'piece_id');
    }

    public function getEtatLabelAttribute()
    {
        return match($this->etat) {
            'tres_bon'    => 'Très bon',
            'bon'         => 'Bon',
            'moyen'       => 'Moyen',
            'mauvais'     => 'Mauvais',
            'hors_service'=> 'Hors service',
            default       => $this->etat,
        };
    }

    public function getEtatBadgeClassAttribute()
    {
        return match($this->etat) {
            'tres_bon'    => 'bg-success',
            'bon'         => 'bg-info',
            'moyen'       => 'bg-warning text-dark',
            'mauvais'     => 'bg-danger',
            'hors_service'=> 'bg-dark',
            default       => 'bg-secondary',
        };
    }
}
