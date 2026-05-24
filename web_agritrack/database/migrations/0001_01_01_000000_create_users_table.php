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
                Schema::create('users', function (Blueprint $table) {
            // Sesuai gambar: id_user (Primary Key)
            $table->id('id_user'); 
            
            // Sesuai gambar: nama, jabatan, alamat, no_hp (String)
            $table->string('nama');
            $table->string('jabatan');
            $table->text('alamat'); // Gunakan text jika alamat panjang
            $table->string('no_hp');
            $table->enum('role', ['admin', 'owner', 'karyawan']); // Menambahkan kolom role untuk membedakan admin dan petugas
            
            // Sesuai gambar: gaji (Int)
            $table->integer('gaji');
            
            // Sesuai gambar: username & password
            $table->string('username')->unique();
            $table->string('password');
            
            // Tambahan standar Laravel (opsional tapi disarankan)
            $table->timestamps(); 
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('username')->primary(); // Diubah ke username sesuai identitas tabel User
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            // foreignId merujuk ke id_user di tabel users
            $table->foreignId('user_id')->nullable()->index()->constrained('users', 'id_user');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
