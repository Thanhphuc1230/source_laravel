<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class CommentController extends BaseController
{
    protected $nameItem;
    protected $imageFolder;
    protected $commentRepository;
    protected $model;
    public function __construct(CommentRepositoryInterface $commentRepository, $imageFolder = 'comment')
    {
        $this->nameItem = 'Bình luận';
        $this->imageFolder = $imageFolder;
        $this->commentRepository = $commentRepository;
        $this->model = new \App\Models\Comment();
        parent::__construct($this->imageFolder);
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
        return $this->updateStatus($uuid, $status, $field);
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

    public function update(Request $request, $uuid)
    {
        // Comment chỉ để xem, không cần update
        toast('Bình luận chỉ dành để xem, không thể chỉnh sửa', 'info');
        return redirect()->route('admin.comment.index');
    }

    public function numericalOrder(Request $request, $uuid)
    {
        // Comment không cần sắp xếp thứ tự
        toast('Bình luận không hỗ trợ sắp xếp thứ tự', 'info');
        return redirect()->route('admin.comment.index');
    }
}
