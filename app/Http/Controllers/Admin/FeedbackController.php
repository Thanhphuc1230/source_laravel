<?php

namespace App\Http\Controllers\Admin;

use App\Events\Feedback\FeedbackChanged;
use App\Http\Requests\Admin\FeedBackRequest;
use App\Models\FeedBack;
use App\Repositories\Interfaces\FeedbackRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class FeedBackController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $feedbackRepository;

    public function __construct(FeedbackRepositoryInterface $feedbackRepository, $imageFolder = 'feedback')
    {
        $this->module = 'feedback';
        $this->model = new FeedBack;
        $this->nameItem = 'Phản hồi';
        $this->imageFolder = $imageFolder;
        $this->feedbackRepository = $feedbackRepository;

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

        $data['list'] = $this->feedbackRepository->getFilteredFeedback($filters);
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

        $feedback = $this->feedbackRepository->create($data);

        // Dispatch event sau khi tạo feedback
        FeedbackChanged::dispatch($feedback, 'created');

        toast('Thêm '.$this->nameItem.' thành công', 'success');

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $feedback = $this->feedbackRepository->findByUuid($uuid);

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
        $current = $this->feedbackRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');

        // update image
        // Handle image - Update existing image
        $data['image'] = $this->updateImage($request, $current);

        $this->feedbackRepository->update($data, $uuid);

        // Dispatch event sau khi cập nhật feedback
        FeedbackChanged::dispatch($current, 'updated');

        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function destroy(string $uuid)
    {
        $feedback = $this->feedbackRepository->findByUuid($uuid);

        // Dispatch event trước khi xóa feedback
        FeedbackChanged::dispatch($feedback, 'deleted');

        return $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);

        // Optimize: only select fields needed for events
        $feedbacks = $this->feedbackRepository->findByUuids($uuids, ['uuid', 'name']);

        FeedbackChanged::dispatch(null, 'deleted');

        return $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);
    }

    public function status($uuid, $status, $name)
    {
        $feedback = $this->feedbackRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $name, $this->model::class);

        // Remove related cache
        FeedbackChanged::dispatch($feedback, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $feedback = $this->feedbackRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        FeedbackChanged::dispatch($feedback, 'order_updated');

        return $result;
    }
}
