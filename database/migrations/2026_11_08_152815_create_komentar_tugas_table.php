<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar_tugas', function (Blueprint $table) {
            $table->integer('id_komentar')->autoIncrement();
            $table->integer('id_tugas');
            $table->integer('id_pengguna'); //pengirim komentar admin/siswa
            $table->text('isi_komentar');

            $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_tugas');
    }
};
