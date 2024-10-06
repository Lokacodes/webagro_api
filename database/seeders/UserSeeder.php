<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'username' => 'superadmin',
            'password' => Hash::make('passwordku'),
            'nama' => 'Super Admin',
            'no_hp' => null,
            'foto' => null,
            'role_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}