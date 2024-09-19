<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(1)->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Admin,
            'image' => 'images/users/default.png'
        ]);
        User::factory()->count(1)->create([
            'name' => 'merchant',
            'email' => 'merchant@example.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Merchant,
            'image' => 'images/users/default.png'
        ]);
        User::factory()->count(1)->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => UserRole::User,
            'image' => 'images/users/default.png'
        ]);
        User::factory()->count(15)->create([
            'image' => 'images/users/default.png'
        ]);
    }
}
