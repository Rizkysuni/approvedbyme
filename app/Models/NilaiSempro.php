<?php

namespace App\Models;

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
}
