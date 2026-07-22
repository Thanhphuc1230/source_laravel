<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NewsSettingController extends BaseController
{
    protected $module;
    protected $model;
    protected $nameItem;

    public function __construct()
    {
        $this->module = 'news-setting';
        $this->model = new NewsSetting;
        $this->nameItem = 'Cấu hình tin tức';

        parent::__construct($this->module, null);

        View::share('nameClass', $this->module);
    }

    /**
     * Hiển thị form cấu hình chi tiết (Vào thẳng chi tiết)
     */
    public function index()
    {
        $data = [
            'title' => 'Cấu hình tin tức',
            'module' => $this->module,
            'nameClass' => $this->module,
            'settings' => [
                'pagination' => NewsSetting::get('news_pagination', 8),
                'font_size' => NewsSetting::get('news_font_size', '16px'),
                'show_intro' => NewsSetting::get('news_show_intro', true),
                'click_image_detail' => NewsSetting::get('news_click_image_detail', true),
                'title_color' => NewsSetting::get('news_title_color', '#064e3b'),
                'category_color' => NewsSetting::get('news_category_color', '#b45309'),
            ]
        ];

        return $this->view_admin('index', $data);
    }

    /**
     * Cập nhật tất cả các cấu hình tin tức từ form
     */
    public function updateSettings(Request $request)
    {
        // 1. Lưu các giá trị cấu hình vào DB
        NewsSetting::set('news_pagination', (int) $request->input('news_pagination', 8), 'number', 'general');
        NewsSetting::set('news_font_size', $request->input('news_font_size', '16px'), 'text', 'style');
        NewsSetting::set('news_show_intro', $request->has('news_show_intro') ? '1' : '0', 'boolean', 'general');
        NewsSetting::set('news_click_image_detail', $request->has('news_click_image_detail') ? '1' : '0', 'boolean', 'general');
        NewsSetting::set('news_title_color', $request->input('news_title_color', '#064e3b'), 'text', 'style');
        NewsSetting::set('news_category_color', $request->input('news_category_color', '#b45309'), 'text', 'style');

        // 2. Dọn dẹp cache để frontend cập nhật ngay lập tức
        if (class_exists(\App\Services\CacheService::class)) {
            \App\Services\CacheService::forgetTags(['frontend', 'news', 'categories']);
        }
        \Illuminate\Support\Facades\Cache::forget('frontend_global_data');

        toast('Cập nhật cấu hình tin tức thành công!', 'success');

        return back();
    }
}
