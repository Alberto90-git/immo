<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maison extends Model
{
    protected $fillable = [
        'nom_maison','quartier','nombre_chambre','proprio_id','iddirection_ref','idannexe_ref'
    ];
}

