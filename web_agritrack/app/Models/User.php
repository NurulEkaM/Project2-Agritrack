<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $primaryKey = 'id_user'; 

    // 'role' dihapus dari sini karena tidak ada di migration
    protected $fillable = [
        'nama', 'jabatan', 'alamat', 'no_hp', 'gaji', 'username', 'password'
    ];

    // 'remember_token' dihapus dari sini karena tidak ada di migration
    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed', 
        ];
    }
    public function absensi()
    {
        // Pastikan foreign key di tabel absensi adalah 'id_user'
        // dan primary key di tabel users adalah 'id_user'
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }
}