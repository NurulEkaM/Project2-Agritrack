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
        Schema::create('gaji', function (Blueprint $table) {
            $table->id('id_gaji'); // Otomatis BIGINT UNSIGNED (Primary Key)
            $table->date('tanggal');
            $table->integer('total_gaji');
            $table->integer('total_lembur');
            
            // Mengganti gaji_bersih menjadi id_user sebagai Foreign Key yang kompatibel
            $table->foreignId('id_user')
                  ->constrained('users', 'id_user')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');

            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};