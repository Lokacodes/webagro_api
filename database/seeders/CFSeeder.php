<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cf = [
            [
                "Kondisi" => "Pasti tidak",
                "Nilai Bobot" => -1
            ],
            [
                "Kondisi" => "Hampir pasti tidak",
                "Nilai Bobot" => -0.8
            ],
            [
                "Kondisi" => "Kemungkinan besar",
                "Nilai Bobot" => 0.6
            ],
            [
                "Kondisi" => "Mungkin tidak",
                "Nilai Bobot" => -0.4
            ],
            [
                "Kondisi" => "Tidak tahu",
                "Nilai Bobot" => -0.2
            ],
            [
                "Kondisi" => "Mungkin ya",
                "Nilai Bobot" => 0.4
            ],
            [
                "Kondisi" => "Kemungkinan besar ya",
                "Nilai Bobot" => 0.6
            ],
            [
                "Kondisi" => "Hampir Pasti iya",
                "Nilai Bobot" => 0.8
            ],
            [
                "Kondisi" => "Pasti iya",
                "Nilai Bobot" => 1
            ]
        ];

        foreach ($cf as $val) {
            DB::table('cf_pengguna')->insert([
                'kondisi' => $val['Kondisi'],
                'nilai' => (float) $val['Nilai Bobot'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}