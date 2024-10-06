<?php

namespace Database\Seeders;

use App\Models\Pompa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PompaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pompa::create([
            "status" => 'MATI',
            "keterangan" => 'Perangkat Baik',
            "perangkat_id" => 1,
        ]);
    }
}