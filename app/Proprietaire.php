<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Proprietaire extends Model
{
    protected $fillable = [
        'nom','prenom','telephone', 'adresse','delete_at','iddirection_ref','idannexe_ref'
    ];
}


