<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'shipping_charge',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'shipping_full_name',
        'shipping_mobile',
        'shipping_email',
        'shipping_address_line',
        'shipping_landmark',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_postal_code',
        'billing_same_as_shipping',
        'billing_full_name',
        'billing_mobile',
        'billing_email',
        'billing_address_line',
        'billing_landmark',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_postal_code',
        'admin_notes',
        'placed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'billing_same_as_shipping' => 'boolean',
            'placed_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'shipping_charge' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd').'-'.str_pad((string) (static::whereDate('created_at', now())->count() + 1), 5, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::created(function (Order $order): void {
            $order->invoice()->create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'issued_at' => now(),
            ]);

            $order->statusHistories()->create([
                'status' => $order->status,
                'note' => 'Order placed.',
            ]);
        });
    }
}
