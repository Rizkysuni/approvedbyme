<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan user
        User::create([
            'name' => 'John Doe',
            'nim'   => '1908107010001',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0
        ]);

        User::create([
            'name' => 'suni',
            'nim'   => '1234567890',
            'email' => 'suni@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'rizky',
            'nim'   => '0987654321',
            'email' => 'rizky@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 2
        ]);
    }
}
