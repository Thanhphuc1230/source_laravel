<?php

namespace App\Listeners\Gallery;

use App\Events\Gallery\GalleryChanged;
use Illuminate\Support\Facades\Log;

class ClearGalleryCache
{
    public function handle(GalleryChanged $event): void
    {
        // With Cachable trait, cache is automatically cleared on model save/update/delete
        // No need to manually clear cache
    }
}