<?php

return [

    // ── Commun ───────────────────────────────────────────────────────────────────
    'greeting'       => 'Bonjour :name,',
    'footer_rights'  => '© :year Lokativ. Tous droits réservés.',
    'footer_auto'    => 'Ce message a été envoyé automatiquement — merci de ne pas y répondre directement.',
    'contact_note'   => 'Pour toute question, contactez :agence ou écrivez à',
    'auto_note'      => 'Ce message a été envoyé automatiquement par Lokativ. Merci de ne pas y répondre directement.',

    // ── Rappel de loyer ───────────────────────────────────────────────────────────
    'rappel_loyer' => [
        'title'           => 'Rappel de paiement de loyer',
        'subject'         => 'Rappel de paiement de loyer',
        'badge'           => '⏰ Rappel de loyer',
        'intro'           => 'Nous vous adressons ce rappel concernant le paiement de votre loyer. Merci de bien vouloir régulariser votre situation dans les meilleurs délais.',
        'amount_label'    => 'Montant du loyer dû',
        'logement_header' => 'Détails du logement',
        'logement_label'  => 'Logement concerné',
        'alert'           => 'Pour éviter tout désagrément ou pénalité, veuillez procéder au règlement dès que possible. En cas de difficulté, contactez directement votre agence.',
    ],

    // ── Préavis de fin de bail ────────────────────────────────────────────────────
    'preavis' => [
        'title'           => 'Préavis de fin de bail',
        'subject'         => 'Préavis de fin de bail',
        'badge'           => '📋 Préavis de fin de bail',
        'intro'           => 'Par la présente, :agence vous notifie officiellement la fin prochaine de votre contrat de bail.',
        'date_label'      => 'Date de fin de bail',
        'logement_header' => 'Logement concerné',
        'logement_label'  => 'Adresse / Référence',
        'body_1'          => 'Conformément aux termes de votre contrat de location, vous êtes prié(e) de <strong>libérer les lieux et restituer les clés</strong> avant la date indiquée ci-dessus.',
        'body_2'          => 'Nous vous invitons à prendre contact avec notre agence afin de planifier l\'état des lieux de sortie.',
        'alert'           => 'Passé ce délai sans restitution des lieux, des frais supplémentaires pourront être engagés. Pour tout arrangement ou question, contactez directement votre agence.',
    ],

    // ── Recouvrement / Relance ────────────────────────────────────────────────────
    'recouvrement' => [
        'subtitle'         => 'Lokativ · Gestion Immobilière',
        'type_rappel1'     => '1er rappel de paiement',
        'type_rappel2'     => '2ème rappel de paiement',
        'type_demeure'     => 'Mise en demeure',
        'montant_label'    => 'Montant impayé',
        'mois_impayes'     => ':n mois impayés',
        'legal_title'      => '⚖️ Délai légal : 8 jours',
        'legal_body'       => 'À défaut de règlement dans ce délai, une procédure judiciaire sera engagée, incluant la résiliation du bail et l\'expulsion.',
        'contact_note'     => 'Pour toute question ou pour convenir d\'un arrangement de paiement, n\'hésitez pas à nous contacter.',
        'footer_text'      => 'Ce message vous est adressé par votre gestionnaire via Lokativ.',
        'footer_no_reply'  => 'Merci de ne pas répondre à cet email automatique.',
    ],

    // ── Signature état des lieux ──────────────────────────────────────────────────
    'signature_edl' => [
        'subtitle'    => 'État des lieux – Demande de signature',
        'intro'       => 'Votre agence vous invite à signer votre <strong>:typeLabel</strong> établi le <strong>:date</strong>.',
        'doc_label'   => 'Document :',
        'date_label'  => 'Date :',
        'agency_label'=> 'Agence :',
        'cta_text'    => 'Cliquez sur le bouton ci-dessous pour consulter l\'état des lieux et apposer votre signature électronique :',
        'btn_text'    => 'Consulter et signer →',
        'fallback'    => 'Si vous ne parvenez pas à cliquer sur le bouton, copiez ce lien dans votre navigateur :',
        'footer_sent' => 'Ce message a été envoyé par :agence.',
        'footer_ignore'=> 'Si vous n\'êtes pas concerné par ce document, ignorez cet email.',
    ],

    // ── Envoi de document ─────────────────────────────────────────────────────────
    'document_envoi' => [
        'badge'           => '📎 Document joint',
        'intro'           => 'Veuillez trouver ci-joint le document suivant, transmis par <strong>:agence</strong>.',
        'doc_header'      => 'Document transmis',
        'doc_type_label'  => 'Type de document',
        'sent_by_label'   => 'Envoyé par',
        'attachment_note' => 'Le document est disponible en <strong>pièce jointe PDF</strong> de cet email. Conservez-le pour vos archives.',
        'contact_note'    => 'Pour toute question, contactez directement votre agence ou écrivez à',
    ],

    // ── Notification maintenance ──────────────────────────────────────────────────
    'maintenance' => [
        'title'          => 'Mise à jour de votre signalement',
        'badge'          => '🔔 Mise à jour signalement',
        'intro'          => 'Nous vous informons d\'une mise à jour concernant votre demande d\'intervention.',
        'status_header'  => 'Détails du signalement',
        'status_ticket'  => 'Signalement',
        'status_logement'=> 'Logement',
        'status_categorie'=> 'Catégorie',
        'status_en_cours'  => 'En cours de traitement',
        'status_resolu'    => 'Résolu',
        'status_cloture'   => 'Clôturé',
        'note'           => 'Pour toute question, contactez directement votre agence. Ce message est envoyé automatiquement — merci de ne pas y répondre.',
    ],

    // ── Facture de paiement (abonnement plateforme) ───────────────────────────────
    'paiement' => [
        'title'           => 'Facture de paiement',
        'badge'           => '🧾 Facture de paiement',
        'subtitle'        => 'Plateforme de gestion immobilière',
        'intro'           => 'Nous vous remercions pour votre confiance. Voici les détails de votre facture d\'abonnement.',
        'amount_label'    => 'Montant total',
        'plan_header'     => 'Détails de l\'abonnement',
        'client_label'    => 'Client',
        'plan_label'      => 'Plan choisi',
        'duration_label'  => 'Durée d\'abonnement',
        'duration_value'  => ':n mois',
        'code_label'      => 'Code de paiement',
        'mobile_label'    => 'Numéro Mobile Money',
        'date_label'      => 'Date d\'échéance',
        'alert'           => 'Veuillez effectuer le paiement avant la date d\'échéance pour éviter toute interruption de service.',
        'btn_text'        => 'Accéder à mon espace',
        'contact_note'    => 'Pour toute question, contactez notre support à',
    ],

    // ── Automation ────────────────────────────────────────────────────────────────
    // ── Code de vérification OTP ─────────────────────────────────────────────────
    'code_email' => [
        'subject'      => 'Votre code de vérification Lokativ',
        'subtitle'     => 'Plateforme de gestion immobilière',
        'title'        => 'Vérification de votre identité',
        'greeting'     => 'Bonjour,',
        'intro'        => 'Une tentative de connexion a été détectée sur votre compte. Pour confirmer que c\'est bien vous, veuillez utiliser le code de vérification ci-dessous.',
        'code_label'   => 'Votre code de connexion',
        'expiry'       => 'Ce code expire dans <strong>10 minutes</strong>',
        'instructions' => 'Saisissez ce code dans la fenêtre de connexion pour accéder à votre espace Lokativ.',
        'warning'      => '<strong>Attention :</strong> Ne partagez jamais ce code avec qui que ce soit, y compris notre équipe support. Lokativ ne vous demandera jamais votre code de vérification. Si vous n\'avez pas initié cette connexion, ignorez cet email et sécurisez votre compte.',
        'contact_note' => 'Si vous avez des questions, contactez notre support à',
        'privacy'      => 'Politique de confidentialité',
        'help'         => 'Aide',
    ],

    // ── Email de vérification / Information de connexion ─────────────────────
    'verify_email' => [
        'subject'            => 'Information de connexion',
        'title'              => 'Votre compte Lokativ a été créé',
        'platform'           => 'Plateforme de gestion immobilière',
        'badge'              => '✔ Compte créé avec succès',
        'welcome'            => 'Bienvenue sur Lokativ, :prenom !',
        'greeting'           => 'Bonjour :nom :prenom,',
        'created'            => 'Votre compte a bien été créé sur la plateforme Lokativ. Vous trouverez ci-dessous vos identifiants de connexion.',
        'credentials_header' => 'Vos identifiants de connexion',
        'email_label'        => 'Adresse e-mail',
        'password_label'     => 'Mot de passe temporaire',
        'btn_text'           => 'Accéder à mon espace →',
        'security_note'      => 'Pour votre sécurité, nous vous recommandons de <strong>changer votre mot de passe</strong> dès votre première connexion depuis les paramètres de votre profil.',
        'contact_note'       => 'Des questions ? Contactez notre support à',
        'footer_privacy'     => 'Politique de confidentialité',
        'footer_help'        => 'Aide',
    ],

    // ── Facture d'abonnement à l'inscription ─────────────────────────────────
    'subscription_invoice' => [
        'subject'             => "Lokativ - Facture d'abonnement",
        'title'               => "Facture d'abonnement Lokativ",
        'platform'            => 'Plateforme de gestion immobilière',
        'badge'               => "🧾 Facture d'abonnement",
        'welcome'             => 'Merci pour votre confiance, :prenom !',
        'greeting'            => 'Bonjour :nom :prenom,',
        'intro'               => 'Votre abonnement <strong>Lokativ</strong> est confirmé. Vous trouverez ci-dessous le récapitulatif ainsi que la facture PDF en pièce jointe.',
        'plan_header'         => 'Plan souscrit',
        'plan_label'          => 'Plan',
        'period_label'        => 'Période',
        'amount_label'        => 'Montant',
        'free_trial'          => 'Gratuit (essai)',
        'payment_header'      => 'Confirmation de paiement',
        'provider_label'      => 'Prestataire',
        'paid_badge'          => '✓ PAYÉ',
        'transaction_label'   => 'Référence transaction',
        'amount_paid_label'   => 'Montant débité',
        'date_label'          => 'Date',
        'sandbox_label'       => 'Mode',
        'sandbox_value'       => 'Sandbox (test)',
        'btn_text'            => 'Accéder à mon espace →',
        'pdf_note'            => 'La <strong>facture PDF</strong> est jointe à cet email pour vos archives. Conservez-la pour toute demande de remboursement ou justificatif comptable.',
        'contact_note'        => 'Des questions ? Contactez notre support à',
        'footer_privacy'      => 'Politique de confidentialité',
        'footer_help'         => 'Aide',
    ],

    'automation' => [
        'subtitle'     => 'Gestion immobilière',
        'footer_auto'  => 'Ce rappel est généré automatiquement selon vos paramètres de notification.',

        // Thèmes
        'rappel_loyer_sujet'    => 'Rappel de loyer — :mois :annee',
        'rappel_loyer_badge'    => 'Rappel de loyer',
        'rappel_loyer_alert'    => 'Pour éviter tout désagrément ou pénalité, veuillez procéder au règlement dès que possible. En cas de difficulté, contactez directement votre agence.',
        'rappel_loyer_hl_label' => 'Montant du loyer',
        'rappel_loyer_hl_date'  => 'Date échéance',

        'escalade_sujet'  => 'Loyer impayé — Action requise',
        'escalade_badge'  => 'Loyer impayé',
        'escalade_alert'  => 'Ce rappel fait suite à un impayé constaté sur votre compte. Merci de régulariser votre situation dans les plus brefs délais pour éviter toute procédure.',

        'preavis_sujet'   => 'Préavis de fin de bail',
        'preavis_badge'   => 'Préavis de fin de bail',
        'preavis_alert'   => 'Conformément aux termes de votre contrat, vous êtes prié(e) de libérer les lieux et restituer les clés avant la date indiquée. Contactez votre agence pour l\'état des lieux de sortie.',

        'rapport_sujet'   => 'Rapport mensuel — :mois :annee',
        'rapport_badge'   => 'Rapport mensuel',
        'rapport_alert'   => 'Pour visualiser le détail complet de vos revenus et l\'état de vos biens, connectez-vous à votre espace Lokativ.',

        'renouvellement_sujet'  => 'Renouvellement de contrat à prévoir',
        'renouvellement_badge'  => 'Renouvellement de contrat',
        'renouvellement_alert'  => 'Merci de vous rapprocher de votre agence pour convenir des modalités de renouvellement avant la date d\'échéance.',
    ],

];
