<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = ['pupuk AB', 'pupuk KN Kalium nitrogen (kanitro)', 'pupuk biourin', 'pupuk NPK'];

        foreach ($jenis as $val) {
            DB::table('jenis')->insert(['nama' => $val]);
        }
    }
}