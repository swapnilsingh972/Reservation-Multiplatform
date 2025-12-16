<?php

namespace Database\Seeders;

use App\Models\Reservasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ReservasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 4; $i++) {
            $faker = Faker::create();

            Reservasi::create([
                'nama' => $faker->name,
                'tanggal_pemesanan' => now(),
                'sesi' => 1,
                // 'jam_awal' => ,
            ]);
        }
    }
}
