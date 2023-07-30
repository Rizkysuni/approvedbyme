<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSempro extends Model
{
    use HasFactory;

    protected $table = 'nilai_sempro';

    protected $fillable = [
        'id_sempro', 
        'id_dosen', 
        'nilai_1', 
        'nilai_2',
        'nilai_3', 
        'nilai_4',
        'nilai_5',
    ];

    // Definisikan relasi dengan model User (Dosen)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class, 'id_dosen', 'user_id');
    }
}
