/**
 * Banner Slider Initialization
 * Banner轮播初始化
 * 
 * @package Angola_B2B
 */

document.addEventListener('DOMContentLoaded', function() {
    // 检查Swiper是否加载
    if (typeof Swiper === 'undefined') {
        console.warn('Swiper library not loaded. Banner slider will not function.');
        return;
    }

    // 检查Banner轮播容器是否存在
    const bannerSwiperContainer = document.querySelector('.banner-swiper');
    if (!bannerSwiperContainer) {
        return; // Banner可能被禁用或没有选择产品
    }

    // 初始化Banner Swiper
    const bannerSwiper = new Swiper('.banner-swiper', {
        // 自动播放配置
        autoplay: {
            delay: 2500, // 2.5秒间隔
            disableOnInteraction: false, // 用户操作后继续自动播放
            pauseOnMouseEnter: true, // 鼠标悬停时暂停
        },
        
        // 循环播放
        loop: true,
        
        // 切换效果
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        
        // 速度
        speed: 800,
        
        // 分页器
        pagination: {
            el: '.banner-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        
        // 导航按钮
        navigation: {
            nextEl: '.banner-next',
            prevEl: '.banner-prev',
        },
        
        // 键盘控制
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
        
        // 无障碍
        a11y: {
            enabled: true,
            prevSlideMessage: 'Previous slide',
            nextSlideMessage: 'Next slide',
            paginationBulletMessage: 'Go to slide {{index}}',
        },
    });

    // 在控制台输出初始化成功信息（仅开发环境）
    console.log('Banner slider initialized successfully with', bannerSwiper.slides.length, 'slides');
});

