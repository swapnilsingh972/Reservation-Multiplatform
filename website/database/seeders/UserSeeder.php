<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert users
        DB::table('users')->insert([
            [
                'email' => 'admin@gmail.com',
                'roles' => 'admin',
                'password' => Hash::make('admin'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'karyawan1@gmail.com',
                'roles' => 'karyawan',
                'password' => Hash::make('karyawan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'karyawan2@gmail.com',
                'roles' => 'karyawan',
                'password' => Hash::make('karyawan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'pelanggan@gmail.com',
                'roles' => 'pelanggan',
                'password' => Hash::make('pelanggan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert admins
        DB::table('admins')->insert([
            [
                'nama' => 'Admin',
                'alamat' => 'Tulungagung, Jawa Timur',
                'id_user' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert karyawans
        DB::table('karyawans')->insert([
            [
                'nama' => 'Ifa',
                'no_telp' => '08123456',
                'id_user' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dina',
                'no_telp' => '085654321',
                'id_user' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert pelanggans
        DB::table('pelanggans')->insert([
            [
                'nama' => 'Pelanggan',
                'no_telp' => '08123456',
                'alamat' => 'Tulungagung, Jawa Timur',
                'id_user' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
