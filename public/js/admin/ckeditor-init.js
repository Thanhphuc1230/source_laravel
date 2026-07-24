/**
 * CKEditor 4 – Full Feature & Direct File Manager Initialization
 * ─────────────────────────────────────────────────────────────────────────
 * - Tự động phát hiện mọi textarea[data-ckeditor]
 * - Tích hợp Laravel File Manager (LFM)
 * - Click icon "Hình ảnh" trên Toolbar: MỞ TRỰC TIẾP File Manager (không qua dialog thừa)
 * - Nếu đang chọn ảnh có sẵn trong bài: Mở dialog thuộc tính để chỉnh kích thước/căn lề
 * - Nút "Danh sách a.b.c." (NumberedListAlpha) nằm ngay cạnh "Danh sách 1.2.3." (NumberedList)
 * ─────────────────────────────────────────────────────────────────────────
 */
(function () {
    'use strict';

    if (typeof CKEDITOR === 'undefined') {
        console.warn('[CKEditor4] Thư viện chưa tải. Kiểm tra đường dẫn /ckeditor/ckeditor.js');
        return;
    }

    // ─── Inject CSS cho Icon nút "Danh sách a.b.c." ─────────────────────────
    if (!document.getElementById('cke-custom-alpha-icon-style')) {
        var style = document.createElement('style');
        style.id = 'cke-custom-alpha-icon-style';
        style.innerHTML = `
            .cke_button__numberedlistalpha_icon {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 12px !important;
                font-weight: bold !important;
                font-family: Arial, sans-serif !important;
                color: #1e40af !important;
                background: none !important;
            }
            .cke_button__numberedlistalpha_icon::before {
                content: "a." !important;
            }
        `;
        document.head.appendChild(style);
    }

    // ─── Hàm mở trực tiếp cửa sổ LFM File Manager ─────────────────────────────
    function openDirectLfmImage(editor) {
        var funcNum = editor._lfmFuncNum;
        if (!funcNum) {
            funcNum = CKEDITOR.tools.addFunction(function (fileUrl) {
                if (fileUrl) {
                    editor.insertHtml('<img src="' + fileUrl + '" alt="" style="max-width:100%;height:auto;" />');
                }
            });
            editor._lfmFuncNum = funcNum;
        }
        var url = '/filemanager?type=image&CKEditor=' + encodeURIComponent(editor.name) +
                  '&CKEditorFuncNum=' + funcNum + '&langCode=vi';

        var left = (screen.width - 950) / 2;
        var top = (screen.height - 650) / 2;
        window.open(url, 'FileManager', 'width=950,height=650,top=' + top + ',left=' + left + ',scrollbars=yes,status=no');
    }

    // ─── Đăng ký Custom Plugin "numberedlistalpha" (Danh sách a.b.c.) ──────────
    if (!CKEDITOR.plugins.get('numberedlistalpha')) {
        CKEDITOR.plugins.add('numberedlistalpha', {
            init: function (editor) {
                editor.addCommand('toggleAlphaList', {
                    exec: function (editorInstance) {
                        var targetEditor = editorInstance || editor;
                        var sel = targetEditor.getSelection();
                        if (!sel) return;

                        var startElem = sel.getStartElement();
                        var ol = startElem ? startElem.getAscendant('ol', true) : null;

                        if (ol) {
                            var currentType = ol.getAttribute('type');
                            if (currentType === 'a') {
                                ol.removeAttribute('type');
                                ol.setStyle('list-style-type', '');
                            } else {
                                ol.setAttribute('type', 'a');
                                ol.setStyle('list-style-type', 'lower-alpha');
                            }
                        } else {
                            targetEditor.execCommand('numberedlist');
                            setTimeout(function () {
                                var s = targetEditor.getSelection();
                                if (s && s.getStartElement()) {
                                    var newOl = s.getStartElement().getAscendant('ol', true);
                                    if (newOl) {
                                        newOl.setAttribute('type', 'a');
                                        newOl.setStyle('list-style-type', 'lower-alpha');
                                    }
                                }
                            }, 50);
                        }
                    }
                });

                editor.ui.addButton('NumberedListAlpha', {
                    label: 'Danh sách a.b.c. (Chữ cái thường)',
                    command: 'toggleAlphaList',
                    toolbar: 'paragraph,10'
                });
            }
        });
    }

    // ─── CSRF & LFM ──────────────────────────────────────────────────────────
    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    var lfm = {
        imageBrowse:  '/filemanager?type=image',
        imageUpload:  '/filemanager/upload?type=image&_token=' + csrfToken,
        fileBrowse:   '/filemanager?type=file',
        fileUpload:   '/filemanager/upload?type=file&_token=' + csrfToken,
        audioBrowse:  '/filemanager?type=audio',
        audioUpload:  '/filemanager/upload?type=audio&_token=' + csrfToken
    };

    // ─── Editor Configuration ────────────────────────────────────────────────
    var editorConfig = {
        filebrowserImageBrowseUrl:  lfm.imageBrowse,
        filebrowserImageUploadUrl:  lfm.imageUpload,
        filebrowserBrowseUrl:       lfm.fileBrowse,
        filebrowserUploadUrl:       lfm.fileUpload,

        extraPlugins: 'image2,html5audio,youtube,copyformatting,tableselection,tabletools,numberedlistalpha',
        removePlugins: 'elementspath,resize,exportpdf,image',

        contentsCss: ['/ckeditor/customCKEStyles.css', '/ckeditor/contents.css'],
        allowedContent: true,
        extraAllowedContent: 'ol[type,style]; ul[type,style]; li[style]',

        pasteFilter: null,
        pasteFromWordPromptCleanup: false,
        pasteFromWordRemoveFontStyles: false,
        pasteFromWordRemoveStyles: false,
        forcePasteAsPlainText: false,

        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: 2,

        baseHref: '/',
        forceRelativeUrls: true,
        height: 380,

        table_defaultBorder: 1,
        table_defaultStyle: 'border-collapse:collapse; width:100%',

        linkShowTargetTab: true,
        linkShowAdvancedTab: true,

        youtube_responsive: true,
        youtube_related: false,
        youtube_privacy: true,
        youtube_controls: true,

        stylesSet: [
            { name: 'Danh sách a. b. c. (Chữ thường)', element: 'ol', attributes: { 'type': 'a', 'style': 'list-style-type: lower-alpha;' } },
            { name: 'Danh sách A. B. C. (Chữ hoa)',    element: 'ol', attributes: { 'type': 'A', 'style': 'list-style-type: upper-alpha;' } },
            { name: 'Danh sách i. ii. iii. (La Mã thường)', element: 'ol', attributes: { 'type': 'i', 'style': 'list-style-type: lower-roman;' } },
            { name: 'Danh sách I. II. III. (La Mã hoa)',    element: 'ol', attributes: { 'type': 'I', 'style': 'list-style-type: upper-roman;' } },
            { name: 'Danh sách 1. 2. 3. (Số chuẩn)',   element: 'ol', attributes: { 'type': '1', 'style': 'list-style-type: decimal;' } }
        ],

        toolbar: [
            {
                name: 'document',
                items: ['Source', '-', 'Preview', 'Print', '-', 'Templates']
            },
            {
                name: 'clipboard',
                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
            },
            {
                name: 'editing',
                items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
            },
            {
                name: 'tools',
                items: ['Maximize', 'ShowBlocks']
            },
            '/',
            {
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            },
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript',
                        '-', 'CopyFormatting', 'RemoveFormat']
            },
            {
                name: 'colors',
                items: ['TextColor', 'BGColor']
            },
            '/',
            {
                name: 'paragraph',
                items: ['NumberedList', 'NumberedListAlpha', 'BulletedList', '-',
                        'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-',
                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            {
                name: 'links',
                items: ['Link', 'Unlink', 'Anchor']
            },
            {
                name: 'insert',
                items: ['Image', 'Html5audio', 'Youtube', 'Table', '-',
                        'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
            }
        ]
    };

    // ─── Khởi tạo & Ghi đè sự kiện nút Hình ảnh ─────────────────────────────
    function initEditors() {
        var textareas = document.querySelectorAll('textarea[data-ckeditor]');
        if (textareas.length === 0) return;

        textareas.forEach(function (textarea) {
            var id = textarea.id;
            if (!id) return;

            if (CKEDITOR.instances[id]) {
                CKEDITOR.instances[id].destroy(true);
            }

            var editor = CKEDITOR.replace(id, editorConfig);

            // Ghi đè hành vi nút Hình ảnh: Mở trực tiếp LFM nếu không chọn ảnh sẵn có
            editor.on('instanceReady', function () {
                var imgCmd = editor.getCommand('image2') || editor.getCommand('image');
                if (imgCmd) {
                    imgCmd.exec = function () {
                        var sel = editor.getSelection();
                        var selectedElement = sel ? sel.getSelectedElement() : null;

                        // Nếu đang nhấp vào 1 ảnh sẵn có trong bài -> Mở popup chỉnh sửa thuộc tính ảnh
                        if (selectedElement && selectedElement.is && selectedElement.is('img')) {
                            if (editor.plugins.image2) {
                                editor.openDialog('image2');
                            } else {
                                editor.openDialog('image');
                            }
                        } else {
                            // Nếu chèn ảnh mới -> Mở TRỰC TIẾP cửa sổ File Manager
                            openDirectLfmImage(editor);
                        }
                    };
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditors);
    } else {
        initEditors();
    }

})();
