<?php

namespace Database\Seeders;

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'fulan1',
            'nim'   => '1111111111',
            'email' => 'fulan1@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'fulan2',
            'nim'   => '2222222222',
            'email' => 'fulan2@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'fulan3',
            'nim'   => '3333333333',
            'email' => 'fulan3@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'fulan4',
            'nim'   => '4444444444',
            'email' => 'fulan4@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'fulan5',
            'nim'   => '5555555555',
            'email' => 'fulan5@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'fulan6',
            'nim'   => '6666666666',
            'email' => 'fulan6@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);
    }
}
