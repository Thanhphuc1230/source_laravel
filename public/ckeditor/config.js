/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function(config) {
    // config base url
    config.baseHref = '/';
    config.forceRelativeUrls = true;
    config.allowedContent = true;
    config.pasteFilter = null;
    config.pasteFromWordPromptCleanup = false;
    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;
    config.forcePasteAsPlainText = false;
    
    // Set enter mode to BR to remove large spacing on enter
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_P;

    // Add custom fonts
    config.font_names = 'Chakra Petch;Arial;Comic Sans MS;Courier New;Times New Roman;Roboto;';

    // Include custom CSS files to load fonts and other styles
    config.contentsCss = ['customCKEStyles.css', 'contents.css']; 
    // Thêm plugin html5audio

};

// Global event to turn off Bold styling when pressing Enter to go to new line
CKEDITOR.on('instanceReady', function(ev) {
    ev.editor.on('key', function(evt) {
        if (evt.data.keyCode === 13) {
            var editor = evt.editor;
            setTimeout(function() {
                if (editor.getCommand('bold').state === CKEDITOR.TRISTATE_ON) {
                    editor.execCommand('bold');
                }
            }, 50);
        }
    });
});