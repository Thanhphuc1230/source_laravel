<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CommentController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository, $imageFolder = 'comment')
    {
        $this->module = 'comment';
        $this->model = new \App\Models\Comment();
        $this->nameItem = 'Bình luận';
        $this->imageFolder = $imageFolder;
        $this->commentRepository = $commentRepository;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'sort_field' => 'created_at',
            'sort_direction' => 'desc'
        ];
        $data['list'] = $this->commentRepository->getFilteredComments($filters);
        $data['nameItem'] = $this->nameItem;
        return $this->view_admin('list', $data);
    }

    public function status($uuid, $status, $field)
    {
        return $this->toggleService->toggleModelStatus($uuid, $status, $field, $this->model::class);
    }

    public function edit($uuid)
    {
        $comment = $this->commentRepository->findByUuid($uuid);
        if ($comment) {
            $data['page'] = $comment;
            $data['action'] = 'edit';
            $data['nameItem'] = $this->nameItem;
            return $this->view_admin('detail', $data);
        } else {
            toast('Không tìm thấy '.$this->nameItem, 'error');
            return back();
        }
    }

    public function update(Request $request, $uuid)
    {
        $comment = $this->commentRepository->findByUuid($uuid);
        if ($comment) {
            $comment->update($request->only(['status']));
            toast('Cập nhật ' . $this->nameItem . ' thành công', 'success');
        } else {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
        }
        return redirect()->route('admin.comment.index');
    }

    public function destroy(string $uuid)
    {
        $comment = $this->commentRepository->findByUuid($uuid);
        if ($comment) {
            $comment->delete();
            toast('Xóa ' . $this->nameItem . ' thành công', 'success');
        } else {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
        }
        return redirect()->route('admin.comment.index');
    }

    public function destroyAll(Request $request)
    {
        $uuids = $request->input('uuids', []);
        if (!empty($uuids)) {
            $deleted = $this->model->whereIn('uuid', $uuids)->delete();
            toast("Đã xóa {$deleted} " . $this->nameItem, 'success');
        } else {
            toast('Không có dữ liệu để xóa', 'error');
        }
        return redirect()->route('admin.comment.index');
    }
}
