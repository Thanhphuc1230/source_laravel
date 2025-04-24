<?php

if (!function_exists('lang')) {
    function lang($item, $property) {
        $locale = session()->get('locale');
        $localizedProperty = $property . '_' . $locale; // e.g., name_vn, intro_vn, content_vn

        // Check if $item is an object or an array and access the property accordingly
        if (is_object($item)) {
            return $item->$localizedProperty ?? $item->{$property . '_en'}; // Fallback to English if property doesn't exist
        } elseif (is_array($item)) {
            return $item[$localizedProperty] ?? $item[$property . '_en']; // Fallback to English if property doesn't exist
        }

        return null; // Return null if $item is neither an object nor an array
    }
}
