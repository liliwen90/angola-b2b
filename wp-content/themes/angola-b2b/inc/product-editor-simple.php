<?php
/**
 * 产品编辑器 - 简洁版
 * 加载简洁版产品编辑器的CSS和JS
 *
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 加载产品编辑器资源（简洁版）
 */
function angola_b2b_load_simple_product_editor_assets($hook) {
    // 只在产品编辑页面加载
    global $post_type;
    if (('post.php' !== $hook && 'post-new.php' !== $hook) || 'product' !== $post_type) {
        return;
    }

    // 加载CSS
    wp_enqueue_style(
        'angola-product-editor-simple',
        get_template_directory_uri() . '/assets/css/product-editor-simple.css',
        array(),
        '2.0.1'
    );

    // 加载JavaScript
    wp_enqueue_script(
        'angola-product-editor-simple',
        get_template_directory_uri() . '/assets/js/product-editor-simple.js',
        array('jquery'),
        '2.0.0',
        true
    );

    // 传递数据到JavaScript
    wp_localize_script('angola-product-editor-simple', 'angolaProductEditor', array(
        'version' => '2.0.0',
        'languages' => array(
            'en' => 'English',
            'pt' => 'Português',
            'zh' => '简体中文',
            'zh_tw' => '繁體中文',
        ),
        'defaultLang' => 'en',
    ));
}
add_action('admin_enqueue_scripts', 'angola_b2b_load_simple_product_editor_assets');

/**
 * 自定义产品编辑页面布局
 */
function angola_b2b_customize_product_edit_screen() {
    global $post_type;
    
    if ('product' !== $post_type) {
        return;
    }
    
    ?>
    <style>
        /* 优化产品编辑页面布局 */
        #post-body-content {
            margin-bottom: 20px;
        }
        
        /* 优化元数据框间距 */
        #poststuff #post-body.columns-2 {
            margin-right: 320px;
        }
        
        /* 简化侧边栏 */
        #side-sortables .postbox {
            margin-bottom: 12px;
        }
        
        /* 特色图片提示 */
        #postimagediv .inside {
            padding: 12px;
        }
        
        #set-post-thumbnail {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 40px 20px;
            border: 2px dashed #dcdcde;
            border-radius: 4px;
            background: #f9f9f9;
            transition: all 0.2s ease;
        }
        
        #set-post-thumbnail:hover {
            border-color: #0073aa;
            background: #e7f3ff;
        }
        
        /* 产品分类样式 */
        #taxonomy-product_category .categorychecklist {
            max-height: 300px;
            overflow-y: auto;
        }
        
        /* 隐藏不需要的元素 */
        #edit-slug-box,
        #visibility,
        #minor-publishing-actions {
            display: none !important;
        }
        
        /* 发布按钮样式 */
        #publishing-action {
            text-align: right;
            padding: 12px 0;
        }
        
        #publish {
            min-width: 100px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        console.log('=== Customizing Product Edit Screen ===');
        
        // 添加特色图片提示文字
        if ($('#postimagediv').length > 0 && !$('#set-post-thumbnail img').length) {
            $('#set-post-thumbnail').html('<span style="font-size: 48px; display: block; margin-bottom: 10px;">🖼️</span><span style="font-size: 14px; color: #646970;">点击添加产品主图</span>');
        }
        
        // 简化分类元数据框标题
        $('#taxonomy-product_category .hndle span').text('产品分类');
        $('#postimagediv .hndle span').text('产品主图');
        
        console.log('✓ Product edit screen customized');
    });
    </script>
    <?php
}
add_action('admin_head-post.php', 'angola_b2b_customize_product_edit_screen');
add_action('admin_head-post-new.php', 'angola_b2b_customize_product_edit_screen');

/**
 * 注意：产品列表页面的列定义在 admin-customization.php 中
 * 这里不需要重复定义
 */

/**
 * 添加产品列表页面样式
 */
function angola_b2b_product_list_styles() {
    global $post_type;
    
    if ('product' !== $post_type) {
        return;
    }
    
    ?>
    <style>
        /* 产品列表表格样式 */
        .wp-list-table .column-featured_image {
            width: 80px;
            text-align: center;
        }
        
        .wp-list-table .column-featured_image img {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .wp-list-table .column-title {
            width: auto;
        }
        
        .wp-list-table .column-product_category {
            width: 20%;
        }
        
        .wp-list-table .column-date {
            width: 15%;
        }
        
        /* 添加产品按钮样式 */
        .page-title-action {
            background: #0073aa !important;
            border-color: #0073aa !important;
            color: #fff !important;
            font-weight: 600 !important;
        }
        
        .page-title-action:hover {
            background: #005a87 !important;
            border-color: #005a87 !important;
        }
    </style>
    <?php
}
add_action('admin_head-edit.php', 'angola_b2b_product_list_styles');

