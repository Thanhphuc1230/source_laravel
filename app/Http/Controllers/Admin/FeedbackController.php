<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedBack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests\Admin\FeedBackRequest;
use Illuminate\Support\Str;
use App\Events\Feedback\FeedbackChanged;
class FeedBackController extends BaseController
{
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'feedback')
    {
        $this->module = 'feedback';
        $this->model = new FeedBack();
        $this->nameItem = 'Phản hồi';
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

        $data['list'] = $query->orderBy('created_at','desc')->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(FeedBackRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null);

        // handle image
        if ($request->hasFile('image')) {
            $data['image'] = $this->handleSingleImage($request);
        }

        $feedback = $this->model::create($data);

        // Dispatch event sau khi tạo feedback
        FeedbackChanged::dispatch($feedback, 'created');

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

    public function update(FeedBackRequest $request, string $uuid)
    {
        $current = $this->model::where('uuid', $uuid)->first();
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['created_at'] = $this->resolveCreatedAt($data['created_at'] ?? null, $current->created_at);

        // update image
        $data['image'] = $this->handleSingleImage($request, $current);

        $current->update($data);

        // Dispatch event sau khi cập nhật feedback
        FeedbackChanged::dispatch($current, 'updated');

        toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function destroy(string $uuid)
    {
        $feedback = $this->model::where('uuid', $uuid)->first();

        // Dispatch event trước khi xóa feedback
        FeedbackChanged::dispatch($feedback, 'deleted');

        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids');

        // Optimize: only select fields needed for events
        $feedbacks = $this->model::whereIn('uuid', $uuids)
            ->select('uuid', 'name')
            ->get();

        FeedbackChanged::dispatch(null, 'deleted');

        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function status($uuid, $status, $name)
    {
        $feedback = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        FeedbackChanged::dispatch($feedback, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $feedback = $this->model::where('uuid', $uuid)->first();
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        FeedbackChanged::dispatch($feedback, 'order_updated');

        return $result;
    }
}
