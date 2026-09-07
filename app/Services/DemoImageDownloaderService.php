<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemoImageDownloaderService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Xóa sạch toàn bộ file/folder nằm trong public/uploads/demo/
     */
    public function cleanOldUploads(): bool
    {
        $demoPath = public_path('uploads/demo');

        if (File::exists($demoPath)) {
            File::cleanDirectory($demoPath);
        } else {
            File::makeDirectory($demoPath, 0755, true);
        }

        return true;
    }

    /**
     * Tải ảnh từ LoremFlickr / Picsum Photos, lưu tạm vào storage, convert sang WebP và lưu vào public/uploads/demo/
     *
     * @param  string  $keyword  Từ khóa tìm kiếm ảnh
     * @param  int  $width  Chiều rộng
     * @param  int  $height  Chiều cao
     * @return string|null Đường dẫn tương đối của file webp (vd: uploads/demo/xxx.webp) hoặc null nếu thất bại
     */
    public function fetchAndSaveImage(string $keyword, int $width = 800, int $height = 600): ?string
    {
        $tempDir = storage_path('app/temp');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $tempFileName = 'demo_' . Str::slug($keyword) . '_' . uniqid() . '.jpg';
        $tempFilePath = $tempDir . DIRECTORY_SEPARATOR . $tempFileName;

        $targetDir = public_path('uploads/demo');
        if (! File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $slugKeyword = Str::slug($keyword);
        $finalName = ($slugKeyword ?: 'image') . '_' . uniqid() . '.webp';
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $finalName;

        try {
            $encodedKeyword = urlencode($keyword);
            
            // Danh sách các nguồn tải ảnh demo theo thứ tự ưu tiên
            $sources = [
                "https://loremflickr.com/{$width}/{$height}/{$encodedKeyword}",
                "https://picsum.photos/{$width}/{$height}",
            ];

            $imageBytes = null;

            foreach ($sources as $url) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    ])->withOptions([
                        'verify' => false,
                        'allow_redirects' => true,
                    ])->timeout(15)->get($url);

                    if ($response->successful() && strlen($response->body()) > 0) {
                        $imageBytes = $response->body();
                        break;
                    }
                } catch (Exception $subEx) {
                    Log::warning("Thử tải ảnh từ {$url} thất bại: " . $subEx->getMessage());
                    continue;
                }
            }

            if (! $imageBytes) {
                Log::warning("Không thể tải ảnh demo cho từ khóa [{$keyword}] từ bất kỳ nguồn nào.");
                return null;
            }

            // Lưu nội dung tải về vào file tạm
            File::put($tempFilePath, $imageBytes);

            if (! File::exists($tempFilePath) || filesize($tempFilePath) === 0) {
                Log::warning("File ảnh tạm không hợp lệ: {$tempFilePath}");
                return null;
            }

            // Gọi ImageService để convert sang WebP
            $savedFileName = $this->imageService->convertToWebp($tempFilePath, $targetPath, 80);

            if (! $savedFileName) {
                Log::warning("Không thể chuyển đổi WebP cho ảnh demo: {$tempFilePath}");
                return null;
            }

            return "uploads/demo/{$savedFileName}";
        } catch (Exception $e) {
            Log::error("Lỗi khi tải hoặc convert ảnh demo: " . $e->getMessage());
            return null;
        } finally {
            // Xóa file tạm
            if (File::exists($tempFilePath)) {
                File::delete($tempFilePath);
            }
        }
    }
}
