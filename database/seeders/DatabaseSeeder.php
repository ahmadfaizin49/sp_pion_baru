<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Seed Super Admin
        User::create([
            'name' => 'Super Admin',
            'nik' => '12345678',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '08123456789',
            'department' => 'IT System',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'pin' => Hash::make('000000'),
        ]);
    }
}
