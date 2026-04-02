<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    protected $fillable = ['nom', 'role', 'texte', 'etoiles'];
}
