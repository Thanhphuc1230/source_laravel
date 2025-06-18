<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ImageHandlerTrait
{
    protected $defaultImageConfig = [
        'convertToWebp' => true,
        'quality' => 80,
        'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
    ];

    protected function handleSingleImage(Request $request, $model = null, $folder = null, $field = 'image')
    {
        $folder = $folder ?? $this->imageFolder;
        
        if ($model) {
            return $this->imageService->updateImage($request, $model, $folder, $field, $this->defaultImageConfig);
        }

        if ($request->hasFile($field)) {
            return $this->imageService->saveImage($request, $folder, $field, $this->defaultImageConfig);
        }

        return null;
    }

    protected function handleMultipleImages(Request $request, $model = null, $folder = null, $field = 'image_detail') 
    {
        $folder = $folder ?? $this->imageFolder;

        if ($model) {
            return $this->imageService->updateDetailImages($request, $model, $folder, $field, $this->defaultImageConfig);
        }

        if ($request->hasFile($field)) {
            return $this->imageService->handleDetailImages($request, $folder, $this->defaultImageConfig);
        }

        return null;
    }
} 