<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DetailTransaksi;
class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['kode_transaksi', 'total_harga', 'nama_pembeli', 'lokasi', 'no_tlp'];

    public function details() {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
