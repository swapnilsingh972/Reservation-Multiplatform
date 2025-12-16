<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SettingSistem;

class SettingSistemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingSistem::create([
            'nama' => 'WindaSalon',
            'alamat' => 'Tuban, Domasan, Kec. Kalidawir, Kabupaten Tulungagung, Jawa Timur 66281',
            'no_telp' => '0813-3591-8339',
            'jam_operasional_buka' => '08:00:00',
            'jam_operasional_tutup' => '16:00:00',
        ]);
    }
}
