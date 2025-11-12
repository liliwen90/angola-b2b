<?php
/**
 * 产品字段定义 - V2（尝试修复字段为空问题）
 *
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册产品多语言字段组 - V2
 */
function angola_b2b_register_simple_product_fields_v2() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // 先定义所有字段
    $fields = array();
    
    // ==================== English Tab ====================
    $fields[] = array(
        'key' => 'field_tab_english',
        'label' => 'English',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    );
    
    $fields[] = array(
        'key' => 'field_product_title_en',
        'label' => 'Product Title',
        'name' => 'title_en',
        'type' => 'text',
        'placeholder' => 'e.g. 50 Ton Mobile Crane',
    );
    
    $fields[] = array(
        'key' => 'field_product_content_en',
        'label' => 'Product Details',
        'name' => 'content_en',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 1,
    );
    
    // ==================== Português Tab ====================
    $fields[] = array(
        'key' => 'field_tab_portuguese',
        'label' => 'Português',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    );
    
    $fields[] = array(
        'key' => 'field_product_title_pt',
        'label' => 'Título do Produto',
        'name' => 'title_pt',
        'type' => 'text',
        'placeholder' => 'Ex: Guindaste Móvel 50 Toneladas',
    );
    
    $fields[] = array(
        'key' => 'field_product_content_pt',
        'label' => 'Detalhes do Produto',
        'name' => 'content_pt',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 1,
    );
    
    // ==================== 简体中文 Tab ====================
    $fields[] = array(
        'key' => 'field_tab_chinese',
        'label' => '简体中文',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    );
    
    $fields[] = array(
        'key' => 'field_product_title_zh',
        'label' => '产品标题',
        'name' => 'title_zh',
        'type' => 'text',
        'placeholder' => '例如：50吨移动式起重机',
    );
    
    $fields[] = array(
        'key' => 'field_product_content_zh',
        'label' => '产品详情',
        'name' => 'content_zh',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 1,
    );
    
    // ==================== 繁體中文 Tab ====================
    $fields[] = array(
        'key' => 'field_tab_chinese_tw',
        'label' => '繁體中文',
        'name' => '',
        'type' => 'tab',
        'placement' => 'top',
        'endpoint' => 0,
    );
    
    $fields[] = array(
        'key' => 'field_product_title_zh_tw',
        'label' => '產品標題',
        'name' => 'title_zh_tw',
        'type' => 'text',
        'placeholder' => '例如：50噸移動式起重機',
    );
    
    $fields[] = array(
        'key' => 'field_product_content_zh_tw',
        'label' => '產品詳情',
        'name' => 'content_zh_tw',
        'type' => 'wysiwyg',
        'toolbar' => 'full',
        'media_upload' => 1,
    );

    // 注册字段组
    acf_add_local_field_group(array(
        'key' => 'group_product_simple_multilingual_v2',
        'title' => '产品多语言信息（V2测试）',
        'fields' => $fields,
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
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array('the_content'),
        'active' => 1,
        'description' => 'V2测试版本',
    ));
}
add_action('acf/init', 'angola_b2b_register_simple_product_fields_v2', 5);
?>

