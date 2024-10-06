<?php

namespace Database\Seeders;

use App\Models\Perangkat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perangkat::create([
            "nama" => 'Perangkat 1',
            "keterangan" => 'Perangkat Baik',
            "greenhouse_id" => 1,
        ]);
    }
}