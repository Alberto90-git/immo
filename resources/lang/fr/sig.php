<?php

return [
    // Types de document
    'type_contrat'   => 'Contrat',
    'type_edl'       => 'État des lieux',
    'type_quittance' => 'Quittance',
    'type_autre'     => 'Autre document',

    // Statuts
    'statut_en_attente' => 'En attente',
    'statut_signe'      => 'Signé',
    'statut_expire'     => 'Expiré',
    'statut_annule'     => 'Annulé',

    // Page index
    'title'             => 'Signature électronique',
    'breadcrumb'        => 'Signature électronique',
    'btn_new'           => 'Nouvelle demande',
    'col_document'      => 'Document',
    'col_signataire'    => 'Signataire',
    'col_type'          => 'Type',
    'col_statut'        => 'Statut',
    'col_created'       => 'Créé le',
    'col_signed'        => 'Signé le',
    'col_actions'       => 'Actions',
    'empty'             => 'Aucune demande de signature.',

    // Formulaire
    'form_title'        => 'Nouvelle demande de signature',
    'form_doc_type'     => 'Type de document',
    'form_doc_titre'    => 'Titre du document',
    'form_doc_desc'     => 'Description (optionnel)',
    'form_signataire'   => 'Nom complet du signataire',
    'form_email'        => 'Email du signataire',
    'form_telephone'    => 'Téléphone du signataire',
    'form_locataire'    => 'Locataire associé (optionnel)',
    'form_pdf'          => 'Fichier PDF à faire signer (optionnel)',
    'form_expires'      => 'Validité du lien (jours)',
    'form_hint_contact' => 'Au moins un email ou un téléphone est requis.',
    'btn_create'        => 'Créer et envoyer',

    // Messages
    'error_no_contact'   => 'Veuillez fournir un email ou un téléphone pour le signataire.',
    'error_not_pending'  => 'Cette demande n\'est plus en attente.',
    'error_not_signable' => 'Ce lien n\'est plus valide ou a déjà été utilisé.',
    'error_invalid_sig'   => 'Données de signature invalides.',
    'error_name_mismatch' => 'Le nom saisi ne correspond pas au signataire attendu.',
    'sent_via'           => 'Lien de signature envoyé par :canal.',
    'resent_via'         => 'Lien renvoyé par :canal.',
    'link_only'          => 'Lien généré. Le signataire n\'a pas de contact configuré — copiez le lien.',
    'signed_success'     => 'Document signé avec succès.',
    'cancelled'          => 'Demande annulée.',

    // Page publique de signature
    'sign_page_title'   => 'Signer le document',
    'sign_intro'        => 'Vous avez été invité(e) à signer le document suivant :',
    'sign_expires'      => 'Ce lien expire le :date.',
    'sign_draw'         => 'Tracez votre signature ci-dessous',
    'sign_clear'        => 'Effacer',
    'sign_name'         => 'Confirmer votre nom complet',
    'sign_btn'          => 'Valider et signer',
    'sign_legal'        => 'En signant, vous acceptez que cette signature électronique soit juridiquement valide conformément à la législation en vigueur.',

    // Page expirée
    'expire_title'      => 'Lien expiré',
    'expire_msg_expire' => 'Ce lien de signature a expiré. Veuillez contacter l\'agence pour obtenir un nouveau lien.',
    'expire_msg_signed' => 'Ce document a déjà été signé.',
    'expire_msg_cancel' => 'Cette demande de signature a été annulée.',

    // Page confirmée
    'confirm_title'     => 'Signature enregistrée',
    'confirm_msg'       => 'Votre signature a été enregistrée avec succès. Un exemplaire signé vous sera envoyé par email si une adresse est disponible.',
    'confirm_sha'       => 'Empreinte numérique du document signé (SHA-256) :',

    // Emails
    'mail_subject'       => 'Signature électronique requise',
    'mail_signed_subject'=> 'Votre document signé',
    'sms_body'           => 'Bonjour :nom, veuillez signer votre document via ce lien : :lien',

    // Certificat PDF
    'cert_title'        => 'Certificat de signature électronique',
    'cert_document'     => 'Document signé',
    'cert_signataire'   => 'Signataire',
    'cert_date'         => 'Date et heure de signature',
    'cert_ip'           => 'Adresse IP',
    'cert_sha_before'   => 'Empreinte SHA-256 (avant signature)',
    'cert_sha_after'    => 'Empreinte SHA-256 (après signature)',
    'cert_journal'      => 'Journal d\'audit',
    'cert_legal_note'   => 'Ce certificat atteste de la signature électronique du document ci-dessus conformément aux exigences légales.',

    // Actions
    'btn_resend'      => 'Renvoyer',
    'btn_cancel'      => 'Annuler',
    'btn_cert'        => 'Certificat PDF',
    'btn_copy_link'   => 'Copier le lien',
    'link_copied'     => 'Lien copié !',

    // Audit actions labels
    'audit_created'             => 'Demande créée',
    'audit_sent_email'          => 'Lien envoyé par email',
    'audit_sent_sms'            => 'Lien envoyé par SMS',
    'audit_viewed'              => 'Page consultée',
    'audit_signed'              => 'Document signé',
    'audit_exemplaire_envoye'   => 'Exemplaire signé envoyé',
    'audit_annule'              => 'Demande annulée',
    'audit_certificate_generated'=> 'Certificat généré',

    // Certificat PDF — champs non encore traduits
    'cert_sha_section'   => 'Empreintes numériques (SHA-256)',
    'cert_col_date'      => 'Date',
    'cert_col_action'    => 'Action',
    'cert_col_canal'     => 'Canal',
    'cert_col_ip'        => 'IP',
    'cert_legal_label'   => 'Note légale :',
    'cert_generated_on'  => 'Document généré le',
    'cert_footer'        => 'Certificat de signature électronique — Généré automatiquement',
    'cert_description'   => 'Description',
    'cert_email'         => 'Email',
    'cert_telephone'     => 'Téléphone',
];
