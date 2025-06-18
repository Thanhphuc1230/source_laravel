<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugHandlerTrait
{
    /**
     * Generate unique slug
     */
    protected function generateUniqueSlug(string $title, string $modelClass, ?string $currentUuid = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $modelClass, $currentUuid)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists
     */
    private function slugExists(string $slug, string $modelClass, ?string $currentUuid = null): bool
    {
        $query = $modelClass::where('slug', $slug);
        
        if ($currentUuid) {
            $query->where('uuid', '!=', $currentUuid);
        }
        
        return $query->exists();
    }
} 