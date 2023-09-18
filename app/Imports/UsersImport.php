<?php

namespace App\Imports;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $name = $row['name']; // Extract the name from the row

        // Generate an email address by combining the name with @example.com
        $email = strtolower(str_replace(' ', '', $name)) . '@example.com';

        return new User([
            'name' => $row['name'],
            'nim' => $row['nim'],
            'Kelamin' => $row['kelamin'],
            'Pendidikan' => $row['pendidikan'],
            'Jabatan' => $row['jabatan'],
            'Golongan' => $row['golongan'],
            'password' => Hash::make('password'),
            'gambar' => 'profile1.jpg',
            'jurusan' => 'Informatika',
            'role' => 1,
            'email'=>  $email,
        ]);
    }
}
