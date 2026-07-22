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
        // Chạy permissions và roles trước
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        $this->call(SystemSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(CategoryProductSeeder::class);
        $this->call(CategoryNewSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductSettingSeeder::class);
        $this->call(NewsSettingSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(MailConfigSeeder::class);
        $this->call(MailTemplateSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(SliderSeeder::class);
        $this->call(AnalyticSeeder::class);
    }
}
