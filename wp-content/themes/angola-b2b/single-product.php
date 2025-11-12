<?php
/**
 * The template for displaying single product pages
 * Features: Product gallery, specifications, certifications, customer cases
 *
 * @package Angola_B2B
 */

// Debug output (only visible when WP_DEBUG is true)
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- SINGLE-PRODUCT.PHP IS RUNNING -->';
}

get_header();

while (have_posts()) :
    the_post();
    $product_id = get_the_ID();

    // Language gating: only show page if current language content exists for this product
    if (!function_exists('angola_b2b_get_current_language')) {
        function angola_b2b_get_current_language() {
            return 'en';
        }
    }
    $current_lang = angola_b2b_get_current_language();
    $lang_title_field = 'title_' . $current_lang;
    $lang_content_field = 'content_' . $current_lang;
    $lang_title_value = function_exists('get_field') ? get_field($lang_title_field, $product_id) : '';
    $lang_content_value = function_exists('get_field') ? get_field($lang_content_field, $product_id) : '';

    // If both title and content for the current language are empty, respond with 404
    if (empty($lang_title_value) && empty($lang_content_value)) {
        status_header(404);
        nocache_headers();
        include get_query_template('404');
        exit;
    }
    ?>

    <main id="primary" class="site-main product-single">
        <div class="container">
            <?php angola_b2b_display_breadcrumbs(); ?>
            
            <!-- Product Title -->
            <h1 class="product-title-main">
                <?php 
                // Display title from ACF field based on current language
                $title_field = 'title_' . $current_lang;
                $product_title = function_exists('get_field') ? get_field($title_field, $product_id) : '';
                
                // Fallback to WordPress post title if ACF field is empty
                if (empty($product_title)) {
                    $product_title = get_the_title();
                }
                
                echo esc_html($product_title);
                ?>
            </h1>
            
            <div class="product-layout">
                
                <!-- Product Gallery Section -->
                <div class="product-gallery-wrapper">
                    <?php 
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        echo '<!-- BEFORE get_template_part for product-gallery -->';
                        echo '<!-- Template path: ' . esc_html(get_template_directory()) . '/template-parts/product/product-gallery.php -->';
                        echo '<!-- File exists: ' . (file_exists(get_template_directory() . '/template-parts/product/product-gallery.php') ? 'YES' : 'NO') . ' -->';
                    }
                    
                    get_template_part('template-parts/product/product-gallery'); 
                    
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        echo '<!-- AFTER get_template_part for product-gallery -->';
                    }
                    ?>
                </div>

                <!-- Product Info Section -->
                <div class="product-info-wrapper">
                    <?php get_template_part('template-parts/product/product-info'); ?>
                </div>

            </div>

            <!-- Product Description Tabs -->
            <div class="product-tabs">
                <div class="tabs-content">
                    <!-- Description Tab -->
                    <div class="tab-pane active" id="tab-description">
                        <div class="product-description">
                            <?php 
                            // Get current language content only (no cross-language fallback)
                            $content_field = 'content_' . $current_lang;
                            $product_content = function_exists('get_field') ? get_field($content_field, $product_id) : '';

                            // Render using the_content filters for embeds/shortcodes
                            if (!empty($product_content)) {
                                echo apply_filters('the_content', $product_content);
                            } else {
                                // When empty, show nothing; language gating above should already 404.
                            }
                            ?>
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tab-pane" id="tab-specifications">
                        <?php
                        // Get specifications using helper function (spec_name_1 to spec_name_8 fields)
                        $specifications = angola_b2b_get_specifications($product_id);
                        
                        if (!empty($specifications)) :
                            ?>
                            <table class="specifications-table">
                                <thead>
                                    <tr>
                                        <th><?php _et('specification'); ?></th>
                                        <th><?php _et('value'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($specifications as $spec) : ?>
                                        <tr class="spec-row">
                                            <td><?php echo esc_html($spec['name']); ?></td>
                                            <td><strong><?php echo esc_html($spec['value']); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php
                        else :
                            ?>
                            <p><?php _et('no_specifications'); ?></p>
                            <?php
                        endif;
                        ?>

                        <!-- Product Advantages Comparison -->
                        <?php
                        if (have_rows('product_advantages')) :
                            ?>
                            <h3><?php _et('why_choose_this_product'); ?></h3>
                            <table class="advantages-comparison-table">
                                <thead>
                                    <tr>
                                        <th><?php _et('feature'); ?></th>
                                        <th><?php _et('our_product'); ?></th>
                                        <th><?php _et('regular_product'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while (have_rows('product_advantages')) : the_row();
                                        ?>
                                        <tr class="advantage-row anim-fade-in">
                                            <td><?php echo esc_html(get_sub_field('advantage_item')); ?></td>
                                            <td class="our-value"><strong class="highlight">✓ <?php echo esc_html(get_sub_field('our_value')); ?></strong></td>
                                            <td class="competitor-value">✗ <?php echo esc_html(get_sub_field('competitor_value')); ?></td>
                                        </tr>
                                        <?php
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        endif;
                        ?>
                    </div>

                    <!-- Certifications Tab -->
                    <div class="tab-pane" id="tab-certifications">
                        <?php
                        $certifications = get_field('certification_badges');
                        if ($certifications) :
                            ?>
                            <div class="certifications-grid">
                                <?php foreach ($certifications as $cert) : 
                                    if (is_array($cert) && !empty($cert['url'])) :
                                        $cert_alt = !empty($cert['alt']) ? $cert['alt'] : __('Certification Badge', 'angola-b2b');
                                ?>
                                    <div class="certification-item">
                                        <img src="<?php echo esc_url($cert['url']); ?>" alt="<?php echo esc_attr($cert_alt); ?>">
                                    </div>
                                <?php 
                                    endif;
                                endforeach; ?>
                            </div>
                            <?php
                        endif;

                        $technical_docs = get_field('technical_documents');
                        if ($technical_docs) :
                            ?>
                            <div class="technical-documents">
                                <h3><?php _et('technical_documents'); ?></h3>
                                <a href="<?php echo esc_url($technical_docs['url']); ?>" class="btn-download" download>
                                    <span class="dashicons dashicons-download"></span>
                                    <?php _et('download_technical_specs'); ?>
                                </a>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>

                    <!-- Customer Cases Tab -->
                    <div class="tab-pane" id="tab-cases">
                        <?php
                        if (have_rows('customer_cases')) :
                            ?>
                            <div class="customer-cases">
                                <?php
                                while (have_rows('customer_cases')) : the_row();
                                    $case_title = get_sub_field('case_title');
                                    $case_description = get_sub_field('case_description');
                                    $case_image = get_sub_field('case_image');
                                    $case_video = get_sub_field('case_video');
                                    $customer_logo = get_sub_field('customer_logo');
                                    ?>
                                    <div class="customer-case-item">
                                        <?php if ($case_image && is_array($case_image) && !empty($case_image['url'])) : 
                                            $case_image_alt = !empty($case_image['alt']) ? $case_image['alt'] : $case_title;
                                        ?>
                                            <div class="case-image">
                                                <img src="<?php echo esc_url($case_image['url']); ?>" alt="<?php echo esc_attr($case_image_alt); ?>">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="case-content">
                                            <h3><?php echo esc_html($case_title); ?></h3>
                                            <p><?php echo esc_html($case_description); ?></p>
                                            
                                            <?php if ($customer_logo && is_array($customer_logo) && !empty($customer_logo['url'])) : 
                                                $customer_logo_alt = !empty($customer_logo['alt']) ? $customer_logo['alt'] : __('Customer Logo', 'angola-b2b');
                                            ?>
                                                <div class="customer-logo">
                                                    <img src="<?php echo esc_url($customer_logo['url']); ?>" alt="<?php echo esc_attr($customer_logo_alt); ?>">
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($case_video) : ?>
                                                <div class="case-video">
                                                    <a href="<?php echo esc_url($case_video); ?>" class="video-play-button">
                                                        <span class="dashicons dashicons-controls-play"></span>
                                                        <?php _et('watch_video'); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;
                                ?>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Inquiry Section at Bottom -->
            <div id="product-inquiry" class="product-inquiry-section" style="margin-top: 48px;">
                <?php echo angola_b2b_inquiry_form($product_id); ?>
            </div>

            <!-- Related Products -->
            <div class="related-products-section">
                <h2><?php _et('related_products'); ?></h2>
                <div class="related-products-slider">
                    <?php
                    // Get related products from same category
                    $current_cats = wp_get_post_terms($product_id, 'product_category', array('fields' => 'ids'));
                    
                    $related_args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 6,
                        'post__not_in' => array($product_id),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_category',
                                'field' => 'term_id',
                                'terms' => $current_cats,
                            ),
                        ),
                    );

                    $related_products = new WP_Query($related_args);

                    if ($related_products->have_posts()) :
                        while ($related_products->have_posts()) : $related_products->the_post();
                            get_template_part('template-parts/product/product-card');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </div>

        </div>
    </main>

    <?php
endwhile;

get_footer();

