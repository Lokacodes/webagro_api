<?php

namespace Database\Seeders;

use App\Models\Kontrol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KontrolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kontrol::create([
            "suhu_min" => '10',
            "suhu_max" => '40',
            "tds_min" => '20',
            "tds_max" => '80',
            "kelembaban_min" => '40',
            "kelembaban_max" => '80',
            "volume_min" => '10',
            "volume_max" => '90',
            "perangkat_id" => 1
        ]);
    }
}