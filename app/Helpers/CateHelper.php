<?php

// Gọi chủ đề con của chủ đề con, nhớ phải có tên id của category ở biến cuối

if (! function_exists('renderCategoryOptions')) {
    function renderCategoryOptions($categories, $level = 0, $selectedParentId = null, $idAttribute = 'id_category_product')
    {
        foreach ($categories as $item) {
            $prefix = str_repeat('|---', $level);
            $categoryId = $item->{$idAttribute};  // Access the dynamic ID attribute
            
            $style = ($level == 0) ? ' style="font-weight: bold;"' : '';
            
            echo '<option value="'.$categoryId.'"'.(($selectedParentId == $categoryId) ? ' selected' : '').$style.'>'.$prefix.$item->name_vn.'</option>';

            if (! empty($item->children)) {
                renderCategoryOptions($item->children, $level + 1, $selectedParentId, $idAttribute);
            }
        }
    }
}