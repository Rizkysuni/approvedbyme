<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'gambar',
        'jurusan',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * image
     *
     * @return Attribute
     */
    public function getProfileImageAttribute($value)
    {
    if ($value) {
        return asset('storage/profile_images/' . $value);
    }

    return asset('images/profile1.jpg'); // Ganti dengan path ke foto profil default
    }

    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function role(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["mahasiswa", "dosen", "koordinator","admin"][$value],
        );
    }

    // Definisikan relasi dengan model NilaiSempro
    public function nilaiSempros()
    {
        return $this->hasMany(nilai_sempro::class, 'id_dosen');
    }

    public function signature(): HasOne
    {
        return $this->hasOne(Signature::class);
    }

    public function sempros()
    {
        return $this->hasMany(Sempro::class, 'id');
    }
}
