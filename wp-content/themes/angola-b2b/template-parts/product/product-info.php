<?php
/**
 * Template part for displaying product information
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
$product_model = get_field('product_model', $product_id);
$product_sku = get_field('product_sku', $product_id);
$min_order = get_field('min_order_quantity', $product_id);
$lead_time = get_field('lead_time', $product_id);
$certifications = get_field('certification_badges', $product_id);
$key_features = get_field('key_features', $product_id);
?>

<div class="product-info">
    
    <!-- Product Title -->
    <div class="product-header">
        <h1 class="product-title"><?php the_title(); ?></h1>
        
        <?php if (angola_b2b_is_featured_product()) : ?>
            <span class="featured-badge-large">
                <span class="dashicons dashicons-star-filled"></span>
                <?php esc_html_e('推荐产品', 'angola-b2b'); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <!-- Product Meta: intentionally removed per design simplification -->
    
    <!-- Key Features with Icons -->
    <?php if ($key_features && is_array($key_features)) : ?>
        <div class="product-key-features">
            <h3><?php esc_html_e('产品特点', 'angola-b2b'); ?></h3>
            <ul class="features-list">
                <?php foreach ($key_features as $feature) : 
                    if (!empty($feature['feature_text'])) :
                        $feature_icon = !empty($feature['feature_icon']) && is_array($feature['feature_icon']) ? $feature['feature_icon'] : null;
                ?>
                    <li class="feature-item">
                        <?php if ($feature_icon && !empty($feature_icon['url'])) : 
                            $icon_alt = !empty($feature_icon['alt']) ? $feature_icon['alt'] : __('Feature icon', 'angola-b2b');
                        ?>
                            <img src="<?php echo esc_url($feature_icon['url']); ?>" 
                                 alt="<?php echo esc_attr($icon_alt); ?>" 
                                 class="feature-icon">
                        <?php else : ?>
                            <span class="dashicons dashicons-yes feature-icon-default"></span>
                        <?php endif; ?>
                        <span class="feature-text"><?php echo esc_html($feature['feature_text']); ?></span>
                    </li>
                <?php 
                    endif;
                endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Product Specifications Highlights -->
    <?php
    $highlight_specs = get_field('highlight_specifications', $product_id);
    if ($highlight_specs && is_array($highlight_specs)) :
    ?>
        <div class="product-spec-highlights">
            <?php foreach ($highlight_specs as $spec) : 
                if (!empty($spec['spec_value']) && !empty($spec['spec_label'])) :
            ?>
                <div class="spec-highlight-item">
                    <div class="spec-value-animated">
                        <?php
                        // Check if numeric for animation
                        $value = $spec['spec_value'];
                        if (is_numeric($value)) {
                            echo angola_b2b_animated_number($value, $spec['spec_unit'] ?? '');
                        } else {
                            echo esc_html($value);
                            if (!empty($spec['spec_unit'])) {
                                echo ' ' . esc_html($spec['spec_unit']);
                            }
                        }
                        ?>
                    </div>
                    <div class="spec-label"><?php echo esc_html($spec['spec_label']); ?></div>
                </div>
            <?php 
                endif;
            endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Order Information -->
    <div class="product-order-info">
        <?php if ($min_order) : ?>
            <div class="order-info-item">
                <span class="dashicons dashicons-cart"></span>
                <span class="info-label"><?php esc_html_e('最小订购量:', 'angola-b2b'); ?></span>
                <span class="info-value"><?php echo esc_html($min_order); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($lead_time) : ?>
            <div class="order-info-item">
                <span class="dashicons dashicons-clock"></span>
                <span class="info-label"><?php esc_html_e('交货时间:', 'angola-b2b'); ?></span>
                <span class="info-value"><?php echo esc_html($lead_time); ?></span>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Certifications -->
    <?php if ($certifications && is_array($certifications)) : ?>
        <div class="product-certifications">
            <h4><?php esc_html_e('认证证书', 'angola-b2b'); ?></h4>
            <div class="certifications-badges">
                <?php foreach ($certifications as $cert) : 
                    if (is_array($cert) && !empty($cert['url'])) :
                        $cert_alt = !empty($cert['alt']) ? $cert['alt'] : __('Certification', 'angola-b2b');
                ?>
                    <img src="<?php echo esc_url($cert['url']); ?>" 
                         alt="<?php echo esc_attr($cert_alt); ?>" 
                         class="cert-badge"
                         loading="lazy">
                <?php 
                    endif;
                endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- CTA Buttons and quick actions removed per design simplification -->
    
</div>
