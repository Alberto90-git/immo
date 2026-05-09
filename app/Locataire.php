<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locataire extends Model
{
    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'date_entree', 'nom', 'quartier', 'prenom',
        'telephone', 'email', 'automation_opt_out', 'profession', 'status',
        'nombre_caution', 'nombre_avance', 'nombre_avance_consomme',
        'maison_id', 'chambre_id', 'prix_mois', 'caution_courant', 'caution_eau',
        'mode_paiement', 'statut_bail',
    ];

    public function factures()
    {
        return $this->hasMany(Facture::class, 'locataire_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function signatureDemandes()
    {
        return $this->hasMany(SignatureDemande::class, 'locataire_id');
    }

    public function getStatutBailBadgeClass(): string
    {
        return [
            'en_cours'  => 'bg-label-success',
            'signe'     => 'bg-label-primary',
            'en_litige' => 'bg-label-warning',
            'resilie'   => 'bg-label-danger',
        ][$this->statut_bail ?? 'en_cours'] ?? 'bg-label-success';
    }

    public function getStatutBailLabel(): string
    {
        return [
            'en_cours'  => __('pages.bail_statut_en_cours'),
            'signe'     => __('pages.bail_statut_signe'),
            'en_litige' => __('pages.bail_statut_en_litige'),
            'resilie'   => __('pages.bail_statut_resilie'),
        ][$this->statut_bail ?? 'en_cours'] ?? __('pages.bail_statut_en_cours');
    }
}
