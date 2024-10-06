<?php

namespace Database\Seeders;

use App\Models\GreenHouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GreenHouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GreenHouse::create([
            'nama' => 'Green House 1',
            'jenis_tanaman_id' => 1
        ]);
    }
}