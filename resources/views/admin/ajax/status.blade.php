<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const uuid = this.getAttribute('data-uuid');
                const status = this.checked ? 1 : 0;
                const name = this.getAttribute('data-name');

                const url = `{{ url('admin/' . $nameClass . '/status') }}/${uuid}/${status}/${name}`;

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
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    toast(data.message, 'success');
                })
                .catch(error => {
                    console.error('Error:', error);
                    toast('Có lỗi xảy ra', 'error');
                });
            });
        });
    });
</script>
