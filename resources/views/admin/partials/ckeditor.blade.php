<script>
    // Cấu hình CKEditor với các tùy chọn
    var options = {
        filebrowserImageBrowseUrl: '/filemanager?type=Images',
        filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/filemanager?type=Files',
        filebrowserUploadUrl: '/filemanager/upload?type=Files&_token=',
        // Thêm cấu hình cho audio
        filebrowserAudioBrowseUrl: '/filemanager?type=Audio',
        filebrowserAudioUploadUrl: '/filemanager/upload?type=Audio&_token=',
        extraPlugins: 'image2,html5audio,justify,font,colorbutton,maximize,link,table,youtube,pastefromword',
        allowedContent: true,
        pasteFromWordPromptCleanup: false,
        pasteFromWordRemoveFontStyles: false,
        pasteFromWordRemoveStyles: false,
        pasteFilter: null,
        forcePasteAsPlainText: false,
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
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
        if (document.getElementById(editor)) {
            CKEDITOR.replace(editor, options);
        }
    });
</script>


