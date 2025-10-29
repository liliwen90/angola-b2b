<?php
/**
 * Register Custom Taxonomies
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Product Category Taxonomy (Hierarchical)
 */
function angola_b2b_register_product_category_taxonomy() {
    $labels = array(
        'name'                       => _x('产品分类', 'Taxonomy General Name', 'angola-b2b'),
        'singular_name'              => _x('产品分类', 'Taxonomy Singular Name', 'angola-b2b'),
        'menu_name'                  => __('产品分类', 'angola-b2b'),
        'all_items'                  => __('所有分类', 'angola-b2b'),
        'parent_item'                => __('父级分类', 'angola-b2b'),
        'parent_item_colon'          => __('父级分类:', 'angola-b2b'),
        'new_item_name'              => __('新分类名称', 'angola-b2b'),
        'add_new_item'               => __('添加新分类', 'angola-b2b'),
        'edit_item'                  => __('编辑分类', 'angola-b2b'),
        'update_item'                => __('更新分类', 'angola-b2b'),
        'view_item'                  => __('查看分类', 'angola-b2b'),
        'separate_items_with_commas' => __('用逗号分隔分类', 'angola-b2b'),
        'add_or_remove_items'        => __('添加或移除分类', 'angola-b2b'),
        'choose_from_most_used'      => __('从常用分类中选择', 'angola-b2b'),
        'popular_items'              => __('热门分类', 'angola-b2b'),
        'search_items'               => __('搜索分类', 'angola-b2b'),
        'not_found'                  => __('未找到分类', 'angola-b2b'),
        'no_terms'                   => __('没有分类', 'angola-b2b'),
        'items_list'                 => __('分类列表', 'angola-b2b'),
        'items_list_navigation'      => __('分类列表导航', 'angola-b2b'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'         => 'product-category',
            'with_front'   => false,
            'hierarchical' => true,
        ),
    );

    register_taxonomy('product_category', array('product'), $args);
}
add_action('init', 'angola_b2b_register_product_category_taxonomy', 0);

/**
 * Register Product Tag Taxonomy (Non-hierarchical)
 */
function angola_b2b_register_product_tag_taxonomy() {
    $labels = array(
        'name'                       => _x('产品标签', 'Taxonomy General Name', 'angola-b2b'),
        'singular_name'              => _x('产品标签', 'Taxonomy Singular Name', 'angola-b2b'),
        'menu_name'                  => __('产品标签', 'angola-b2b'),
        'all_items'                  => __('所有标签', 'angola-b2b'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => __('新标签名称', 'angola-b2b'),
        'add_new_item'               => __('添加新标签', 'angola-b2b'),
        'edit_item'                  => __('编辑标签', 'angola-b2b'),
        'update_item'                => __('更新标签', 'angola-b2b'),
        'view_item'                  => __('查看标签', 'angola-b2b'),
        'separate_items_with_commas' => __('用逗号分隔标签', 'angola-b2b'),
        'add_or_remove_items'        => __('添加或移除标签', 'angola-b2b'),
        'choose_from_most_used'      => __('从常用标签中选择', 'angola-b2b'),
        'popular_items'              => __('热门标签', 'angola-b2b'),
        'search_items'               => __('搜索标签', 'angola-b2b'),
        'not_found'                  => __('未找到标签', 'angola-b2b'),
        'no_terms'                   => __('没有标签', 'angola-b2b'),
        'items_list'                 => __('标签列表', 'angola-b2b'),
        'items_list_navigation'      => __('标签列表导航', 'angola-b2b'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'       => 'product-tag',
            'with_front' => false,
        ),
    );

    register_taxonomy('product_tag', array('product'), $args);
}
add_action('init', 'angola_b2b_register_product_tag_taxonomy', 0);

