<?php
/**
 * The template for displaying product archives
 * MSC-style category archive page
 *
 * @package Angola_B2B
 */

get_header();
?>

<main id="primary" class="site-main product-archive">
    <div class="container">
        <?php 
        // Temporary debug info - remove after testing
        if (current_user_can('manage_options') && isset($_GET['debug'])) {
            echo '<div style="position: fixed; top: 100px; right: 20px; background: #0073aa; color: white; padding: 15px; border-radius: 8px; z-index: 9999; font-size: 14px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">';
            echo '<strong>调试信息：</strong><br>';
            echo '<span id="screen-width-debug">屏幕宽度: <span id="current-width"></span>px</span><br>';
            echo '<span id="columns-debug">应该显示: <span id="expected-columns"></span>列</span>';
            echo '<script>
                function updateDebugInfo() {
                    var width = window.innerWidth;
                    document.getElementById("current-width").textContent = width;
                    var columns = 1;
                    if (width >= 1280) columns = 4;
                    else if (width >= 1024) columns = 3;
                    else if (width >= 640) columns = 2;
                    document.getElementById("expected-columns").textContent = columns;
                    document.getElementById("expected-columns").style.color = columns === 4 ? "#90EE90" : "#FFD700";
                }
                updateDebugInfo();
                window.addEventListener("resize", updateDebugInfo);
            </script>';
            echo '</div>';
        }
        ?>
        <?php angola_b2b_display_breadcrumbs(); ?>
        
        <?php if (is_tax('product_category')) : 
            $term = get_queried_object();
        ?>
            <!-- Tab Navigation -->
            <?php angola_b2b_display_tab_navigation(); ?>
            
            <!-- Overview Section -->
            <section id="overview" class="archive-content-section">
                <?php
                // Get category description
                $category_description = term_description($term->term_id, 'product_category');
                if ($category_description || function_exists('get_field')) {
                    // Try to get custom content from ACF
                    $overview_title = function_exists('get_field') ? get_field('overview_title', $term) : '';
                    $overview_content = function_exists('get_field') ? get_field('overview_content', $term) : '';
                    
                    if ($overview_title || $overview_content || $category_description) {
                        angola_b2b_display_title_description(array(
                            'title' => $overview_title ?: $term->name,
                            'description' => $overview_content ?: $category_description,
                            'alignment' => 'left',
                            'size' => 'medium',
                        ));
                    }
                }
                ?>
            </section>
            
            <!-- Advantages Section -->
            <?php
            if (function_exists('get_field')) {
                $advantages = get_field('advantages_list', $term);
                if ($advantages && is_array($advantages) && !empty($advantages)) {
                    ?>
                    <section id="advantages" class="archive-content-section">
                        <?php
                        angola_b2b_display_advantages_list(array(
                            'title' => pll__('Why Choose Us', 'angola-b2b') ?: __('Why Choose Us', 'angola-b2b'),
                            'advantages' => $advantages,
                            'layout' => 'grid',
                            'columns' => 3,
                        ));
                        ?>
                    </section>
                    <?php
                }
            }
            ?>
            
            <!-- Image + Text Sections (if configured via ACF) -->
            <?php
            if (function_exists('get_field')) {
                $content_blocks = get_field('content_blocks', $term);
                if ($content_blocks && is_array($content_blocks)) {
                    foreach ($content_blocks as $block) {
                        if ($block['acf_fc_layout'] === 'image_text') {
                            $image = isset($block['image']) ? $block['image'] : '';
                            $image_url = '';
                            if (is_array($image) && isset($image['url'])) {
                                $image_url = $image['url'];
                            } elseif (is_numeric($image)) {
                                $image_url = wp_get_attachment_image_url($image, 'large');
                            } elseif (is_string($image)) {
                                $image_url = $image;
                            }
                            
                            if ($image_url || $block['title'] || $block['content']) {
                                ?>
                                <section class="archive-content-section">
                                    <?php
                                    angola_b2b_display_image_text(array(
                                        'image' => $image_url,
                                        'image_position' => isset($block['image_position']) ? $block['image_position'] : 'left',
                                        'title' => isset($block['title']) ? $block['title'] : '',
                                        'content' => isset($block['content']) ? $block['content'] : '',
                                        'cta' => isset($block['cta']) ? $block['cta'] : array(),
                                    ));
                                    ?>
                                </section>
                                <?php
                            }
                        }
                    }
                }
            }
            ?>
            
            <!-- Products Section -->
            <section id="products" class="archive-content-section">
                <div class="section-header">
                    <h2 class="section-title"><?php echo esc_html($term->name); ?></h2>
                </div>
                
                <!-- Products Grid -->
                <div class="products-grid">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();
                            get_template_part('template-parts/product/product-card');
                        endwhile;
                    else :
                        ?>
                        <p class="no-products"><?php esc_html_e('No products found in this category.', 'angola-b2b'); ?></p>
                        <?php
                    endif;
                    ?>
                </div>
                
                <!-- Pagination -->
                <?php if (paginate_links()) : ?>
                    <div class="pagination">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => esc_html__('Previous', 'angola-b2b'),
                            'next_text' => esc_html__('Next', 'angola-b2b'),
                        ));
                        ?>
                    </div>
                <?php endif; ?>
            </section>
            
        <?php else : ?>
            <!-- Product Archive (not category) -->
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Products', 'angola-b2b'); ?></h1>
            </header>

            <!-- Products Grid -->
            <div class="products-grid">
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

            <!-- Pagination -->
            <?php if (paginate_links()) : ?>
                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => esc_html__('Previous', 'angola-b2b'),
                        'next_text' => esc_html__('Next', 'angola-b2b'),
                    ));
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
