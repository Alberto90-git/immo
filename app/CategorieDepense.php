<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorieDepense extends Model
{
    protected $table = 'categories_depenses';

    protected $fillable = ['iddirection_ref', 'nom', 'type'];

    public static $types = [
        'entretien'   => 'Entretien & maintenance',
        'taxes'       => 'Taxes & impôts',
        'honoraires'  => 'Honoraires prestataire',
        'travaux'     => 'Travaux',
        'assurance'   => 'Assurance',
        'autre'       => 'Autre',
    ];

    public function getTypeLabelAttribute(): string
    {
        return self::$types[$this->type] ?? ucfirst($this->type);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class, 'categorie_id');
    }
}
