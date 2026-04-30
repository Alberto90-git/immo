<?php

return [
    // Document types
    'type_contrat'   => 'Contract',
    'type_edl'       => 'Property inspection',
    'type_quittance' => 'Receipt',
    'type_autre'     => 'Other document',

    // Statuses
    'statut_en_attente' => 'Pending',
    'statut_signe'      => 'Signed',
    'statut_expire'     => 'Expired',
    'statut_annule'     => 'Cancelled',

    // Index page
    'title'             => 'Electronic signature',
    'breadcrumb'        => 'Electronic signature',
    'btn_new'           => 'New request',
    'col_document'      => 'Document',
    'col_signataire'    => 'Signatory',
    'col_type'          => 'Type',
    'col_statut'        => 'Status',
    'col_created'       => 'Created',
    'col_signed'        => 'Signed',
    'col_actions'       => 'Actions',
    'empty'             => 'No signature requests.',

    // Form
    'form_title'        => 'New signature request',
    'form_doc_type'     => 'Document type',
    'form_doc_titre'    => 'Document title',
    'form_doc_desc'     => 'Description (optional)',
    'form_signataire'   => 'Signatory full name',
    'form_email'        => 'Signatory email',
    'form_telephone'    => 'Signatory phone',
    'form_locataire'    => 'Associated tenant (optional)',
    'form_pdf'          => 'PDF file to sign (optional)',
    'form_expires'      => 'Link validity (days)',
    'form_hint_contact' => 'At least one email or phone number is required.',
    'btn_create'        => 'Create and send',

    // Messages
    'error_no_contact'   => 'Please provide an email or phone number for the signatory.',
    'error_not_pending'  => 'This request is no longer pending.',
    'error_not_signable' => 'This link is no longer valid or has already been used.',
    'error_invalid_sig'   => 'Invalid signature data.',
    'error_name_mismatch' => 'The name entered does not match the expected signatory.',
    'sent_via'           => 'Signature link sent by :canal.',
    'resent_via'         => 'Link resent by :canal.',
    'link_only'          => 'Link generated. The signatory has no contact configured — copy the link.',
    'signed_success'     => 'Document signed successfully.',
    'cancelled'          => 'Request cancelled.',

    // Public signature page
    'sign_page_title'   => 'Sign the document',
    'sign_intro'        => 'You have been invited to sign the following document:',
    'sign_expires'      => 'This link expires on :date.',
    'sign_draw'         => 'Draw your signature below',
    'sign_clear'        => 'Clear',
    'sign_name'         => 'Confirm your full name',
    'sign_btn'          => 'Validate and sign',
    'sign_legal'        => 'By signing, you agree that this electronic signature is legally valid in accordance with applicable law.',

    // Expired page
    'expire_title'      => 'Link expired',
    'expire_msg_expire' => 'This signature link has expired. Please contact the agency to get a new link.',
    'expire_msg_signed' => 'This document has already been signed.',
    'expire_msg_cancel' => 'This signature request has been cancelled.',

    // Confirmed page
    'confirm_title'     => 'Signature recorded',
    'confirm_msg'       => 'Your signature has been successfully recorded. A signed copy will be sent to you by email if an address is available.',
    'confirm_sha'       => 'Digital fingerprint of the signed document (SHA-256):',

    // Emails
    'mail_subject'       => 'Electronic signature required',
    'mail_signed_subject'=> 'Your signed document',
    'sms_body'           => 'Hello :nom, please sign your document via this link: :lien',

    // PDF certificate
    'cert_title'        => 'Electronic Signature Certificate',
    'cert_document'     => 'Signed document',
    'cert_signataire'   => 'Signatory',
    'cert_date'         => 'Date and time of signature',
    'cert_ip'           => 'IP address',
    'cert_sha_before'   => 'SHA-256 fingerprint (before signature)',
    'cert_sha_after'    => 'SHA-256 fingerprint (after signature)',
    'cert_journal'      => 'Audit trail',
    'cert_legal_note'   => 'This certificate attests to the electronic signature of the above document in accordance with legal requirements.',

    // Actions
    'btn_resend'      => 'Resend',
    'btn_cancel'      => 'Cancel',
    'btn_cert'        => 'PDF Certificate',
    'btn_copy_link'   => 'Copy link',
    'link_copied'     => 'Link copied!',

    // Audit action labels
    'audit_created'             => 'Request created',
    'audit_sent_email'          => 'Link sent by email',
    'audit_sent_sms'            => 'Link sent by SMS',
    'audit_viewed'              => 'Page viewed',
    'audit_signed'              => 'Document signed',
    'audit_exemplaire_envoye'   => 'Signed copy sent',
    'audit_annule'              => 'Request cancelled',
    'audit_certificate_generated'=> 'Certificate generated',
];
