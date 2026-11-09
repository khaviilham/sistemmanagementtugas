<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
    ];

    protected $hidden = [
        'kata_sandi',
    ];

    // Relasi: Admin/Guru membuat banyak Proyek
    public function proyekDibuat()
    {
        return $this->hasMany(Proyek::class, 'dibuat_oleh', 'id_pengguna');
    }

    // Relasi: Siswa menerima banyak Tugas
    public function tugasDitugaskan()
    {
        return $this->hasMany(Tugas::class, 'ditugaskan_kepada', 'id_pengguna');
    }

    // Relasi: Siswa mengunggah banyak Lampiran
    public function lampiran()
    {
        return $this->hasMany(LampiranTugas::class, 'diupload_oleh', 'id_pengguna');
    }

    // Relasi: Pengguna menulis banyak Komentar
    public function komentar()
    {
        return $this->hasMany(KomentarTugas::class, 'id_pengguna', 'id_pengguna');
    }
}