/**
 * Industries Carousel
 * Initialize Swiper for MSC-style industries carousel
 */

document.addEventListener('DOMContentLoaded', function() {
    const industriesSwiper = document.querySelector('.industries-swiper');
    
    if (!industriesSwiper) {
        return;
    }

    // Initialize Swiper
    new Swiper('.industries-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        grabCursor: true,
        
        // Responsive breakpoints
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 40,
            },
            1280: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        },
        
        // Navigation arrows
        navigation: {
            nextEl: '.industries-next',
            prevEl: '.industries-prev',
        },
        
        // Pagination
        pagination: {
            el: '.industries-pagination',
            clickable: true,
            dynamicBullets: false,
        },
        
        // Auto height
        autoHeight: false,
        
        // Smooth scrolling
        speed: 600,
        
        // Autoplay
        autoplay: {
            delay: 5000,
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

