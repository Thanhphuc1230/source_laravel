<?php

namespace App\Http\Controllers\Admin;

use App\Events\About\AboutChanged;
use App\Http\Requests\Admin\AboutRequest;
use App\Models\About;
use App\Repositories\Interfaces\AboutRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class AboutController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $aboutRepository;

    public function __construct(AboutRepositoryInterface $aboutRepository, $imageFolder = 'about')
    {
        $this->module = 'about';
        $this->model = new About;
        $this->nameItem = 'Giới thiệu';
        $this->imageFolder = $imageFolder;
        $this->aboutRepository = $aboutRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $this->module);
    }

    public function index(Request $request)
    {
        $about = \App\Models\About::first();

        if (!$about) {
            $about = \App\Models\About::create([
                'uuid' => \Illuminate\Support\Str::uuid()->toString(),
                'name_vn' => 'Giới thiệu về chúng tôi',
                'content_vn' => '<p>Chúng tôi là công ty lữ hành hàng đầu chuyên cung cấp các giải pháp tour du lịch cao cấp.</p>',
                'status' => 1,
                'stt' => 1,
                'stats' => [
                    ['icon' => 'uploads/icon/vietnam.png', 'value' => '10+', 'name_vn' => 'Năm kinh nghiệm', 'name_en' => 'Years of Experience'],
                    ['icon' => 'uploads/icon/usa.png', 'value' => '900+', 'name_vn' => 'Khách hàng & Dự án', 'name_en' => 'Clients & Projects'],
                    ['icon' => 'uploads/icon/ytb.png', 'value' => '20+', 'name_vn' => 'Giải pháp chuyên ngành', 'name_en' => 'Professional Solutions'],
                    ['icon' => 'uploads/icon/zalo.png', 'value' => '50+', 'name_vn' => 'Đối tác uy tín chất lượng', 'name_en' => 'Prestigious Partners'],
                ]
            ]);
        }

        return redirect()->route('admin.about.edit', ['uuid' => $about->uuid, 'page' => 1]);
    }

    public function edit($uuid, $currentPage)
    {
        $about = $this->aboutRepository->findByUuid($uuid);

        if (! $about) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $about, // template expects 'page' variable
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(AboutRequest $request, string $uuid)
    {
        $current = $this->aboutRepository->findByUuid($uuid);
        
        if (! $current) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage', 'stats_files');
        $data['image'] = $this->updateImage($request, $current, null, 'image');
        $data['stats'] = $this->handleStatsFiles($request, $current->stats);

        $this->aboutRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');
        AboutChanged::dispatch($current, 'updated');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    protected function handleStatsFiles(AboutRequest $request, ?array $currentStats = null)
    {
        $stats = $request->input('stats', []);

        if (!is_array($stats)) {
            return [];
        }

        foreach ($stats as $index => $stat) {
            // Kiểm tra xem có file upload mới cho index này không
            if ($request->hasFile("stats_files.{$index}.icon")) {
                $file = $request->file("stats_files.{$index}.icon");
                // Upload file
                $filename = 'stat_icon_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/about'), $filename);
                $stats[$index]['icon'] = 'uploads/about/' . $filename;
            } else {
                // Giữ nguyên icon cũ
                $stats[$index]['icon'] = $stat['icon'] ?? ($currentStats[$index]['icon'] ?? null);
            }
        }

        return $stats;
    }
}
