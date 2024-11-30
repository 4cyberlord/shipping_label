<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_notifications_enabled' => 'boolean',
        'sms_notifications_enabled' => 'boolean',
        'allowed_delivery_areas' => 'array',
        'tax_rate' => 'decimal:2',
        'maximum_parcel_weight' => 'decimal:2',
    ];
}
