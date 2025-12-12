<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo Toast instance từ SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        document.querySelectorAll('.status-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const uuid = this.getAttribute('data-uuid');
                const status = this.checked ? 1 : 0;
                const field = this.getAttribute('data-field');

                const url = `{{ route('admin.' . $nameClass . '.status', ['uuid' => ':uuid', 'status' => ':status', 'field' => ':field']) }}`.replace(':uuid', uuid).replace(':status', status).replace(':field', field);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                })
                .catch(error => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Có lỗi xảy ra: ' + error.message
                    });
                });
            });
        });
    });
</script>
