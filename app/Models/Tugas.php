<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use Hasfactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';

    protected $fillable = [
        'id_proyek', // Menyesuaikan dengan kolom migration baru
        'judul_tugas',
        'deskripsi_tugas',
        'tenggat_waktu',
        'ditugaskan_kepada',
        'status_tugas',
        'nilai',
        'feedback',
    ];

    // Relasi ke Proyek
    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    // Relasi ke Siswa yang ditugaskan
    public function siswa()
    {
        return $this->belongsTo(Pengguna::class, 'ditugaskan_kepada', 'id_pengguna');
    }
    // Relasi ke Lampiran Tugas
    public function lampiran()
    {
        return $this->hasMany(LampiranTugas::class, 'id_tugas', 'id_tugas');
    }

    // Relasi ke Komentar Tugas
    public function komentar()
    {
        return $this->hasMany(KomentarTugas::class, 'id_tugas', 'id_tugas');
    }

    public function guru()
{
    // Sesuaikan foreign key jika ada (misal: 'id_pengguna' atau 'dibuat_oleh')
    return $this->belongsTo(Pengguna::class, 'id_pengguna'); 
}

}
