<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'from_address_id',
        'to_address_id',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'shipping_cost',
        'label_data',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'label_data' => 'json',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function fromAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'from_address_id');
    }

    public function toAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'to_address_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (!$package->tracking_number) {
                $package->tracking_number = 'PKG-' . strtoupper(uniqid());
            }
        });
    }
}
