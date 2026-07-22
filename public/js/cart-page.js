// Adjust input quantity by value (+1 or -1)
function adjustQty(stt, change) {
    const input = document.getElementById(`qty-input-${stt}`);
    if (!input) return;
    
    let newQty = parseInt(input.value) + change;
    if (newQty < 1) newQty = 1;
    if (newQty > 99) newQty = 99;
    
    input.value = newQty;
    updateCartAjax();
}

// Sends form parameters to updateCart via AJAX and updates subtotals and totals in real-time
function updateCartAjax() {
    const form = document.getElementById('cart-form');
    if (!form) return;

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Cập nhật giỏ hàng thất bại. Vui lòng thử lại.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update header badge count
            const badge = document.getElementById('cart-badge-count');
            if (badge) {
                badge.innerText = data.totalItems;
                if (data.totalItems > 0) {
                    badge.classList.remove('scale-0');
                    badge.classList.add('scale-100');
                } else {
                    badge.classList.remove('scale-100');
                    badge.classList.add('scale-0');
                }
            }

            // Update subtotals for each row based on updated quantites returned
            const cartList = Object.values(data.cart);
            cartList.forEach(item => {
                const subtotalSpan = document.getElementById(`subtotal-${item.stt}`);
                if (subtotalSpan) {
                    const formattedSubtotal = new Intl.NumberFormat('vi-VN').format(item.price * item.qty) + 'đ';
                    subtotalSpan.innerText = formattedSubtotal;
                }
            });

            // Update summary totals in real-time
            const formattedTotalPrice = new Intl.NumberFormat('vi-VN').format(data.totalPrice) + 'đ';
            
            const summarySubtotal = document.getElementById('summary-subtotal');
            if (summarySubtotal) summarySubtotal.innerText = formattedTotalPrice;

            const summaryTotal = document.getElementById('summary-total');
            if (summaryTotal) summaryTotal.innerText = formattedTotalPrice;

            showToast(data.message || 'Giỏ hàng đã được cập nhật.', 'success');
        } else {
            showToast(data.message || 'Không thể cập nhật giỏ hàng.', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        showToast(error.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng.', 'error');
    });
}
