<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyek', function (Blueprint $table) {
            $table->integer('id_proyek')->autoIncrement();
            $table->string('judul_proyek', 255);
            $table->text('deskripsi_proyek')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('dibuat_oleh'); // Foreign key ke pengguna
            $table->foreign('dibuat_oleh')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
