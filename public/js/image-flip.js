/**
 * Before / After Image Flip 3D Logic
 * 2-Stage rotation & src swapping
 */
(function () {
    'use strict';

    function initImageFlip() {
        document.addEventListener('click', function (e) {
            var wrapper = e.target.closest('.image-flip-wrapper');
            if (!wrapper) return;

            var container = wrapper.closest('.image-flip-container');
            if (!container) return;

            var img = wrapper.querySelector('.image-flip-img');
            if (!img) return;

            // Chống click nhiều lần khi đang chạy animation
            if (img.dataset.isFlipping === 'true') return;

            var beforeSrc = container.getAttribute('data-before-src');
            var afterSrc = container.getAttribute('data-after-src');
            var currentState = container.getAttribute('data-state') || 'before';

            if (!beforeSrc || !afterSrc) return;

            img.dataset.isFlipping = 'true';

            // Bước 1: Xoay nghiêng 90 độ
            img.style.transform = 'rotateY(90deg)';

            // Bước 2: Sau 250ms (giai đoạn giữa cú lật) hoán đổi src & xoay về 0 độ
            setTimeout(function () {
                if (currentState === 'before') {
                    img.src = afterSrc;
                    container.setAttribute('data-state', 'after');
                } else {
                    img.src = beforeSrc;
                    container.setAttribute('data-state', 'before');
                }

                // Trả ảnh về phẳng 0 độ
                img.style.transform = 'rotateY(0deg)';

                setTimeout(function () {
                    delete img.dataset.isFlipping;
                }, 250);
            }, 250);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initImageFlip);
    } else {
        initImageFlip();
    }
})();
