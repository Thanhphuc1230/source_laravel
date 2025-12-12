<?php

namespace App\Http\Controllers\Admin;

use App\Events\FeedBack\FeedBackChanged;
use App\Http\Requests\Admin\FeedBackRequest;
use App\Models\FeedBack;
use App\Repositories\Interfaces\FeedBackRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class FeedBackController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $feedBackRepository;

    public function __construct(FeedBackRepositoryInterface $feedBackRepository, $imageFolder = 'feedback')
    {
        $this->module = 'feedback';
        $this->model = new FeedBack;
        $this->nameItem = 'Phản hồi';
        $this->imageFolder = $imageFolder;
        $this->feedBackRepository = $feedBackRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status', ''),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->feedBackRepository->getFilteredFeedback($filters);
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
        
        // handle image
        if ($request->hasFile('image')) {
            // Handle image - Save new image
            $data['image'] = $this->saveImage($request);
        }

        $feedback = $this->feedBackRepository->create($data);

        // Dispatch event sau khi tạo feedback
        FeedBackChanged::dispatch($feedback, 'created');

        toast('Thêm '.$this->nameItem.' thành công', 'success');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $feedback = $this->feedBackRepository->findByUuid($uuid);

        if (! $feedback) {
            toast('Không tìm thấy '.$this->nameItem, 'error');

            return back();
        }

        $data = [
            'page' => $feedback,
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(FeedBackRequest $request, string $uuid)
    {
        $current = $this->feedBackRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // update image
        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->feedBackRepository->update($data, $uuid);

        // Dispatch event sau khi cập nhật feedback
        FeedBackChanged::dispatch($current, 'updated');

        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function destroy(string $uuid)
    {
        $feedback = $this->feedBackRepository->findByUuid($uuid);

        // Dispatch event trước khi xóa feedback
        FeedBackChanged::dispatch($feedback, 'deleted');

        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $feedbacks = $this->feedBackRepository->findByUuids($uuids, ['uuid', 'name']);

        FeedbackChanged::dispatch(null, 'deleted');

        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function status($uuid, $status, $field)
    {
        $feedback = $this->feedBackRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        FeedBackChanged::dispatch($feedback, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $feedback = $this->feedBackRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        FeedBackChanged::dispatch($feedback, 'order_updated');

        return $result;
    }
}
