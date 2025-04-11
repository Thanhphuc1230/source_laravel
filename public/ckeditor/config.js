/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
    // config base url
    config.baseHref = '/';
    config.forceRelativeUrls = true;
    // Add custom fonts
    config.font_names = 'Chakra Petch;Arial;Comic Sans MS;Courier New;Times New Roman;Roboto;';

    // Include custom CSS files to load fonts and other styles
    config.contentsCss = ['customCKEStyles.css', 'contents.css']; 
    // Thêm plugin html5audio

};