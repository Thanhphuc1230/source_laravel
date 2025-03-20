<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;

class ImageService
{
    /**
     * Lưu hình ảnh từ request vào thư mục chỉ định
     *
     * @param \Illuminate\Http\Request $request
     * @param string $imageFolder Tên thư mục lưu ảnh
     * @param string $fieldName Tên field chứa ảnh trong request
     * @param array $options Các tùy chọn thêm
     * @return string|null Tên file ảnh đã lưu hoặc null nếu không có ảnh
     * @throws \Exception
     */
    public function saveImage($request, string $imageFolder, string $fieldName, array $options = [])
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        try {
            $file = $request->file($fieldName);
            
            // Kiểm tra mime type nếu cần
            if (isset($options['mimeTypes']) && !in_array($file->getMimeType(), $options['mimeTypes'])) {
                throw new Exception("File không đúng định dạng cho phép");
            }
            
            // Tạo tên file duy nhất
            $fileName = $this->generateFileName($file, $options);
            
            // Đảm bảo thư mục tồn tại
            $this->ensureDirectoryExists($imageFolder);
            
            // Lưu file
            $file->move(public_path("images/{$imageFolder}"), $fileName);
            
            return $fileName;
        } catch (Exception $e) {
            // Log lỗi nếu cần
            \Log::error("Lỗi lưu file: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cập nhật hình ảnh, xóa ảnh cũ nếu có
     *
     * @param \Illuminate\Http\Request $request
     * @param object $model Model chứa ảnh hiện tại
     * @param string $imageFolder Tên thư mục lưu ảnh
     * @param string $fieldName Tên field chứa ảnh trong request và model
     * @param array $options Các tùy chọn thêm
     * @return string Tên file ảnh mới hoặc file ảnh hiện tại
     * @throws \Exception
     */
    public function updateImage($request, $model, string $imageFolder, string $fieldName, array $options = [])
    {
        if (!$request->hasFile($fieldName)) {
            return $model->$fieldName;
        }

        try {
            // Xóa file cũ nếu có
            $this->deleteImage($model->$fieldName, $imageFolder);
            
            // Lưu file mới
            return $this->saveImage($request, $imageFolder, $fieldName, $options);
        } catch (Exception $e) {
            \Log::error("Lỗi cập nhật file: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xóa một hình ảnh
     *
     * @param string|null $fileName Tên file cần xóa
     * @param string $imageFolder Thư mục chứa file
     * @return bool
     */
    public function deleteImage($fileName, string $imageFolder): bool
    {
        if (!$fileName) {
            return false;
        }

        $filePath = public_path("images/{$imageFolder}/{$fileName}");
        
        if (File::exists($filePath)) {
            return File::delete($filePath);
        }
        
        return false;
    }

    /**
     * Đảm bảo thư mục tồn tại, tạo mới nếu chưa có
     *
     * @param string $imageFolder
     * @return void
     */
    private function ensureDirectoryExists(string $imageFolder): void
    {
        $path = public_path("images/{$imageFolder}");
        
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Tạo tên file duy nhất
     *
     * @param UploadedFile $file
     * @param array $options
     * @return string
     */
    private function generateFileName(UploadedFile $file, array $options = []): string
    {
        $prefix = $options['prefix'] ?? '';
        $useOriginalName = $options['useOriginalName'] ?? true;
        
        if ($useOriginalName) {
            $fileName = time() . '-' . $file->getClientOriginalName();
        } else {
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '-' . Str::random(10) . '.' . $extension;
        }
        
        return $prefix ? $prefix . '-' . $fileName : $fileName;
    }
} 