<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    protected $table = 'debit';
    protected $primaryKey = 'id_debit';
    protected $fillable = [
        'id_penjualan',
        'nama',
        'tanggal',
        'total_pemasukan',
        'saldo_debit',
        'keterangan'
    ];
}
