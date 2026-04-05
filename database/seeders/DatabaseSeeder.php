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
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'nik_ktp' => '12345678',
                'nik_karyawan' => '12345678',
                'email' => 'admin@gmail.com',
                'phone' => '08123456789',
                'department' => 'IT System',
                'role' => 'admin',
                'address' => 'Jl. Merdeka No. 1, Jakarta Pusat',
                'password' => Hash::make('admin123'),
                'password_hint' => 'admin123',
                'pin' => Hash::make('000000'),
                'pin_hint' => '000000',
            ]
        );

        // 2. Seed 10 Users
        $users = [
            ['name' => 'Budi Santoso', 'username' => 'budi', 'address' => 'Jl. Bijaksana No. 25'],
            ['name' => 'Siti Aminah', 'username' => 'siti', 'address' => 'Jl. Melati No. 12'],
            ['name' => 'Agus Setiawan', 'username' => 'agus', 'address' => 'Jl. Mawar No. 5'],
            ['name' => 'Ani Wijaya', 'username' => 'ani', 'address' => 'Jl. Anggrek No. 8'],
            ['name' => 'Bambang Hartono', 'username' => 'bambang', 'address' => 'Jl. Garuda No. 10'],
            ['name' => 'Ratna Sari', 'username' => 'ratna', 'address' => 'Jl. Kartini No. 3'],
            ['name' => 'Dedi Kurniawan', 'username' => 'dedi', 'address' => 'Jl. Sudirman No. 45'],
            ['name' => 'Linda Permata', 'username' => 'linda', 'address' => 'Jl. Diponegoro No. 17'],
            ['name' => 'Eko Prasetyo', 'username' => 'eko', 'address' => 'Jl. Gajah Mada No. 9'],
            ['name' => 'Maya Indah', 'username' => 'maya', 'address' => 'Jl. Hayam Wuruk No. 21'],
        ];

        foreach ($users as $index => $u) {
            User::updateOrCreate(
                ['username' => $u['username']],
                [
                    'name' => $u['name'],
                    'nik_ktp' => '3201' . str_pad($index + 1, 12, '0', STR_PAD_LEFT),
                    'nik_karyawan' => str_pad($index + 1, 7, '0', STR_PAD_LEFT),
                    'kta_number' => str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'barcode_number' => str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'email' => $u['username'] . '@example.com',
                    'phone' => '0812' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'department' => 'Staff Operasional',
                    'birth_place' => fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Palembang']),
                    'birth_date' => fake()->date('Y-m-d', '2000-01-01'),
                    'joint_date' => fake()->dateTimeBetween('2023-01-01', '2025-12-31')->format('Y-m-d'),
                    'address' => $u['address'],
                    'gender' => ($index % 2 == 0) ? 'male' : 'female',
                    'religion' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'education' => fake()->randomElement(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2']),
                    'role' => 'user',
                    'password' => Hash::make('password123'),
                    'password_hint' => 'password123',
                    'pin' => Hash::make('123456'),
                    'pin_hint' => '123456',
                ]
            );
        }

        // 3. Seed Settings (Pengaturan Default)
        $this->call(SettingSeeder::class);
    }
}
        