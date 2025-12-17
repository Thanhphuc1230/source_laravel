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
        $group = $request->input('group', 'homepage');
        
        // Hiển thị view homepage settings
        $settings = $this->siteSettingService->getHomepageSettings();
        $data['settings'] = $settings;
        $data['nameItem'] = $this->nameItem;
        
        return $this->view_admin('homepage', $data);
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->input('settings', []);
        $features = $request->input('features', []);
        
        // Xử lý upload hero image
        if ($request->hasFile('hero_image')) {
            $heroImageSetting = $this->siteSettingRepository->getByKey('hero_image');
            $imagePath = $this->updateImage($request, $heroImageSetting, 'site_setting', 'hero_image');
            $settings['hero_image'] = $imagePath;
        }
        
        // Lưu settings thông thường
        foreach ($settings as $key => $value) {
            $existing = $this->siteSettingRepository->getByKey($key);
            if ($existing && $existing->type == 'json') {
                $value = json_decode($value, true);
            }
            $this->siteSettingService->setSetting($key, $value, $existing ? $existing->type : 'text', $existing ? $existing->group : null, $existing ? $existing->description : null);
        }
        
        // Lưu features dưới dạng JSON
        if (!empty($features)) {
            $this->siteSettingService->setSetting('features', $features, 'json', 'homepage', 'Danh sách tính năng nổi bật');
        }

        return redirect()->back()->with('success', 'Cập nhật cài đặt thành công');
    }
}