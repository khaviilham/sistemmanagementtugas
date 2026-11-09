<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('lampiran_tugas', function (Blueprint $table) {
    $table->integer('id_lampiran')->autoIncrement();
    $table->integer('id_tugas');
    $table->string('nama_file', 255);
    $table->string('file_berkas', 255);
    $table->integer('diupload_oleh'); //foreign key ke siswa

    $table->foreign('id_tugas')->references('id_tugas')->on('tugas')->onDelete('cascade');
    $table->foreign('diupload_oleh')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_tugas');
    }
};