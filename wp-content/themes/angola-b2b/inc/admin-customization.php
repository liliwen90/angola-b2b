<?php
/**
 * Admin Customization
 * Customize WordPress admin interface for better Chinese UX
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set admin language to Simplified Chinese
 */
function angola_b2b_set_admin_locale($locale) {
    if (is_admin()) {
        return 'zh_CN';
    }
    return $locale;
}
add_filter('locale', 'angola_b2b_set_admin_locale');

/**
 * Add custom admin columns for Product post type
 */
function angola_b2b_product_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // Add thumbnail column after checkbox
        if ($key === 'cb') {
            $new_columns['product_thumbnail'] = __('图片', 'angola-b2b');
        }
        
        // Add category column after title
        if ($key === 'title') {
            $new_columns['product_category'] = __('分类', 'angola-b2b');
            $new_columns['product_featured'] = __('推荐', 'angola-b2b');
        }
    }
    
    return $new_columns;
}
add_filter('manage_product_posts_columns', 'angola_b2b_product_admin_columns');

/**
 * Populate custom admin columns for Product post type
 */
function angola_b2b_product_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'product_thumbnail':
            $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
            echo $thumbnail ? $thumbnail : '—';
            break;
            
        case 'product_category':
            $terms = get_the_terms($post_id, 'product_category');
            if ($terms && !is_wp_error($terms)) {
                $category_names = array();
                foreach ($terms as $term) {
                    $category_names[] = esc_html($term->name);
                }
                echo implode(', ', $category_names);
            } else {
                echo '—';
            }
            break;
            
        case 'product_featured':
            $is_featured = get_post_meta($post_id, 'product_featured', true);
            if ($is_featured === '1' || $is_featured === 1) {
                echo '<span class="dashicons dashicons-star-filled featured-icon" style="color:#f0b429" aria-label="' . esc_attr__('推荐产品', 'angola-b2b') . '"></span>';
            } else {
                echo '<span aria-hidden="true">—</span>';
            }
            break;
    }
}
add_action('manage_product_posts_custom_column', 'angola_b2b_product_admin_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function angola_b2b_product_sortable_columns($columns) {
    $columns['product_category'] = 'product_category';
    $columns['product_featured'] = 'product_featured';
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'angola_b2b_product_sortable_columns');

/**
 * Add admin notice for theme setup
 */
function angola_b2b_admin_notices() {
    $screen = get_current_screen();
    
    // Only show on product pages for new installations
    if ($screen && $screen->post_type === 'product') {
        $products_count = wp_count_posts('product');
        
        if ($products_count->publish < 1) {
            ?>
            <div class="notice notice-info">
                <p><strong><?php esc_html_e('欢迎使用Angola B2B主题！', 'angola-b2b'); ?></strong></p>
                <p><?php esc_html_e('开始添加您的第一个产品吧。确保已安装并激活ACF Pro插件以使用完整功能。', 'angola-b2b'); ?></p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'angola_b2b_admin_notices');

/**
 * Add custom dashboard widget
 */
function angola_b2b_dashboard_widget() {
    wp_add_dashboard_widget(
        'angola_b2b_dashboard_widget',
        __('Angola B2B 主题信息', 'angola-b2b'),
        'angola_b2b_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'angola_b2b_dashboard_widget');

/**
 * Dashboard widget content
 */
function angola_b2b_dashboard_widget_content() {
    $products_count = wp_count_posts('product');
    $categories_count = wp_count_terms(array('taxonomy' => 'product_category', 'hide_empty' => false));
    
    if (is_wp_error($categories_count)) {
        $categories_count = 0;
    }
    
    ?>
    <div class="angola-b2b-dashboard-widget">
        <h3><?php esc_html_e('网站统计', 'angola-b2b'); ?></h3>
        <ul>
            <li><strong><?php esc_html_e('已发布产品:', 'angola-b2b'); ?></strong> <?php echo esc_html($products_count->publish); ?></li>
            <li><strong><?php esc_html_e('草稿产品:', 'angola-b2b'); ?></strong> <?php echo esc_html($products_count->draft); ?></li>
            <li><strong><?php esc_html_e('产品分类:', 'angola-b2b'); ?></strong> <?php echo esc_html($categories_count); ?></li>
        </ul>
        
        <h3><?php esc_html_e('快速链接', 'angola-b2b'); ?></h3>
        <ul>
            <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><?php esc_html_e('添加新产品', 'angola-b2b'); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>"><?php esc_html_e('管理产品', 'angola-b2b'); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=theme-general-settings')); ?>"><?php esc_html_e('主题设置', 'angola-b2b'); ?></a></li>
        </ul>
    </div>
    <style>
        .angola-b2b-dashboard-widget ul {
            margin-left: 1.5em;
        }
        .angola-b2b-dashboard-widget h3 {
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
    </style>
    <?php
}

/**
 * Remove unnecessary dashboard widgets
 */
function angola_b2b_remove_dashboard_widgets() {
    // Remove WordPress Events and News
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    // Remove Quick Draft
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'angola_b2b_remove_dashboard_widgets');

/**
 * Customize admin footer text
 */
function angola_b2b_admin_footer_text() {
    echo '<span id="footer-thankyou">' . 
         esc_html__('Angola B2B 主题', 'angola-b2b') . 
         ' | ' . 
         esc_html__('版本', 'angola-b2b') . 
         ' ' . 
         esc_html(ANGOLA_B2B_VERSION) . 
         '</span>';
}
add_filter('admin_footer_text', 'angola_b2b_admin_footer_text');

