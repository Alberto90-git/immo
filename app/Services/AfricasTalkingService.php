<?php

namespace App\Services;

use App\PlatformConfig;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Http;

class AfricasTalkingService
{
    protected function getConfig(): array
    {
        $cfg = PlatformConfig::getConfig();

        return [
            'username'            => $cfg->at_username             ?? env('AT_USERNAME', ''),
            'api_key'             => $cfg->at_api_key              ?? env('AT_API_KEY', ''),
            'sender_id'           => $cfg->at_sender_id            ?? env('AT_SENDER_ID', ''),
            'whatsapp_product_id' => $cfg->at_whatsapp_product_id  ?? env('AT_WHATSAPP_PRODUCT_ID', ''),
        ];
    }

    public function estConnecte(): bool
    {
        $config = $this->getConfig();
        return !empty($config['username']) && !empty($config['api_key']);
    }

    /**
     * Envoie un SMS via le SDK Africa's Talking.
     */
    public function envoyerSMS(string $telephone, string $message): array
    {
        $config = $this->getConfig();

        if (!$this->estConnecte()) {
            return [
                'success' => false,
                'message' => 'Africa\'s Talking non configuré. Renseignez le Username et l\'API Key dans Paramétrage > Communication.',
            ];
        }

        $telephone = $this->formaterNumero($telephone);

        try {
            $AT  = new AfricasTalking($config['username'], $config['api_key']);
            $sms = $AT->sms();

            $params = [
                'to'      => $telephone,
                'message' => $message,
            ];

            if (!empty($config['sender_id'])) {
                $params['from'] = $config['sender_id'];
            }

            $response = $sms->send($params);

            if (($response['status'] ?? '') === 'success') {
                $data       = $response['data'];
                $recipients = $data->SMSMessageData->Recipients ?? [];
                $first      = $recipients[0] ?? null;
                $statusCode = (int) ($first->statusCode ?? 0);

                // 100 = Processed, 101 = Sent, 102 = Queued → succès
                if (in_array($statusCode, [100, 101, 102])) {
                    return ['success' => true, 'message' => 'SMS envoyé avec succès.'];
                }

                $err = $first->status ?? ('Code ' . $statusCode);
                return ['success' => false, 'message' => 'Échec envoi SMS : ' . $err];
            }

            return ['success' => false, 'message' => 'Échec envoi SMS.'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur SMS : ' . $e->getMessage()];
        }
    }

    /**
     * Vérifie que le WhatsApp AT est configuré (productId requis en plus des credentials).
     */
    public function whatsappConnecte(): bool
    {
        $config = $this->getConfig();
        return $this->estConnecte() && !empty($config['whatsapp_product_id']);
    }

    /**
     * Envoie un message WhatsApp via l'API Africa's Talking (productId requis).
     */
    public function envoyerWhatsApp(string $telephone, string $message): array
    {
        $config = $this->getConfig();

        if (!$this->whatsappConnecte()) {
            return [
                'success' => false,
                'message' => 'WhatsApp AT non configuré. Renseignez le Product ID WhatsApp dans Paramétrage > Communication.',
            ];
        }

        $telephone = $this->formaterNumero($telephone);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'apiKey'       => $config['api_key'],
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->asForm()
                ->post('https://chat.africastalking.com/whatsapp/messaging/send', [
                    'username'  => $config['username'],
                    'productId' => $config['whatsapp_product_id'],
                    'channel'   => 'whatsapp',
                    'to'        => $telephone,
                    'message'   => $message,
                ]);

            $statusHttp = $response->status();
            $body       = $response->body();
            $data       = $response->json() ?? [];

            if ($response->successful()) {
                if (($data['status'] ?? '') === 'success' || isset($data['messageId'])) {
                    return ['success' => true, 'message' => 'Message WhatsApp envoyé avec succès.'];
                }
                $err = $data['message'] ?? $data['errorMessage'] ?? $body;
                return ['success' => false, 'message' => "Échec WhatsApp (HTTP {$statusHttp}) : {$err}"];
            }

            $err = $data['message'] ?? $data['errorMessage'] ?? $body;
            return ['success' => false, 'message' => "Échec WhatsApp (HTTP {$statusHttp}) : {$err}"];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Erreur WhatsApp : ' . $e->getMessage()];
        }
    }

    /**
     * Normalise un numéro de téléphone béninois en format international.
     */
    public function formaterNumero(string $tel): string
    {
        $tel = preg_replace('/[\s\-\(\)]/', '', $tel);

        if (str_starts_with($tel, '+')) {
            return $tel;
        }
        if (str_starts_with($tel, '00')) {
            return '+' . substr($tel, 2);
        }
        if (str_starts_with($tel, '229')) {
            return '+' . $tel;
        }
        if (strlen($tel) === 8) {
            return '+229' . $tel;
        }

        return $tel;
    }
}
