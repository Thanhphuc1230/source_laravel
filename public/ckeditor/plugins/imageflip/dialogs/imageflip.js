(function () {
    'use strict';

    CKEDITOR.dialog.add('imageflip', function (editor) {
        function openLfmForField(dialog, fieldId) {
            var funcNum = CKEDITOR.tools.addFunction(function (fileUrl) {
                if (fileUrl) {
                    dialog.setValueOf('tab-basic', fieldId, fileUrl);
                }
            });

            var url = '/filemanager?type=Images&CKEditor=' + encodeURIComponent(editor.name) +
                      '&CKEditorFuncNum=' + funcNum + '&langCode=vi';

            var left = (screen.width - 950) / 2;
            var top = (screen.height - 650) / 2;
            window.open(url, 'FileManager', 'width=950,height=650,top=' + top + ',left=' + left + ',scrollbars=yes,status=no');
        }

        return {
            title: 'Cấu hình So sánh / Lật ảnh Trước - Sau',
            minWidth: 480,
            minHeight: 220,
            contents: [
                {
                    id: 'tab-basic',
                    label: 'Cấu hình ảnh',
                    elements: [
                        {
                            type: 'hbox',
                            widths: ['75%', '25%'],
                            children: [
                                {
                                    type: 'text',
                                    id: 'beforeSrc',
                                    label: 'Ảnh 1 (Trước - Before)',
                                    required: true,
                                    validate: CKEDITOR.dialog.validate.notEmpty('Vui lòng chọn hoặc nhập đường dẫn Ảnh 1 (Before)'),
                                    setup: function (widget) {
                                        this.setValue(widget.data.beforeSrc || '');
                                    },
                                    commit: function (widget) {
                                        widget.setData('beforeSrc', this.getValue());
                                    }
                                },
                                {
                                    type: 'button',
                                    id: 'browseBefore',
                                    label: 'Chọn ảnh 1',
                                    style: 'margin-top: 18px;',
                                    onClick: function () {
                                        openLfmForField(this.getDialog(), 'beforeSrc');
                                    }
                                }
                            ]
                        },
                        {
                            type: 'hbox',
                            widths: ['75%', '25%'],
                            children: [
                                {
                                    type: 'text',
                                    id: 'afterSrc',
                                    label: 'Ảnh 2 (Sau - After)',
                                    required: true,
                                    validate: CKEDITOR.dialog.validate.notEmpty('Vui lòng chọn hoặc nhập đường dẫn Ảnh 2 (After)'),
                                    setup: function (widget) {
                                        this.setValue(widget.data.afterSrc || '');
                                    },
                                    commit: function (widget) {
                                        widget.setData('afterSrc', this.getValue());
                                    }
                                },
                                {
                                    type: 'button',
                                    id: 'browseAfter',
                                    label: 'Chọn ảnh 2',
                                    style: 'margin-top: 18px;',
                                    onClick: function () {
                                        openLfmForField(this.getDialog(), 'afterSrc');
                                    }
                                }
                            ]
                        },
                        {
                            type: 'text',
                            id: 'btnText',
                            label: 'Nội dung nút lật ảnh',
                            'default': 'Bấm để lật ảnh',
                            setup: function (widget) {
                                this.setValue(widget.data.btnText || 'Bấm để lật ảnh');
                            },
                            commit: function (widget) {
                                widget.setData('btnText', this.getValue() || 'Bấm để lật ảnh');
                            }
                        }
                    ]
                }
            ]
        };
    });
})();
