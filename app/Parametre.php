<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $table = 'parametres';

    protected $fillable = [
        'iddirection_ref',
        'timezone',
        'format_choisi',
        'cash_electronique_url',
        'logo_url',
        'email_envoi',
        'whatsapp_numero_envoi',
        'whatsapp_api_token',
        'whatsapp_phone_number_id',
        // Multi-devise, multi-pays & locale
        'pays',
        'devise',
        'locale',
        'indicatif_tel',
        'format_date',
        'taux_change',
        'taxes',
        // Africa's Talking
        'at_username',
        'at_api_key',
        'at_sender_id',
        'at_whatsapp_product_id',
    ];

    protected $casts = [
        'taux_change' => 'array',
        'taxes'       => 'array',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // Accesseurs fichiers
    // ─────────────────────────────────────────────────────────────────────────

    public function getCashElectroniqueUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getLogoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Devise
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Retourne le code devise configuré (défaut XOF).
     */
    public function getDeviseCodeAttribute(): string
    {
        return $this->devise ?? 'XOF';
    }

    /**
     * Retourne la config complète de la devise configurée.
     */
    public function getDeviseConfigAttribute(): array
    {
        return self::deviseConfig($this->devise_code);
    }

    /**
     * Formate un montant avec la devise de cette direction.
     */
    public function formatMontant(float $montant): string
    {
        $cfg = $this->devise_config;
        $formatted = number_format($montant, $cfg['decimales'], $cfg['sep_decimal'], $cfg['sep_milliers']);
        return $cfg['symbole_avant']
            ? $cfg['symbole'] . ' ' . $formatted
            : $formatted . ' ' . $cfg['symbole'];
    }

    /**
     * Retourne le taux TVA configuré (0 si non défini).
     */
    public function getTvaAttribute(): float
    {
        return (float) ($this->taxes['tva'] ?? 0);
    }

    /**
     * Indique si la TVA est applicable.
     */
    public function isTvaApplicable(): bool
    {
        return (bool) ($this->taxes['tva_applicable'] ?? false);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Référentiel statique des devises supportées
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Retourne la configuration d'une devise.
     *
     * @param string $code  Code ISO 4217 (XOF, XAF, GHS, NGN, EUR, USD)
     * @return array{
     *   code: string,
     *   label: string,
     *   symbole: string,
     *   symbole_avant: bool,
     *   decimales: int,
     *   sep_decimal: string,
     *   sep_milliers: string
     * }
     */
    public static function deviseConfig(string $code): array
    {
        $configs = [
            'XOF' => ['code'=>'XOF','label'=>'Franc CFA (UEMOA)','symbole'=>'FCFA','symbole_avant'=>false,'decimales'=>0,'sep_decimal'=>',','sep_milliers'=>' '],
            'XAF' => ['code'=>'XAF','label'=>'Franc CFA (CEMAC)','symbole'=>'FCFA','symbole_avant'=>false,'decimales'=>0,'sep_decimal'=>',','sep_milliers'=>' '],
            'GHS' => ['code'=>'GHS','label'=>'Cedi ghanéen','symbole'=>'GH₵','symbole_avant'=>true,'decimales'=>2,'sep_decimal'=>'.','sep_milliers'=>','],
            'NGN' => ['code'=>'NGN','label'=>'Naira nigérian','symbole'=>'₦','symbole_avant'=>true,'decimales'=>2,'sep_decimal'=>'.','sep_milliers'=>','],
            'EUR' => ['code'=>'EUR','label'=>'Euro','symbole'=>'€','symbole_avant'=>false,'decimales'=>2,'sep_decimal'=>',','sep_milliers'=>' '],
            'USD' => ['code'=>'USD','label'=>'Dollar américain','symbole'=>'$','symbole_avant'=>true,'decimales'=>2,'sep_decimal'=>'.','sep_milliers'=>','],
        ];

        return $configs[$code] ?? $configs['XOF'];
    }

    /**
     * Toutes les devises supportées (pour les selects).
     */
    public static function devises(): array
    {
        return array_map(fn($cfg) => ['code' => $cfg['code'], 'label' => $cfg['label']], [
            self::deviseConfig('XOF'),
            self::deviseConfig('XAF'),
            self::deviseConfig('GHS'),
            self::deviseConfig('NGN'),
            self::deviseConfig('EUR'),
            self::deviseConfig('USD'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Référentiel statique des pays supportés
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Retourne la configuration d'un pays.
     *
     * @param string $code  Code ISO 3166-1 alpha-2
     */
    public static function paysConfig(string $code): array
    {
        $pays = self::paysList();
        return $pays[$code] ?? $pays['BJ'];
    }

    /**
     * Liste complète des pays supportés.
     */
    public static function paysList(): array
    {
        return [
            'BJ' => ['code'=>'BJ','nom'=>'Bénin',           'devise'=>'XOF','indicatif'=>'+229','format_date'=>'d/m/Y','drapeau'=>'🇧🇯'],
            'SN' => ['code'=>'SN','nom'=>'Sénégal',          'devise'=>'XOF','indicatif'=>'+221','format_date'=>'d/m/Y','drapeau'=>'🇸🇳'],
            'CI' => ['code'=>'CI','nom'=>"Côte d'Ivoire",    'devise'=>'XOF','indicatif'=>'+225','format_date'=>'d/m/Y','drapeau'=>'🇨🇮'],
            'ML' => ['code'=>'ML','nom'=>'Mali',              'devise'=>'XOF','indicatif'=>'+223','format_date'=>'d/m/Y','drapeau'=>'🇲🇱'],
            'BF' => ['code'=>'BF','nom'=>'Burkina Faso',      'devise'=>'XOF','indicatif'=>'+226','format_date'=>'d/m/Y','drapeau'=>'🇧🇫'],
            'TG' => ['code'=>'TG','nom'=>'Togo',              'devise'=>'XOF','indicatif'=>'+228','format_date'=>'d/m/Y','drapeau'=>'🇹🇬'],
            'NE' => ['code'=>'NE','nom'=>'Niger',             'devise'=>'XOF','indicatif'=>'+227','format_date'=>'d/m/Y','drapeau'=>'🇳🇪'],
            'GN' => ['code'=>'GN','nom'=>'Guinée',            'devise'=>'XOF','indicatif'=>'+224','format_date'=>'d/m/Y','drapeau'=>'🇬🇳'],
            'CM' => ['code'=>'CM','nom'=>'Cameroun',          'devise'=>'XAF','indicatif'=>'+237','format_date'=>'d/m/Y','drapeau'=>'🇨🇲'],
            'GA' => ['code'=>'GA','nom'=>'Gabon',             'devise'=>'XAF','indicatif'=>'+241','format_date'=>'d/m/Y','drapeau'=>'🇬🇦'],
            'CG' => ['code'=>'CG','nom'=>'Congo',             'devise'=>'XAF','indicatif'=>'+242','format_date'=>'d/m/Y','drapeau'=>'🇨🇬'],
            'CD' => ['code'=>'CD','nom'=>'Congo (RDC)',        'devise'=>'XAF','indicatif'=>'+243','format_date'=>'d/m/Y','drapeau'=>'🇨🇩'],
            'GH' => ['code'=>'GH','nom'=>'Ghana',             'devise'=>'GHS','indicatif'=>'+233','format_date'=>'d/m/Y','drapeau'=>'🇬🇭'],
            'NG' => ['code'=>'NG','nom'=>'Nigeria',           'devise'=>'NGN','indicatif'=>'+234','format_date'=>'d/m/Y','drapeau'=>'🇳🇬'],
            'FR' => ['code'=>'FR','nom'=>'France',            'devise'=>'EUR','indicatif'=>'+33', 'format_date'=>'d/m/Y','drapeau'=>'🇫🇷'],
            'US' => ['code'=>'US','nom'=>'États-Unis',        'devise'=>'USD','indicatif'=>'+1',  'format_date'=>'m/d/Y','drapeau'=>'🇺🇸'],
        ];
    }
}
