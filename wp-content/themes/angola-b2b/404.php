<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Angola_B2B
 */

get_header();
?>

<main id="primary" class="site-main error-404-page">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('404 - Page Not Found', 'angola-b2b'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('Sorry, the page you are looking for does not exist.', 'angola-b2b'); ?></p>
                
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>

                <div class="error-404-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Back to Homepage', 'angola-b2b'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-secondary">
                        <?php esc_html_e('View Products', 'angola-b2b'); ?>
                    </a>
                </div>

                <div class="suggested-content">
                    <h2><?php esc_html_e('Popular Products', 'angola-b2b'); ?></h2>
                    <div class="products-grid">
                        <?php
                        $popular_products = new WP_Query(array(
                            'post_type' => 'product',
                            'posts_per_page' => 4,
                            'meta_key' => 'product_views',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC'
                        ));

                        if ($popular_products->have_posts()) :
                            while ($popular_products->have_posts()) : $popular_products->the_post();
                                get_template_part('template-parts/product/product-card');
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();

