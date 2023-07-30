<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sidang extends Model
{
    use HasFactory;

    protected $table = 'sempros'; // Nama tabel yang digunakan oleh model "Semhas"

    protected $fillable = [ // Kolom-kolom yang dapat diisi pada model "Semhas"
        'user_id', // ID mahasiswa yang memiliki seminar hasil
        'tanggal', // Tanggal seminar hasil
        'ruangan', // Ruangan seminar hasil
        // tambahkan atribut lain sesuai kebutuhan
    ];

    // Relasi dengan model User (mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
