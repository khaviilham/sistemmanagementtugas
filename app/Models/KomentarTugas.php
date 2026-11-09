<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Sesuaikan jika menggunakan HasFactory

class KomentarTugas extends Model
{
    use HasFactory;

    protected $table = 'komentar_tugas';
    protected $primaryKey = 'id_komentar'; // Sesuaikan jika primary key berbeda (misal: id_komentar_tugas)

    protected $fillable = [
        'id_tugas',
        'id_pengguna',
        'isi_komentar',
    ];

    /**
     * Relasi ke Model Pengguna (Pembuat Komentar)
     */
    public function pengirim()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}