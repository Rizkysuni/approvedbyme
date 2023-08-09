<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sempro extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'id_mahasiswa',
        'nama',
        'nim',
        'judul',
        'jurusan',
        'ruangan',
        'tglSempro',
        'dospem1',
        'dospem2',
        'penguji1',
        'penguji2',
        'penguji3', 
        'seminar',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    
}
