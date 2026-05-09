<?php

return [

    // ── Common ────────────────────────────────────────────────────────────────────
    'greeting'       => 'Hello :name,',
    'footer_rights'  => '© :year Lokativ. All rights reserved.',
    'footer_auto'    => 'This message was sent automatically — please do not reply directly.',
    'contact_note'   => 'For any questions, contact :agence or write to',
    'auto_note'      => 'This message was sent automatically by Lokativ. Please do not reply directly.',
    'wa_closing'     => "Best regards,\n:agence",

    // ── Rent reminder ─────────────────────────────────────────────────────────────
    'rappel_loyer' => [
        'title'           => 'Rent Payment Reminder',
        'subject'         => 'Rent Payment Reminder',
        'badge'           => '⏰ Rent Reminder',
        'intro'           => 'We are sending you this reminder regarding the payment of your rent. Please settle your balance as soon as possible.',
        'amount_label'    => 'Rent Amount Due',
        'logement_header' => 'Property Details',
        'logement_label'  => 'Property',
        'alert'           => 'To avoid any inconvenience or penalties, please make your payment as soon as possible. If you are experiencing difficulties, contact your agency directly.',
        'wa_body'         => "This is a reminder that your rent for the month of :mois in the amount of :montant :devise is due.\n\nPlease settle your balance as soon as possible.",
    ],

    // ── End of lease notice ───────────────────────────────────────────────────────
    'preavis' => [
        'title'           => 'End of Lease Notice',
        'subject'         => 'End of Lease Notice',
        'badge'           => '📋 End of Lease Notice',
        'intro'           => ':agence hereby officially notifies you of the upcoming end of your lease agreement.',
        'date_label'      => 'Lease End Date',
        'logement_header' => 'Property Concerned',
        'logement_label'  => 'Address / Reference',
        'body_1'          => 'In accordance with the terms of your lease, you are requested to <strong>vacate the premises and return the keys</strong> before the date indicated above.',
        'body_2'          => 'We invite you to contact our agency to schedule the move-out property inspection.',
        'alert'           => 'After this deadline without vacating the property, additional charges may apply. For any arrangement or question, contact your agency directly.',
        'wa_body'         => "We are informing you that your lease for property :logement expires on :date.\n\nIn accordance with your lease terms, you are requested to vacate the premises and return the keys before this date.",
    ],

    // ── Debt collection / Reminders ───────────────────────────────────────────────
    'recouvrement' => [
        'subtitle'         => 'Lokativ · Property Management',
        'type_rappel1'     => '1st Payment Reminder',
        'type_rappel2'     => '2nd Payment Reminder',
        'type_demeure'     => 'Formal Notice',
        'montant_label'    => 'Unpaid Amount',
        'mois_impayes'     => ':n unpaid months',
        'legal_title'      => '⚖️ Legal Deadline: 8 days',
        'legal_body'       => 'If payment is not made within this deadline, legal proceedings will be initiated, including lease termination and eviction.',
        'contact_note'     => 'For any questions or to arrange a payment plan, please do not hesitate to contact us.',
        'footer_text'      => 'This message is sent to you by your property manager via Lokativ.',
        'footer_no_reply'  => 'Please do not reply to this automated email.',
    ],

    // ── Inventory signature request ───────────────────────────────────────────────
    'signature_edl' => [
        'subtitle'     => 'Property Inventory – Signature Request',
        'intro'        => 'Your agency invites you to sign your <strong>:typeLabel</strong> dated <strong>:date</strong>.',
        'doc_label'    => 'Document:',
        'date_label'   => 'Date:',
        'agency_label' => 'Agency:',
        'cta_text'     => 'Click the button below to view the property inventory and add your electronic signature:',
        'btn_text'     => 'View and Sign →',
        'fallback'     => 'If you cannot click the button, copy this link into your browser:',
        'footer_sent'  => 'This message was sent by :agence.',
        'footer_ignore'=> 'If you are not concerned by this document, please ignore this email.',
    ],

    // ── Document delivery ─────────────────────────────────────────────────────────
    'document_envoi' => [
        'badge'           => '📎 Attached Document',
        'intro'           => 'Please find attached the following document, sent by <strong>:agence</strong>.',
        'doc_header'      => 'Transmitted Document',
        'doc_type_label'  => 'Document Type',
        'sent_by_label'   => 'Sent by',
        'attachment_note' => 'The document is available as a <strong>PDF attachment</strong> in this email. Please keep it for your records.',
        'contact_note'    => 'For any questions, contact your agency directly or write to',
    ],

    // ── Maintenance notification ──────────────────────────────────────────────────
    'maintenance' => [
        'title'           => 'Update on Your Maintenance Request',
        'badge'           => '🔔 Maintenance Update',
        'intro'           => 'We are informing you of an update regarding your maintenance request.',
        'status_header'   => 'Request Details',
        'status_ticket'   => 'Request',
        'status_logement' => 'Property',
        'status_categorie'=> 'Category',
        'status_en_cours' => 'In Progress',
        'status_resolu'   => 'Resolved',
        'status_cloture'  => 'Closed',
        'note'            => 'For any questions, contact your agency directly. This message is sent automatically — please do not reply.',
    ],

    // ── Payment invoice (platform subscription) ───────────────────────────────────
    'paiement' => [
        'title'          => 'Payment Invoice',
        'badge'          => '🧾 Payment Invoice',
        'subtitle'       => 'Property Management Platform',
        'intro'          => 'Thank you for your trust. Here are the details of your subscription invoice.',
        'amount_label'   => 'Total Amount',
        'plan_header'    => 'Subscription Details',
        'client_label'   => 'Client',
        'plan_label'     => 'Selected Plan',
        'duration_label' => 'Subscription Duration',
        'duration_value' => ':n months',
        'code_label'     => 'Payment Code',
        'mobile_label'   => 'Mobile Money Number',
        'date_label'     => 'Due Date',
        'alert'          => 'Please make your payment before the due date to avoid any service interruption.',
        'btn_text'       => 'Access My Account',
        'contact_note'   => 'For any questions, contact our support at',
    ],

    // ── Automation ────────────────────────────────────────────────────────────────
    // ── Verification code (OTP) ───────────────────────────────────────────────────
    'code_email' => [
        'subject'      => 'Your Lokativ verification code',
        'subtitle'     => 'Property Management Platform',
        'title'        => 'Identity Verification',
        'greeting'     => 'Hello,',
        'intro'        => 'A login attempt was detected on your account. To confirm it was you, please use the verification code below.',
        'code_label'   => 'Your login code',
        'expiry'       => 'This code expires in <strong>10 minutes</strong>',
        'instructions' => 'Enter this code in the login window to access your Lokativ account.',
        'warning'      => '<strong>Warning:</strong> Never share this code with anyone, including our support team. Lokativ will never ask for your verification code. If you did not initiate this login, ignore this email and secure your account.',
        'contact_note' => 'If you have any questions, contact our support at',
        'privacy'      => 'Privacy Policy',
        'help'         => 'Help',
    ],

    // ── Verification email / Login information ────────────────────────────────
    'verify_email' => [
        'subject'            => 'Login information',
        'title'              => 'Your Lokativ account has been created',
        'platform'           => 'Property management platform',
        'badge'              => '✔ Account successfully created',
        'welcome'            => 'Welcome to Lokativ, :prenom!',
        'greeting'           => 'Hello :nom :prenom,',
        'created'            => 'Your account has been successfully created on the Lokativ platform. You will find your login credentials below.',
        'credentials_header' => 'Your login credentials',
        'email_label'        => 'Email address',
        'password_label'     => 'Temporary password',
        'btn_text'           => 'Access my account →',
        'security_note'      => 'For your security, we recommend <strong>changing your password</strong> upon your first login from your profile settings.',
        'contact_note'       => 'Questions? Contact our support at',
        'footer_privacy'     => 'Privacy Policy',
        'footer_help'        => 'Help',
    ],

    // ── Subscription invoice at registration ──────────────────────────────────
    'subscription_invoice' => [
        'subject'             => 'Lokativ - Subscription Invoice',
        'title'               => 'Lokativ Subscription Invoice',
        'platform'            => 'Property management platform',
        'badge'               => '🧾 Subscription Invoice',
        'welcome'             => 'Thank you for your trust, :prenom!',
        'greeting'            => 'Hello :nom :prenom,',
        'intro'               => 'Your <strong>Lokativ</strong> subscription is confirmed. You will find below the summary and the PDF invoice attached.',
        'plan_header'         => 'Subscribed Plan',
        'plan_label'          => 'Plan',
        'period_label'        => 'Period',
        'amount_label'        => 'Amount',
        'free_trial'          => 'Free (trial)',
        'payment_header'      => 'Payment Confirmation',
        'provider_label'      => 'Provider',
        'paid_badge'          => '✓ PAID',
        'transaction_label'   => 'Transaction Reference',
        'amount_paid_label'   => 'Amount Charged',
        'date_label'          => 'Date',
        'sandbox_label'       => 'Mode',
        'sandbox_value'       => 'Sandbox (test)',
        'btn_text'            => 'Access my account →',
        'pdf_note'            => 'The <strong>PDF invoice</strong> is attached to this email for your records. Keep it for any refund request or accounting document.',
        'contact_note'        => 'Questions? Contact our support at',
        'footer_privacy'      => 'Privacy Policy',
        'footer_help'         => 'Help',
    ],

    // ── Document labels (email + filename) ───────────────────────────────────────
    'document_labels' => [
        'contrat'               => 'Lease Agreement for :name',
        'quittance_mensuelle'   => 'Monthly Receipt for :name — :mois',
        'quittance_caution'     => 'Security Deposit Receipt for :name',
        'releve_proprietaire'   => 'Owner Statement for :name from :debut to :fin',
        'releve_agence'         => 'Agency Statement for :name from :debut to :fin',
        'releve_locataire'      => 'Tenant Statement for :name',
        'attestation_residence' => 'Certificate of Residence for :name',
        'attestation_paiement'  => 'Payment Certificate for :name — :mois',
    ],

    'automation' => [
        'subtitle'     => 'Property Management',
        'footer_auto'  => 'This reminder is generated automatically based on your notification settings.',

        // Themes
        'rappel_loyer_sujet'    => 'Rent Reminder — :mois :annee',
        'rappel_loyer_badge'    => 'Rent Reminder',
        'rappel_loyer_alert'    => 'To avoid any inconvenience or penalties, please make your payment as soon as possible. If you are experiencing difficulties, contact your agency directly.',
        'rappel_loyer_hl_label' => 'Rent Amount',
        'rappel_loyer_hl_date'  => 'Due Date',

        'escalade_sujet'  => 'Unpaid Rent — Action Required',
        'escalade_badge'  => 'Unpaid Rent',
        'escalade_alert'  => 'This reminder follows an unpaid balance on your account. Please settle your situation as soon as possible to avoid any legal proceedings.',

        'preavis_sujet'   => 'End of Lease Notice',
        'preavis_badge'   => 'End of Lease Notice',
        'preavis_alert'   => 'In accordance with your lease terms, you are requested to vacate the premises and return the keys before the indicated date. Contact your agency for the move-out inspection.',

        'rapport_sujet'   => 'Monthly Report — :mois :annee',
        'rapport_badge'   => 'Monthly Report',
        'rapport_alert'   => 'To view the full details of your income and the status of your properties, log in to your Lokativ account.',

        'renouvellement_sujet'  => 'Lease Renewal Required',
        'renouvellement_badge'  => 'Lease Renewal',
        'renouvellement_alert'  => 'Please contact your agency to discuss the terms of renewal before the expiry date.',
    ],

];
