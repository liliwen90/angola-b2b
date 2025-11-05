<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue frontend scripts and styles
 */
function angola_b2b_enqueue_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'angola-b2b-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&family=Noto+Sans+SC:wght@300;400;500;700&family=Noto+Sans+TC:wght@300;400;500;700&display=swap',
        array(),
        null
    );

    // Theme CSS files (in order)
    wp_enqueue_style(
        'angola-b2b-variables',
        ANGOLA_B2B_THEME_URI . '/assets/css/variables.css',
        array(),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-reset',
        ANGOLA_B2B_THEME_URI . '/assets/css/reset.css',
        array('angola-b2b-variables'),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-base',
        ANGOLA_B2B_THEME_URI . '/assets/css/base.css',
        array('angola-b2b-reset'),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-layout',
        ANGOLA_B2B_THEME_URI . '/assets/css/layout.css',
        array('angola-b2b-base'),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-components',
        ANGOLA_B2B_THEME_URI . '/assets/css/components.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-animations',
        ANGOLA_B2B_THEME_URI . '/assets/css/animations.css',
        array('angola-b2b-components'),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_style(
        'angola-b2b-responsive',
        ANGOLA_B2B_THEME_URI . '/assets/css/responsive.css',
        array('angola-b2b-animations'),
        ANGOLA_B2B_VERSION
    );

    // Mega Menu Navigation styles
    wp_enqueue_style(
        'angola-b2b-mega-menu',
        ANGOLA_B2B_THEME_URI . '/assets/css/navigation-mega-menu.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );

    // Hero Section styles
    wp_enqueue_style(
        'angola-b2b-hero-section',
        ANGOLA_B2B_THEME_URI . '/assets/css/hero-section.css',
        array('angola-b2b-components'),
        ANGOLA_B2B_VERSION
    );

    // Breadcrumbs styles
    wp_enqueue_style(
        'angola-b2b-breadcrumbs',
        ANGOLA_B2B_THEME_URI . '/assets/css/breadcrumbs.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );

    // Tab Navigation styles
    wp_enqueue_style(
        'angola-b2b-tab-navigation',
        ANGOLA_B2B_THEME_URI . '/assets/css/tab-navigation.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );

    // Content Blocks styles
    wp_enqueue_style(
        'angola-b2b-content-blocks',
        ANGOLA_B2B_THEME_URI . '/assets/css/content-blocks.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );

    // Statistics section styles
    wp_enqueue_style(
        'angola-b2b-statistics',
        ANGOLA_B2B_THEME_URI . '/assets/css/statistics.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Category Showcase styles
    wp_enqueue_style(
        'angola-b2b-category-showcase',
        ANGOLA_B2B_THEME_URI . '/assets/css/category-showcase.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Product Categories Showcase styles
    wp_enqueue_style(
        'angola-b2b-product-categories-showcase',
        ANGOLA_B2B_THEME_URI . '/assets/css/product-categories-showcase.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Services showcase styles
    wp_enqueue_style(
        'angola-b2b-services-showcase',
        ANGOLA_B2B_THEME_URI . '/assets/css/services-showcase.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Industries carousel styles
    wp_enqueue_style(
        'angola-b2b-industries-carousel',
        ANGOLA_B2B_THEME_URI . '/assets/css/industries-carousel.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // News carousel styles
    wp_enqueue_style(
        'angola-b2b-news-carousel',
        ANGOLA_B2B_THEME_URI . '/assets/css/news-carousel.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Customer advisories styles
    wp_enqueue_style(
        'angola-b2b-customer-advisories',
        ANGOLA_B2B_THEME_URI . '/assets/css/customer-advisories.css',
        array('angola-b2b-layout'),
        ANGOLA_B2B_VERSION
    );
    
    // Homepage specific styles
    wp_enqueue_style(
        'angola-b2b-homepage',
        ANGOLA_B2B_THEME_URI . '/assets/css/homepage.css',
        array('angola-b2b-responsive'),
        ANGOLA_B2B_VERSION
    );

    // Homepage product sliders
    wp_enqueue_script(
        'angola-b2b-homepage-sliders',
        ANGOLA_B2B_THEME_URI . '/assets/js/homepage-sliders.js',
        array('swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );

    // Tab Navigation script
    wp_enqueue_script(
        'angola-b2b-tab-navigation',
        ANGOLA_B2B_THEME_URI . '/assets/js/tab-navigation.js',
        array(),
        ANGOLA_B2B_VERSION,
        true
    );

    // Swiper.js CSS
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );

    // PhotoSwipe CSS
    wp_enqueue_style(
        'photoswipe-css',
        'https://cdn.jsdelivr.net/npm/photoswipe@5/dist/photoswipe.css',
        array(),
        '5.0.0'
    );

    // Main theme stylesheet (loads last)
    wp_enqueue_style(
        'angola-b2b-style',
        get_stylesheet_uri(),
        array('angola-b2b-responsive', 'swiper-css', 'photoswipe-css'),
        ANGOLA_B2B_VERSION
    );

    // GSAP (GreenSock Animation Platform)
    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3.12/dist/gsap.min.js',
        array(),
        '3.12.0',
        true
    );

    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdn.jsdelivr.net/npm/gsap@3.12/dist/ScrollTrigger.min.js',
        array('gsap'),
        '3.12.0',
        true
    );

    // Swiper.js
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );

    // PhotoSwipe
    wp_enqueue_script(
        'photoswipe-js',
        'https://cdn.jsdelivr.net/npm/photoswipe@5/dist/photoswipe.umd.min.js',
        array(),
        '5.0.0',
        true
    );

    wp_enqueue_script(
        'photoswipe-lightbox',
        'https://cdn.jsdelivr.net/npm/photoswipe@5/dist/photoswipe-lightbox.umd.min.js',
        array('photoswipe-js'),
        '5.0.0',
        true
    );

    // Theme JavaScript files
    wp_enqueue_script(
        'angola-b2b-utils',
        ANGOLA_B2B_THEME_URI . '/assets/js/utils.js',
        array('jquery'),
        ANGOLA_B2B_VERSION,
        true
    );

    wp_enqueue_script(
        'angola-b2b-mobile-menu',
        ANGOLA_B2B_THEME_URI . '/assets/js/mobile-menu.js',
        array('jquery', 'angola-b2b-utils'),
        ANGOLA_B2B_VERSION,
        true
    );

    // Mega Menu Navigation script
    wp_enqueue_script(
        'angola-b2b-mega-menu',
        ANGOLA_B2B_THEME_URI . '/assets/js/mega-menu.js',
        array(),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // Hero Quick Actions script
    wp_enqueue_script(
        'angola-b2b-hero-quick-actions',
        ANGOLA_B2B_THEME_URI . '/assets/js/hero-quick-actions.js',
        array(),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // Statistics counter animation script
    wp_enqueue_script(
        'angola-b2b-statistics',
        ANGOLA_B2B_THEME_URI . '/assets/js/statistics.js',
        array(),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // Category showcase / network carousel script
    wp_enqueue_script(
        'angola-b2b-category-showcase',
        ANGOLA_B2B_THEME_URI . '/assets/js/category-showcase.js',
        array('swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // Services showcase carousel script
    wp_enqueue_script(
        'angola-b2b-services-showcase',
        ANGOLA_B2B_THEME_URI . '/assets/js/services-showcase.js',
        array('swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // Industries carousel script
    wp_enqueue_script(
        'angola-b2b-industries-carousel',
        ANGOLA_B2B_THEME_URI . '/assets/js/industries-carousel.js',
        array('swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );
    
    // News carousel script
    wp_enqueue_script(
        'angola-b2b-news-carousel',
        ANGOLA_B2B_THEME_URI . '/assets/js/news-carousel.js',
        array('swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );

    wp_enqueue_script(
        'angola-b2b-animations',
        ANGOLA_B2B_THEME_URI . '/assets/js/animations.js',
        array('gsap', 'gsap-scrolltrigger'),
        ANGOLA_B2B_VERSION,
        true
    );

    // Product gallery with PhotoSwipe lightbox and Swiper carousel
    wp_enqueue_script(
        'angola-b2b-product-gallery',
        ANGOLA_B2B_THEME_URI . '/assets/js/product-gallery.js',
        array('photoswipe-lightbox', 'swiper-js'),
        ANGOLA_B2B_VERSION,
        true
    );

    // Note: product-360.js removed - 360-degree rotation feature not implemented (lack of multi-angle product images)

    wp_enqueue_script(
        'angola-b2b-ajax-filters',
        ANGOLA_B2B_THEME_URI . '/assets/js/ajax-filters.js',
        array('jquery'),
        ANGOLA_B2B_VERSION,
        true
    );

    wp_enqueue_script(
        'angola-b2b-main',
        ANGOLA_B2B_THEME_URI . '/assets/js/main.js',
        array('jquery', 'angola-b2b-utils'),
        ANGOLA_B2B_VERSION,
        true
    );

    // Localize script for AJAX
    wp_localize_script('angola-b2b-ajax-filters', 'angolaB2B', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('angola_b2b_nonce'),
        'homeUrl' => home_url('/'),
        'themeUrl' => ANGOLA_B2B_THEME_URI,
    ));

    // Load language switcher script if needed
    wp_enqueue_script(
        'angola-b2b-language-switcher',
        ANGOLA_B2B_THEME_URI . '/assets/js/language-switcher.js',
        array('jquery'),
        ANGOLA_B2B_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'angola_b2b_enqueue_scripts');

/**
 * Enqueue admin scripts and styles
 */
function angola_b2b_admin_enqueue_scripts($hook) {
    // Only load on post edit screens
    if ('post.php' !== $hook && 'post-new.php' !== $hook) {
        return;
    }

    wp_enqueue_style(
        'angola-b2b-admin',
        ANGOLA_B2B_THEME_URI . '/assets/css/admin.css',
        array(),
        ANGOLA_B2B_VERSION
    );

    wp_enqueue_script(
        'angola-b2b-admin',
        ANGOLA_B2B_THEME_URI . '/assets/js/admin.js',
        array('jquery'),
        ANGOLA_B2B_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'angola_b2b_admin_enqueue_scripts');

/**
 * Add async/defer attributes to scripts
 */
function angola_b2b_script_loader_tag($tag, $handle, $src) {
    // Add defer to external library scripts for better performance
    // Note: photoswipe-js and photoswipe-lightbox should NOT use defer
    // as they need to be available when product-gallery.js initializes
    $defer_scripts = array(
        'gsap',
        'gsap-scrolltrigger',
        'swiper-js',
    );

    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'angola_b2b_script_loader_tag', 10, 3);

