<?php
/**
 * The template for displaying single product pages
 * Features: 360° rotation, hot spots, comparison slider, dynamic animations
 *
 * @package Angola_B2B
 */

get_header();

while (have_posts()) :
    the_post();
    $product_id = get_the_ID();
    ?>

    <main id="primary" class="site-main product-single">
        <div class="container">
            <div class="product-layout">
                
                <!-- Product Gallery Section -->
                <div class="product-gallery-wrapper">
                    <?php get_template_part('template-parts/product/product-gallery'); ?>
                </div>

                <!-- Product Info Section -->
                <div class="product-info-wrapper">
                    <?php get_template_part('template-parts/product/product-info'); ?>
                </div>

            </div>

            <!-- Product Description Tabs -->
            <div class="product-tabs">
                <div class="tabs-nav">
                    <button class="tab-button active" data-tab="description"><?php esc_html_e('Description', 'angola-b2b'); ?></button>
                    <button class="tab-button" data-tab="specifications"><?php esc_html_e('Specifications', 'angola-b2b'); ?></button>
                    <button class="tab-button" data-tab="certifications"><?php esc_html_e('Certifications', 'angola-b2b'); ?></button>
                    <button class="tab-button" data-tab="cases"><?php esc_html_e('Customer Cases', 'angola-b2b'); ?></button>
                </div>

                <div class="tabs-content">
                    <!-- Description Tab -->
                    <div class="tab-pane active" id="tab-description">
                        <div class="product-description">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tab-pane" id="tab-specifications">
                        <?php
                        if (have_rows('specifications')) :
                            ?>
                            <table class="specifications-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Specification', 'angola-b2b'); ?></th>
                                        <th><?php esc_html_e('Value', 'angola-b2b'); ?></th>
                                        <th><?php esc_html_e('Performance Level', 'angola-b2b'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while (have_rows('specifications')) : the_row();
                                        $spec_name = get_sub_field('spec_name');
                                        $spec_value = get_sub_field('spec_value');
                                        $spec_icon = get_sub_field('spec_icon');
                                        $spec_level = get_sub_field('spec_level');
                                        ?>
                                        <tr class="spec-row">
                                            <td>
                                                <?php if ($spec_icon && is_array($spec_icon) && !empty($spec_icon['url'])) : ?>
                                                    <img src="<?php echo esc_url($spec_icon['url']); ?>" alt="" class="spec-icon">
                                                <?php endif; ?>
                                                <?php echo esc_html($spec_name); ?>
                                            </td>
                                            <td><strong><?php echo esc_html($spec_value); ?></strong></td>
                                            <td>
                                                <?php if ($spec_level) : ?>
                                                    <div class="spec-level-bar">
                                                        <div class="spec-level-fill" data-level="<?php echo esc_attr($spec_level); ?>" style="width: 0%"></div>
                                                        <span class="spec-level-text"><?php echo esc_html($spec_level); ?>%</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    endwhile;
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        endif;
                        ?>

                        <!-- Product Advantages Comparison -->
                        <?php
                        if (have_rows('product_advantages')) :
                            ?>
                            <h3><?php esc_html_e('Why Choose This Product', 'angola-b2b'); ?></h3>
                            <table class="advantages-comparison-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Feature', 'angola-b2b'); ?></th>
                                        <th><?php esc_html_e('Our Product', 'angola-b2b'); ?></th>
                                        <th><?php esc_html_e('Regular Product', 'angola-b2b'); ?></th>
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
                                <h3><?php esc_html_e('Technical Documents', 'angola-b2b'); ?></h3>
                                <a href="<?php echo esc_url($technical_docs['url']); ?>" class="btn-download" download>
                                    <span class="dashicons dashicons-download"></span>
                                    <?php esc_html_e('Download Technical Specifications', 'angola-b2b'); ?>
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
                                                        <?php esc_html_e('Watch Video', 'angola-b2b'); ?>
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

            <!-- Related Products -->
            <div class="related-products-section">
                <h2><?php esc_html_e('Related Products', 'angola-b2b'); ?></h2>
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

