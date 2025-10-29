<?php
/**
 * Register Custom Post Types
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Product Custom Post Type
 */
function angola_b2b_register_product_post_type() {
    $labels = array(
        'name'                  => _x('产品', 'Post Type General Name', 'angola-b2b'),
        'singular_name'         => _x('产品', 'Post Type Singular Name', 'angola-b2b'),
        'menu_name'             => __('产品管理', 'angola-b2b'),
        'name_admin_bar'        => __('产品', 'angola-b2b'),
        'archives'              => __('产品列表', 'angola-b2b'),
        'attributes'            => __('产品属性', 'angola-b2b'),
        'parent_item_colon'     => __('父级产品:', 'angola-b2b'),
        'all_items'             => __('所有产品', 'angola-b2b'),
        'add_new_item'          => __('添加新产品', 'angola-b2b'),
        'add_new'               => __('添加产品', 'angola-b2b'),
        'new_item'              => __('新产品', 'angola-b2b'),
        'edit_item'             => __('编辑产品', 'angola-b2b'),
        'update_item'           => __('更新产品', 'angola-b2b'),
        'view_item'             => __('查看产品', 'angola-b2b'),
        'view_items'            => __('查看产品', 'angola-b2b'),
        'search_items'          => __('搜索产品', 'angola-b2b'),
        'not_found'             => __('未找到产品', 'angola-b2b'),
        'not_found_in_trash'    => __('回收站中未找到产品', 'angola-b2b'),
        'featured_image'        => __('产品主图', 'angola-b2b'),
        'set_featured_image'    => __('设置产品主图', 'angola-b2b'),
        'remove_featured_image' => __('移除产品主图', 'angola-b2b'),
        'use_featured_image'    => __('使用产品主图', 'angola-b2b'),
        'insert_into_item'      => __('插入到产品', 'angola-b2b'),
        'uploaded_to_this_item' => __('上传到此产品', 'angola-b2b'),
        'items_list'            => __('产品列表', 'angola-b2b'),
        'items_list_navigation' => __('产品列表导航', 'angola-b2b'),
        'filter_items_list'     => __('筛选产品列表', 'angola-b2b'),
    );

    $args = array(
        'label'               => __('产品', 'angola-b2b'),
        'description'         => __('B2B产品展示', 'angola-b2b'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'taxonomies'          => array('product_category', 'product_tag'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-products',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'rewrite'             => array(
            'slug'       => 'products',
            'with_front' => false,
        ),
    );

    register_post_type('product', $args);
}
add_action('init', 'angola_b2b_register_product_post_type', 0);

