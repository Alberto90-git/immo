<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcelle extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','prenom','quartier','superficie','prix','etat',
        'telephone','delete_at','status','client_acheteur','iddirection_ref','idannexe_ref'
    ];
}
