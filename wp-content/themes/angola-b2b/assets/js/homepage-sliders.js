/**
 * Homepage Product Sliders
 * 首页产品轮播功能
 * 
 * @package Angola_B2B
 */

(function() {
    'use strict';

    // 等待DOM加载完成
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSliders);
    } else {
        initSliders();
    }

    function initSliders() {
        // 检查Swiper是否已加载
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper not loaded');
            return;
        }

        // 初始化库存产品轮播
        const stockSwiper = document.querySelector('.stock-products-swiper');
        if (stockSwiper) {
            new Swiper('.stock-products-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.stock-swiper-next',
                    prevEl: '.stock-swiper-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                watchOverflow: true,
                observer: true,
                observeParents: true,
            });
        }

        // 初始化精选产品轮播
        const featuredSwiper = document.querySelector('.featured-products-swiper');
        if (featuredSwiper) {
            new Swiper('.featured-products-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.featured-swiper-next',
                    prevEl: '.featured-swiper-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 24,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 24,
                    },
                    1280: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                },
                watchOverflow: true,
                observer: true,
                observeParents: true,
            });
        }
    }
})();

