<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * Lưu hình ảnh từ request vào thư mục chỉ định
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $imageFolder  Tên thư mục lưu ảnh
     * @param  string  $fieldName  Tên field chứa ảnh trong request
     * @param  array  $options  Các tùy chọn thêm
     *                          - mimeTypes: array - Các định dạng cho phép
     *                          - convertToWebp: bool - Có chuyển sang WebP không
     *                          - quality: int - Chất lượng ảnh WebP (1-100)
     * @return string|null Tên file ảnh đã lưu hoặc null nếu không có ảnh
     *
     * @throws \Exception
     */
    public function saveImage($request, string $imageFolder, string $fieldName, array $options = [])
    {
        if (! $request->hasFile($fieldName)) {
            return null;
        }
        try {
            $file = $request->file($fieldName);

            // Kiểm tra mime type nếu cần
            if (isset($options['mimeTypes']) && ! in_array($file->getMimeType(), $options['mimeTypes'])) {
                throw new Exception('File không đúng định dạng cho phép');
            }

            // Tạo tên file duy nhất
            $fileName = $this->generateFileName($file, $options);

            // Đảm bảo thư mục tồn tại
            $this->ensureDirectoryExists($imageFolder);

            // Xử lý chuyển đổi WebP nếu được yêu cầu
            if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                $fileName = $this->handleWebpConversion($file, $imageFolder, $fileName, $options);
            } else {
                // Lưu file gốc nếu không chuyển WebP
                $file->move(public_path("images/{$imageFolder}"), $fileName);
            }

            return "images/{$imageFolder}/{$fileName}";
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Cập nhật hình ảnh, xóa ảnh cũ nếu có
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  object  $model  Model chứa ảnh hiện tại
     * @param  string  $imageFolder  Tên thư mục lưu ảnh
     * @param  string  $fieldName  Tên field chứa ảnh trong request và model
     * @param  array  $options  Các tùy chọn thêm
     * @return string Tên file ảnh mới hoặc file ảnh hiện tại
     *
     * @throws \Exception
     */
    public function updateImage($request, $model, string $imageFolder, string $fieldName, array $options = [])
    {
        if (! $request->hasFile($fieldName)) {
            return method_exists($model, 'getRawOriginal') ? $model->getRawOriginal($fieldName) : $model->$fieldName;
        }

        try {
            // Xóa file cũ nếu có
            $this->deleteImage($model->$fieldName, $imageFolder);

            // Lưu file mới
            return $this->saveImage($request, $imageFolder, $fieldName, $options);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Xóa một hình ảnh
     *
     * @param  string|null  $fileName  Tên file cần xóa
     * @param  string  $imageFolder  Thư mục chứa file
     */
    public function deleteImage($fileName, string $imageFolder): bool
    {
        if (! $fileName) {
            return false;
        }

        $filePath = $this->getPhysicalPath($fileName, $imageFolder);

        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * Lấy đường dẫn vật lý trên server từ tên file, đường dẫn tương đối hoặc URL tuyệt đối
     */
    private function getPhysicalPath($fileName, string $imageFolder): string
    {
        if (filter_var($fileName, FILTER_VALIDATE_URL)) {
            $path = parse_url($fileName, PHP_URL_PATH);
            $search = "images/";
            $pos = strpos($path, $search);
            if ($pos !== false) {
                $relativePath = substr($path, $pos);
                return public_path($relativePath);
            }
            return public_path(ltrim($path, '/'));
        }

        if (str_starts_with($fileName, 'images/')) {
            return public_path($fileName);
        }

        return public_path("images/{$imageFolder}/{$fileName}");
    }

    /**
     * Đảm bảo thư mục tồn tại, tạo mới nếu chưa có
     */
    private function ensureDirectoryExists(string $imageFolder): void
    {
        $path = public_path("images/{$imageFolder}");

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    /**
     * Tạo tên file duy nhất
     */
    private function generateFileName(UploadedFile $file, array $options = []): string
    {
        $prefix = $options['prefix'] ?? '';
        $useOriginalName = $options['useOriginalName'] ?? true;

        if ($useOriginalName) {
            $fileName = time().'-'.$file->getClientOriginalName();
        } else {
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'-'.Str::random(10).'.'.$extension;
        }

        return $prefix ? $prefix.'-'.$fileName : $fileName;
    }

    /**
     * Chuyển đổi ảnh sang định dạng WebP với queue processing
     *
     * @param  string  $sourcePath  Đường dẫn file nguồn
     * @param  string  $targetPath  Đường dẫn file đích (webp)
     * @param  int  $quality  Chất lượng ảnh (1-100)
     */
    public function convertToWebp(string $sourcePath, string $targetPath, int $quality = 80): bool
    {
        try {
            // Kiểm tra file nguồn
            if (! file_exists($sourcePath)) {
                throw new Exception("File nguồn không tồn tại: {$sourcePath}");
            }

            // Kiểm tra file size - skip nếu quá lớn
            $fileSize = filesize($sourcePath);
            if ($fileSize > 10 * 1024 * 1024) { // 10MB
                return $this->fallbackToOriginal($sourcePath, $targetPath);
            }

            // Kiểm tra memory available
            $memoryLimit = $this->getMemoryLimit();
            $estimatedMemory = $fileSize * 4; // Rough estimate

            if ($estimatedMemory > $memoryLimit * 0.8) {
                return $this->fallbackToOriginal($sourcePath, $targetPath);
            }

            // Kiểm tra và tạo thư mục đích
            $targetDir = dirname($targetPath);
            if (! File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // Kiểm tra quyền ghi
            if (! is_writable($targetDir)) {
                throw new Exception("Không có quyền ghi vào thư mục: {$targetDir}");
            }

            // Tải và xử lý ảnh với memory optimization
            $image = Image::make($sourcePath);

            // Kiểm tra xem ảnh có được tải thành công không
            if (! $image) {
                throw new Exception('Không thể tải ảnh từ nguồn');
            }

            // Resize nếu ảnh quá lớn
            if ($image->width() > 2048 || $image->height() > 2048) {
                $image->resize(2048, 2048, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Encode sang WebP với quality optimization
            $result = $image->encode('webp', $quality);

            // Lưu file
            $result->save($targetPath);

            // Destroy image object để free memory
            $image->destroy();

            // Kiểm tra file đã được tạo
            if (! file_exists($targetPath)) {
                throw new Exception('File WebP không được tạo thành công');
            }

            // WebP conversion successful - no logging needed for production

            return true;

        } catch (Exception $e) {
            // WebP conversion failed - fallback to original format
            return $this->fallbackToOriginal($sourcePath, $targetPath);
        }
    }

    /**
     * Fallback to original image format
     */
    private function fallbackToOriginal(string $sourcePath, string $targetPath): bool
    {
        try {
            $originalExt = pathinfo($sourcePath, PATHINFO_EXTENSION);
            $fallbackPath = str_replace('.webp', '.'.$originalExt, $targetPath);

            if (copy($sourcePath, $fallbackPath)) {
                return true;
            }
        } catch (Exception $copyError) {
            // Fallback copy failed - return false
        }

        return false;
    }

    /**
     * Get PHP memory limit in bytes
     */
    private function getMemoryLimit(): int
    {
        $memoryLimit = ini_get('memory_limit');

        if ($memoryLimit == -1) {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) $memoryLimit;

        switch ($unit) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Xử lý chuyển đổi và lưu ảnh dạng WebP
     *
     * @param  UploadedFile  $file  File ảnh gốc
     * @param  string  $imageFolder  Thư mục lưu ảnh
     * @param  string  $fileName  Tên file (sẽ được đổi đuôi thành .webp)
     * @param  array  $options  Tùy chọn (quality)
     * @return string Tên file WebP đã lưu
     *
     * @throws Exception
     */
    private function handleWebpConversion(UploadedFile $file, string $imageFolder, string $fileName, array $options = []): string
    {
        // Đảm bảo thư mục tồn tại
        $this->ensureDirectoryExists($imageFolder);

        $webpFileName = pathinfo($fileName, PATHINFO_FILENAME).'.webp';
        $targetPath = public_path("images/{$imageFolder}/{$webpFileName}");
        $quality = $options['quality'] ?? 80;

        if (! $this->convertToWebp($file->getPathname(), $targetPath, $quality)) {
            throw new Exception('Không thể chuyển đổi ảnh sang WebP');
        }

        return $webpFileName;
    }

    // xử lý hình ảnh chi tiết
    public function handleDetailImages($request, $imageFolder, array $options = [])
    {
        $files = [];
        if ($request->hasFile('image_detail')) {
            foreach ($request->file('image_detail') as $file) {
                try {
                    // Tạo tên file duy nhất
                    $fileName = $this->generateFileName($file, $options);

                    // Đảm bảo thư mục tồn tại
                    $this->ensureDirectoryExists($imageFolder);

                    // Xử lý chuyển đổi WebP nếu được yêu cầu
                    if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                        $fileName = $this->handleWebpConversion($file, $imageFolder, $fileName, $options);
                    } else {
                        $file->move(public_path("images/{$imageFolder}"), $fileName);
                    }

                    $files[] = $fileName;
                } catch (Exception $e) {
                    continue;
                }
            }
        }

        return json_encode($files);
    }

    /**
     * Xử lý hình ảnh chi tiết lúc update với logic xóa và thêm mới
     */
    public function updateDetailImages($request, $current, $imageFolder, $fieldName = 'image_detail', array $options = [])
    {
        // Lấy danh sách hình ảnh hiện tại từ model
        $existingImages = json_decode($current->image_detail, true) ?: [];

        // Lấy danh sách hình ảnh được giữ lại từ request (nếu có)
        $keptImages = $request->input('kept_images', '[]');

        // Chuyển đổi string JSON thành array
        if (is_string($keptImages)) {
            $keptImages = json_decode($keptImages, true) ?: [];
        }

        // Lọc ra những hình ảnh được giữ lại
        $filteredExistingImages = [];
        if (! empty($keptImages) && is_array($keptImages)) {
            foreach ($keptImages as $keptImage) {
                if (in_array($keptImage, $existingImages)) {
                    $filteredExistingImages[] = $keptImage;
                }
            }
        }

        // Xử lý hình ảnh mới được upload
        $newImages = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                try {
                    // Tạo tên file duy nhất
                    $fileName = $this->generateFileName($file, $options);

                    // Đảm bảo thư mục tồn tại
                    $this->ensureDirectoryExists($imageFolder);

                    // Xử lý chuyển đổi WebP nếu được yêu cầu
                    if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                        $fileName = $this->handleWebpConversion($file, $imageFolder, $fileName, $options);
                    } else {
                        $file->move(public_path("images/{$imageFolder}"), $fileName);
                    }

                    $newImages[] = $fileName;
                } catch (Exception $e) {
                    continue;
                }
            }
        }

        // Kết hợp hình ảnh được giữ lại và hình ảnh mới
        $allImages = array_merge($filteredExistingImages, $newImages);

        // Xóa những hình ảnh cũ không được giữ lại
        $this->cleanupUnusedImages($existingImages, $filteredExistingImages, $imageFolder);

        return json_encode($allImages);
    }

    /**
     * Xóa những hình ảnh không được sử dụng
     */
    private function cleanupUnusedImages($existingImages, $keptImages, $imageFolder)
    {
        $imagesToDelete = array_diff($existingImages, $keptImages);

        foreach ($imagesToDelete as $imageToDelete) {
            $this->deleteImage($imageToDelete, $imageFolder);
        }
    }

    /**
     * Lưu và chuyển đổi ảnh sang WebP
     */
    public function saveImageAsWebp($request, string $imageFolder, string $fieldName, array $options = [])
    {
        if (! $request->hasFile($fieldName)) {
            return null;
        }

        try {
            $file = $request->file($fieldName);

            // Kiểm tra mime type
            if (isset($options['mimeTypes']) && ! in_array($file->getMimeType(), $options['mimeTypes'])) {
                throw new Exception('File không đúng định dạng cho phép');
            }

            // Tạo tên file WebP
            $fileName = pathinfo($this->generateFileName($file, $options), PATHINFO_FILENAME).'.webp';

            // Đảm bảo thư mục tồn tại
            $this->ensureDirectoryExists($imageFolder);

            // Đường dẫn file đích
            $targetPath = public_path("images/{$imageFolder}/{$fileName}");

            // Chuyển đổi và lưu ảnh dạng WebP
            $quality = $options['quality'] ?? 80;
            $this->convertToWebp($file->getPathname(), $targetPath, $quality);

            return "images/{$imageFolder}/{$fileName}";
        } catch (Exception $e) {
            throw $e;
        }
    }
}
