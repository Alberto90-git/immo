<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SubscriptionInvoiceMail;
use App\Services\SubscriptionInvoiceService;

class SendSubscriptionInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 60;

    protected $invoiceData;

    public function __construct(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }

    public function handle()
    {
        // Générer le PDF dans le contexte du job (async)
        $service = new SubscriptionInvoiceService();
        $pdfContent = $service->generate($this->invoiceData);

        // Ajouter le contenu PDF aux données pour le Mailable
        $mailData = $this->invoiceData;
        $mailData['pdf_content'] = $pdfContent;

        // Envoyer l'email avec le PDF en pièce jointe
        Mail::to($this->invoiceData['user']['email'])
            ->send(new SubscriptionInvoiceMail($mailData));
    }

    public function failed(\Throwable $exception)
    {
        Log::error('SendSubscriptionInvoiceJob failed: ' . $exception->getMessage(), [
            'email' => $this->invoiceData['user']['email'] ?? 'unknown',
        ]);
    }
}
