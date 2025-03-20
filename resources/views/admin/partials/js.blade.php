<!-- JAVASCRIPT -->
@vite(['resources/js/admin/bootstrap/js/bootstrap.bundle.min.js'])
@vite(['resources/js/admin/simplebar/simplebar.min.js'])
@vite(['resources/js/admin/node-waves/waves.min.js'])
@vite(['resources/js/admin/feather-icons/feather.min.js'])
@vite(['resources/js/admin/list.js/list.min.js'])
@vite(['resources/js/admin/list.pagination.js/list.pagination.min.js'])
<!-- prismjs plugin -->
@vite(['resources/js/admin/prismjs/prism.js'])
@vite(['resources/js/admin/lord-icon-2.1.0.js'])


<!-- listjs init -->
@vite(['resources/js/admin/listjs.init.js'])

<!-- App js -->
@vite(['resources/js/admin/app.js'])
@vite(['resources/js/admin/plugins.js'])

<!-- Thêm hàm preview image -->
<script>
    // Hàm xử lý preview image khi chọn file
    function previewImage(inputId, containerId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const container = document.getElementById(containerId);
            if (!container) return;
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    container.innerHTML = `<img src="${event.target.result}" alt="Preview" style="max-width: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>

<!-- Stack cho script từ các trang con -->
@stack('scripts')

