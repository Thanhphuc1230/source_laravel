<?php

namespace App\Http\Controllers\Admin;

use App\Events\News\NewsChanged;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Repositories\Interfaces\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NewsController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;
    
    protected $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository, $imageFolder = 'news')
    {
        $this->module = 'news';
        $this->model = new News;
        $this->nameItem = 'bài viết';
        $this->imageFolder = $imageFolder;
        $this->newsRepository = $newsRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];

        $data['list'] = $this->newsRepository->getFilteredNews($filters);
        $data['nameItem'] = $this->nameItem;
        $data['category'] = $this->newsRepository->getActiveCategories();

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['category'] = $this->newsRepository->getActiveCategories();
        $data['action'] = 'create';
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('detail', $data);
    }

    public function store(NewsRequest $request)
    {
        $data = $request->except('_token', 'return_back', 'return_list');
        $data['slug'] = $data['slug'] ?? $this->newsRepository->generateUniqueSlug($data['name_vn']);
        $data['image'] = $this->saveImage($request);

        $news = $this->newsRepository->createWithAutoSlug($data);
        toast('Thêm '.$this->nameItem.' thành công', 'success');
        NewsChanged::dispatch($news, 'created', $news->slug);

        return $request->has('return_back') ? back() : ($request->has('return_list') ? $this->route_admin('index') : null);
    }

    public function edit($uuid, $currentPage)
    {
        $page = $this->newsRepository->findByUuid($uuid);
        
        if (!$page) {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }

        $data = [
            'page' => $page,
            'category' => $this->newsRepository->getActiveCategories(),
            'action' => 'edit',
            'nameItem' => $this->nameItem,
            'currentPage' => $currentPage,
            'imageFolder' => $this->imageFolder,
        ];

        return $this->view_admin('detail', $data);
    }

    public function update(NewsRequest $request, string $uuid)
    {
        $current = $this->newsRepository->findByUuid($uuid);
        $data = $request->except('_token', 'return_back', 'return_list', 'currentPage');
        $data['slug'] = empty($data['slug']) ? $this->newsRepository->generateUniqueSlug($data['name_vn'], $uuid) : $data['slug'];
        
        // Handle image
        $data['image'] = $this->updateImage($request, $current);

        $this->newsRepository->update($data, $uuid);
        toast('Cập nhật '.$this->nameItem.' thành công', 'success');

        // Remove related cache
        NewsChanged::dispatch($current, 'updated', $data['slug']);

        return $this->route_admin('index', [], [], $request->input('currentPage'));
    }

    public function status($uuid, $status, $field)
    {
        $news = $this->newsRepository->findByUuid($uuid);
        $result = $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);

        // Remove related cache
        NewsChanged::dispatch($news, 'status_updated');

        return $result;
    }

    public function numericalOrder(Request $request, $uuid)
    {
        $news = $this->newsRepository->findByUuid($uuid);
        $result = $this->toggleService->updateModelOrder($request, $uuid, $this->model::class);

        // Remove related cache
        NewsChanged::dispatch($news, 'order_updated');

        return $result;
    }

    public function destroy(string $uuid)
    {
        $news = $this->newsRepository->findByUuid($uuid);
        $result = $this->dataRemovalService->destroyData($this->model::class, $uuid, $this->imageFolder);

        // Remove related cache
        NewsChanged::dispatch($news, 'deleted');

        return $result;
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        
        // Get items before deletion for event dispatch if needed
        $newsItems = $this->newsRepository->findByUuids($uuids, ['uuid', 'slug', 'name_vn', 'category_id']);

        $result = $this->dataRemovalService->destroyAllByUUIDs($this->model::class, $uuids, $this->imageFolder);

        // Chỉ cần xóa cache toàn bộ news
        NewsChanged::dispatch(null, 'deleted');

        return $result;
    }
}
