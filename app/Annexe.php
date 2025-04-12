<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annexe extends Model
{
    protected $fillable = [
        'designation','telephone','email','siege_social','status','iddirection_ref','userdata','blocage_annexe'
    ];
}
