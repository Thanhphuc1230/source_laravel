<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Http\Request;
use App\Models\Font;
use Illuminate\Support\Facades\View;

class FontController extends BaseController
{
    protected $model;
    protected $nameItem;

    public function __construct()
    {
        parent::__construct('fonts');
        $this->model = new Font();
        $this->nameItem = 'Font';
        View::share('nameClass', 'fonts');
    }

    // API lấy danh sách font active cho CKEditor
    public function getActiveFonts()
    {
        $fonts = Font::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function($font) {
                return [
                    'name' => $font->name,
                    'family' => $font->family,
                    'url' => $font->full_url,
                    'type' => $font->type
                ];
            });

        return response()->json($fonts);
    }

    // Trang quản lý fonts
    public function index(Request $request)
    {
        $query = $this->model::query();

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'LIKE', "%{$searchTerm}%");
        }

        $data['list'] = $query->orderBy('sort_order')->paginate(20);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    // Form tạo font mới
    public function create()
    {
        $data['nameItem'] = $this->nameItem;
        $data['action'] = 'create';
        return $this->view_admin('detail', $data);
    }

    // Lưu font mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'type' => 'required|in:system,upload,google',
            'css_url' => 'required_if:type,google',
            'font_file' => 'required_if:type,upload|file|mimes:woff,woff2,ttf,otf|max:5120'
        ]);

        $data = $request->only(['name', 'family', 'type', 'css_url', 'is_active', 'sort_order']);

        // Xử lý upload file font
        if ($request->type === 'upload' && $request->hasFile('font_file')) {
            $file = $request->file('font_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('fronts');

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Di chuyển file vào public/fronts/
            $file->move($destinationPath, $filename);
            $data['file_path'] = 'fronts/' . $filename;
        }

        Font::create($data);

        if ($request->has('return_list')) {
            return $this->route_admin('index', [], ['success' => 'Thêm font thành công!']);
        } else {
            return $this->route_admin('create', [], ['success' => 'Thêm font thành công!']);
        }
    }

    // Form chỉnh sửa font
    public function edit($id)
    {
        $data['page'] = $this->model::findOrFail($id);
        $data['nameItem'] = $this->nameItem;
        $data['action'] = 'edit';
        return $this->view_admin('detail', $data);
    }

    // Cập nhật font
    public function update(Request $request, $id)
    {
        $font = $this->model::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'type' => 'required|in:system,upload,google',
            'css_url' => 'required_if:type,google',
            'font_file' => 'nullable|file|mimes:woff,woff2,ttf,otf|max:5120'
        ]);

        $data = $request->only(['name', 'family', 'type', 'css_url', 'is_active', 'sort_order']);

        // Xử lý upload file font mới
        if ($request->type === 'upload' && $request->hasFile('font_file')) {
            // Xóa file cũ
            if ($font->file_path && file_exists(public_path($font->file_path))) {
                unlink(public_path($font->file_path));
            }

            $file = $request->file('font_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('fronts');

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Di chuyển file vào public/fronts/
            $file->move($destinationPath, $filename);
            $data['file_path'] = 'fronts/' . $filename;
        }

        $font->update($data);

        return $this->route_admin('index', [], ['success' => 'Cập nhật font thành công!']);
    }

    // Xóa font
    public function destroy($id)
    {
        $font = $this->model::findOrFail($id);

        // Xóa file nếu là upload
        if ($font->type === 'upload' && $font->file_path) {
            $filePath = public_path($font->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $font->delete();

        return back()->with('success', 'Xóa font thành công!');
    }

    // Xóa nhiều fonts
    public function destroyAll(Request $request)
    {
        $ids = $request->input('uuids', []);

        if (empty($ids)) {
            return back()->with('error', 'Không có font nào được chọn!');
        }

        foreach ($ids as $id) {
            $font = $this->model::find($id);
            if ($font) {
                // Xóa file nếu là upload
                if ($font->type === 'upload' && $font->file_path) {
                    $filePath = public_path($font->file_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                $font->delete();
            }
        }

        return back()->with('success', 'Xóa ' . count($ids) . ' font thành công!');
    }
}
