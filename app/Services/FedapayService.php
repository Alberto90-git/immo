<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedapayService
{
    protected string $secretKey;
    protected bool $sandbox;

    public function __construct(string $secretKey, bool $sandbox = true)
    {
        $this->secretKey = $secretKey;
        $this->sandbox   = $sandbox;
    }

    /**
     * Vérifie une transaction FedaPay côté serveur.
     *
     * @param  string $transactionId  L'ID retourné par le widget JS
     * @return array  ['success' => bool, 'status' => string, 'amount' => int]
     */
    public function verifyTransaction(string $transactionId): array
    {
        $baseUrl = $this->sandbox
            ? 'https://api.sandbox.fedapay.com'
            : 'https://api.fedapay.com';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept'        => 'application/json',
            ])->get("{$baseUrl}/v1/transactions/{$transactionId}");

            if (!$response->successful()) {
                Log::warning('FedaPay API error', [
                    'status'        => $response->status(),
                    'transactionId' => $transactionId,
                ]);
                return ['success' => false, 'status' => 'API_ERROR', 'amount' => 0];
            }

            $body = $response->json();

            // La réponse FedaPay encapsule la transaction sous la clé 'v1/transaction'
            $transaction = $body['v1/transaction'] ?? $body['transaction'] ?? $body ?? [];

            $status = $transaction['status'] ?? '';
            $amount = (int) ($transaction['amount'] ?? 0);

            return [
                'success' => $status === 'approved',
                'status'  => $status,
                'amount'  => $amount,
            ];
        } catch (\Exception $e) {
            Log::error('FedaPay verifyTransaction exception', [
                'message'       => $e->getMessage(),
                'transactionId' => $transactionId,
            ]);
            return ['success' => false, 'status' => 'EXCEPTION', 'amount' => 0];
        }
    }
}
