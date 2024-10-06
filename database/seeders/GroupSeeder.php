<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = ['absen', 'penyakit', 'keuangan', 'iot'];

        foreach ($groups as $group) {
            DB::table('group')->insert(['nama' => $group]);
        }
    }
}