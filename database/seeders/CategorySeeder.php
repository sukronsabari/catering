<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->create([
            'name' => $name = 'Foods',
            'slug' => str()->slug($name),
            'featured' => true,
            'description' => 'Foods collections',
        ]);

        Category::factory()->create([
            'name' => $name = 'Drinks',
            'slug' => str()->slug($name),
            'featured' => true,
            'description' => 'Drinks collections',
        ]);
    }
}
