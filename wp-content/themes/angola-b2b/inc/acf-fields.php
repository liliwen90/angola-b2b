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
 * Set ACF JSON save and load points
 */
// Save JSON to theme folder
add_filter('acf/settings/save_json', 'angola_b2b_acf_json_save_point');
function angola_b2b_acf_json_save_point($path) {
    return ANGOLA_B2B_THEME_DIR . '/acf-json';
}

// Load JSON from theme folder
add_filter('acf/settings/load_json', 'angola_b2b_acf_json_load_point');
function angola_b2b_acf_json_load_point($paths) {
    // Remove original path
    unset($paths[0]);
    
    // Append new path
    $paths[] = ANGOLA_B2B_THEME_DIR . '/acf-json';
    
    return $paths;
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
 * 暂时注释掉，开发阶段需要访问ACF菜单
 */
// if (!defined('WP_DEBUG') || WP_DEBUG === false) {
//     add_filter('acf/settings/show_admin', '__return_false');
// }

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

