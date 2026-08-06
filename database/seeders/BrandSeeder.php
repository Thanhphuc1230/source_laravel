<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name_vn' => 'Marriott International',
                'name_en' => 'Marriott International',
                'slug_vn' => 'marriott-international',
                'slug_en' => 'marriott-international',
                'image_vn' => 'marriott.svg',
                'image_en' => 'marriott.svg',
                'status' => 1,
                'stt' => 1,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Hilton Hotels & Resorts',
                'name_en' => 'Hilton Hotels & Resorts',
                'slug_vn' => 'hilton-hotels-resorts',
                'slug_en' => 'hilton-hotels-resorts',
                'image_vn' => 'hilton.svg',
                'image_en' => 'hilton.svg',
                'status' => 1,
                'stt' => 2,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Accor Hotels Group',
                'name_en' => 'Accor Hotels Group',
                'slug_vn' => 'accor-hotels-group',
                'slug_en' => 'accor-hotels-group',
                'image_vn' => 'accor.svg',
                'image_en' => 'accor.svg',
                'status' => 1,
                'stt' => 3,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'InterContinental Hotels',
                'name_en' => 'InterContinental Hotels',
                'slug_vn' => 'intercontinental-hotels',
                'slug_en' => 'intercontinental-hotels',
                'image_vn' => 'intercontinental.svg',
                'image_en' => 'intercontinental.svg',
                'status' => 1,
                'stt' => 4,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Vinpearl Resorts',
                'name_en' => 'Vinpearl Resorts',
                'slug_vn' => 'vinpearl-resorts',
                'slug_en' => 'vinpearl-resorts',
                'image_vn' => 'vinpearl.svg',
                'image_en' => 'vinpearl.svg',
                'status' => 1,
                'stt' => 5,
                'uuid' => Str::uuid()->toString(),
            ],
            [
                'name_vn' => 'Hyatt Hotels',
                'name_en' => 'Hyatt Hotels',
                'slug_vn' => 'hyatt-hotels',
                'slug_en' => 'hyatt-hotels',
                'image_vn' => 'hyatt.svg',
                'image_en' => 'hyatt.svg',
                'status' => 1,
                'stt' => 6,
                'uuid' => Str::uuid()->toString(),
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::updateOrCreate(
                ['slug_vn' => $brandData['slug_vn']],
                $brandData
            );
        }
    }
}
