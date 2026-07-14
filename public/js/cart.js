// Shows beautiful Tailwind notifications dynamically
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `transform translate-x-10 opacity-0 transition-all duration-300 ease-out pointer-events-auto p-4 rounded-xl shadow-lg border flex items-start space-x-3 bg-white`;
    
    let iconHtml = '';
    let borderClass = '';
    
    if (type === 'success') {
        iconHtml = `<div class="bg-emerald-100 text-emerald-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-check"></i></div>`;
        borderClass = 'border-emerald-100';
    } else if (type === 'error') {
        iconHtml = `<div class="bg-red-100 text-red-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-exclamation"></i></div>`;
        borderClass = 'border-red-100';
    } else {
        iconHtml = `<div class="bg-blue-100 text-blue-800 p-1.5 rounded-full"><i class="fa-solid fa-circle-info"></i></div>`;
        borderClass = 'border-blue-100';
    }
    
    toast.classList.add(borderClass);
    toast.innerHTML = `
        ${iconHtml}
        <div class="flex-grow">
            <p class="text-xs font-bold text-emerald-950">${type === 'success' ? 'Thành công!' : 'Thông báo!'}</p>
            <p class="text-3xs text-gray-500 mt-0.5 leading-snug">${message}</p>
        </div>
        <button class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark text-2xs"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Slide in animation
    setTimeout(() => {
        toast.classList.remove('translate-x-10', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-10');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 4000);
}

// Global function to add product to cart via AJAX
function addToCartAjax(uuid, buttonElement = null) {
    let originalHtml = '';
    if (buttonElement) {
        // Keep backup and show spinner
        originalHtml = buttonElement.innerHTML;
        buttonElement.innerHTML = `<i class="fa-solid fa-circle-notch animate-spin text-sm"></i>`;
        buttonElement.disabled = true;
    }

    // Pull add to cart base URL dynamically
    const baseUrl = window.addToCartUrl || '/add-to-cart';
    const url = `${baseUrl}/${uuid}/1`;

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Mạng gặp sự cố. Vui lòng thử lại.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update cart badge count
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
                
                // Bounce badge effect
                badge.classList.add('animate-bounce');
                setTimeout(() => {
                    badge.classList.remove('animate-bounce');
                }, 1000);
            }
            
            showToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng thành công!', 'success');
            
            // Dispatch custom event to let other views know cart was updated
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
        } else {
            showToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng.', 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showToast(error.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
    })
    .finally(() => {
        if (buttonElement) {
            buttonElement.innerHTML = originalHtml;
            buttonElement.disabled = false;
        }
    });
}
