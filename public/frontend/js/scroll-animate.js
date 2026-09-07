/**
 * Lightweight Scroll Animations using Intersection Observer API
 */
document.addEventListener('DOMContentLoaded', function () {
    const animElements = document.querySelectorAll('.scroll-anim');

    if (!('IntersectionObserver' in window) || animElements.length === 0) {
        animElements.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, observerInstance) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observerInstance.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
    });

    animElements.forEach(el => observer.observe(el));
});
