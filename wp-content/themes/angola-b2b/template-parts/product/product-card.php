<?php
/**
 * Product Card Template Part
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
$featured = get_field('product_featured', $product_id);
?>

<div class="product-card <?php echo $featured ? 'is-featured' : ''; ?>" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="product-card-image">
        <a href="<?php the_permalink(); ?>">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('product-thumbnail', array('loading' => 'lazy'));
            } else {
                echo '<img src="' . esc_url(ANGOLA_B2B_THEME_URI . '/assets/images/placeholders/product-placeholder.png') . '" alt="' . esc_attr(get_the_title()) . '">';
            }
            ?>
            
            <?php if ($featured) : ?>
                <span class="featured-badge"><?php esc_html_e('Featured', 'angola-b2b'); ?></span>
            <?php endif; ?>
        </a>
    </div>
    
    <div class="product-card-content">
        <h3 class="product-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <div class="product-card-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
        </div>
        
        <div class="product-card-footer">
            <a href="<?php the_permalink(); ?>" class="btn-view-product">
                <?php esc_html_e('View Details', 'angola-b2b'); ?>
                <span class="dashicons dashicons-arrow-right-alt2"></span>
            </a>
        </div>
    </div>
</div>

