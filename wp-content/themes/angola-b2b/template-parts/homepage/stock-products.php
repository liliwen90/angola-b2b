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
    'posts_per_page' => 20, // 显示所有库存产品
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
                if ($stock_title) {
                    echo esc_html($stock_title);
                } elseif (function_exists('pll__')) {
                    echo esc_html(pll__('现货供应 - 即刻发货'));
                } else {
                    esc_html_e('现货供应 - 即刻发货', 'angola-b2b');
                }
                ?>
            </h2>
            <p class="section-subtitle">
                <?php 
                $stock_subtitle = get_field('stock_products_subtitle', 45);
                if ($stock_subtitle) {
                    echo esc_html($stock_subtitle);
                } elseif (function_exists('pll__')) {
                    echo esc_html(pll__('本地库存，即刻发货'));
                } else {
                    esc_html_e('本地库存，即刻发货', 'angola-b2b');
                }
                ?>
            </p>
        </div>
        
        <!-- Swiper容器 -->
        <div class="stock-products-slider-wrapper">
            <div class="swiper stock-products-swiper">
                <div class="swiper-wrapper">
                    <?php
                    while ($stock_products->have_posts()) : $stock_products->the_post();
                        echo '<div class="swiper-slide">';
                        // 使用库存产品卡片模板
                        get_template_part('template-parts/product/product-card', 'stock');
                        echo '</div>';
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            
            <!-- 导航按钮 -->
            <div class="swiper-button-prev stock-swiper-prev">
                <span class="nav-arrow">‹</span>
                <span class="nav-text">
                    <?php 
                    if (function_exists('pll__')) {
                        echo esc_html(pll__('查看更多'));
                    } else {
                        esc_html_e('查看更多', 'angola-b2b');
                    }
                    ?>
                </span>
            </div>
            <div class="swiper-button-next stock-swiper-next">
                <span class="nav-text">
                    <?php 
                    if (function_exists('pll__')) {
                        echo esc_html(pll__('查看更多'));
                    } else {
                        esc_html_e('查看更多', 'angola-b2b');
                    }
                    ?>
                </span>
                <span class="nav-arrow">›</span>
            </div>
        </div>
    </div>
</section>

