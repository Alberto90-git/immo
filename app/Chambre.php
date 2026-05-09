<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
     protected $fillable = [
        'numero_chambre','type_chambre','etat','maison_id','delete_at','iddirection_ref','idannexe_ref','prix_chambre'
    ];

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function locataire()
    {
        return $this->hasOne(Locataire::class, 'chambre_id')->where('status', true)->whereNull('delete_at');
    }
}
