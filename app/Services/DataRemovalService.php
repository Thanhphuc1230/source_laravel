<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DataRemovalService
{
    // Danh sách các field ảnh có thể có
    private $imageFields = ['image', 'avatar', 'logo', 'favicon'];

    public function destroyAllByUUIDs($model, $uuids, $imageFolder)
    {
        if (empty($uuids)) {
            toast('Không có mục nào được chọn để xóa.', 'error');

            return redirect()->back();
        }

        // Lấy các bản ghi cần xóa
        $items = $model::whereIn('uuid', $uuids)->get();

        // Xóa ảnh trước khi xóa records
        foreach ($items as $item) {
            $this->deleteAllImages($item, $imageFolder);
        }

        // Xóa tất cả records
        $deletedCount = $model::whereIn('uuid', $uuids)->delete();

        if ($deletedCount > 0) {
            toast('Xóa '.$deletedCount.' mục thành công.', 'success');
        } else {
            toast('Không có mục nào được xóa.', 'error');
        }

        return redirect()->back();
    }

    public function destroyData($model, $uuid, $imageFolder)
    {
        $item = $model::where('uuid', $uuid)->first();

        if (! $item) {
            toast('Không tìm thấy mục để xóa.', 'error');

            return back();
        }

        // Xóa ảnh trước khi xóa record
        $this->deleteAllImages($item, $imageFolder);

        // Xóa record
        $item->delete();

        // Dispatch event nếu có thể
        $this->dispatchModelEvent($model, $item, 'deleted');

        toast('Xóa mục thành công.', 'success');

        return back();
    }

    /**
     * Xóa tất cả ảnh liên quan đến một item
     */
    private function deleteAllImages($item, $imageFolder)
    {
        // Xóa các ảnh đơn (image, avatar, logo, favicon)
        foreach ($this->imageFields as $field) {
            $this->deleteSingleImage($item, $field, $imageFolder);
        }

        // Xóa ảnh chi tiết (JSON array)
        $this->deleteDetailImages($item, $imageFolder);
    }

    /**
     * Xóa một ảnh đơn
     */
    private function deleteSingleImage($item, $field, $imageFolder)
    {
        if (isset($item->$field) && $item->$field) {
            $imagePath = public_path("images/{$imageFolder}/{$item->$field}");
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
    }

    /**
     * Xóa ảnh chi tiết (image_detail field)
     */
    private function deleteDetailImages($item, $imageFolder)
    {
        if (! isset($item->image_detail) || ! $item->image_detail) {
            return;
        }

        $imageDetail = json_decode($item->image_detail, true);

        if (! is_array($imageDetail)) {
            return;
        }

        foreach ($imageDetail as $imageName) {
            if ($imageName) {
                $imagePath = public_path("images/{$imageFolder}/{$imageName}");
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        }
    }

    /**
     * Dispatch event cho model
     */
    private function dispatchModelEvent($model, $item, $action)
    {
        try {
            // Xác định event class dựa trên model
            $modelName = class_basename($model);
            $eventClass = "App\\Events\\{$modelName}\\{$modelName}Changed";

            if (class_exists($eventClass)) {
                $eventClass::dispatch($item, $action);
            }
        } catch (\Exception $e) {
            // Log error nhưng không làm crash
            Log::error("Failed to dispatch event for {$modelName}: ".$e->getMessage());
        }
    }
}
