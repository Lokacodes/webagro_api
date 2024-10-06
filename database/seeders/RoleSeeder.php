<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            'nama' => 'superAdmin',
            'group_id' => 1
        ]);
        DB::table('role')->insert([
            'nama' => 'admin',
            'group_id' => 1
        ]);
        DB::table('role')->insert([
            'nama' => 'admin',
            'group_id' => 2
        ]);
        DB::table('role')->insert([
            'nama' => 'admin',
            'group_id' => 3
        ]);
        DB::table('role')->insert([
            'nama' => 'marketing',
            'group_id' => 3
        ]);
        DB::table('role')->insert([
            'nama' => 'pimpinan',
            'group_id' => 1
        ]);
        DB::table('role')->insert([
            'nama' => 'pimpinan',
            'group_id' => 2
        ]);
        DB::table('role')->insert([
            'nama' => 'pimpinan',
            'group_id' => 3
        ]);
        DB::table('role')->insert([
            'nama' => 'pimpinan',
            'group_id' => 4
        ]);
        DB::table('role')->insert([
            'nama' => 'mitra',
            'group_id' => 3
        ]);
        DB::table('role')->insert([
            'nama' => 'pegawai',
            'group_id' => 1
        ]);
        DB::table('role')->insert([
            'nama' => 'pegawai',
            'group_id' => 4
        ]);
        DB::table('role')->insert([
            'nama' => 'operator',
            'group_id' => 2
        ]);
        DB::table('role')->insert([
            'nama' => 'operator',
            'group_id' => 4
        ]);
        DB::table('role')->insert([
            'nama' => 'mitra',
            'group_id' => 4
        ]);
    }
}