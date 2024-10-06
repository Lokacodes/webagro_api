<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengetahuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengetahuan = [
            [
                "penyakit_id" => "1",
                "gejala_id" => "4",
                "mb" => "0.4",
                "md" => "0.3"
            ],
            [
                "penyakit_id" => "1",
                "gejala_id" => "5",
                "mb" => "0.2",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "1",
                "gejala_id" => "6",
                "mb" => "0.67",
                "md" => "0.5"
            ],
            [
                "penyakit_id" => "1",
                "gejala_id" => "7",
                "mb" => "0.8",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "1",
                "gejala_id" => "8",
                "mb" => "0.4",
                "md" => "0.1"
            ],
            [
                "penyakit_id" => 1,
                "gejala_id" => "9",
                "mb" => "0.8",
                "md" => "0.3"
            ],
            [
                "penyakit_id" => 1,
                "gejala_id" => "30",
                "mb" => "0.6",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "2",
                "gejala_id" => "10",
                "mb" => "0.7",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "2",
                "gejala_id" => "11",
                "mb" => "0.3",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "2",
                "gejala_id" => "12",
                "mb" => "0.2",
                "md" => "0.5"
            ],
            [
                "penyakit_id" => "2",
                "gejala_id" => "13",
                "mb" => "0.6",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "3",
                "gejala_id" => "14",
                "mb" => "0.8",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "3",
                "gejala_id" => "15",
                "mb" => "0.8",
                "md" => "0.7"
            ],
            [
                "penyakit_id" => "3",
                "gejala_id" => "16",
                "mb" => "0.2",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "3",
                "gejala_id" => "17",
                "mb" => "0.5",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "3",
                "gejala_id" => "26",
                "mb" => "0.4",
                "md" => "0.67"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "18",
                "mb" => "0.4",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "19",
                "mb" => "0.8",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "20",
                "mb" => "0.7",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "21",
                "mb" => "0.4",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "22",
                "mb" => "0.3",
                "md" => "0.7"
            ],
            [
                "penyakit_id" => "4",
                "gejala_id" => "29",
                "mb" => "0.2",
                "md" => "0.3"
            ],
            [
                "penyakit_id" => "5",
                "gejala_id" => "22",
                "mb" => "0.6",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "5",
                "gejala_id" => "23",
                "mb" => "0.6",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "5",
                "gejala_id" => "25",
                "mb" => "0.8",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "14",
                "mb" => "0.5",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "16",
                "mb" => "0.6",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "17",
                "mb" => "0.4",
                "md" => "0.5"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "26",
                "mb" => "0.2",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "27",
                "mb" => "0.67",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "28",
                "mb" => "0.8",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "6",
                "gejala_id" => "29",
                "mb" => "0.4",
                "md" => "0.67"
            ],
            [
                "penyakit_id" => "7",
                "gejala_id" => "9",
                "mb" => "0.8",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "7",
                "gejala_id" => "30",
                "mb" => "0.6",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "7",
                "gejala_id" => "31",
                "mb" => "0.7",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "8",
                "gejala_id" => "22",
                "mb" => "0.3",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "8",
                "gejala_id" => "29",
                "mb" => "0.2",
                "md" => "0.7"
            ],
            [
                "penyakit_id" => "8",
                "gejala_id" => "32",
                "mb" => "0.6",
                "md" => "0.3"
            ],
            [
                "penyakit_id" => "8",
                "gejala_id" => "33",
                "mb" => "0.8",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "9",
                "gejala_id" => "22",
                "mb" => "0.8",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "9",
                "gejala_id" => "29",
                "mb" => "0.2",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "9",
                "gejala_id" => "32",
                "mb" => "0.5",
                "md" => "0.8"
            ],
            [
                "penyakit_id" => "9",
                "gejala_id" => "33",
                "mb" => "0.4",
                "md" => "0.2"
            ],
            [
                "penyakit_id" => "9",
                "gejala_id" => "34",
                "mb" => "0.8",
                "md" => "0.5"
            ],
            [
                "penyakit_id" => "10",
                "gejala_id" => "2",
                "mb" => "0.4",
                "md" => "0.4"
            ],
            [
                "penyakit_id" => "10",
                "gejala_id" => "3",
                "mb" => "0.2",
                "md" => "0.6"
            ],
            [
                "penyakit_id" => "10",
                "gejala_id" => "35",
                "mb" => "0.6",
                "md" => "0.9"
            ],
            [
                "penyakit_id" => "10",
                "gejala_id" => "36",
                "mb" => "0.8",
                "md" => "0.11"
            ]
        ];

        foreach ($pengetahuan as $val) {
            DB::table('pengetahuan')->insert([
                'mb' => (float) $val['mb'],
                'md' => (float) $val['md'],
                'penyakit_id' => (int) $val['penyakit_id'],
                'gejala_id' => (int) $val['gejala_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}