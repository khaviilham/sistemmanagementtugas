<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->integer('id_tugas')->autoIncrement();
            $table->integer('id_proyek');
            $table->integer('ditugaskan_kepada');
            $table->string('judul_tugas', 255);
            $table->text('deskripsi_tugas');
            $table->enum('status_tugas', ['belum_mulai', 'proses', 'selesai'])->default('belum_mulai');
            $table->dateTime('tenggat_waktu');
            $table->integer('nilai')->nullable();
            $table->text('feedback')->nullable();

            $table->foreign('id_proyek')->references('id_proyek')->on('proyek')->onDelete('cascade');
            $table->foreign('ditugaskan_kepada')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
