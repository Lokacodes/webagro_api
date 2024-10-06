<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatan = ['manager', 'staff', 'karyawan'];

        foreach ($jabatan as $val) {
            DB::table('jabatan')->insert(['nama' => $val]);
        }
    }
}