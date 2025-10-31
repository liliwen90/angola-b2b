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
        <h2 class="section-title"><?php esc_html_e('Featured Products', 'angola-b2b'); ?></h2>
        <div class="products-grid">
            <?php
            $featured_products = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => 8,
                'meta_query' => array(
                    array(
                        'key' => 'product_featured',
                        'value' => '1',
                        'compare' => '='
                    )
                )
            ));

            if ($featured_products->have_posts()) :
                while ($featured_products->have_posts()) : $featured_products->the_post();
                    get_template_part('template-parts/product/product-card');
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p class="no-products-message">
                    <?php esc_html_e('No featured products at the moment.', 'angola-b2b'); ?>
                </p>
                <?php
            endif;
            ?>
        </div>
        <div class="section-cta">
            <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-primary">
                <?php esc_html_e('View All Products', 'angola-b2b'); ?>
            </a>
        </div>
    </div>
</section>

