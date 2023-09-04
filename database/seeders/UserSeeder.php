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
            'name' => 'Muhammad Al Farisi Rizki Suni',
            'nim'   => '1908107010047',
            'email' => 'sunii@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
        User::create([
            'name' => 'Reza Angga Putra',
            'nim'   => '1908107010048',
            'email' => 'rejaa@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
        User::create([
            'name' => 'Affan Ian Amara',
            'nim'   => '1908107010038',
            'email' => 'apann@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
        User::create([
            'name' => 'Muhammad Nurifai',
            'nim'   => '1908107010060',
            'email' => 'paii@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
    }
}
