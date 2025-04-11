<script>
    // Cấu hình CKEditor với các tùy chọn
    var options = {
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token=',
        // Thêm cấu hình cho audio
        filebrowserAudioBrowseUrl: '/laravel-filemanager?type=Audio',
        filebrowserAudioUploadUrl: '/laravel-filemanager/upload?type=Audio&_token=',
        extraPlugins: 'image2,html5audio,justify,font,colorbutton,maximize,link,table,youtube',
        allowedContent: true,
         // Thêm hai dòng này để sử dụng URL tương đối
        baseHref: '/',
        forceRelativeUrls: true,
        toolbar: [{
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'TextColor',
                    'BGColor'
                ]
            },
            {
                name: 'clipboard',
                items: ['Cut', 'Copy', 'Paste']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft',
                    'JustifyCenter', 'JustifyRight', 'JustifyBlock'
                ]
            },
            {
                name: 'links',
                items: ['Link', 'Unlink', 'Anchor']
            },
            {
                name: 'insert',
                items: ['Image', 'Html5audio', 'Youtube', 'Table', 'Smiley']
            },
            {
                name: 'source',
                items: ['Source', 'Maximize']
            },
            '/',
            {
                name: 'styles',
                items: ['Format', 'Font', 'FontSize']
            },
            {
                name: 'colors',
                items: ['TextColor', 'BGColor']
            },
            {
                name: 'undo',
                items: ['Undo', 'Redo']
            }
        ],
        removeButtons: 'Anchor,Styles,Specialchar',
        height: 300,
        removePlugins: 'elementspath,resize',
        // Cấu hình cho bảng
        table_defaultBorder: 1,
        table_defaultStyle: 'border-collapse:collapse',
        // Cấu hình cho link
        linkShowTargetTab: true,
        linkShowAdvancedTab: true,
        // Thêm cấu hình cho YouTube
        youtube_responsive: true,
        youtube_related: false,
        youtube_privacy: true,
        youtube_controls: true
    };

    // Khởi tạo CKEditor cho nhiều editor
    const editors = ['intro-vn', 'intro-en', 'content-vn', 'content-en'];
    editors.forEach(editor => {
        CKEDITOR.replace(editor, options);
    });
</script>


