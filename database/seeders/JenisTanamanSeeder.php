<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisTanamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = ['Sky Rocket', 'Jade Dew', 'Autumn Sweet Melon', 'Melon Madu', 'Golden Melon', 'Melon Hijau'];

        foreach ($jenis as $val) {
            DB::table('jenis_tanaman')->insert(['nama' => $val]);
        }
    }
}