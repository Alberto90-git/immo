<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $table = 'parametres';
    
    protected $fillable = [
        'iddirection_ref',
        'format_choisi', // À supprimer ou garder si utilisé ailleurs
        'cash_electronique_url', // Nouveau champ
        'logo_url',
    ];


    public function getCashElectroniqueUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    
    public function getLogoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}

