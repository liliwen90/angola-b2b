<?php
/**
 * Homepage Featured Products Section
 * 首页精选产品区域
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="featured-products">
    <div class="container">
        <h2 class="section-title">
            <?php 
            if (function_exists('pll__')) {
                echo esc_html(pll__('Featured Products'));
            } else {
                esc_html_e('Featured Products', 'angola-b2b');
            }
            ?>
        </h2>
        <!-- Swiper容器 -->
        <div class="featured-products-slider-wrapper">
            <?php
            $featured_products = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => 20, // 显示所有精选产品
                'meta_query' => array(
                    array(
                        'key' => 'product_featured',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));

            if ($featured_products->have_posts()) :
                ?>
                <div class="swiper featured-products-swiper">
                    <div class="swiper-wrapper">
                        <?php
                        while ($featured_products->have_posts()) : $featured_products->the_post();
                            echo '<div class="swiper-slide">';
                            get_template_part('template-parts/product/product-card');
                            echo '</div>';
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                
                <!-- 导航按钮 -->
                <div class="swiper-button-prev featured-swiper-prev">
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
                <div class="swiper-button-next featured-swiper-next">
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
                <?php
            else :
                ?>
                <p class="no-products-message">
                    <?php 
                    if (function_exists('pll__')) {
                        echo esc_html(pll__('No featured products at the moment.'));
                    } else {
                        esc_html_e('No featured products at the moment.', 'angola-b2b');
                    }
                    ?>
                </p>
                <?php
            endif;
            ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-primary">
                <?php 
                if (function_exists('pll__')) {
                    echo esc_html(pll__('View All Products'));
                } else {
                    esc_html_e('View All Products', 'angola-b2b');
                }
                ?>
            </a>
        </div>
    </div>
</section>

