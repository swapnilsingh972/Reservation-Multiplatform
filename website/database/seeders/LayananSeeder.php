<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Layanan;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanan = [
            [
                'nama' => 'Potong Rambut Pria',
                'deskripsi' => 'Layanan potong rambut khusus untuk pria dengan berbagai gaya.',
                'durasi' => '00:30:00', // 30 menit
                'poin_aktif' => 'aktif',
                'harga' => 10000,
            ],
            [
                'nama' => 'Potong Rambut Wanita',
                'deskripsi' => 'Layanan potong rambut khusus untuk wanita dengan berbagai gaya.',
                'durasi' => '00:30:00', // 45 menit
                'poin_aktif' => 'aktif',
                'harga' => 15000,
            ],
            [
                'nama' => 'Toning',
                'deskripsi' => 'Proses pewarnaan rambut untuk meningkatkan warna alami.',
                'durasi' => '01:00:00', // 1 jam
                'poin_aktif' => 'aktif',
                'harga' => 90000,
            ],
            [
                'nama' => 'Masker Rambut',
                'deskripsi' => 'Perawatan rambut dengan masker untuk menutrisi dan menguatkan rambut.',
                'durasi' => '01:00:00', // 45 menit
                'poin_aktif' => 'aktif',
                'harga' => 50000,
            ],
            [
                'nama' => 'Coloring',
                'deskripsi' => 'Pewarnaan rambut dengan berbagai pilihan warna.',
                'durasi' => '02:00:00', // 2 jam
                'poin_aktif' => 'aktif',
                'harga' => 120000,
            ],
            [
                'nama' => 'Creambath',
                'deskripsi' => 'Perawatan rambut dan kulit kepala dengan krim.',
                'durasi' => '01:00:00', // 1 jam
                'poin_aktif' => 'aktif',
                'harga' => 60000,
            ],
            [
                'nama' => 'Rebonding',
                'deskripsi' => 'Meluruskan rambut secara permanen.',
                'durasi' => '03:00:00', // 3 jam
                'poin_aktif' => 'aktif',
                'harga' => 180000,
            ],
        ];

        foreach ($layanan as $data) {
            Layanan::create([
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'durasi' => $data['durasi'], // Durasi dalam menit
                'poin_aktif' => $data['poin_aktif'],
                'harga' => $data['harga'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
