<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'payment_date',
        'reference_number',
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who processed the payment.
     */
    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get formatted amount in USD.
     */
    public function getFormattedAmountAttribute(): string
    {
        return format_usd($this->amount);
    }

    /**
     * Get formatted amount in KHR.
     */
    public function getFormattedAmountKhrAttribute(): string
    {
        return format_khr(usd_to_khr($this->amount));
    }

    /**
     * Get formatted dual amount (USD + KHR).
     */
    public function getFormattedDualAmountAttribute(): string
    {
        return format_dual_currency($this->amount);
    }

    /**
     * Get payment method label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $methods = paymentMethods();
        if (isset($methods[$this->payment_method])) {
            return $methods[$this->payment_method];
        }

        return match($this->payment_method) {
            'cash' => 'Cash (USD)',
            'card' => 'Credit / Debit Card',
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            default => ucfirst(str_replace('_', ' ', $this->payment_method ?: 'unknown')),
        };
    }
}
