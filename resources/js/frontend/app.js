import '../css/app.css';

// Global event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Header dropdown menu functionality
    initializeDropdownMenus();

    // Color options functionality for product cards
    initializeProductColorOptions();
});

/**
 * Initialize dropdown menus for desktop and mobile
 */
function initializeDropdownMenus() {
    const isMobile = () => window.innerWidth < 768;

    // Handle mobile dropdown menus
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        if (item.querySelector('.dropdown-menu')) {
            const link = item.querySelector('.nav-link');
            if (link) {
                link.addEventListener('click', (e) => {
                    if (isMobile()) {
                        e.preventDefault();

                        // Close all other dropdowns
                        navItems.forEach(otherItem => {
                            if (otherItem !== item) {
                                const otherDropdown = otherItem.querySelector('.dropdown-menu');
                                if (otherDropdown) {
                                    otherDropdown.style.opacity = '0';
                                    otherDropdown.style.visibility = 'hidden';
                                    otherDropdown.style.transform = 'translateY(10px)';
                                }
                            }
                        });

                        // Toggle current dropdown
                        const dropdown = item.querySelector('.dropdown-menu');
                        if (dropdown) {
                            const isVisible = dropdown.style.visibility === 'visible';
                            dropdown.style.opacity = isVisible ? '0' : '1';
                            dropdown.style.visibility = isVisible ? 'hidden' : 'visible';
                            dropdown.style.transform = isVisible ? 'translateY(10px)' : 'translateY(0)';
                        }
                    }
                });
            }
        }
    });

    // Handle window resize to reset mobile dropdown styling
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!isMobile()) {
                // Reset dropdowns on desktop
                document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                    dropdown.style.opacity = '';
                    dropdown.style.visibility = '';
                    dropdown.style.transform = '';
                });
            }
        }, 250);
    });
}

/**
 * Initialize color selection functionality on product cards
 */
function initializeProductColorOptions() {
    const colorOptions = document.querySelectorAll('.product-card .color-option');
    colorOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            e.preventDefault();

            // Find parent product card
            const productCard = option.closest('.product-card');
            if (!productCard) return;

            // Remove active class from all options in this product card
            const siblings = productCard.querySelectorAll('.color-option');
            siblings.forEach(sib => sib.classList.remove('active'));

            // Add active class to clicked option
            option.classList.add('active');

            // You would typically update the product image here
            const productTitle = productCard.querySelector('.product-title');
            if (productTitle) {
                const colorName = option.getAttribute('data-color') || 'Selected color';
                console.log(`${productTitle.textContent} - ${colorName} selected`);
            }
        });
    });
}

/**
 * Initialize search form
 */
const searchForm = document.querySelector('.search-form');
if (searchForm) {
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const searchInput = document.querySelector('#search-input');
        if (searchInput && searchInput.value.trim()) {
            // In a real app, you would submit to a proper search endpoint
            window.location.href = `/search?q=${encodeURIComponent(searchInput.value.trim())}`;
        }
    });
}

/**
 * Initialize newsletter form
 */
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        // Allow the form to submit normally since it will be handled by Laravel
        // But add some client-side validation
        const emailInput = document.querySelector('#newsletter-email');
        if (emailInput && !emailInput.value.trim()) {
            e.preventDefault();
            alert('Please enter your email address');
        }
    });
}

/**
 * Initialize newsletter mini form in footer
 */
const newsletterMiniForm = document.querySelector('.newsletter-mini-form');
if (newsletterMiniForm) {
    newsletterMiniForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const emailInput = newsletterMiniForm.querySelector('input[type="email"]');
        if (emailInput && emailInput.value.trim()) {
            // This would be replaced with an AJAX call in a real app
            alert(`Thank you for subscribing with email: ${emailInput.value}`);
            emailInput.value = '';
        }
    });
}
