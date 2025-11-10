<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fonts = [
            [
                'name' => 'Arial',
                'family' => 'Arial, sans-serif',
                'type' => 'system',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Times New Roman',
                'family' => "'Times New Roman', serif",
                'type' => 'system',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Roboto',
                'family' => "'Roboto', sans-serif",
                'type' => 'google',
                'css_url' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Montserrat',
                'family' => "'Montserrat', sans-serif",
                'type' => 'google',
                'css_url' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
                'is_active' => true,
                'sort_order' => 4
            ],
            // Sample upload font (bạn có thể thêm file vào public/fronts/)
            [
                'name' => 'Sample Font',
                'family' => "'Sample Font', sans-serif",
                'type' => 'upload',
                'file_path' => 'fronts/sample-font.ttf', // Thêm file vào public/fronts/
                'is_active' => true,
                'sort_order' => 5
            ],
        ];

        foreach ($fonts as $font) {
            \App\Models\Font::create($font);
        }
    }
}
