<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
     protected $fillable = [
       'iddirection_ref','idannexe_ref','date_entree','nom','quartier','prenom','telephone','email','automation_opt_out','profession','status','nombre_caution','nombre_avance','nombre_avance_consomme','maison_id','chambre_id','prix_mois','caution_courant','caution_eau','mode_paiement'
    ];

    public function factures()
    {
        return $this->hasMany(Facture::class, 'locataire_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }
}


