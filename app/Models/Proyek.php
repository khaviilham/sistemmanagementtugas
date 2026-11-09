<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul_proyek',
        'deskripsi_proyek',
        'tanggal_mulai',
        'tanggal_selesai',
        'dibuat_oleh',
    ];

    // Relasi ke Pembuat Proyek (Admin)
    public function pembuat()
    {
        return $this->belongsTo(Pengguna::class, 'dibuat_oleh', 'id_pengguna');
    }

    
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_proyek', 'id_proyek');
    }
}