<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->integer('id_pengguna')->autoIncrement(); // Primary key int(10)
            $table->string('nama', 255);
            $table->string('email', 255)->unique();
            $table->string('kata_sandi', 255);
            $table->enum('peran', ['admin', 'siswa']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
