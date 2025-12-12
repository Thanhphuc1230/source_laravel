<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductSettingRequest;
use App\Models\ProductSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ProductSettingController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = null)
    {
        $this->module = 'product-setting';
        $this->model = new ProductSetting;
        $this->nameItem = 'Product Setting';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $this->module);
    }

    /**
     * Get base data for all views
     */
    private function getBaseData(): array
    {
        return [
            'title' => 'Product Settings',
            'module' => $this->module,
            'nameClass' => $this->module,
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->getBaseData();
        $data['settings'] = $this->model::orderBy('group')->orderBy('sort_order')->paginate(20);

        return $this->view_admin('list', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->getBaseData();
        $data['action'] = 'create';

        return $this->view_admin('detail', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductSettingRequest $request)
    {
        $this->model::create($request->validated());

        toast('Product setting created successfully', 'success');

        return $this->route_admin('index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show($uuid)
    {
        $setting = $this->model::where('uuid', $uuid)->firstOrFail();

        $data = $this->getBaseData();
        $data['action'] = 'show';
        $data['setting'] = $setting;

        return $this->view_admin('detail', $data);
    }

    public function edit($uuid, $page = null)
    {
        $setting = $this->model::where('uuid', $uuid)->firstOrFail();

        $data = $this->getBaseData();
        $data['action'] = 'edit';
        $data['setting'] = $setting;
        $data['currentPage'] = $page;

        return $this->view_admin('detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductSettingRequest $request, $uuid)
    {
        $setting = $this->model::where('uuid', $uuid)->firstOrFail();

        $setting->update($request->validated());

        toast('Product setting updated successfully', 'success');

        return $this->route_admin('index');
    }

    /**
     * Remove the specified resource in storage.
     */
    public function destroy($uuid)
    {
        $setting = $this->model::where('uuid', $uuid)->firstOrFail();
        $setting->delete();

        toast('Product setting deleted successfully', 'success');

        return $this->route_admin('index');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids');

        if ($uuids && count($uuids) > 0) {
            $this->model::whereIn('uuid', $uuids)->delete();
            toast('Đã xóa '.count($uuids).' setting được chọn', 'success');
        } else {
            toast('Vui lòng chọn ít nhất một setting để xóa', 'error');
        }

        return $this->route_admin('index');
    }

    public function status($uuid, $status, $field)
    {
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        return $result;
    }
}
