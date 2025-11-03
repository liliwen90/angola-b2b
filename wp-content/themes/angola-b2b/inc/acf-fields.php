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
 * ACF免费版不支持Options Page功能，已改用专门的WordPress页面（ID: 45）存储首页设置
 * 其他设置（社交媒体、联系信息）将来可以用类似方式创建独立页面
 */
// function angola_b2b_register_acf_options_pages() {
//     // ACF PRO required for Options Pages
//     // Using dedicated WordPress page (ID: 45) as alternative
// }
// add_action('acf/init', 'angola_b2b_register_acf_options_pages', 5);

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

/**
 * Register Homepage Settings Fields
 * 注册首页设置字段
 */
function angola_b2b_register_homepage_settings_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_homepage_settings',
        'title' => '首页设置',
        'fields' => array(
            
            // Tab: Banner轮播
            array(
                'key' => 'field_tab_banner_slider',
                'label' => 'Banner轮播',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_enable_banner_slider',
                'label' => '显示Banner轮播',
                'name' => 'enable_banner_slider',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => '关闭后，首页将不显示Banner轮播',
            ),
            array(
                'key' => 'field_banner_products',
                'label' => '选择展示的产品',
                'name' => 'banner_products',
                'type' => 'relationship',
                'instructions' => '选择要在Banner中展示的产品（将显示产品的特色图片）',
                'post_type' => array('product'),
                'filters' => array('search'),
                'return_format' => 'id',
                'min' => 1,
                'max' => 10,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_banner_slider',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            
            // Tab: 库存产品模块
            array(
                'key' => 'field_tab_stock_products',
                'label' => '库存产品模块',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_enable_stock_products_section',
                'label' => '显示热门库存产品区域',
                'name' => 'enable_stock_products_section',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
                'instructions' => '关闭后，首页将不显示库存产品区域',
            ),
            array(
                'key' => 'field_stock_products_title',
                'label' => '库存产品区域标题',
                'name' => 'stock_products_title',
                'type' => 'text',
                'default_value' => '现货供应 - 即刻发货',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_stock_products_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
            array(
                'key' => 'field_stock_products_subtitle',
                'label' => '库存产品区域副标题',
                'name' => 'stock_products_subtitle',
                'type' => 'text',
                'default_value' => '本地库存，即刻发货',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_enable_stock_products_section',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45', // 首页设置页面ID
                ),
            ),
        ),
        'menu_order' => 0,
    ));
}
add_action('acf/init', 'angola_b2b_register_homepage_settings_fields');

/**
 * Register Product Stock Fields
 * 注册产品库存字段（追加到产品基本信息字段组）
 */
function angola_b2b_register_product_stock_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_product_stock_info',
        'title' => '产品库存信息',
        'fields' => array(
            array(
                'key' => 'field_product_in_stock',
                'label' => '是否为库存商品',
                'name' => 'product_in_stock',
                'type' => 'true_false',
                'instructions' => '勾选后将在首页"热门库存"区域显示',
                'ui' => 1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_product_stock_quantity',
                'label' => '库存数量',
                'name' => 'product_stock_quantity',
                'type' => 'number',
                'instructions' => '留空则不显示库存数',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_product_in_stock',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'min' => 0,
                'step' => 1,
            ),
            array(
                'key' => 'field_product_stock_badge_text',
                'label' => '库存徽章文字',
                'name' => 'product_stock_badge_text',
                'type' => 'text',
                'instructions' => '显示在产品卡片上的徽章文字',
                'default_value' => '现货',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_product_in_stock',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
        'menu_order' => 5,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_product_stock_fields');

