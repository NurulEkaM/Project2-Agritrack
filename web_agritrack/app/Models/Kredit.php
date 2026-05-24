<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gaji;

class Kredit extends Model
{
    protected $table = 'kredit'; // Nama tabel di database
    protected $primaryKey = 'id_kredit'; // Primary key

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama',
        'tanggal',
        'id_gaji',
        'jenis_pengeluaran',
        'saldo_kredit',
        'keterangan',
        'status',
    ];

    public function gaji() {
    return $this->belongsTo(Gaji::class, 'id_gaji', 'id_gaji');
    }
   
}
