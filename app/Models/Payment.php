<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    const STATUS_PAID = 'paid';
    const STATUS_NOT_PAID = 'not_paid';
    const STATUS_WAITING = 'waiting';
    const STATUS_CANCELED = 'canceled';
    const STATUTES = [self::STATUS_PAID, self::STATUS_NOT_PAID, self::STATUS_WAITING, self::STATUS_CANCELED];

    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    /**
     * Get the order that owns the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
