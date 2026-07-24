<?php

/*
|--------------------------------------------------------------------------
| Documentation for this config :
|--------------------------------------------------------------------------
| online  => http://unisharp.github.io/laravel-filemanager/config
| offline => vendor/unisharp/laravel-filemanager/docs/config.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    'use_package_routes' => true,
    'use_absolute_path' => false,

    /*
    |--------------------------------------------------------------------------
    | Shared folder / Private folder
    |--------------------------------------------------------------------------
    |
    | If both options are set to false, then shared folder will be activated.
    |
     */

    'allow_private_folder' => false,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHandler class
    // And set 'user_field' to App\Handler\ConfigHandler::class
    // Ex: The private folder of user will be named as the user id.
    'private_folder_name' => '',

    'allow_shared_folder' => true,

    'shared_folder_name' => '',

    /*
    |--------------------------------------------------------------------------
    | Folder Names
    |--------------------------------------------------------------------------
     */

    'folder_categories' => [
        'file' => [
            'folder_name' => 'photos',
            'startup_view' => 'list',
            'max_size' => 50000,
            'valid_mime' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'text/plain',
                'audio/mpeg',
                'audio/mp3',
                'audio/wav',
                'audio/ogg',
            ],
        ],
        'image' => [
            'folder_name' => 'photos',
            'startup_view' => 'grid',
            'max_size' => 50000,
            'valid_mime' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ],
        ],
        'audio' => [
            'folder_name' => 'audio',
            'startup_view' => 'list',
            'max_size' => 50000,
            'valid_mime' => [
                'audio/mpeg',
                'audio/mp3',
                'audio/wav',
                'audio/ogg',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
     */

    'paginator' => [
        'perPage' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
     */

    'disk' => 'images',

    'rename_file' => false,

    'rename_duplicates' => true,

    'alphanumeric_filename' => false,

    'alphanumeric_directory' => false,

    'should_validate_size' => true,

    'should_validate_mime' => true,

    // behavior on files with identical name
    // setting it to true cause old file replace with new one
    // setting it to false show `error-file-exist` error and stop upload
    'over_write_on_duplicate' => false,

    // mimetypes of executables to prevent from uploading
    'disallowed_mimetypes' => ['text/x-php', 'text/html', 'text/plain'],

    // extensions of executables to prevent from uploading
    'disallowed_extensions' => ['php', 'html'],

    // Item Columns
    'item_columns' => ['name', 'url', 'time', 'icon', 'is_file', 'is_image', 'thumb_url'],

    /*
    |--------------------------------------------------------------------------
    | Sort Settings
    |--------------------------------------------------------------------------
    */
    'sort_type' => 'time',
    'sort_order' => 'desc',

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
     */

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => true,

    'thumb_folder_name' => 'thumbs',

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/webp',
    ],

    'thumb_img_width' => 200, // px

    'thumb_img_height' => 200, // px

    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
     */

    'file_type_array' => [
        'pdf' => 'Adobe Acrobat',
        'doc' => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls' => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'zip' => 'Archive',
        'gif' => 'GIF Image',
        'jpg' => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png' => 'PNG Image',
        'webp' => 'WebP Image',
        'ppt' => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
     */
    'php_ini_overrides' => [
        'memory_limit' => '256M',
    ],

    // Cấu hình thư mục gốc
    'base_directory' => 'uploads',

    'valid_file_mimetypes' => [
        // ... các mime types khác
        'audio/mpeg',
        'audio/mp3',
        'audio/wav',
        'audio/ogg',
    ],

    // Thêm đuôi file được phép
    'valid_extensions' => [
        // ... các extensions khác
        'mp3',
        'wav',
        'ogg',
    ],

    'allow_format' => 'jpeg,jpg,png,gif,webp,mp3,wav,ogg',
];
