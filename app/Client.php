<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom','prenom','telephone','zone_voulu','superficie','budget','etat','status','delete_at','iddirection_ref','idannexe_ref'
    ];
}