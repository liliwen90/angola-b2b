<?php
/**
 * Homepage Stock Products Section
 * 首页热门库存产品区域
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// 检查是否启用库存产品区域
$enable_stock_section = get_field('enable_stock_products_section', 45);
if ($enable_stock_section === false || $enable_stock_section === '0') {
    return; // 不显示
}

// 查询库存产品
$stock_products = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'meta_query' => array(
        array(
            'key' => 'product_in_stock',
            'value' => '1',
            'compare' => '='
        )
    ),
    'orderby' => 'date',
    'order' => 'DESC'
));

// 如果没有库存产品，不显示区域
if (!$stock_products->have_posts()) {
    wp_reset_postdata();
    return;
}
?>

<section id="stock-products" class="stock-products-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <?php 
                $stock_title = get_field('stock_products_title', 45);
                echo esc_html($stock_title ? $stock_title : __('现货供应 - 即刻发货', 'angola-b2b')); 
                ?>
            </h2>
            <p class="section-subtitle">
                <?php 
                $stock_subtitle = get_field('stock_products_subtitle', 45);
                echo esc_html($stock_subtitle ? $stock_subtitle : __('本地库存，即刻发货', 'angola-b2b')); 
                ?>
            </p>
        </div>
        
        <div class="products-grid">
            <?php
            while ($stock_products->have_posts()) : $stock_products->the_post();
                // 使用库存产品卡片模板
                get_template_part('template-parts/product/product-card', 'stock');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

