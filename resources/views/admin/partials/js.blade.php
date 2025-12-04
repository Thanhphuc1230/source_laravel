<!-- JAVASCRIPT -->
<script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ asset('admin/libs/node-waves/waves.min.js')}}"></script>
<script src="{{ asset('admin/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{ asset('admin/js/plugins.js')}}"></script>

<!-- prismjs plugin -->
<script src="{{ asset('admin/libs/prismjs/prism.js')}}"></script>

<!-- App js -->
<script src="{{ asset('admin/js/app.js')}}"></script>

<!-- Checkbox Handler -->
<script src="{{ asset('admin/js/checkbox-handler.js')}}"></script>

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

{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

