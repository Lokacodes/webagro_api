<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satuan = ['Kg', 'Gram', 'Liter', 'Mililiter', 'biji', 'Km'];

        foreach ($satuan as $val) {
            DB::table('satuan')->insert(['nama' => $val]);
        }
    }
}