<?php

namespace Database\Seeders;

use App\Models\ProductSetting;
use Illuminate\Database\Seeder;

class ProductSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'max_products_per_page',
                'value' => '12',
                'type' => 'number',
                'group' => 'Display',
                'description' => 'Maximum number of products to display per page',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'enable_product_reviews',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'Features',
                'description' => 'Enable or disable product reviews',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'product_image_sizes',
                'value' => '{"small": "150x150", "medium": "300x300", "large": "600x600"}',
                'type' => 'json',
                'group' => 'Images',
                'description' => 'Product image sizes configuration',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'product_description_template',
                'value' => '<div class="product-desc"><h3>Product Details</h3><p>{{description}}</p></div>',
                'type' => 'html',
                'group' => 'Templates',
                'description' => 'HTML template for product descriptions',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'key' => 'default_product_category',
                'value' => 'general',
                'type' => 'text',
                'group' => 'Defaults',
                'description' => 'Default category for new products',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            ProductSetting::create($setting);
        }
    }
}
