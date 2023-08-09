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
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 0
        ]);

        User::create([
            'name' => 'Alim Misbullah, S.Si., M.S',
            'nim'   => '198806032019031011',
            'email' => 'alimmisubullah@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 2
        ]);

        User::create([
            'name' => 'Razief Perucha Fauzie Afidh, M.Sc',
            'nim'   => '198408062012121002',
            'email' => 'raziefh@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'Viska Mutiawarni, B.IT, M.IT',
            'nim'   => '198008312009122003',
            'email' => 'viska@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'Laina Farsiah, S.Si., M.S.',
            'nim'   => '198902032022032004',
            'email' => 'laina@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);

        User::create([
            'name' => 'Husaini M.Sc',
            'nim'   => '198806242022031006',
            'email' => 'husaini@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 1
        ]);
    }
}
