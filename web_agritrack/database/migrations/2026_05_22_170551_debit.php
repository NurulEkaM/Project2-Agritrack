<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('debit', function (Blueprint $blueprint) {
            // Menggunakan id_debit sebagai Primary Key sesuai diagram
            $blueprint->id('id_debit'); 
            
            // id_penjualan bersifat nullable (null) sesuai diagram
            $blueprint->integer('id_penjualan')->nullable(); 
            
            $blueprint->string('nama');
            $blueprint->date('tanggal');
            
            // total_pemasukan dan saldo_debit menggunakan integer
            $blueprint->integer('total_pemasukan');
            $blueprint->integer('saldo_debit');
            
            // keterangan menggunakan text agar bisa menampung deskripsi panjang
            $blueprint->text('keterangan')->nullable();
            
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debit');
    }
};