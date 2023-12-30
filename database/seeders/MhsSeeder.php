<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class MhsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Menambahkan user
         User::create([
            'name' => 'Hafizul Akbar',
            'nim'   => '1908107010051',
            'email' => 'hafizul@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'Informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
        User::create([
            'name' => 'Muhammad Shabri Rabbani',
            'nim'   => '1908107010041',
            'email' => 'sabri@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'Informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
        User::create([
            'name' => 'Ikram Muhaimin',
            'nim'   => '1908107010043',
            'email' => 'ikram@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'Informatika',
            'role'  => 0,
            'Kelamin' => 'L'
        ]);
    }
}
