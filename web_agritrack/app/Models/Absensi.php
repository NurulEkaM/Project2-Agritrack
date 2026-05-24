<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    // --- TAMBAHKAN BARIS INI ---
    
    public $timestamps = false; 

    protected $fillable = [
        'id_user', 'tanggal_datang', 'status', 'kegiatan', 'lokasi', 'total_lembur' , 'tanggal_pulang'
    ];
}