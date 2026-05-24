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
        Schema::create('kredit', function (Blueprint $table) {
            $table->id('id_kredit'); // id_kredit: int (Primary Key)
            $table->string('nama'); // nama: string
            $table->date('tanggal'); // tanggal: date
            
            // id_gaji: int (null) -> diasumsikan sebagai foreign key atau kolom nullable
            $table->integer('id_gaji')->nullable(); 
            
            // jenis_pengeluaran: enum(tetap & tidak tetap)
            $table->enum('jenis_pengeluaran', ['tetap', 'tidak tetap']);
            
            $table->integer('saldo_kredit'); // saldo_kredit: int
            $table->string('keterangan'); // keterangan: string
            
            // status: enum(setuju & tidak disetuju)
            $table->enum('status', ['setuju', 'tidak disetuju']);
            
            $table->timestamps(); // Opsional: untuk created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredit');
    }
};