<?php
/**
 * Product Gallery Template Part
 * Includes: Regular gallery, 360° rotation, image hotspots, comparison slider
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
$gallery = get_field('product_gallery', $product_id);
$images_360 = get_field('product_360_images', $product_id);
$comparison = get_field('comparison_slider', $product_id);
$hotspots = get_field('hotspot_annotations', $product_id);
?>

<div class="product-gallery">
    
    <!-- Main Image Display -->
    <div class="gallery-main-image">
        <div class="main-image-container">
            <?php
            if ($gallery && !empty($gallery)) :
                ?>
                <img src="<?php echo esc_url($gallery[0]['sizes']['product-large']); ?>" 
                     alt="<?php echo esc_attr(get_the_title()); ?>" 
                     class="main-image" 
                     id="main-product-image"
                     data-zoom="<?php echo esc_url($gallery[0]['url']); ?>">
                <?php
            elseif (has_post_thumbnail()) :
                ?>
                <img src="<?php echo esc_url(get_the_post_thumbnail_url($product_id, 'product-large')); ?>" 
                     alt="<?php echo esc_attr(get_the_title()); ?>" 
                     class="main-image" 
                     id="main-product-image">
                <?php
            endif;
            ?>
            
            <!-- Image Hotspots -->
            <?php if ($hotspots) : ?>
                <div class="image-hotspots">
                    <?php foreach ($hotspots as $index => $hotspot) : ?>
                        <button class="hotspot-marker" 
                                style="left: <?php echo esc_attr($hotspot['hotspot_x']); ?>%; top: <?php echo esc_attr($hotspot['hotspot_y']); ?>%;" 
                                data-hotspot-index="<?php echo esc_attr($index); ?>">
                            <span class="hotspot-pulse"></span>
                            <span class="hotspot-number"><?php echo ($index + 1); ?></span>
                        </button>
                        
                        <div class="hotspot-tooltip" id="hotspot-tooltip-<?php echo esc_attr($index); ?>" style="display: none;">
                            <h4><?php echo esc_html($hotspot['hotspot_title']); ?></h4>
                            <p><?php echo esc_html($hotspot['hotspot_content']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Zoom Icon -->
            <button class="zoom-button" aria-label="<?php esc_attr_e('Zoom image', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-search"></span>
            </button>
        </div>
    </div>

    <!-- Thumbnail Gallery -->
    <?php if ($gallery && count($gallery) > 1) : ?>
        <div class="gallery-thumbnails">
            <div class="thumbnails-container">
                <?php foreach ($gallery as $index => $image) : ?>
                    <button class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                            data-image-index="<?php echo esc_attr($index); ?>"
                            data-full-url="<?php echo esc_url($image['sizes']['product-large']); ?>"
                            data-zoom-url="<?php echo esc_url($image['url']); ?>">
                        <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>" 
                             alt="<?php echo esc_attr($image['alt']); ?>" 
                             loading="lazy">
                    </button>
                <?php endforeach; ?>
                
                <!-- 360° Button -->
                <?php if ($images_360 && count($images_360) > 10) : ?>
                    <button class="thumbnail-item thumbnail-360" data-action="show-360">
                        <span class="icon-360">360°</span>
                        <span class="thumbnail-label"><?php esc_html_e('Rotate View', 'angola-b2b'); ?></span>
                    </button>
                <?php endif; ?>
                
                <!-- Comparison Button -->
                <?php if ($comparison && $comparison['enable_comparison']) : ?>
                    <button class="thumbnail-item thumbnail-comparison" data-action="show-comparison">
                        <span class="dashicons dashicons-image-flip-horizontal"></span>
                        <span class="thumbnail-label"><?php esc_html_e('Compare', 'angola-b2b'); ?></span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- 360° Rotation Container (Hidden by default) -->
    <?php if ($images_360 && count($images_360) > 10) : ?>
        <div class="rotation-360-container" style="display: none;">
            <div class="rotation-360-canvas" data-frame-count="<?php echo count($images_360); ?>">
                <?php foreach ($images_360 as $index => $image) : ?>
                    <img src="<?php echo esc_url($image['sizes']['product-360']); ?>" 
                         alt="360° view frame <?php echo ($index + 1); ?>" 
                         class="rotation-frame <?php echo $index === 0 ? 'active' : ''; ?>"
                         data-frame="<?php echo esc_attr($index); ?>"
                         loading="lazy">
                <?php endforeach; ?>
            </div>
            <div class="rotation-controls">
                <p class="rotation-hint"><?php esc_html_e('Drag to rotate', 'angola-b2b'); ?></p>
                <button class="rotation-close"><?php esc_html_e('Close', 'angola-b2b'); ?></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Comparison Slider Container (Hidden by default) -->
    <?php if ($comparison && $comparison['enable_comparison']) : ?>
        <div class="comparison-slider-container" style="display: none;">
            <div class="comparison-slider">
                <div class="comparison-before">
                    <img src="<?php echo esc_url($comparison['before_image']['url']); ?>" 
                         alt="<?php echo esc_attr($comparison['comparison_label_before']); ?>">
                    <span class="comparison-label label-before"><?php echo esc_html($comparison['comparison_label_before']); ?></span>
                </div>
                <div class="comparison-after">
                    <img src="<?php echo esc_url($comparison['after_image']['url']); ?>" 
                         alt="<?php echo esc_attr($comparison['comparison_label_after']); ?>">
                    <span class="comparison-label label-after"><?php echo esc_html($comparison['comparison_label_after']); ?></span>
                </div>
                <div class="comparison-handle">
                    <span class="handle-arrow handle-arrow-left"></span>
                    <span class="handle-arrow handle-arrow-right"></span>
                </div>
            </div>
            <button class="comparison-close"><?php esc_html_e('Close', 'angola-b2b'); ?></button>
        </div>
    <?php endif; ?>

</div>

