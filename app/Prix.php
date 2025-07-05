<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prix extends Model
{
    protected $fillable = [
        'prix','status','maison_id','chambre_id','iddirection_ref','idannexe_ref'
    ];
}

