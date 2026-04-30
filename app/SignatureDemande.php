<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SignatureDemande extends Model
{
    protected $fillable = [
        'iddirection_ref', 'idannexe_ref',
        'document_type', 'document_id', 'document_titre', 'document_description',
        'pdf_path', 'sha256_document',
        'locataire_id', 'signataire_nom', 'signataire_email', 'signataire_telephone',
        'token', 'statut', 'expires_at',
        'signature_image', 'signe_at', 'ip_adresse', 'user_agent',
        'pdf_signe_path', 'sha256_signe',
        'created_by', 'delete_at',
    ];

    protected $casts = [
        'signe_at'   => 'datetime',
        'expires_at' => 'datetime',
        'delete_at'  => 'datetime',
    ];

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function audits()
    {
        return $this->hasMany(SignatureAudit::class, 'signature_demande_id');
    }

    public function isExpire(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isSignable(): bool
    {
        return $this->statut === 'en_attente' && !$this->isExpire();
    }

    public function getDocumentTypeLabel(): string
    {
        return [
            'contrat'       => __('sig.type_contrat'),
            'etat_des_lieux'=> __('sig.type_edl'),
            'quittance'     => __('sig.type_quittance'),
            'autre'         => __('sig.type_autre'),
        ][$this->document_type] ?? $this->document_type;
    }

    public function getStatutLabel(): string
    {
        return [
            'en_attente' => __('sig.statut_en_attente'),
            'signe'      => __('sig.statut_signe'),
            'expire'     => __('sig.statut_expire'),
            'annule'     => __('sig.statut_annule'),
        ][$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeClass(): string
    {
        return [
            'en_attente' => 'bg-warning text-dark',
            'signe'      => 'bg-success',
            'expire'     => 'bg-secondary',
            'annule'     => 'bg-danger',
        ][$this->statut] ?? 'bg-secondary';
    }
}
