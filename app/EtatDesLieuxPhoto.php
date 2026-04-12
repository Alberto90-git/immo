<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EtatDesLieuxPhoto extends Model
{
    protected $table = 'etat_des_lieux_photos';

    protected $fillable = ['etat_des_lieux_id', 'piece_id', 'chemin_fichier', 'nom_original'];

    public function etatDesLieux()
    {
        return $this->belongsTo(EtatDesLieux::class, 'etat_des_lieux_id');
    }

    public function piece()
    {
        return $this->belongsTo(EtatDesLieuxPiece::class, 'piece_id');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->chemin_fichier);
    }
}
