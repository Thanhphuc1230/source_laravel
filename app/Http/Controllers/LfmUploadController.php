<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use UniSharp\LaravelFilemanager\Controllers\UploadController as BaseUploadController;
use App\Services\WatermarkService;

class LfmUploadController extends BaseUploadController
{
    protected $watermarkService;

    public function __construct()
    {
        parent::__construct();
        $this->watermarkService = app(WatermarkService::class);
    }

    /**
     * Upload files with watermark application
     *
     * @param void
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function upload()
    {
        $uploaded_files = request()->file('upload');
        $error_bag = [];
        $new_filename = null;

        foreach (is_array($uploaded_files) ? $uploaded_files : [$uploaded_files] as $file) {
            try {
                $this->lfm->validateUploadedFile($file);

                $new_filename = $this->lfm->upload($file);

                // Apply watermark to uploaded image if it's an image
                if ($this->isImageFile($file)) {
                    $this->applyWatermarkToUploadedFile($new_filename);
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, $e->getMessage());
            } catch (\Error $e) {
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);
                array_push($error_bag, 'Some error occured during uploading.');
            }
        }

        // Check if this is a test request (from web form instead of AJAX)
        if (request()->has('_token') && !request()->ajax()) {
            if (count($error_bag) > 0) {
                return redirect()->back()->with('error', 'Upload failed: ' . implode(', ', $error_bag));
            } else {
                return redirect()->back()->with('success', 'File uploaded successfully with watermark! Filename: ' . $new_filename);
            }
        }

        if (is_array($uploaded_files)) {
            $response = count($error_bag) > 0 ? $error_bag : parent::$success_response;
        } else { // upload via ckeditor5 expects json responses
            if (is_null($new_filename)) {
                $response = [
                    'error' => [ 'message' =>  $error_bag[0] ]
                ];
            } else {
                $url = $this->lfm->setName($new_filename)->url();

                $response = [
                    'url' => $url,
                    'uploaded' => $url
                ];
            }
        }

        return response()->json($response);
    }

    /**
     * Check if uploaded file is an image
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return bool
     */
    private function isImageFile($file)
    {
        $mimeType = $file->getMimeType();
        return in_array($mimeType, [
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ]);
    }

    /**
     * Apply watermark to uploaded file
     *
     * @param string $filename
     * @return void
     */
    private function applyWatermarkToUploadedFile($filename)
    {
        try {
            // Get the full path to the uploaded file
            $filePath = $this->lfm->setName($filename)->path('absolute');

            // Apply watermark (overwrite the original file)
            $this->watermarkService->applyWatermark($filePath, $filePath);

            // Update thumbnail if it exists
            $this->updateThumbnail($filename);
        } catch (\Exception $e) {
            Log::error('Failed to apply watermark to uploaded file: ' . $e->getMessage(), [
                'filename' => $filename,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    /**
     * Update thumbnail after watermark application
     *
     * @param string $filename
     * @return void
     */
    private function updateThumbnail($filename)
    {
        try {
            // Regenerate thumbnail for the watermarked image
            $this->lfm->setName($filename)->generateThumbnail($filename);
        } catch (\Exception $e) {
            Log::warning('Failed to update thumbnail after watermark: ' . $e->getMessage());
        }
    }
}