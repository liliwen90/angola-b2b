<?php
/**
 * Theme Setup Functions
 * Register menus, theme supports, image sizes, and widget areas
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set up theme defaults and register support for WordPress features
 */
function angola_b2b_setup() {
    // Make theme available for translation
    load_theme_textdomain('angola-b2b', ANGOLA_B2B_THEME_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'angola-b2b'),
        'footer'  => esc_html__('Footer Menu', 'angola-b2b'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Register custom image sizes for product showcase
    // 所有尺寸都使用hard crop (true) 确保固定尺寸
    // 产品卡片使用4:3比例以匹配CSS aspect-ratio设置
    add_image_size('product-card', 800, 600, true); // 产品卡片（4:3高清） - 用于列表页
    add_image_size('product-thumbnail', 600, 450, true); // 产品缩略图（4:3）
    add_image_size('product-medium', 1200, 900, true); // 产品中等尺寸（4:3）
    add_image_size('product-large', 1600, 1200, true); // 产品详情页大图（4:3）
    add_image_size('homepage-banner', 1100, 400, true); // Banner轮播固定尺寸（1100x400）
    add_image_size('product-360', 600, 600, true); // 360度展示（正方形）
    add_image_size('hero-banner', 1920, 800, true); // Hero横幅（16:6.67）
}
add_action('after_setup_theme', 'angola_b2b_setup');

/**
 * Set the content width in pixels
 */
function angola_b2b_content_width() {
    $GLOBALS['content_width'] = apply_filters('angola_b2b_content_width', 1200);
}
add_action('after_setup_theme', 'angola_b2b_content_width', 0);

/**
 * Register widget areas
 */
function angola_b2b_widgets_init() {
    // Footer widget areas
    register_sidebar(array(
        'name'          => esc_html__('Footer Column 1', 'angola-b2b'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in footer column 1.', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 2', 'angola-b2b'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in footer column 2.', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 3', 'angola-b2b'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in footer column 3.', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column 4', 'angola-b2b'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in footer column 4.', 'angola-b2b'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'angola_b2b_widgets_init');

/**
 * Flush rewrite rules on theme activation
 */
function angola_b2b_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'angola_b2b_flush_rewrite_rules');

