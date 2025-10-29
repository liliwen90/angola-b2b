<?php
/**
 * Product Info Template Part
 * Includes: Title, features, dynamic animations, sticky sidebar
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
$features = get_field('feature_list', $product_id);
?>

<div class="product-info sticky-sidebar">
    
    <!-- Product Title -->
    <header class="product-header">
        <h1 class="product-title"><?php the_title(); ?></h1>
        
        <!-- Product Meta -->
        <div class="product-meta">
            <?php
            $categories = get_the_terms($product_id, 'product_category');
            if ($categories && !is_wp_error($categories)) :
                ?>
                <div class="product-categories">
                    <?php
                    foreach ($categories as $category) {
                        echo '<a href="' . esc_url(get_term_link($category)) . '" class="product-category-link">' . esc_html($category->name) . '</a>';
                    }
                    ?>
                </div>
                <?php
            endif;
            ?>
        </div>
    </header>

    <!-- Product Excerpt/Short Description -->
    <div class="product-excerpt">
        <?php the_excerpt(); ?>
    </div>

    <!-- Product Features List -->
    <?php if ($features) : ?>
        <div class="product-features">
            <h3><?php esc_html_e('Key Features', 'angola-b2b'); ?></h3>
            <ul class="features-list">
                <?php foreach ($features as $index => $feature) : ?>
                    <li class="feature-item anim-fade-in" style="animation-delay: <?php echo ($index * 0.1); ?>s;">
                        <?php if ($feature['feature_icon']) : ?>
                            <img src="<?php echo esc_url($feature['feature_icon']['url']); ?>" 
                                 alt="<?php echo esc_attr($feature['feature_icon']['alt']); ?>" 
                                 class="feature-icon">
                        <?php else : ?>
                            <span class="dashicons dashicons-yes-alt feature-icon-default"></span>
                        <?php endif; ?>
                        <div class="feature-content">
                            <strong><?php echo esc_html($feature['feature_text']); ?></strong>
                            <?php if (!empty($feature['feature_description'])) : ?>
                                <p class="feature-description"><?php echo esc_html($feature['feature_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Social Proof / Trust Signals -->
    <div class="trust-signals">
        <div class="trust-item">
            <span class="dashicons dashicons-shield-alt"></span>
            <span><?php esc_html_e('Quality Guaranteed', 'angola-b2b'); ?></span>
        </div>
        <div class="trust-item">
            <span class="dashicons dashicons-admin-site-alt3"></span>
            <span><?php esc_html_e('Global Shipping', 'angola-b2b'); ?></span>
        </div>
        <div class="trust-item">
            <span class="dashicons dashicons-yes-alt"></span>
            <span><?php esc_html_e('Certified Product', 'angola-b2b'); ?></span>
        </div>
    </div>

    <!-- Product Views Counter (with animation) -->
    <div class="product-views">
        <span class="views-icon dashicons dashicons-visibility"></span>
        <span class="views-text">
            <?php
            $views = get_post_meta($product_id, 'product_views', true);
            if (!$views) {
                $views = 0;
            }
            // Increment view count
            update_post_meta($product_id, 'product_views', ($views + 1));
            ?>
            <span class="views-count" data-count="<?php echo esc_attr($views); ?>">0</span>
            <?php esc_html_e('people viewed this product', 'angola-b2b'); ?>
        </span>
    </div>

    <!-- Call to Action Buttons -->
    <div class="product-actions">
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" 
           class="btn btn-primary btn-inquiry">
            <span class="dashicons dashicons-email-alt"></span>
            <?php esc_html_e('Request Quote', 'angola-b2b'); ?>
        </a>
        
        <?php
        $whatsapp_number = get_field('whatsapp_number', 'option');
        if ($whatsapp_number) :
            $whatsapp_message = sprintf(
                esc_html__('Hi, I\'m interested in %s', 'angola-b2b'),
                get_the_title()
            );
            $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($whatsapp_message);
            ?>
            <a href="<?php echo esc_url($whatsapp_url); ?>" 
               class="btn btn-whatsapp" 
               target="_blank" 
               rel="noopener noreferrer">
                <span class="dashicons dashicons-whatsapp"></span>
                <?php esc_html_e('WhatsApp Us', 'angola-b2b'); ?>
            </a>
            <?php
        endif;
        ?>
        
        <?php
        $technical_docs = get_field('technical_documents', $product_id);
        if ($technical_docs) :
            ?>
            <a href="<?php echo esc_url($technical_docs['url']); ?>" 
               class="btn btn-secondary btn-download" 
               download>
                <span class="dashicons dashicons-download"></span>
                <?php esc_html_e('Download Specifications', 'angola-b2b'); ?>
            </a>
            <?php
        endif;
        ?>
    </div>

    <!-- Social Share -->
    <div class="product-share">
        <h4><?php esc_html_e('Share this product:', 'angola-b2b'); ?></h4>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
               class="share-btn share-facebook" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e('Share on Facebook', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-facebook-alt"></span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
               class="share-btn share-twitter" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e('Share on Twitter', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-twitter"></span>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
               class="share-btn share-linkedin" 
               target="_blank" 
               rel="noopener noreferrer"
               aria-label="<?php esc_attr_e('Share on LinkedIn', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-linkedin"></span>
            </a>
        </div>
    </div>

</div>

