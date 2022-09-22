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

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'payment_intent',
        'payment_status',
        'payment_method',
        'payment_error',
    ];

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
