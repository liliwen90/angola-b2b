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
            
            // Tab: 站点信息
            array(
                'key' => 'field_tab_site_info',
                'label' => '站点信息',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_site_logo',
                'label' => '网站Logo',
                'name' => 'site_logo',
                'type' => 'image',
                'instructions' => '上传网站Logo图片（建议尺寸：200x60px，透明背景PNG格式）',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_site_title',
                'label' => '网站标题',
                'name' => 'site_title',
                'type' => 'text',
                'instructions' => '网站名称，显示在Header和浏览器标签页',
                'default_value' => 'Unibro-b2b',
                'placeholder' => 'Unibro-b2b',
            ),
            array(
                'key' => 'field_site_tagline',
                'label' => '网站副标题',
                'name' => 'site_tagline',
                'type' => 'text',
                'instructions' => '网站描述或口号',
                'default_value' => '',
                'placeholder' => 'Your trusted partner for quality products',
            ),
            
            // Tab: Hero区域
            array(
                'key' => 'field_tab_hero',
                'label' => 'Hero区域',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_hero_background_image',
                'label' => 'Hero背景图片',
                'name' => 'hero_background_image',
                'type' => 'image',
                'instructions' => '首页Hero区域的背景图片（建议尺寸：1920x800px）',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_hero_title',
                'label' => 'Hero标题',
                'name' => 'hero_title',
                'type' => 'text',
                'instructions' => '首页Hero区域的主标题',
                'default_value' => '',
            ),
            array(
                'key' => 'field_hero_subtitle',
                'label' => 'Hero副标题/描述',
                'name' => 'hero_subtitle',
                'type' => 'textarea',
                'instructions' => '首页Hero区域的副标题或描述文字（显示在标题下方，较小字体）',
                'rows' => 3,
                'new_lines' => 'wpautop',
            ),
            
            // Tab: 联系信息
            array(
                'key' => 'field_tab_contact',
                'label' => '联系信息',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_contact_email',
                'label' => '联系邮箱',
                'name' => 'contact_email',
                'type' => 'email',
                'instructions' => '网站联系邮箱（显示在Header和Footer）',
                'default_value' => 'info@example.com',
                'placeholder' => 'info@example.com',
            ),
            array(
                'key' => 'field_contact_phone',
                'label' => '联系电话',
                'name' => 'contact_phone',
                'type' => 'text',
                'instructions' => '网站联系电话（显示在Header和Footer）',
                'default_value' => '+1 234 567 8900',
                'placeholder' => '+1 234 567 8900',
            ),
            
            // Tab: 社交媒体
            array(
                'key' => 'field_tab_social',
                'label' => '社交媒体',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_social_facebook',
                'label' => 'Facebook链接',
                'name' => 'social_facebook',
                'type' => 'url',
                'instructions' => 'Facebook主页链接',
                'default_value' => '',
                'placeholder' => 'https://facebook.com/your-page',
            ),
            array(
                'key' => 'field_social_facebook_show',
                'label' => '显示Facebook',
                'name' => 'social_facebook_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示Facebook图标',
                'ui' => 1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_social_twitter',
                'label' => 'Twitter链接',
                'name' => 'social_twitter',
                'type' => 'url',
                'instructions' => 'Twitter主页链接',
                'default_value' => '',
                'placeholder' => 'https://twitter.com/your-account',
            ),
            array(
                'key' => 'field_social_twitter_show',
                'label' => '显示Twitter',
                'name' => 'social_twitter_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示Twitter图标',
                'ui' => 1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_social_linkedin',
                'label' => 'LinkedIn链接',
                'name' => 'social_linkedin',
                'type' => 'url',
                'instructions' => 'LinkedIn公司主页链接',
                'default_value' => '',
                'placeholder' => 'https://linkedin.com/company/your-company',
            ),
            array(
                'key' => 'field_social_linkedin_show',
                'label' => '显示LinkedIn',
                'name' => 'social_linkedin_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示LinkedIn图标',
                'ui' => 1,
                'default_value' => 0,
            ),
            array(
                'key' => 'field_social_whatsapp',
                'label' => 'WhatsApp号码',
                'name' => 'social_whatsapp',
                'type' => 'text',
                'instructions' => 'WhatsApp联系号码（国际格式，如：+8615319996326）',
                'default_value' => '',
                'placeholder' => '+8615319996326',
            ),
            array(
                'key' => 'field_social_whatsapp_show',
                'label' => '显示WhatsApp',
                'name' => 'social_whatsapp_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示WhatsApp图标',
                'ui' => 1,
                'default_value' => 0,
            ),
            
            // Tab: 关于我们
            array(
                'key' => 'field_tab_about',
                'label' => '关于我们',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_footer_about_us_content',
                'label' => 'English',
                'name' => 'footer_about_us_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_about_us_content_pt',
                'label' => 'Português',
                'name' => 'footer_about_us_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_about_us_content_zh',
                'label' => '简体中文',
                'name' => 'footer_about_us_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_about_us_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_about_us_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            
            // Tab: 我们的服务
            array(
                'key' => 'field_tab_services',
                'label' => '我们的服务',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_footer_services_content',
                'label' => 'English',
                'name' => 'footer_services_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_services_content_pt',
                'label' => 'Português',
                'name' => 'footer_services_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_services_content_zh',
                'label' => '简体中文',
                'name' => 'footer_services_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_services_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_services_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            
            // Tab: 招聘
            array(
                'key' => 'field_tab_careers',
                'label' => '招聘',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
            ),
            array(
                'key' => 'field_footer_careers_content',
                'label' => 'English',
                'name' => 'footer_careers_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_careers_content_pt',
                'label' => 'Português',
                'name' => 'footer_careers_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_careers_content_zh',
                'label' => '简体中文',
                'name' => 'footer_careers_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
            ),
            array(
                'key' => 'field_footer_careers_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_careers_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
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
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
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

/**
 * Register Product Category Hero Fields
 * 为产品分类添加Hero区域字段
 */
function angola_b2b_register_category_hero_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_category_hero',
        'title' => '分类Hero设置',
        'fields' => array(
            array(
                'key' => 'field_category_hero_image',
                'label' => 'Hero背景图片',
                'name' => 'category_hero_image',
                'type' => 'image',
                'instructions' => '分类归档页Hero区域的背景图片（建议尺寸：1920x800px）',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_category_nav_image',
                'label' => '导航菜单图片',
                'name' => 'category_nav_image',
                'type' => 'image',
                'instructions' => '导航菜单下拉面板中显示的图片（建议尺寸：400x300px）',
                'return_format' => 'url',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'product_category',
                ),
            ),
        ),
        'menu_order' => 10,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_category_hero_fields');

/**
 * Register Product Hero Fields
 * 为产品添加Hero区域字段
 */
function angola_b2b_register_product_hero_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_product_hero',
        'title' => '产品Hero设置',
        'fields' => array(
            array(
                'key' => 'field_product_hero_image',
                'label' => 'Hero背景图片',
                'name' => 'product_hero_image',
                'type' => 'image',
                'instructions' => '产品详情页Hero区域的背景图片（建议尺寸：1920x800px）。如果未设置，将使用产品特色图片。',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
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
        'menu_order' => 15,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_product_hero_fields');

/**
 * Register Service (解决方案) ACF Fields
 */
function angola_b2b_register_service_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_service_details',
        'title' => '解决方案详细信息',
        'fields' => array(
            array(
                'key' => 'field_service_icon',
                'label' => '图标SVG代码',
                'name' => 'service_icon',
                'type' => 'textarea',
                'instructions' => '粘贴SVG图标代码（可选）。留空则不显示图标。',
                'rows' => 4,
            ),
            array(
                'key' => 'field_service_link',
                'label' => '链接地址',
                'name' => 'service_link',
                'type' => 'url',
                'instructions' => '点击该解决方案后跳转的链接（可选）。留空则不可点击。',
            ),
            array(
                'key' => 'field_service_features',
                'label' => '特性列表',
                'name' => 'service_features',
                'type' => 'repeater',
                'instructions' => '添加该解决方案的关键特性（显示为列表）',
                'button_label' => '添加特性',
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_feature_text',
                        'label' => '特性文本',
                        'name' => 'feature_text',
                        'type' => 'text',
                    ),
                ),
                'min' => 0,
                'max' => 5,
                'layout' => 'table',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_service_fields');

/**
 * Register Industry (行业) ACF Fields
 */
function angola_b2b_register_industry_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_industry_details',
        'title' => '行业详细信息',
        'fields' => array(
            array(
                'key' => 'field_industry_link',
                'label' => '链接地址',
                'name' => 'industry_link',
                'type' => 'url',
                'instructions' => '点击该行业后跳转的链接（可选）。留空则不可点击。',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'industry',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_industry_fields');

