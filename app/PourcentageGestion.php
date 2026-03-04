<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PourcentageGestion extends Model
{
    protected $table = 'pourcentage_gestions';

    protected $fillable = [
        'iddirection_ref',
        'type',
        'nom',
        'pourcentage',
        'is_active',
        'delete_at',
    ];

    protected $casts = [
        'pourcentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    public function scopeGroupe($query)
    {
        return $query->where('type', 'groupe');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotDeleted($query)
    {
        return $query->whereNull('delete_at');
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'iddirection_ref', 'iddirection');
    }

    public function proprietaires()
    {
        return $this->belongsToMany(
            Proprietaire::class,
            'pourcentage_gestion_proprietaire',
            'pourcentage_gestion_id',
            'proprietaire_id'
        );
    }
}
