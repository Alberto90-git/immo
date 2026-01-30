<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;

    public function __construct(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function build()
    {
        return $this->subject("ImmoManager - Facture d'abonnement")
                    ->view('mail.subscriptionInvoice')
                    ->attachData(
                        $this->invoiceData['pdf_content'],
                        'Facture_ImmoManager_' . date('Ymd') . '.pdf',
                        ['mime' => 'application/pdf']
                    );
    }
}
