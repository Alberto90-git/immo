<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratConfig extends Model
{
    protected $fillable = [
        'iddirection_ref',
        'titre_contrat',
        'sous_titre',
        'articles',
        'nom_modele',
        'type_bail',
        'is_default',
    ];

    protected $casts = [
        'articles'   => 'array',
        'is_default' => 'boolean',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'iddirection_ref', 'iddirection');
    }

    public function getTypeBailLabel(): string
    {
        return [
            'meuble'     => __('pages.contrat_type_meuble'),
            'nu'         => __('pages.contrat_type_nu'),
            'commercial' => __('pages.contrat_type_commercial'),
            'autre'      => __('pages.contrat_type_autre'),
        ][$this->type_bail ?? 'autre'] ?? __('pages.contrat_type_autre');
    }

    public function scopeForDirection($query, int $dirId)
    {
        return $query->where('iddirection_ref', $dirId);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
