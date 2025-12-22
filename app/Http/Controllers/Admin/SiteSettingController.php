<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Repositories\Interfaces\SiteSettingRepositoryInterface;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SiteSettingController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $siteSettingRepository;

    protected $siteSettingService;

    public function __construct(SiteSettingRepositoryInterface $siteSettingRepository, SiteSettingService $siteSettingService)
    {
        $this->module = 'site_setting';
        $this->model = new SiteSetting;
        $this->nameItem = 'cài đặt site';
        $this->siteSettingRepository = $siteSettingRepository;
        $this->siteSettingService = $siteSettingService;

        parent::__construct($this->module);

        View::share('nameClass', $this->module);
    }

    public function index(Request $request)
    {
        return redirect()->route('admin.site_setting.show', ['key' => 'homepage']);
    }

    public function show(Request $request, $key = 'homepage')
    {
        // Load settings theo group
        $groups = [
            'homepage' => 'homepage',
            'trade-partner' => 'trade_partner',
        ];
        $group = $groups[$key] ?? 'homepage';
        
        $settings = $this->siteSettingService->getByGroup($group);
        $data['settings'] = [];
        foreach ($settings as $setting) {
            if ($setting->type == 'json' && is_string($setting->value)) {
                $data['settings'][$setting->key] = json_decode($setting->value, true);
            } else {
                $data['settings'][$setting->key] = $setting->value;
            }
        }
        
        // Đặt tên item theo key
        $titles = [
            'homepage' => 'Cài đặt Homepage',
            'trade-partner' => 'Cài đặt Trade Partner',
        ];
        $data['nameItem'] = $titles[$key] ?? $this->nameItem;
        
        // Tên view tương ứng
        $viewNames = [
            'homepage' => 'homepage',
            'trade-partner' => 'trade-partner',
        ];
        $viewName = $viewNames[$key] ?? 'homepage';
        
        return $this->view_admin($viewName, $data);
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->input('settings', []);
        $features = $request->input('features', []);
        
        // Xử lý upload hero image
        if ($request->hasFile('hero_image')) {
            $heroImageSetting = $this->siteSettingRepository->getByKey('hero_image');
            // Nếu chưa có setting, tạo mới trước
            if (!$heroImageSetting) {
                $heroImageSetting = $this->siteSettingService->setSetting('hero_image', '', 'text', 'homepage', 'Hero Image');
            }
            $imagePath = $this->updateImage($request, $heroImageSetting, 'site_setting', 'hero_image');
            $settings['hero_image'] = $imagePath;
        }
        
        // Xử lý upload trade partner image
        if ($request->hasFile('trade_partner_image')) {
            $tradePartnerImageSetting = $this->siteSettingRepository->getByKey('trade_partner_image');
            if (!$tradePartnerImageSetting) {
                $tradePartnerImageSetting = $this->siteSettingService->setSetting('trade_partner_image', '', 'image', 'trade_partner', 'Hình ảnh Trade Partner');
            }
            $imagePath = $this->updateImage($request, $tradePartnerImageSetting, 'site_setting', 'trade_partner_image');
            $settings['trade_partner_image'] = $imagePath;
        }
        
        // Lưu settings thông thường
        foreach ($settings as $key => $value) {
            $existing = $this->siteSettingRepository->getByKey($key);
            
            // Xác định group dựa trên key prefix
            $group = 'homepage';
            if (strpos($key, 'trade_partner_') === 0) {
                $group = 'trade_partner';
            }
            
            // Xử lý JSON type
            $type = 'text';
            if ($key === 'trade_partner_features' || ($existing && $existing->type == 'json')) {
                $type = 'json';
                if (is_string($value)) {
                    $value = json_decode($value, true);
                }
            } elseif ($key === 'trade_partner_image' || strpos($key, '_image') !== false) {
                $type = 'image';
            }
            
            $description = $existing ? $existing->description : null;
            $this->siteSettingService->setSetting($key, $value, $type, $group, $description);
        }
        
        // Lưu features dưới dạng JSON cho homepage
        if (!empty($features)) {
            $this->siteSettingService->setSetting('features', $features, 'json', 'homepage', 'Danh sách tính năng nổi bật');
        }
        
        // Lưu trade_partner_features nếu có
        if (isset($settings['trade_partner_features'])) {
            $this->siteSettingService->setSetting('trade_partner_features', $settings['trade_partner_features'], 'json', 'trade_partner', 'Danh sách lợi ích Trade Partner');
        }

        return redirect()->back()->with('success', 'Cập nhật cài đặt thành công');
    }
}