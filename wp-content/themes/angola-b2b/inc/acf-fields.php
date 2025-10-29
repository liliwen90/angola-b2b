<?php
/**
 * ACF Field Groups Registration
 * This file will be populated with ACF field groups after ACF Pro is installed
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF options pages
 */
if (function_exists('acf_add_options_page')) {
    // Main theme options page
    acf_add_options_page(array(
        'page_title' => __('主题选项', 'angola-b2b'),
        'menu_title' => __('主题选项', 'angola-b2b'),
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'icon_url'   => 'dashicons-admin-generic',
        'redirect'   => false,
        'position'   => 60,
    ));

    // Homepage settings
    acf_add_options_sub_page(array(
        'page_title'  => __('首页设置', 'angola-b2b'),
        'menu_title'  => __('首页设置', 'angola-b2b'),
        'parent_slug' => 'theme-general-settings',
    ));

    // Social media settings
    acf_add_options_sub_page(array(
        'page_title'  => __('社交媒体', 'angola-b2b'),
        'menu_title'  => __('社交媒体', 'angola-b2b'),
        'parent_slug' => 'theme-general-settings',
    ));

    // Contact settings
    acf_add_options_sub_page(array(
        'page_title'  => __('联系信息', 'angola-b2b'),
        'menu_title'  => __('联系信息', 'angola-b2b'),
        'parent_slug' => 'theme-general-settings',
    ));
}

/**
 * Hide ACF menu in production
 */
if (!defined('WP_DEBUG') || WP_DEBUG === false) {
    add_filter('acf/settings/show_admin', '__return_false');
}

/**
 * ACF field groups will be added here after ACF Pro installation
 * Field groups will be exported from ACF UI and added programmatically
 * 
 * Expected field groups:
 * - Product Fields (gallery, specifications, 360 images, etc.)
 * - Homepage Hero Section
 * - Core Advantages
 * - Social Media Links
 * - Contact Information
 */

