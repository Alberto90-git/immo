<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlatformConfig extends Model
{
    protected $table = 'platform_configs';

    protected $fillable = [
        // KKiaPay
        'kkiapay_public_key',
        'kkiapay_private_key',
        'kkiapay_secret_key',
        'kkiapay_sandbox',
        'kkiapay_enabled',
        // FedaPay
        'fedapay_public_key',
        'fedapay_secret_key',
        'fedapay_sandbox',
        // Prestataire actif
        'active_payment_provider',
    ];

    protected $casts = [
        'kkiapay_sandbox'  => 'boolean',
        'kkiapay_enabled'  => 'boolean',
        'fedapay_sandbox'  => 'boolean',
    ];

    /**
     * Retourne la ligne unique de configuration (la crée si elle n'existe pas).
     */
    public static function getConfig(): self
    {
        return static::firstOrCreate([], [
            'kkiapay_public_key'      => null,
            'kkiapay_private_key'     => null,
            'kkiapay_secret_key'      => null,
            'kkiapay_sandbox'         => true,
            'kkiapay_enabled'         => false,
            'fedapay_public_key'      => null,
            'fedapay_secret_key'      => null,
            'fedapay_sandbox'         => true,
            'active_payment_provider' => 'none',
        ]);
    }

    // ─── Prestataire actif ────────────────────────────────────────────────────

    /** Retourne 'kkiapay', 'fedapay' ou 'none'. */
    public function getActiveProvider(): string
    {
        return $this->active_payment_provider ?? 'none';
    }

    public function isKkiapayActive(): bool
    {
        return $this->getActiveProvider() === 'kkiapay';
    }

    public function isFedapayActive(): bool
    {
        return $this->getActiveProvider() === 'fedapay';
    }

    /**
     * Clé publique du prestataire actif (utilisée par le widget JS).
     */
    public function getActivePublicKey(): ?string
    {
        if ($this->isKkiapayActive()) {
            return !empty($this->kkiapay_public_key) ? $this->kkiapay_public_key : null;
        }
        if ($this->isFedapayActive()) {
            return !empty($this->fedapay_public_key) ? $this->fedapay_public_key : null;
        }
        return null;
    }

    /**
     * Mode sandbox du prestataire actif.
     */
    public function getActiveSandbox(): bool
    {
        if ($this->isKkiapayActive()) {
            return (bool) $this->kkiapay_sandbox;
        }
        if ($this->isFedapayActive()) {
            return (bool) $this->fedapay_sandbox;
        }
        return true;
    }

    /**
     * Indique si un prestataire de paiement est opérationnel (actif + clé publique présente).
     * Pour KKiaPay : exige aussi la clé privée.
     */
    public function isOperational(): bool
    {
        if ($this->isKkiapayActive()) {
            return !empty($this->kkiapay_public_key) && !empty($this->kkiapay_private_key) && !empty($this->kkiapay_secret_key);
        }
        if ($this->isFedapayActive()) {
            return !empty($this->fedapay_public_key) && !empty($this->fedapay_secret_key);
        }
        return false;
    }
}
