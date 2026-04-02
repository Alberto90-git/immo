<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagingRate extends Model
{
    protected $table = 'messaging_rates';

    protected $fillable = [
        'country_code',
        'country_name',
        'sms_unit_cost',
        'whatsapp_unit_cost',
        'currency',
        'is_default',
    ];

    protected $casts = [
        'sms_unit_cost'      => 'decimal:2',
        'whatsapp_unit_cost' => 'decimal:2',
        'is_default'         => 'boolean',
    ];

    public static function getForCountry(string $code): self
    {
        return static::where('country_code', $code)->first()
            ?? static::where('is_default', true)->first()
            ?? static::first();
    }
}
