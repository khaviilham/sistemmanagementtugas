<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaBaruSeeder extends Seeder
{
    public function run(): void
    {
        // Tambah akun baru di sini, cukup satu baris per akun
        $akunBaru = [
            ['nama' => 'Ibu Rusminah',   'email' => 'admin3@sekolah.com',   'sandi' => 'rusminah123',   'peran' => 'admin'],
            ['nama' => 'Andika Satrio',     'email' => 'andika@siswa.com', 'sandi' => 'andika123',   'peran' => 'admin'],
        ];

        foreach ($akunBaru as $akun) {
            DB::table('pengguna')->updateOrInsert(
                ['email' => $akun['email']], // kunci pengecekan
                [
                    'nama'       => $akun['nama'],
                    'kata_sandi' => Hash::make($akun['sandi']),
                    'peran'      => $akun['peran'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}