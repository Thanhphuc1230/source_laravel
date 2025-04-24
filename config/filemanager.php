<?php
return [
    'base_route'      => 'admin/filemanager',
    'middleware' => ['auth'],
    'allow_format'    => 'jpeg,jpg,png,gif,webp',
    'max_size'        => 500,
    'max_image_width' => 1024,
    'image_quality'   => 80,

    'dir' => [
        'images' => [
            'driver' => 'local',
            'root' => public_path('images/upload'),
            'path' => '/',
            'shared' => true,
        ],
    ],
    'user_folder' => false, 
];