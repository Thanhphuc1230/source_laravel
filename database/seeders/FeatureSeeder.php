<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'title_vn' => 'Free Shipping',
                'content_vn' => 'For orders from $50',
                'image' => null,
                'status' => 1,
                'stt' => 1,
                'uuid' => \Illuminate\Support\Str::uuid(),
            ],
            [
                'title_vn' => 'Support 24/7',
                'content_vn' => 'Call us anytime',
                'image' => null,
                'status' => 1,
                'stt' => 2,
                'uuid' => \Illuminate\Support\Str::uuid(),
            ],
            [
                'title_vn' => '100% Safety',
                'content_vn' => 'Only secure payments',
                'image' => null,
                'status' => 1,
                'stt' => 3,
                'uuid' => \Illuminate\Support\Str::uuid(),
            ],
            [
                'title_vn' => 'Hot Offers',
                'content_vn' => 'Discounts up to 90%',
                'image' => null,
                'status' => 1,
                'stt' => 4,
                'uuid' => \Illuminate\Support\Str::uuid(),
            ],
        ];

        foreach ($features as $feature) {
            \App\Models\Feature::create($feature);
        }
    }
}
