<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin / Guru
        DB::table('pengguna')->insert([
            'nama' => 'Ibu Guru Admin',
            'email' => 'admin@sekolah.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Membuat Akun Siswa Uji Coba
        DB::table('pengguna')->insert([
            'nama' => 'Budi Siswa',
            'email' => 'budi@siswa.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pengguna')->insert([
            'nama' => 'Ani Siswa',
            'email' => 'ani@siswa.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('pengguna')->insert([
            'nama' => 'Hibban Ibrahim',
            'email' => 'hibban@siswa.com',
            'kata_sandi' => Hash::make('hibban123'),
            'peran' => 'siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            DB::table('pengguna')->insert([
            'nama' => 'Bapak Hendri S.Pd',
            'email' => 'admin2@sekolah.com',
            'kata_sandi' => Hash::make('hendri123'),
            'peran' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }


}