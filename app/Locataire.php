<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
     protected $fillable = [
       'iddirection_ref','idannexe_ref','date_entree','nom','quartier','prenom','telephone','profession','status','nombre_avance','nombre_avance_consomme','maison_id','chambre_id','prix_mois','caution_courant','caution_eau',
    ];
}


