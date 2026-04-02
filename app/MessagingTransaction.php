<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessagingTransaction extends Model
{
    protected $table = 'messaging_transactions';

    protected $fillable = [
        'iddirection_ref',
        'channel',
        'recipient_count',
        'unit_cost',
        'total_amount',
        'currency',
        'country_code',
        'payment_provider',
        'payment_transaction_id',
        'payment_status',
        'messages_sent',
    ];

    protected $casts = [
        'messages_sent' => 'boolean',
    ];
}
