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
        $this->call(SystemSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(CateProductSeeder::class);
        $this->call(CateNewSeeder::class);
        $this->call(MenuSeeder::class);

    }
}
