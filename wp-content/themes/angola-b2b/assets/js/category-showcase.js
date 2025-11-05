/**
 * Network Carousel (Category Showcase)
 * Initialize Swiper for MSC-style network carousel
 */

document.addEventListener('DOMContentLoaded', function() {
    const networkSwiper = document.querySelector('.network-swiper');
    
    if (!networkSwiper) {
        return;
    }

    // Initialize Swiper
    new Swiper('.network-swiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        effect: 'fade',
        fadeEffect: {
            crossFade: true,
        },
        
        // Pagination
        pagination: {
            el: '.network-pagination',
            clickable: true,
            dynamicBullets: false,
        },
        
        // Smooth scrolling
        speed: 1000,
        
        // Autoplay
        autoplay: {
            delay: 7000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },
        
        // Keyboard control
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
        
        // Accessibility
        a11y: {
            prevSlideMessage: 'Previous slide',
            nextSlideMessage: 'Next slide',
        },
    });
});

