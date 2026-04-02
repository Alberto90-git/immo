<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'sujet',
        'message',
        'lu',
        'ip_address',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    public static function countUnread(): int
    {
        return static::where('lu', false)->count();
    }
}
