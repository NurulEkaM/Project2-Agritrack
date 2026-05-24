<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'gaji';
    protected $primaryKey = 'id_gaji';
    protected $fillable = [
        'tanggal',
        'total_gaji',
        'total_lembur',
        'id_user', // Pastikan ini sesuai dengan nama kolom di database
        'keterangan',
    ];

    // Relasi dengan User
    public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
