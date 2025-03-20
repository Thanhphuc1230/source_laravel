<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Exception;

class DataRemovalService
{   
    public function destroyAllByUUIDs($model, $uuids, $imageFolder)
    {
        // Kiểm tra nếu không có UUID nào được gửi lên
        if (empty($uuids)) {
            toast('Không có mục nào được chọn để xóa.', 'error');
            return redirect()->back();
        }

        // Lấy các bản ghi cần xóa
        $models = $model::whereIn('uuid', $uuids)->get();

        foreach ($models as $model) {
            // Xóa ảnh đại diện nếu tồn tại
            $avatarPath = public_path('images/' . $imageFolder . '/' . $model->avatar);
            if (File::exists($avatarPath)) {
                File::delete($avatarPath);
            }

            if($model->image_detail){
                $imageDetail = json_decode($model->image_detail, true);
                foreach ($imageDetail as $image) {
                    $imagePath = public_path('images/' . $imageFolder . '/' . $image);
                    if (File::exists($imagePath)) {
                        File::delete($imagePath);
                    }
                }
            }
        }

        // Xóa tất cả các mục có UUID nằm trong danh sách
        $deletedCount = $model::whereIn('uuid', $uuids)->delete();

        // Kiểm tra xem có mục nào được xóa thành công không
        if ($deletedCount > 0) {
            toast('Xóa ' . $deletedCount . ' mục thành công.', 'success');
        } else {
            toast('Không có mục nào được xóa.', 'error');
        }
        return redirect()->back();
    }

    public function destroyData($model, $uuid, $imageFolder)
    {
        // Tìm bản ghi cần xóa
        $page = $model::where('uuid', $uuid)->first();
        if (!$page) {
            toast('Không tìm thấy sản phẩm để xóa.', 'error');
            return back();
        }

        // Xóa ảnh đại diện nếu tồn tại
        $avatarPath = public_path('images/' . $imageFolder . '/' . $page->avatar);
        if (File::exists($avatarPath)) {
            File::delete($avatarPath);
        }

        // Xóa ảnh chi tiết nếu tồn tại
        if($page->image_detail){
            $imageDetail = json_decode($page->image_detail, true);
            foreach ($imageDetail as $image) {
            $imagePath = public_path('images/' . $imageFolder . '/' . $image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
                }
            }
        }

        // Tiếp tục xóa bản ghi trong CSDL
        $page->delete();

        toast('Xóa sản phẩm thành công.', 'success');
        return back();
    }
}