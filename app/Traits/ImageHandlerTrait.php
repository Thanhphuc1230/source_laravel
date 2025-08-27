<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ImageHandlerTrait
{
    protected $defaultImageConfig = [
        'convertToWebp' => true,
        'quality' => 80,
        'mimeTypes' => ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'],
    ];

    /**
     * Save new image (for create/store operations)
     */
    protected function saveImage(Request $request, $folder = null, $field = 'image')
    {
        $folder = $folder ?? $this->imageFolder;

        if ($request->hasFile($field)) {
            return $this->imageService->saveImage($request, $folder, $field, $this->defaultImageConfig);
        }

        return null;
    }

    /**
     * Update existing image (for edit/update operations)
     */
    protected function updateImage(Request $request, $model, $folder = null, $field = 'image')
    {
        $folder = $folder ?? $this->imageFolder;

        return $this->imageService->updateImage($request, $model, $folder, $field, $this->defaultImageConfig);
    }

    /**
     * Handle multiple images for create/store operations
     */
    protected function saveMultipleImages(Request $request, $folder = null, $field = 'image_detail')
    {
        $folder = $folder ?? $this->imageFolder;

        if ($request->hasFile($field)) {
            return $this->imageService->handleDetailImages($request, $folder, $this->defaultImageConfig);
        }

        return null;
    }

    /**
     * Handle multiple images for edit/update operations
     */
    protected function updateMultipleImages(Request $request, $model, $folder = null, $field = 'image_detail')
    {
        $folder = $folder ?? $this->imageFolder;

        return $this->imageService->updateDetailImages($request, $model, $folder, $field, $this->defaultImageConfig);
    }

    /**
     * Helper method để loại bỏ kept_images khỏi request data
     * Sử dụng trong controllers để tránh lỗi "Column not found"
     */
    protected function cleanRequestData($request, $excludeFields = [])
    {
        $defaultExclude = ['_token', 'return_back', 'return_list', 'currentPage', 'kept_images'];
        $excludeFields = array_merge($defaultExclude, $excludeFields);

        return $request->except($excludeFields);
    }

    // ===== BACKWARD COMPATIBILITY METHODS =====

    /**
     * @deprecated Use saveImage() or updateImage() instead for better clarity
     */
    protected function handleSingleImage(Request $request, $model = null, $folder = null, $field = 'image')
    {
        if ($model) {
            return $this->updateImage($request, $model, $folder, $field);
        }

        return $this->saveImage($request, $folder, $field);
    }

    /**
     * @deprecated Use saveMultipleImages() or updateMultipleImages() instead for better clarity
     */
    protected function handleMultipleImages(Request $request, $model = null, $folder = null, $field = 'image_detail')
    {
        if ($model) {
            return $this->updateMultipleImages($request, $model, $folder, $field);
        }

        return $this->saveMultipleImages($request, $folder, $field);
    }
}
