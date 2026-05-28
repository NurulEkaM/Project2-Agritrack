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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Contoh: "Laporan Cashflow Desember 2025"
            $table->string('file_path')->nullable(); // Nama file atau path unduhan PDF
            $table->date('tanggal_buat'); // Tanggal ketika admin menekan tombol cetak
            $table->string('dibuat_oleh')->default('Admin'); // Penanda siapa yang generate dokumen
            $table->timestamps(); // Menyediakan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
