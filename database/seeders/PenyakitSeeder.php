<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penyakit = [
            [
                "Kode" => "P1",
                "Penyakit" => "Layu Fusarium"
            ],
            [
                "Kode" => "P2",
                "Penyakit" => "Embun Tepung"
            ],
            [
                "Kode" => "P3",
                "Penyakit" => "Busuk Daun"
            ],
            [
                "Kode" => "P4",
                "Penyakit" => "Antraknos"
            ],
            [
                "Kode" => "P5",
                "Penyakit" => "Kudis"
            ],
            [
                "Kode" => "P6",
                "Penyakit" => "Bercak Daun Bersudut"
            ],
            [
                "Kode" => "P7",
                "Penyakit" => "Layu Bakteri"
            ],
            [
                "Kode" => "P8",
                "Penyakit" => "Busuk Phytophthora"
            ],
            [
                "Kode" => "P9",
                "Penyakit" => "Busukpythuim"
            ],
            [
                "Kode" => "P10",
                "Penyakit" => "Mosaic (Wmv)"
            ]
        ];

        foreach ($penyakit as $val) {
            DB::table('penyakit')->insert([
                'kode' => $val['Kode'],
                'nama' => $val['Penyakit'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}