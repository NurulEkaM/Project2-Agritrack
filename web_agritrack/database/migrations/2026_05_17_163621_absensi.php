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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi'); // Primary Key (int)
            $table->date('tanggal'); // + tanggal: date
            $table->enum('lokasi', ['pulo', 'sindang']); // + lokasi: enum
            $table->enum('status', ['absen_datang', 'absen_pulang', 'lembur_datang', 'lembur_pulang', 'tidak_hadir']); // + status: enum
            $table->string('kegiatan')->nullable(); // + kegiatan: string
            
            // Foreign Key ke tabel users/karyawan (int)
            // Sesuaikan 'id_user' atau 'id_karyawan' dengan primary key di tabel karyawanmu
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade'); 
            $table->integer('total_lembur')->default(0); // + total_lembur: int
            $table->timestamps(); // Menambahkan created_at & updated_at secara otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
