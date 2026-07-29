(function () {
    'use strict';

    CKEDITOR.plugins.add('imageflip', {
        requires: 'widget,dialog',
        icons: 'imageflip',
        init: function (editor) {
            CKEDITOR.dialog.add('imageflip', this.path + 'dialogs/imageflip.js');

            editor.widgets.add('imageflip', {
                button: 'Chèn Ảnh Trước/Sau (Flip 3D)',
                dialog: 'imageflip',

                template:
                    '<figure class="image-flip-container" data-before-src="" data-after-src="" data-state="before">' +
                        '<div class="image-flip-wrapper">' +
                            '<img src="" class="image-flip-img" alt="Before/After Image" />' +
                            '<button type="button" class="image-flip-btn">' +
                                '<span class="image-flip-btn-text">Bấm để lật ảnh</span>' +
                                '<svg class="image-flip-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                                    '<path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>' +
                                    '<path d="M3 3v5h5"></path>' +
                                    '<path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path>' +
                                    '<path d="M16 16h5v5"></path>' +
                                '</svg>' +
                            '</button>' +
                        '</div>' +
                        '<figcaption class="image-flip-caption">Ghi chú chú thích ảnh ở đây</figcaption>' +
                    '</figure>',

                editables: {
                    caption: {
                        selector: '.image-flip-caption',
                        allowedContent: 'text'
                    }
                },

                allowedContent:
                    'figure(!image-flip-container)[data-before-src,data-after-src,data-state]; ' +
                    'div(!image-flip-wrapper); ' +
                    'img(!image-flip-img)[src,alt]; ' +
                    'button(!image-flip-btn)[type]; ' +
                    'span(!image-flip-btn-text); ' +
                    'svg(!image-flip-icon)[width,height,viewBox,fill,stroke,stroke-width,stroke-linecap,stroke-linejoin]; ' +
                    'path[d]; ' +
                    'figcaption(!image-flip-caption)',

                requiredContent: 'figure(image-flip-container)',

                upcast: function (element) {
                    return element.name === 'figure' && element.hasClass('image-flip-container');
                },

                init: function () {
                    var beforeSrc = this.element.getAttribute('data-before-src') || '';
                    var afterSrc = this.element.getAttribute('data-after-src') || '';
                    var btnTextElem = this.element.findOne('.image-flip-btn-text');
                    var btnText = btnTextElem ? btnTextElem.getText() : 'Bấm để lật ảnh';

                    var img = this.element.findOne('img.image-flip-img');
                    if (img && !beforeSrc) {
                        beforeSrc = img.getAttribute('src');
                    }

                    this.setData('beforeSrc', beforeSrc);
                    this.setData('afterSrc', afterSrc);
                    this.setData('btnText', btnText);
                },

                data: function () {
                    if (this.data.beforeSrc) {
                        this.element.setAttribute('data-before-src', this.data.beforeSrc);
                        var img = this.element.findOne('img.image-flip-img');
                        if (img) {
                            img.setAttribute('src', this.data.beforeSrc);
                        }
                    }

                    if (this.data.afterSrc) {
                        this.element.setAttribute('data-after-src', this.data.afterSrc);
                    }

                    this.element.setAttribute('data-state', 'before');

                    if (this.data.btnText) {
                        var btnTextElem = this.element.findOne('.image-flip-btn-text');
                        if (btnTextElem) {
                            btnTextElem.setText(this.data.btnText);
                        }
                    }
                }
            });
        }
    });
})();
