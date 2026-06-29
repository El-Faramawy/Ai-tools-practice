<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default Admin
        \App\Models\Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Default Setting
        \App\Models\Setting::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'لوحة التحكم',
                'logo' => 'assets/logo.png',
                'fav_icon' => 'assets/fav_icon.png',
            ]
        );

        // Default User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
