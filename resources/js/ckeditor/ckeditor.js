import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// Xóa dòng import CSS nếu bạn chưa tạo file này
 import './css/ckeditor.css'; 

// Khởi tạo CKEditor khi trang đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    // Tìm các element theo ID cụ thể trong form
    const editorElements = [
        document.getElementById('my-editor'),
        document.getElementById('my-editor-en')
    ];
    
    editorElements.forEach(element => {
        if (element) {
            ClassicEditor
                .create(element, {
                    // Cấu hình toolbar
                    toolbar: [
                        'heading', '|', 
                        'bold', 'italic', 'link', '|',
                        'bulletedList', 'numberedList', '|',
                        'insertTable', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                })
                .then(editor => {
                    console.log('CKEditor đã được khởi tạo:', editor);
                })
                .catch(error => {
                    console.error('CKEditor khởi tạo thất bại:', error);
                });
        }
    });
});
