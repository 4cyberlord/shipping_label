<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'company_name',
        'contact_name',
        'phone',
        'email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'country',
        'delivery_instructions',
    ];

    public function fromPackages(): HasMany
    {
        return $this->hasMany(Package::class, 'from_address_id');
    }

    public function toPackages(): HasMany
    {
        return $this->hasMany(Package::class, 'to_address_id');
    }
}
