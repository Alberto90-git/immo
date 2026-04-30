<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Depense extends Model
{
    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'categorie_id',
        'montant', 'date_depense', 'description',
        'type_imputation', 'proprietaire_id', 'maison_id', 'chambre_id',
        'justificatif_url',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'montant'      => 'float',
    ];

    public static $imputations = [
        'agence'       => 'Agence',
        'proprietaire' => 'Propriétaire',
        'maison'       => 'Maison',
        'chambre'      => 'Chambre',
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieDepense::class, 'categorie_id');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'proprietaire_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function getJustificatifUrlAttribute($value): ?string
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getImputationLabelAttribute(): string
    {
        return self::$imputations[$this->type_imputation] ?? ucfirst($this->type_imputation);
    }
}
