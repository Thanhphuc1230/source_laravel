<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use UniSharp\LaravelFilemanager\Controllers\UploadController;

class LfmUploadController extends UploadController
{
    /**
     * Override upload method to automatically convert image uploads to WebP
     * with 80% quality compression, 1920px max dimension, 1024M memory limit,
     * and reset Laravel 10's convertedFiles request cache.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        @ini_set('memory_limit', '1024M');

        $req = request();
        $uploadedFiles = $req->file('upload');

        if ($uploadedFiles) {
            $isArray = is_array($uploadedFiles);
            $filesList = $isArray ? $uploadedFiles : [$uploadedFiles];
            $convertedFiles = [];

            foreach ($filesList as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $mime = $file->getMimeType();
                    $extension = strtolower($file->getClientOriginalExtension() ?: ($file->guessExtension() ?: 'jpg'));
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                    if (in_array($extension, $imageExtensions) || str_starts_with($mime, 'image/')) {
                        try {
                            $convertedFile = $this->convertToWebpUploadedFile($file);
                            $convertedFiles[] = $convertedFile ?: $file;
                        } catch (\Throwable $e) {
                            // Fallback to original file if conversion fails
                            $convertedFiles[] = $file;
                        }
                    } else {
                        $convertedFiles[] = $file;
                    }
                } else {
                    $convertedFiles[] = $file;
                }
            }

            $req->files->set('upload', $isArray ? $convertedFiles : $convertedFiles[0]);

            // Reset cache convertedFiles in Laravel 10 Request
            try {
                $refProp = new \ReflectionProperty($req, 'convertedFiles');
                $refProp->setAccessible(true);
                $refProp->setValue($req, null);
            } catch (\Throwable $e) {
                // Ignore if reflection property does not exist or fails
            }
        }

        return parent::upload();
    }

    /**
     * Convert an UploadedFile to a WebP UploadedFile stored temporarily
     */
    protected function convertToWebpUploadedFile(UploadedFile $file): ?UploadedFile
    {
        $sourcePath = $file->getPathname();
        if (empty($sourcePath) || ! file_exists($sourcePath)) {
            return null;
        }

        $image = Image::make($sourcePath);
        if (! $image) {
            return null;
        }

        // Resize if width or height > 1920
        if ($image->width() > 1920 || $image->height() > 1920) {
            $image->resize(1920, 1920, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $tempPath = sys_get_temp_dir() . '/' . Str::slug($filename) . '_' . time() . '_' . Str::random(6) . '.webp';

        // Encode to WebP with 80% quality
        $image->encode('webp', 80)->save($tempPath);
        $image->destroy();

        if (! file_exists($tempPath)) {
            return null;
        }

        return new UploadedFile(
            $tempPath,
            Str::slug($filename) . '.webp',
            'image/webp',
            null,
            true // test mode to bypass is_uploaded_file check for local temp files
        );
    }
}
