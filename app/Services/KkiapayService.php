<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KkiapayService
{
    private string $publicKey;
    private string $privateKey;
    private string $secretKey;
    private bool   $sandbox;

    public function __construct(string $publicKey, string $privateKey, string $secretKey, bool $sandbox = true)
    {
        $this->publicKey  = $publicKey;
        $this->privateKey = $privateKey;
        $this->secretKey  = $secretKey;
        $this->sandbox    = $sandbox;
    }

    /**
     * Vérifie une transaction KKiaPay auprès de l'API.
     *
     * @return array{success: bool, status: string, amount: int}
     */
    public function verifyTransaction(string $transactionId): array
    {
        $baseUrl = $this->sandbox
            ? 'https://api-sandbox.kkiapay.me'
            : 'https://api.kkiapay.me';

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-API-KEY'     => $this->publicKey,
                    'X-PRIVATE-KEY' => $this->privateKey,
                    'X-SECRET-KEY'  => $this->secretKey,
                    'Accept'        => 'application/json',
                ])
                ->post("{$baseUrl}/api/v1/transactions/status", [
                    'transactionId' => $transactionId,
                ]);

            if ($response->successful()) {
                $data   = $response->json();
                $status = $data['status'] ?? 'UNKNOWN';

                return [
                    'success' => $status === 'SUCCESS',
                    'status'  => $status,
                    'amount'  => (int) ($data['amount'] ?? 0),
                    'data'    => $data,
                ];
            }

            Log::error('KKiaPay: vérification échouée', [
                'transaction_id' => $transactionId,
                'http_status'    => $response->status(),
                'body'           => $response->body(),
            ]);

            return ['success' => false, 'status' => 'API_ERROR', 'amount' => 0];

        } catch (\Exception $e) {
            Log::error('KKiaPay: exception lors de la vérification', [
                'transaction_id' => $transactionId,
                'message'        => $e->getMessage(),
            ]);

            return ['success' => false, 'status' => 'EXCEPTION', 'amount' => 0];
        }
    }
}
