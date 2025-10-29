<?php
/**
 * The template for displaying product archives
 *
 * @package Angola_B2B
 */

get_header();
?>

<main id="primary" class="site-main product-archive">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Products', 'angola-b2b'); ?></h1>
            <?php
            if (is_tax('product_category')) {
                echo '<p class="archive-description">' . term_description() . '</p>';
            }
            ?>
        </header>

        <div class="archive-controls">
            <!-- Search Box -->
            <div class="product-search">
                <input type="text" id="product-search-input" placeholder="<?php esc_attr_e('Search products...', 'angola-b2b'); ?>">
            </div>

            <!-- Filter Options -->
            <div class="product-filters">
                <select id="product-category-filter">
                    <option value=""><?php esc_html_e('All Categories', 'angola-b2b'); ?></option>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'product_category',
                        'hide_empty' => true,
                    ));
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
                
                <select id="product-sort">
                    <option value="date-desc"><?php esc_html_e('Newest First', 'angola-b2b'); ?></option>
                    <option value="date-asc"><?php esc_html_e('Oldest First', 'angola-b2b'); ?></option>
                    <option value="title-asc"><?php esc_html_e('Name A-Z', 'angola-b2b'); ?></option>
                    <option value="title-desc"><?php esc_html_e('Name Z-A', 'angola-b2b'); ?></option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container" class="products-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/product/product-card');
                endwhile;
            else :
                ?>
                <p class="no-products"><?php esc_html_e('No products found.', 'angola-b2b'); ?></p>
                <?php
            endif;
            ?>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="loading-indicator" style="display: none;">
            <span class="spinner"></span>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => esc_html__('Previous', 'angola-b2b'),
                'next_text' => esc_html__('Next', 'angola-b2b'),
            ));
            ?>
        </div>
    </div>
</main>

<?php
get_footer();

