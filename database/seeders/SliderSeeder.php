<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::create([
            'title' => 'Hot Promo',
            'subtitle' => 'Chicken Nugget Variant',
            'starting_price' => 15000,
            'is_active' => true,
            'position' => 1,
            'url' => '/products',
            'image' => 'images/sliders/slider1.png',
        ]);
        Slider::create([
            'title' => 'Hot Promo',
            'subtitle' => 'Kepiting King Krab',
            'starting_price' => 50000,
            'is_active' => true,
            'position' => 2,
            'url' => '/products',
            'image' => 'images/sliders/slider2.png',
        ]);
        Slider::create([
            'title' => 'Hot Promo',
            'subtitle' => 'Nasi Padang Collection',
            'starting_price' => 10000,
            'is_active' => true,
            'position' => 3,
            'url' => '/products',
            'image' => 'images/sliders/slider3.png',
        ]);
        Slider::create([
            'title' => 'Hot Promo',
            'subtitle' => 'Sate Series',
            'starting_price' => 20000,
            'is_active' => true,
            'position' => 4,
            'url' => '/products',
            'image' => 'images/sliders/slider4.png',
        ]);
        Slider::create([
            'title' => 'Hot Promo',
            'subtitle' => 'Sop Series',
            'starting_price' => 20000,
            'is_active' => true,
            'position' => 5,
            'url' => '/products',
            'image' => 'images/sliders/slider5.png',
        ]);
    }
}
