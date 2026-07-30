<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductSettingController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;

    public function __construct()
    {
        $this->module = 'product-setting';
        $this->model = new ProductSetting;
        $this->nameItem = 'Cấu hình sản phẩm';

        parent::__construct($this->module, null);

        View::share('nameClass', $this->module);
    }

    /**
     * Hiển thị form cấu hình chi tiết (Vào thẳng chi tiết)
     */
    public function index()
    {
        $data = [
            'title' => 'Cấu hình sản phẩm',
            'module' => $this->module,
            'nameClass' => $this->module,
            'settings' => [
                'pagination' => ProductSetting::get('products_pagination', 8),
                'font_size' => ProductSetting::get('products_font_size', '16px'),
                'show_intro' => ProductSetting::get('products_show_intro', true),
                'click_image_detail' => ProductSetting::get('products_click_image_detail', true),
                'title_color' => ProductSetting::get('products_title_color', '#064e3b'),
                'category_color' => ProductSetting::get('products_category_color', '#b45309'),
                'banner_category' => ProductSetting::get('products_banner_category', ''),
                'banner_detail' => ProductSetting::get('products_banner_detail', ''),
            ]
        ];

        return $this->view_admin('index', $data);
    }

    /**
     * Cập nhật tất cả các cấu hình sản phẩm từ form
     */
    public function updateSettings(Request $request)
    {
        // 1. Lưu các giá trị cấu hình chữ, màu sắc vào DB
        ProductSetting::set('products_pagination', (int) $request->input('products_pagination', 8), 'number', 'general');
        ProductSetting::set('products_font_size', $request->input('products_font_size', '16px'), 'text', 'style');
        ProductSetting::set('products_show_intro', $request->has('products_show_intro') ? '1' : '0', 'boolean', 'general');
        ProductSetting::set('products_click_image_detail', $request->has('products_click_image_detail') ? '1' : '0', 'boolean', 'general');
        ProductSetting::set('products_title_color', $request->input('products_title_color', '#064e3b'), 'text', 'style');
        ProductSetting::set('products_category_color', $request->input('products_category_color', '#b45309'), 'text', 'style');

        // 2. Xử lý upload Banner Danh mục
        if ($request->has('delete_products_banner_category')) {
            $oldPath = ProductSetting::get('products_banner_category');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            ProductSetting::set('products_banner_category', '', 'text', 'banner');
        } elseif ($request->hasFile('products_banner_category')) {
            $oldPath = ProductSetting::get('products_banner_category');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            $newPath = $this->imageService->saveImage($request, 'product-setting', 'products_banner_category', $this->defaultImageConfig);
            if ($newPath) {
                ProductSetting::set('products_banner_category', $newPath, 'text', 'banner');
            }
        }

        // 3. Xử lý upload Banner Chi tiết
        if ($request->has('delete_products_banner_detail')) {
            $oldPath = ProductSetting::get('products_banner_detail');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            ProductSetting::set('products_banner_detail', '', 'text', 'banner');
        } elseif ($request->hasFile('products_banner_detail')) {
            $oldPath = ProductSetting::get('products_banner_detail');
            if ($oldPath) {
                $this->imageService->deleteImage($oldPath, 'product-setting');
            }
            $newPath = $this->imageService->saveImage($request, 'product-setting', 'products_banner_detail', $this->defaultImageConfig);
            if ($newPath) {
                ProductSetting::set('products_banner_detail', $newPath, 'text', 'banner');
            }
        }

        // 4. Dọn dẹp cache để frontend cập nhật ngay lập tức
        if (class_exists(\App\Services\CacheService::class)) {
            \App\Services\CacheService::forgetTags(['frontend', 'products', 'categories']);
        }
        \Illuminate\Support\Facades\Cache::forget('frontend_global_data');

        toast('Cập nhật cấu hình sản phẩm thành công!', 'success');

        return back();
    }
}
