<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function skus(): BelongsToMany
    {
        return $this->belongsToMany(Sku::class, 'product_attribute_sku')
            ->withPivot('value');
    }
}
