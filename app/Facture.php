<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
     protected $fillable = [
        'iddirection_ref','idannexe_ref','date_paiement','type_paiement','montant','mois','locataire_id','maison_id','chambre_id','delete_at','numero_chambre','type_chambre'

    ];
}
