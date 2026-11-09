<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LampiranTugas extends Model
{

    use Hasfactory;

    protected $table = 'lampiran_tugas';
    protected $primaryKey = 'id_lampiran_tugas';

    protected $fillable = [
        'id_tugas',
        'nama_file',
        'file_berkas',
        'diupload_oleh',
    ];

    public function task()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function uploader()
    {
        return $this->belongsTo(Pengguna::class, 'diupload_oleh', 'id_pengguna');
    }
}
