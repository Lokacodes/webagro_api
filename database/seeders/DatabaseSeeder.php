<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GroupSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            JabatanSeeder::class,
            JenisSeeder::class,
            JenisTanamanSeeder::class,
            GreenHouseSeeder::class,
            SatuanSeeder::class,
            PenyakitSeeder::class,
            GejalaSeeder::class,
            PengetahuanSeeder::class,
            CFSeeder::class,
            PostSeeder::class,
            PerangkatSeeder::class,
            KontrolSeeder::class,
            PompaSeeder::class,
        ]);
    }
}