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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk'); // Primary Key
            $table->string('nama_produk');
            $table->decimal('harga_satuan', 15, 2); // Menggunakan decimal untuk akurasi mata uang
            $table->integer('stok')->default(0);
            $table->text('deskripsi')->nullable(); // Nullable jika deskripsi tidak wajib diisi
            $table->timestamps(); // Menambahkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};