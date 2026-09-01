<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DataRemovalService
{
    // Danh sách các field ảnh chuẩn
    private $imageFields = [
        'image',
        'image_vn',
        'image_en',
        'image_desktop',
        'image_desktop_vn',
        'image_desktop_en',
        'image_mobile',
        'image_mobile_vn',
        'image_mobile_en',
        'avatar',
        'logo',
        'logo_footer',
        'favicon',
        'icon',
        'bg',
        'bg_header',
        'bg_footer',
        'background',
        'banner',
        'thumbnail',
    ];

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
            // Dispatch event per item before deletion for cache invalidation
            $this->dispatchModelEvent($model, $item, 'deleted');
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
        $fields = $this->imageFields;

        // Quét động các attribute trong model để phát hiện bất kỳ trường ảnh nào
        if (method_exists($item, 'getAttributes')) {
            foreach ($item->getAttributes() as $attrKey => $attrVal) {
                if ($attrKey === 'image_detail') {
                    continue;
                }
                if (! in_array($attrKey, $fields)) {
                    if (str_contains($attrKey, 'image') || str_contains($attrKey, 'avatar') || str_contains($attrKey, 'logo') || str_contains($attrKey, 'icon') || str_contains($attrKey, 'favicon') || str_contains($attrKey, 'banner') || str_contains($attrKey, 'bg')) {
                        $fields[] = $attrKey;
                    } elseif (is_string($attrVal) && (str_starts_with($attrVal, 'uploads/') || str_starts_with($attrVal, '/uploads/'))) {
                        $fields[] = $attrKey;
                    }
                }
            }
        }

        // Xóa các ảnh đơn
        foreach ($fields as $field) {
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
        $fileName = method_exists($item, 'getRawOriginal') ? $item->getRawOriginal($field) : ($item->$field ?? null);
        if (! $fileName || ! is_string($fileName)) {
            return;
        }

        if (filter_var($fileName, FILTER_VALIDATE_URL)) {
            $path = parse_url($fileName, PHP_URL_PATH);
            $search = "uploads/";
            $pos = strpos($path, $search);
            $imagePath = $pos !== false ? public_path(substr($path, $pos)) : public_path(ltrim($path, '/'));
        } elseif (str_starts_with($fileName, 'uploads/') || str_starts_with($fileName, '/uploads/')) {
            $imagePath = public_path(ltrim($fileName, '/'));
        } else {
            $imagePath = public_path("uploads/{$imageFolder}/{$fileName}");
        }

        if (File::exists($imagePath) && ! File::isDirectory($imagePath)) {
            File::delete($imagePath);
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

        $rawDetail = method_exists($item, 'getRawOriginal') ? $item->getRawOriginal('image_detail') : $item->image_detail;
        if (is_string($rawDetail)) {
            $imageDetail = json_decode($rawDetail, true);
        } elseif (is_array($rawDetail)) {
            $imageDetail = $rawDetail;
        } else {
            $imageDetail = null;
        }

        if (! is_array($imageDetail)) {
            return;
        }

        foreach ($imageDetail as $imageName) {
            if ($imageName && is_string($imageName)) {
                if (filter_var($imageName, FILTER_VALIDATE_URL)) {
                    $path = parse_url($imageName, PHP_URL_PATH);
                    $search = "uploads/";
                    $pos = strpos($path, $search);
                    $imagePath = $pos !== false ? public_path(substr($path, $pos)) : public_path(ltrim($path, '/'));
                } elseif (str_starts_with($imageName, 'uploads/') || str_starts_with($imageName, '/uploads/')) {
                    $imagePath = public_path(ltrim($imageName, '/'));
                } else {
                    $imagePath = public_path("uploads/{$imageFolder}/{$imageName}");
                }
                if (File::exists($imagePath) && ! File::isDirectory($imagePath)) {
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
