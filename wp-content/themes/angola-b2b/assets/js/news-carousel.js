/**
 * News Carousel
 * Initialize Swiper for MSC-style news carousel
 */

document.addEventListener('DOMContentLoaded', function() {
    const newsSwiper = document.querySelector('.news-swiper');
    
    if (!newsSwiper) {
        return;
    }

    // Initialize Swiper
    new Swiper('.news-swiper', {
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
            nextEl: '.news-next',
            prevEl: '.news-prev',
        },
        
        // Pagination
        pagination: {
            el: '.news-pagination',
            clickable: true,
            dynamicBullets: false,
        },
        
        // Auto height
        autoHeight: false,
        
        // Smooth scrolling
        speed: 600,
        
        // Autoplay
        autoplay: {
            delay: 6000,
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

