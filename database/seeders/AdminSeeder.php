<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        User::create([
            'name' => 'admin',
            'nim'   => '12345',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan'   => 'informatika',
            'role'  => 3
        ]);
    }
}
