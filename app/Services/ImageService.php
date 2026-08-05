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
    /**
     * Tạo đường dẫn con dạng {module}/{YYYY}/{MM}
     */
    private function getSubPath(string $imageFolder): string
    {
        $year = date('Y');
        $month = date('m');
        return "{$imageFolder}/{$year}/{$month}";
    }

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

            // Tạo subpath dạng: module/YYYY/MM
            $subPath = $this->getSubPath($imageFolder);

            // Tạo tên file duy nhất (đã băm tên)
            $fileName = $this->generateFileName($file, $options);

            // Đảm bảo thư mục tồn tại
            $this->ensureDirectoryExists($subPath);

            // Xử lý chuyển đổi WebP nếu được yêu cầu
            if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                $fileName = $this->handleWebpConversion($file, $subPath, $fileName, $options);
            } else {
                // Lưu file gốc nếu không chuyển WebP
                $file->move(public_path("uploads/{$subPath}"), $fileName);
            }

            return "uploads/{$subPath}/{$fileName}";
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
            $search = "uploads/";
            $pos = strpos($path, $search);
            if ($pos !== false) {
                $relativePath = substr($path, $pos);
                return public_path($relativePath);
            }
            return public_path(ltrim($path, '/'));
        }

        if (str_starts_with($fileName, 'uploads/')) {
            return public_path($fileName);
        }

        return public_path("uploads/{$imageFolder}/{$fileName}");
    }

    /**
     * Đảm bảo thư mục tồn tại, tạo mới nếu chưa có
     */
    private function ensureDirectoryExists(string $imageFolder): void
    {
        $path = public_path("uploads/{$imageFolder}");

        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    private function generateFileName(UploadedFile $file, array $options = []): string
    {
        $prefix = $options['prefix'] ?? '';
        
        // Đoán extension thật dựa trên mime type, nếu không đoán được mới dùng getClientOriginalExtension()
        $extension = $file->guessExtension() ?: $file->getClientOriginalExtension();
        
        // Băm tên file để tránh trùng đè file và an toàn I/O
        $hashName = md5($file->getClientOriginalName() . time() . Str::random(10));
        $fileName = $hashName . '.' . $extension;

        return $prefix ? $prefix.'-'.$fileName : $fileName;
    }

    /**
     * Chuyển đổi ảnh sang định dạng WebP (hoặc fallback lưu file gốc)
     *
     * @param  UploadedFile|string  $file  Đối tượng file upload hoặc đường dẫn vật lý
     * @param  string  $targetPath  Đường dẫn file đích (webp)
     * @param  int  $quality  Chất lượng ảnh (1-100)
     * @return string|bool Tên file thực tế đã lưu (ví dụ: 'hash.webp' hoặc 'hash.jpg'), hoặc false nếu lỗi
     */
    public function convertToWebp(UploadedFile|string $file, string $targetPath, int $quality = 80): string|bool
    {
        // 1. Phân loại đối tượng file và lấy đường dẫn vật lý
        if ($file instanceof UploadedFile) {
            $sourcePath = $file->getPathname();
        } else {
            $sourcePath = (string) $file;
        }

        // 2. Kiểm tra file nguồn tồn tại
        if (empty($sourcePath) || ! file_exists($sourcePath)) {
            return false;
        }

        // 3. Tăng giới hạn bộ nhớ PHP lên 512M để xử lý ảnh độ phân giải siêu cao (40MP+)
        @ini_set('memory_limit', '512M');

        // 4. Kiểm tra kích thước file (cho phép tối đa 100MB)
        $fileSize = filesize($sourcePath);
        if ($fileSize > 100 * 1024 * 1024) { // 100MB
            return $this->fallbackToOriginal($file, $targetPath);
        }

        // 5. Đảm bảo thư mục đích tồn tại và có quyền ghi
        $targetDir = dirname($targetPath);
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        if (! is_writable($targetDir)) {
            return $this->fallbackToOriginal($file, $targetPath);
        }

        // 6. Xử lý convert WebP với Intervention Image
        $image = null;
        try {
            $image = Image::make($sourcePath);

            if (! $image) {
                return $this->fallbackToOriginal($file, $targetPath);
            }

            // Tự động resize nếu chiều rộng hoặc chiều cao > 2560px (giữ aspect ratio & upsize constraint)
            if ($image->width() > 2560 || $image->height() > 2560) {
                $image->resize(2560, 2560, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            // Encode sang WebP
            $result = $image->encode('webp', $quality);
            $result->save($targetPath);

            if (! file_exists($targetPath)) {
                return $this->fallbackToOriginal($file, $targetPath);
            }

            return pathinfo($targetPath, PATHINFO_BASENAME);
        } catch (Exception $e) {
            return $this->fallbackToOriginal($file, $targetPath);
        } finally {
            if ($image && method_exists($image, 'destroy')) {
                $image->destroy();
            }
        }
    }

    /**
     * Fallback lưu file gốc chính xác định dạng đuôi file khi WebP thất bại
     *
     * @param  UploadedFile|string  $file
     * @param  string  $targetPath
     * @return string|bool Tên file đã lưu thành công hoặc false
     */
    private function fallbackToOriginal(UploadedFile|string $file, string $targetPath): string|bool
    {
        try {
            if ($file instanceof UploadedFile) {
                $sourcePath = $file->getPathname();
                $originalExt = $file->getClientOriginalExtension() ?: ($file->guessExtension() ?: 'jpg');
            } else {
                $sourcePath = (string) $file;
                $originalExt = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
            }

            if (empty($sourcePath) || ! file_exists($sourcePath)) {
                return false;
            }

            $fallbackFileName = pathinfo($targetPath, PATHINFO_FILENAME) . '.' . strtolower($originalExt);
            $fallbackPath = dirname($targetPath) . '/' . $fallbackFileName;

            if ($file instanceof UploadedFile) {
                $file->move(dirname($targetPath), $fallbackFileName);
            } else {
                copy($sourcePath, $fallbackPath);
            }

            if (file_exists($fallbackPath)) {
                return $fallbackFileName;
            }
        } catch (Exception $copyError) {
            // Fallback copy failed
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
     * @param  string  $subPath  Thư mục con lưu ảnh
     * @param  string  $fileName  Tên file (sẽ được đổi đuôi thành .webp hoặc giữ nguyên nếu fallback)
     * @param  array  $options  Tùy chọn (quality)
     * @return string Tên file thực tế đã lưu
     *
     * @throws Exception
     */
    private function handleWebpConversion(UploadedFile $file, string $subPath, string $fileName, array $options = []): string
    {
        $this->ensureDirectoryExists($subPath);

        $webpFileName = pathinfo($fileName, PATHINFO_FILENAME).'.webp';
        $targetPath = public_path("uploads/{$subPath}/{$webpFileName}");
        $quality = $options['quality'] ?? 80;

        $savedFileName = $this->convertToWebp($file, $targetPath, $quality);

        if (! $savedFileName) {
            throw new Exception('Không thể chuyển đổi hoặc lưu ảnh');
        }

        return $savedFileName;
    }

    // xử lý hình ảnh chi tiết
    public function handleDetailImages($request, $imageFolder, array $options = [])
    {
        $files = [];
        if ($request->hasFile('image_detail')) {
            $subPath = $this->getSubPath($imageFolder);
            foreach ($request->file('image_detail') as $file) {
                try {
                    // Tạo tên file duy nhất
                    $fileName = $this->generateFileName($file, $options);

                    // Đảm bảo thư mục tồn tại
                    $this->ensureDirectoryExists($subPath);

                    // Xử lý chuyển đổi WebP nếu được yêu cầu
                    if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                        $fileName = $this->handleWebpConversion($file, $subPath, $fileName, $options);
                    } else {
                        $file->move(public_path("uploads/{$subPath}"), $fileName);
                    }

                    // Lưu đường dẫn tương đối bắt đầu bằng uploads/
                    $files[] = "uploads/{$subPath}/{$fileName}";
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
    public function updateDetailImages($request, $model, string $imageFolder, string $fieldName = 'image_detail', array $options = [])
    {
        $existingImages = json_decode($model->$fieldName, true) ?: [];

        // Lấy danh sách hình ảnh được giữ lại từ request
        $keptImages = $request->input('kept_images', []);

        // Filter chỉ giữ lại những hình ảnh thực sự tồn tại trong model cũ
        $filteredExistingImages = array_intersect($existingImages, $keptImages);

        $newImages = [];
        if ($request->hasFile('image_detail')) {
            $subPath = $this->getSubPath($imageFolder);
            foreach ($request->file('image_detail') as $file) {
                try {
                    // Tạo tên file duy nhất
                    $fileName = $this->generateFileName($file, $options);

                    // Đảm bảo thư mục tồn tại
                    $this->ensureDirectoryExists($subPath);

                    // Xử lý chuyển đổi WebP nếu được yêu cầu
                    if (isset($options['convertToWebp']) && $options['convertToWebp']) {
                        $fileName = $this->handleWebpConversion($file, $subPath, $fileName, $options);
                    } else {
                        $file->move(public_path("uploads/{$subPath}"), $fileName);
                    }

                    // Lưu đường dẫn tương đối bắt đầu bằng uploads/
                    $newImages[] = "uploads/{$subPath}/{$fileName}";
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

            $subPath = $this->getSubPath($imageFolder);
            $fileName = $this->generateFileName($file, $options);

            $savedFileName = $this->handleWebpConversion($file, $subPath, $fileName, $options);

            return "uploads/{$subPath}/{$savedFileName}";
        } catch (Exception $e) {
            throw $e;
        }
    }
}
