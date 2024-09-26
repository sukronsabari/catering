<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use function PHPUnit\Framework\isEmpty;

class Merchant extends Model
{
    use HasFactory;

    // protected $with = ['user'];

    protected $fillable = [
        'name',
        'is_official',
        'banner_image',
        'description',
        'phone',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    protected function socialLinks(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                // Decode JSON menjadi array assosiatif saat mengakses
                $data = json_decode($value, true);

                return $data;
            },
        );
    }

    protected function formattedSocialLinks(): Attribute
    {
        return Attribute::make(
            get: function () {
                $links = $this->social_links ? $this->social_links : [];

                if (empty($links)) {
                    return 'No social links';
                }

                return collect($links)->map(function ($link) {
                    return ucfirst($link['platform']) . ": '{$link['link']}'";
                })->implode(', ');
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(MerchantAddress::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
