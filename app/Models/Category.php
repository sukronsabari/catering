<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    protected $fillable = [
        'name',
        'slug',
        'featured',
        'description',
        'parent_id',
        'image',
        'icon'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
