<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';
    protected $primaryKey = 'idplan';

    protected $fillable = [
        'nom',
        'code',
        'description',
        'max_maisons',
        'max_annexes',
        'max_envois_email',
        'max_envois_whatsapp',
        'max_rappels_loyer',
        'max_preavis',
        'max_publicites',
        'sms_enabled',
        'whatsapp_enabled',
        'prix_annuel',
        'prix_mensuel',
        'is_active',
        'delete_at'
    ];

    protected $casts = [
        'max_maisons'         => 'integer',
        'max_annexes'         => 'integer',
        'max_envois_email'    => 'integer',
        'max_envois_whatsapp' => 'integer',
        'max_rappels_loyer'   => 'integer',
        'max_preavis'         => 'integer',
        'max_publicites'      => 'integer',
        'prix_annuel'         => 'decimal:2',
        'prix_mensuel'        => 'decimal:2',
        'is_active'           => 'boolean',
        'sms_enabled'         => 'boolean',
        'whatsapp_enabled'    => 'boolean',
    ];

    /**
     * Relation avec les directions (entreprises)
     */
    public function directions()
    {
        return $this->hasMany(Direction::class, 'idplan_ref', 'idplan');
    }

    /**
     * Vérifie si le plan permet des maisons illimitées
     */
    public function hasMaisonsIllimitees()
    {
        return $this->max_maisons === 0;
    }

    /**
     * Vérifie si le plan permet de créer des annexes
     */
    public function canCreateAnnexes()
    {
        return $this->max_annexes > 0;
    }

    /**
     * Récupère le plan Essai
     */
    public static function essai()
    {
        return self::where('code', 'essai')->first();
    }

    /**
     * Récupère le plan Starter
     */
    public static function starter()
    {
        return self::where('code', 'starter')->first();
    }

    /**
     * Récupère le plan Standard
     */
    public static function standard()
    {
        return self::where('code', 'standard')->first();
    }

    /**
     * Récupère le plan Premium
     */
    public static function premium()
    {
        return self::where('code', 'premium')->first();
    }

    /**
     * Récupère tous les plans actifs
     */
    public static function actifs()
    {
        return self::where('is_active', true)->whereNull('delete_at')->get();
    }
}
