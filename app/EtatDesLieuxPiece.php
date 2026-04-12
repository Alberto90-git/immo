<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtatDesLieuxPiece extends Model
{
    protected $table = 'etat_des_lieux_pieces';

    protected $fillable = ['etat_des_lieux_id', 'nom_piece', 'ordre'];

    public function etatDesLieux()
    {
        return $this->belongsTo(EtatDesLieux::class, 'etat_des_lieux_id');
    }

    public function elements()
    {
        return $this->hasMany(EtatDesLieuxElement::class, 'piece_id');
    }

    public function photos()
    {
        return $this->hasMany(EtatDesLieuxPhoto::class, 'piece_id');
    }
}
