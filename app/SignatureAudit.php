<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignatureAudit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'signature_demande_id', 'action', 'canal',
        'ip_adresse', 'user_agent', 'details', 'created_at',
    ];

    protected $casts = [
        'details'    => 'array',
        'created_at' => 'datetime',
    ];

    public function demande()
    {
        return $this->belongsTo(SignatureDemande::class, 'signature_demande_id');
    }
}
