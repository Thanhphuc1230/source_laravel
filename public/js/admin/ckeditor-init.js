/**
 * CKEditor 5 v42 - Admin Panel Initialization
 * ─────────────────────────────────────────────────────────────────────────
 * Tự động khởi tạo CKEditor 5 cho mọi textarea[data-ckeditor] trên trang.
 * Tích hợp Laravel File Manager (LFM) qua nút trong toolbar.
 * Global: window.ckeditor5 (ckeditor5.umd.js v42.0.0)
 * ─────────────────────────────────────────────────────────────────────────
 */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.ckeditor5 === 'undefined') {
        console.warn('[CKEditor5] Thư viện chưa được tải. Kiểm tra /vendor/ckeditor5/ckeditor.js');
        return;
    }

    var ck = window.ckeditor5;

    /* ──────────────────────────────────────────────────────────────────────
       1. UPLOAD ADAPTER – kéo/thả/paste ảnh tự động lên LFM
       ────────────────────────────────────────────────────────────────────── */
    function LFMUploadAdapter(loader) { this.loader = loader; }

    LFMUploadAdapter.prototype.upload = function () {
        var self = this;
        return self.loader.file.then(function (file) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                var csrfMeta = document.querySelector('meta[name="csrf-token"]');

                xhr.open('POST', '/filemanager/upload?type=image', true);
                if (csrfMeta) xhr.setRequestHeader('x-csrf-token', csrfMeta.content);
                xhr.responseType = 'json';

                xhr.addEventListener('error', function () { reject('Upload thất bại: ' + file.name); });
                xhr.addEventListener('abort', function () { reject('Upload bị huỷ.'); });
                xhr.addEventListener('load', function () {
                    var r = xhr.response;
                    if (!r || r.error) {
                        reject(r && r.error ? r.error.message : 'Lỗi server khi upload ảnh');
                        return;
                    }
                    resolve({ default: r.url });
                });
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function (evt) {
                        if (evt.lengthComputable) {
                            self.loader.uploadTotal = evt.total;
                            self.loader.uploaded   = evt.loaded;
                        }
                    });
                }
                var fd = new FormData();
                fd.append('upload', file);
                xhr.send(fd);
            });
        });
    };

    LFMUploadAdapter.prototype.abort = function () { if (this.xhr) this.xhr.abort(); };

    function LFMUploadAdapterPlugin(editor) {
        if (editor.plugins.has('FileRepository')) {
            editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                return new LFMUploadAdapter(loader);
            };
        }
    }

    /* ──────────────────────────────────────────────────────────────────────
       2. LFM TOOLBAR BUTTON – nút "Open file manager" trong thanh công cụ
       ────────────────────────────────────────────────────────────────────── */
    // SVG icon folder/image
    var lfmIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M3 4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-5.586L9.707 3.293A1 1 0 0 0 9 3H3zm0 2h6l1.707 1.707A1 1 0 0 0 11.414 8H17v6H3V6z" fill-rule="evenodd"/></svg>';

    function createLFMPlugin(editorInstance) {
        return function LFMPlugin(editor) {
            editor.ui.componentFactory.add('lfmButton', function (locale) {
                var ButtonView = ck.ButtonView;
                if (!ButtonView) return null;

                var button = new ButtonView(locale);
                button.set({
                    label: 'Open file manager',
                    icon: lfmIcon,
                    tooltip: true,
                    withText: false
                });

                button.on('execute', function () {
                    var popup = window.open(
                        '/filemanager?type=image',
                        'FileManager',
                        'width=1000,height=650,scrollbars=yes,resizable=yes'
                    );

                    window.SetUrl = function (items) {
                        var fileUrl = typeof items === 'string' ? items : items[0].url;
                        editor.model.change(function (writer) {
                            var inserted = false;
                            // Thử chèn imageBlock trước
                            if (editor.model.schema.checkChild(editor.model.document.selection.anchor.parent, 'imageBlock')) {
                                var imgEl = writer.createElement('imageBlock', { src: fileUrl });
                                editor.model.insertContent(imgEl, editor.model.document.selection);
                                inserted = true;
                            }
                            // Fallback: chèn imageInline
                            if (!inserted && editor.model.schema.checkChild(editor.model.document.selection.anchor.parent, 'imageInline')) {
                                var imgInline = writer.createElement('imageInline', { src: fileUrl });
                                editor.model.insertContent(imgInline, editor.model.document.selection);
                                inserted = true;
                            }
                            // Fallback cuối: chèn link
                            if (!inserted) {
                                var linkText = writer.createText(fileUrl, { linkHref: fileUrl });
                                editor.model.insertContent(linkText, editor.model.document.selection);
                            }
                        });
                        if (popup && !popup.closed) popup.close();
                    };
                });

                return button;
            });
        };
    }

    /* ──────────────────────────────────────────────────────────────────────
       3. PLUGINS – tất cả plugins có sẵn trong CKEditor 5 UMD Free
       ────────────────────────────────────────────────────────────────────── */
    var pluginNames = [
        // Core
        'Essentials', 'Paragraph',
        // Text formatting
        'Heading', 'Bold', 'Italic', 'Underline', 'Strikethrough',
        'Subscript', 'Superscript', 'Code', 'RemoveFormat',
        // Font
        'Font', 'Highlight',
        // Alignment
        'Alignment',
        // Lists
        'List', 'ListProperties', 'TodoList',
        'Indent', 'IndentBlock',
        // Links
        'Link', 'AutoLink',
        // Images
        'Image', 'ImageToolbar', 'ImageCaption', 'ImageStyle',
        'ImageResize', 'ImageUpload', 'ImageInsertViaUrl',
        'FileRepository',
        // Media
        'MediaEmbed',
        // Tables
        'Table', 'TableToolbar', 'TableProperties', 'TableCellProperties', 'TableCaption',
        // Misc block
        'BlockQuote', 'HorizontalLine', 'PageBreak',
        // Code
        'CodeBlock',
        // HTML
        'HtmlEmbed', 'GeneralHtmlSupport', 'SourceEditing', 'ShowBlocks',
        // Find & Replace
        'FindAndReplace', 'SelectAll',
        // Special
        'SpecialCharacters', 'SpecialCharactersEssentials',
        'SpecialCharactersArrows', 'SpecialCharactersCurrency',
        'SpecialCharactersLatin', 'SpecialCharactersMathematical',
        'SpecialCharactersText',
        // MenuBar
        'MenuBar',
        // Auto features
        'AutoImage', 'Autoformat'
    ];

    var plugins = [];
    pluginNames.forEach(function (name) {
        if (ck[name]) plugins.push(ck[name]);
    });

    /* ──────────────────────────────────────────────────────────────────────
       4. KHỞI TẠO – detect tất cả textarea[data-ckeditor]
       ────────────────────────────────────────────────────────────────────── */
    var textareas = document.querySelectorAll('textarea[data-ckeditor]');
    if (textareas.length === 0) return;

    window.ckeditors = window.ckeditors || {};

    textareas.forEach(function (textarea) {
        var id = textarea.id;

        ck.ClassicEditor.create(textarea, {
            plugins: plugins,
            extraPlugins: [LFMUploadAdapterPlugin, createLFMPlugin()],
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'showBlocks', 'sourceEditing', 'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'bold', 'italic', 'underline', 'strikethrough',
                    'subscript', 'superscript', 'code', 'removeFormat', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'todoList', 'outdent', 'indent', '|',
                    'link', 'insertImage', 'lfmButton', 'mediaEmbed',
                    'insertTable', 'blockQuote', 'horizontalLine', 'pageBreak', '|',
                    'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters'
                ],
                shouldNotGroupWhenFull: false
            },
            // ─── Menu bar (File, Edit, View, Insert, Format, Help) ───────────
            menuBar: {
                isVisible: true
            },
            image: {
                toolbar: [
                    'imageStyle:block', 'imageStyle:side', 'imageStyle:inline', '|',
                    'toggleImageCaption', 'imageTextAlternative', '|',
                    'resizeImage:25', 'resizeImage:50', 'resizeImage:75', 'resizeImage:original'
                ],
                insert: { type: 'auto' }
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells',
                    'tableProperties', 'tableCellProperties', 'toggleTableCaption'
                ]
            },
            htmlSupport: {
                allow: [{ name: /.*/, attributes: true, classes: true, styles: true }]
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            placeholder: 'Nhập nội dung tại đây...',
            language: 'vi' // Nếu có ngôn ngữ
        })
        .then(function (editor) {
            window.ckeditors[id] = editor;
            console.log('[CKEditor5] Editor khởi tạo thành công: ' + id);
        })
        .catch(function (error) {
            console.error('[CKEditor5] Lỗi khởi tạo editor [' + id + ']:', error);
        });
    });
});
