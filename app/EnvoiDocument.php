<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnvoiDocument extends Model
{
    protected $table = 'envois_documents';

    protected $fillable = [
        'iddirection_ref',
        'destinataire_type',
        'destinataire_id',
        'destinataire_nom',
        'destinataire_contact',
        'type_document',
        'document_ref_id',
        'methode_envoi',
        'statut',
        'message_erreur',
        'pdf_temp_path',
        'message_personnalise',
        'envoye_par',
    ];
}
