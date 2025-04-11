<script>
    function updateStt(input) {
        const uuid = input.getAttribute('data-uuid'); // Sử dụng 'input' thay vì 'this'
        const newValue = input.value;

        // Gửi yêu cầu AJAX
        fetch(`/admin/{{ $nameClass }}/update-stt/${uuid}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    stt: newValue
                })
            })
            .then(response => response.json())
            .then(data => {

            })
            .catch(error => console.error('Error:', error));
    }
</script>
