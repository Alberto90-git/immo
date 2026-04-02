<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $table = 'parametres';
    
    protected $fillable = [
        'iddirection_ref',
        'format_choisi',
        'cash_electronique_url',
        'logo_url',
        'email_envoi',
        'whatsapp_numero_envoi',
        'whatsapp_api_token',
        'whatsapp_phone_number_id',
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

