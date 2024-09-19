<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchantAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_detail',
        'postal_code',
        'province',
        'city',
        'district',
        'village',
    ];


    public function merchant(): BelongsTo
    {
        return $this->belongsTo(MerchantAddress::class);
    }
}
