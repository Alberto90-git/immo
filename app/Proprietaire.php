<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Annexe;

class Proprietaire extends Model
{
    protected $fillable = [
        'nom','prenom','telephone','email', 'adresse','delete_at','iddirection_ref','idannexe_ref'
    ];

    public function annexe()
    {
        return $this->belongsTo(Annexe::class, 'idannexe_ref', 'idannexes');
    }

    public function pourcentageGestion()
    {
        return $this->belongsToMany(
            PourcentageGestion::class,
            'pourcentage_gestion_proprietaire',
            'proprietaire_id',
            'pourcentage_gestion_id'
        );
    }
}


