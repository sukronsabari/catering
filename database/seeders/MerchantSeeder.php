<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officialMerchantUser = User::where('email', 'merchant@example.com')->first();

        $officialMerchantUser->merchant()->create([
            'name' => 'Official Store',
            'banner_image' => '/images/merchants/banners/default.png',
            'description' => 'Official store for eccomerce',
            'phone' => '+62 82253784251',
            'social_links' => [
                ['platform' => 'instagram', 'link' => 'https://instagram.com/sukronsabari__'],
                ['platform' => 'youtube', 'link' => 'https://youtube.com/sukronsabari'],
            ],
            'is_official' => true
        ]);

        $merchantUsers = User::factory()->count(10)->create([
            'password' => bcrypt('password'),
            'role' => UserRole::Merchant,
            'image' => 'images/users/default.png'
        ]);

        $merchantUsers->each(function ($merchantUser) {
            $merchantUser->merchant()->create([
                'name' => fake()->name(),
                'is_official' => false,
                'banner_image' => '/images/merchants/banners/default.png',
                'description' => fake()->paragraph(),
                'phone' => fake()->phoneNumber(),
                'social_links' => [
                    ['platform' => 'instagram', 'link' => fake()->url()],
                    ['platform' => 'youtube', 'link' => fake()->url()],
                ],
            ]);
        });
    }
}
