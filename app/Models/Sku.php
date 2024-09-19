<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'currency_code',
        'price',
        'stock',
        'weight',
        'is_active',
        'is_default',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_attribute_sku')
            ->withPivot('value');
    }
}
