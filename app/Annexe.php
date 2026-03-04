<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annexe extends Model
{
    protected $primaryKey = 'idannexes';

    protected $fillable = [
        'designation',
        'telephone',
        'email',
        'siege_social',
        'logo',
        'cash_electronique',
        'signature',
        'status',
        'iddirection_ref',
        'userdata',
        'blocage_annexe'
    ];

    /**
     * Relation avec la direction
     */
    public function direction()
    {
        return $this->belongsTo(Direction::class, 'iddirection_ref', 'iddirection');
    }

    /**
     * Récupérer le chemin complet du logo
     */
    public function getLogoPathAttribute()
    {
        if (!$this->logo) {
            return null;
        }

        $possiblePaths = [
            storage_path('app/public/' . $this->logo),
            public_path('storage/' . $this->logo),
            public_path($this->logo),
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Récupérer le chemin complet de la signature
     */
    public function getSignaturePathAttribute()
    {
        if (!$this->signature) {
            return null;
        }

        $possiblePaths = [
            storage_path('app/public/' . $this->signature),
            public_path('storage/' . $this->signature),
            public_path($this->signature),
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
