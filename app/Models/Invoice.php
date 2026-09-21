<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'check_in_id',
        'guest_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'payment_method',
        'status',
        'invoice_date',
        'due_date',
        'notes',
        'items',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(CheckIn::class);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedTotalAttribute()
    {
        return format_usd($this->total_amount);
    }

    public function getFormattedPaidAttribute()
    {
        return format_usd($this->paid_amount);
    }

    public function getFormattedBalanceAttribute()
    {
        return format_usd($this->balance_amount);
    }

    public function getFormattedTotalKhrAttribute()
    {
        return format_khr(usd_to_khr($this->total_amount));
    }

    public function getFormattedPaidKhrAttribute()
    {
        return format_khr(usd_to_khr($this->paid_amount));
    }

    public function getFormattedBalanceKhrAttribute()
    {
        return format_khr(usd_to_khr($this->balance_amount));
    }

    public function getFormattedDualTotalAttribute()
    {
        return format_dual_currency($this->total_amount);
    }

    public function getFormattedDualBalanceAttribute()
    {
        return format_dual_currency($this->balance_amount);
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = paymentMethods();
        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method ?: 'unpaid'));
    }
}
