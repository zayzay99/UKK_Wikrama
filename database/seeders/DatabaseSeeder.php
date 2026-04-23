<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents; // Uncomment this if you don't want model events to fire during seeding

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        AppSetting::putValue('fine_late_per_day', 5000);
        AppSetting::putValue('fine_damaged_per_day', 10000);
        AppSetting::putValue('fine_lost_per_day', 15000);

        // Admin User
        User::updateOrCreate(
            ['email' => 'admin@wikrama.sch.id'],
            [
                'name' => 'Admin Wikrama',
                'password' => Hash::make('password'), // Password: password
                'nis' => null,
                'staff_id' => 'ADM-001',
                'role' => 'admin',
                'rayon' => null,
                'rombel' => null,
            ]
        );

        // Petugas User
        User::updateOrCreate(
            ['email' => 'petugas@wikrama.sch.id'],
            [
                'name' => 'Petugas Perpus',
                'password' => Hash::make('password'), // Password: password
                'nis' => null,
                'staff_id' => 'PTG-001',
                'role' => 'petugas',
                'rayon' => null,
                'rombel' => null,
            ]
        );

        // Siswa Users
        User::updateOrCreate(
            ['email' => 'siswaa@wikrama.sch.id'],
            [
                'name' => 'Siswa A',
                'password' => Hash::make('password'), // Password: password
                'nis' => '12230001',
                'staff_id' => null,
                'role' => 'siswa',
                'rayon' => 'Cisarua 1',
                'rombel' => 'PPLG XI-1',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswab@wikrama.sch.id'],
            [
                'name' => 'Siswa B',
                'password' => Hash::make('password'), // Password: password
                'nis' => '12230002',
                'staff_id' => null,
                'role' => 'siswa',
                'rayon' => 'Tajur 2',
                'rombel' => 'TJKT XI-2',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswac@wikrama.sch.id'],
            [
                'name' => 'Siswa C',
                'password' => Hash::make('password'), // Password: password
                'nis' => '12230003',
                'staff_id' => null,
                'role' => 'siswa',
                'rayon' => 'Ciawi 3',
                'rombel' => 'DKV XI-3',
            ]
        );

        // Anda bisa menambahkan lebih banyak data dummy dengan User::factory() jika sudah diatur
        // User::factory(5)->create([
        //     'role' => 'siswa',
        //     'password' => Hash::make('password'),
        // ]);
    }
}
