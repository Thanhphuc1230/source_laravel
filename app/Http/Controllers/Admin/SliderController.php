<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests\Admin\SliderRequest;
use Illuminate\Support\Str;

class SliderController extends BaseController
{
    protected $model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'slider')
    {
        $this->module = 'slider';
        $this->model = new Slider();
        $this->nameItem = 'Hình ảnh';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $query = $this->model::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name_vn', 'LIKE', "%{$searchTerm}%")
                ->orWhere('status', '=', $searchTerm === 'active' ? 1 : 0);
            });
        }

        $data['list'] = $query->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(SliderRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = new \DateTime();

        // handle image
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->saveImage($request, $this->imageFolder, 'image', [
                'convertToWebp' => true,
                'quality' => 80,
                'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
            ]);
        }

        $this->model::create($data);
        toast('Thêm ' . $this->nameItem . ' thành công', 'success');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->model::where('uuid', $uuid);

        if (!$page->exists()) {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page->first(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(SliderRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['updated_at'] = new \DateTime();
        // update image
        $data['image'] = $this->imageService->updateImage($request, $current, $this->imageFolder, 'image', [
            'convertToWebp' => true,
            'quality' => 80,
            'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
        ]);

        $this->model::where('uuid', $uuid)->update($data);
        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function destroy(string $uuid)
    {
        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids');
  
        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function status($uuid, $status, $name)
    {
        return $this->statusManagementService->updateStatus($uuid, $status, $name,$this->model::class);
    }

    public function numericalOrder(Request $request, $uuid)
    {
        return $this->statusManagementService->updateStt($request, $uuid,$this->model::class);
    }
}
