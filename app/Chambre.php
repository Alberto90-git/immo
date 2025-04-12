<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
     protected $fillable = [
        'numero_chambre','type_chambre','etat','maison_id','delete_at','iddirection_ref','idannexe_ref','prix_chambre'
    ];
}
