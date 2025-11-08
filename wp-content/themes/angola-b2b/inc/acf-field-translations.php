<?php
/**
 * ACF字段标签动态翻译 - ACF Field Labels Translation
 * 
 * 根据用户语言设置动态翻译ACF字段标签
 * 
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 翻译ACF字段标签
 * 
 * @param array $field 字段数组
 * @return array 翻译后的字段数组
 */
function angola_b2b_translate_acf_field_labels($field) {
    // 获取当前用户的语言设置
    $user_locale = get_user_locale();
    
    // 只为葡语用户翻译
    if ($user_locale !== 'pt_PT') {
        return $field;
    }
    
    // 定义字段标签的葡语翻译
    $translations = array(
        // 产品基本信息
        '产品基本信息' => 'Informações Básicas do Produto',
        '产品型号' => 'Modelo do Produto',
        '产品SKU' => 'SKU do Produto',
        '产品简短描述' => 'Descrição Curta',
        '产品图片1' => 'Imagem do Produto 1',
        '产品图片2' => 'Imagem do Produto 2',
        '产品图片3' => 'Imagem do Produto 3',
        '产品图片4' => 'Imagem do Produto 4',
        '产品图片5' => 'Imagem do Produto 5',
        '产品视频链接' => 'Link do Vídeo',
        '是否推荐产品' => 'Produto em Destaque',
        
        // 产品规格参数
        '产品规格参数' => 'Especificações do Produto',
        '规格名称1' => 'Nome da Especificação 1',
        '规格值1' => 'Valor da Especificação 1',
        '规格名称2' => 'Nome da Especificação 2',
        '规格值2' => 'Valor da Especificação 2',
        '规格名称3' => 'Nome da Especificação 3',
        '规格值3' => 'Valor da Especificação 3',
        '规格名称4' => 'Nome da Especificação 4',
        '规格值4' => 'Valor da Especificação 4',
        '规格名称5' => 'Nome da Especificação 5',
        '规格值5' => 'Valor da Especificação 5',
        '规格名称6' => 'Nome da Especificação 6',
        '规格值6' => 'Valor da Especificação 6',
        '规格名称7' => 'Nome da Especificação 7',
        '规格值7' => 'Valor da Especificação 7',
        '规格名称8' => 'Nome da Especificação 8',
        '规格值8' => 'Valor da Especificação 8',
    );
    
    // 翻译字段标签
    if (isset($field['label']) && isset($translations[$field['label']])) {
        $field['label'] = $translations[$field['label']];
    }
    
    return $field;
}
add_filter('acf/load_field', 'angola_b2b_translate_acf_field_labels');

/**
 * 翻译ACF字段组标题
 * 
 * @param array $field_group 字段组数组
 * @return array 翻译后的字段组数组
 */
function angola_b2b_translate_acf_field_group_titles($field_group) {
    // 获取当前用户的语言设置
    $user_locale = get_user_locale();
    
    // 只为葡语用户翻译
    if ($user_locale !== 'pt_PT') {
        return $field_group;
    }
    
    // 定义字段组标题的葡语翻译
    $translations = array(
        '产品基本信息' => 'Informações Básicas do Produto',
        '产品规格参数' => 'Especificações do Produto',
        'Product Multilingual Information' => 'Informações Multilíngues do Produto',
    );
    
    // 翻译字段组标题
    if (isset($field_group['title']) && isset($translations[$field_group['title']])) {
        $field_group['title'] = $translations[$field_group['title']];
    }
    
    return $field_group;
}
add_filter('acf/load_field_group', 'angola_b2b_translate_acf_field_group_titles');

/**
 * 翻译产品分类和标签的元数据框标题
 */
function angola_b2b_translate_taxonomy_metaboxes() {
    // 获取当前用户的语言设置
    $user_locale = get_user_locale();
    
    // 只为葡语用户翻译
    if ($user_locale !== 'pt_PT') {
        return;
    }
    
    global $wp_meta_boxes;
    
    // 如果在产品编辑页面
    if (isset($wp_meta_boxes['product'])) {
        // 翻译分类元数据框
        if (isset($wp_meta_boxes['product']['side']['core']['product_categorydiv'])) {
            $wp_meta_boxes['product']['side']['core']['product_categorydiv']['title'] = 'Categorias';
        }
        
        // 翻译标签元数据框
        if (isset($wp_meta_boxes['product']['side']['core']['tagsdiv-product_tag'])) {
            $wp_meta_boxes['product']['side']['core']['tagsdiv-product_tag']['title'] = 'Tags';
        }
    }
}
add_action('do_meta_boxes', 'angola_b2b_translate_taxonomy_metaboxes', 20);

/**
 * 翻译产品主图元数据框标题
 */
function angola_b2b_translate_featured_image_metabox() {
    // 获取当前用户的语言设置
    $user_locale = get_user_locale();
    
    // 只为葡语用户翻译
    if ($user_locale !== 'pt_PT') {
        return;
    }
    
    // 移除原来的"产品主图"或"Featured Image"标题
    remove_meta_box('postimagediv', 'product', 'side');
    
    // 添加新的葡语标题
    add_meta_box(
        'postimagediv',
        'Imagem Principal do Produto',
        'post_thumbnail_meta_box',
        'product',
        'side',
        'low'
    );
}
add_action('do_meta_boxes', 'angola_b2b_translate_featured_image_metabox', 30);

/**
 * 翻译"设置产品主图"链接文本
 */
function angola_b2b_translate_set_featured_image_text($translation, $text, $domain) {
    // 获取当前用户的语言设置
    $user_locale = get_user_locale();
    
    // 只为葡语用户翻译
    if ($user_locale !== 'pt_PT') {
        return $translation;
    }
    
    // 翻译相关文本
    $translations = array(
        'Set featured image' => 'Definir imagem principal',
        'Remove featured image' => 'Remover imagem principal',
        '设置产品主图' => 'Definir imagem principal',
        '移除产品主图' => 'Remover imagem principal',
    );
    
    if (isset($translations[$text])) {
        return $translations[$text];
    }
    
    return $translation;
}
add_filter('gettext', 'angola_b2b_translate_set_featured_image_text', 20, 3);

