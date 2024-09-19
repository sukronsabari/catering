<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_official',
        'banner_image',
        'description',
        'phone',
        'social_links',
    ];

    // protected $casts = [
    //     'social_links' => 'array',
    // ];

    protected function socialLinks(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                // Decode JSON menjadi array saat mengakses
                $data = json_decode($value, true);

                return $data;
            },
            set: function ($value) {
                // Asumsikan $value adalah array of objects, ubah menjadi array key-value dan encode ke JSON
                $formattedLinks = [];
                foreach ($value as $item) {
                    $formattedLinks[$item['platform']] = $item['link'];
                }

                return json_encode($formattedLinks);
            }
        );
    }

    protected function formattedSocialLinks(): Attribute
    {
        return Attribute::make(
            get: function () {
                $links = $this->social_links;

                if (empty($links)) {
                    return 'No social links';
                }

                return collect($links)->map(function ($link, $platform) {
                    return ucfirst($platform) . ": '{$link}'";
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
