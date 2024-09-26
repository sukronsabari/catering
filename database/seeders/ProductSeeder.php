<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temukan merchant dan kategori
        $merchantOfficial = Merchant::find(1);
        $category = Category::find(1);

        Product::factory(5)->withImages()->withVariation()->create([
            'merchant_id' => $merchantOfficial->id,
            'category_id' => $category->id,
        ]);

        Product::factory(5)->withImages()->create([
            'merchant_id' => $merchantOfficial->id,
            'category_id' => $category->id,
        ]);

        Product::factory(100)->withImages()->withVariation()->create([
            'merchant_id' => $merchantOfficial->id,
            'category_id' => $category->id,
        ]);

        Product::factory(50)->withImages()->create([
            'merchant_id' => $merchantOfficial->id,
            'category_id' => $category->id,
        ]);
    }

}
