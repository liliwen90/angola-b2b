<?php
/**
 * ACF多语言字段组定义 - Multilingual Field Groups
 * 
 * 为产品分类和产品自定义文章类型添加多语言字段
 * 
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册ACF字段组 - 产品分类多语言名称
 * 
 * 为product_category分类法添加多语言名称字段
 */
add_action('acf/include_fields', function() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // 字段组1: 产品分类多语言名称
    acf_add_local_field_group(array(
        'key' => 'group_category_multilingual',
        'title' => 'Product Category Multilingual Names',
        'fields' => array(
            array(
                'key' => 'field_category_name_pt',
                'label' => 'Portuguese Name (Português)',
                'name' => 'name_pt',
                'type' => 'text',
                'instructions' => 'Enter the category name in Portuguese',
                'required' => 0,
                'placeholder' => 'e.g., Logística e Alfândega',
            ),
            array(
                'key' => 'field_category_name_zh',
                'label' => 'Simplified Chinese Name (简体中文)',
                'name' => 'name_zh',
                'type' => 'text',
                'instructions' => 'Enter the category name in Simplified Chinese',
                'required' => 0,
                'placeholder' => 'e.g., 物流清关',
            ),
            array(
                'key' => 'field_category_name_zh_tw',
                'label' => 'Traditional Chinese Name (繁體中文)',
                'name' => 'name_zh_tw',
                'type' => 'text',
                'instructions' => 'Enter the category name in Traditional Chinese',
                'required' => 0,
                'placeholder' => 'e.g., 物流清關',
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
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));

    // 字段组2: 产品多语言信息
    acf_add_local_field_group(array(
        'key' => 'group_product_multilingual',
        'title' => 'Product Multilingual Information',
        'fields' => array(
            // 葡萄牙语标题
            array(
                'key' => 'field_product_title_pt',
                'label' => 'Portuguese Title (Português)',
                'name' => 'title_pt',
                'type' => 'text',
                'instructions' => 'Enter the product title in Portuguese',
                'required' => 0,
                'placeholder' => '',
            ),
            // 简体中文标题
            array(
                'key' => 'field_product_title_zh',
                'label' => 'Simplified Chinese Title (简体中文)',
                'name' => 'title_zh',
                'type' => 'text',
                'instructions' => 'Enter the product title in Simplified Chinese',
                'required' => 0,
                'placeholder' => '',
            ),
            // 繁体中文标题
            array(
                'key' => 'field_product_title_zh_tw',
                'label' => 'Traditional Chinese Title (繁體中文)',
                'name' => 'title_zh_tw',
                'type' => 'text',
                'instructions' => 'Enter the product title in Traditional Chinese',
                'required' => 0,
                'placeholder' => '',
            ),
            // 葡萄牙语简短描述 - 已禁用，改为在富文本编辑器中编写
            // array(
            //     'key' => 'field_product_short_description_pt',
            //     'label' => 'Portuguese Short Description',
            //     'name' => 'short_description_pt',
            //     'type' => 'textarea',
            //     'instructions' => 'Enter a short product description in Portuguese',
            //     'required' => 0,
            //     'rows' => 3,
            //     'placeholder' => '',
            // ),
            // // 简体中文简短描述
            // array(
            //     'key' => 'field_product_short_description_zh',
            //     'label' => 'Simplified Chinese Short Description',
            //     'name' => 'short_description_zh',
            //     'type' => 'textarea',
            //     'instructions' => 'Enter a short product description in Simplified Chinese',
            //     'required' => 0,
            //     'rows' => 3,
            //     'placeholder' => '',
            // ),
            // // 繁体中文简短描述
            // array(
            //     'key' => 'field_product_short_description_zh_tw',
            //     'label' => 'Traditional Chinese Short Description',
            //     'name' => 'short_description_zh_tw',
            //     'type' => 'textarea',
            //     'instructions' => 'Enter a short product description in Traditional Chinese',
            //     'required' => 0,
            //     'rows' => 3,
            //     'placeholder' => '',
            // ),
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
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    ));
});

