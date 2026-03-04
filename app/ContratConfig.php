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
    ];

    protected $casts = [
        'articles' => 'array',
    ];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'iddirection_ref', 'iddirection');
    }
}
