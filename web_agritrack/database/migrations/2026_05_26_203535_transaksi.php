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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('kode_transaksi')->unique();
            $table->decimal('total_harga', 15, 2);
            $table->enum('jenis_pesanan', ['wa', 'datang_langsung', 'online_shop'])->after('no_tlp');
            $table->string('no_resi')->nullable()->after('jenis_pesanan');
            $table->date('tanggal_pesan')->after('no_resi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
