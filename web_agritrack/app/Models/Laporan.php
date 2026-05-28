<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan'; // Pastikan ini sesuai dengan nama tabel di database

    protected $primaryKey = 'id'; // Sesuaikan jika primary key bukan 'id'
    protected $fillable = ['judul', 'file_path', 'tanggal_buat', 'dibuat_oleh'];
}
