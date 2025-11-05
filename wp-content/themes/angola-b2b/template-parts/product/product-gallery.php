<?php
/**
 * Template part for displaying product gallery
 * Features: Image lightbox, 360° rotation, comparison slider, hot spots
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
$featured_image = get_the_post_thumbnail_url($product_id, 'product-large');
$gallery_images = angola_b2b_get_gallery_images($product_id);
$images_360 = angola_b2b_get_360_images($product_id); // Deprecated: Returns empty array
$product_video = get_field('product_video', $product_id);
$hotspot_annotations = get_field('hotspot_annotations', $product_id); // Optional: Not defined in ACF yet
$comparison_slider = get_field('comparison_slider', $product_id); // Optional: Not defined in ACF yet

// Debug output (only when WP_DEBUG is enabled)
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- Product Gallery Debug -->';
    echo '<!-- Product ID: ' . esc_html($product_id) . ' -->';
    echo '<!-- Gallery Images Count: ' . count($gallery_images) . ' -->';
}

// Combine featured image with gallery
$all_images = array();

if ($featured_image) {
    $featured_id = get_post_thumbnail_id($product_id);
    $all_images[] = array(
        'id'  => $featured_id,
        'url' => $featured_image,
        'alt' => get_the_title(),
    );
}

if (!empty($gallery_images) && is_array($gallery_images)) {
    foreach ($gallery_images as $image) {
        if (is_array($image) && !empty($image['url'])) {
            // Ensure ID is set (ACF may return 'ID' instead of 'id')
            if (empty($image['id']) && !empty($image['ID'])) {
                $image['id'] = $image['ID'];
            }
            $all_images[] = $image;
        }
    }
}
?>

<div class="product-gallery">
    
    <!-- Main Display Area -->
    <div class="product-gallery-main">
        
        <!-- Regular Gallery -->
        <?php if (!empty($all_images)) : ?>
            <div class="product-gallery-viewer" id="product-gallery-main">
                <?php foreach ($all_images as $index => $image) : 
                    $active_class = ($index === 0) ? 'active' : '';
                    $img_alt = !empty($image['alt']) ? $image['alt'] : get_the_title();
                    // Get full size image for lightbox
                    $full_size_url = $image['url'];
                    // Try to get full size version if available
                    if (!empty($image['id']) && is_numeric($image['id'])) {
                        $full_size = wp_get_attachment_image_src($image['id'], 'full');
                        if ($full_size && !empty($full_size[0])) {
                            $full_size_url = $full_size[0];
                        }
                    }
                ?>
                    <div class="gallery-image <?php echo esc_attr($active_class); ?>" 
                         data-index="<?php echo esc_attr($index); ?>">
                        <a href="<?php echo esc_url($full_size_url); ?>" 
                           data-pswp-width="1200"
                           data-pswp-height="1200"
                           data-cropped="true">
                            <img src="<?php echo esc_url($image['url']); ?>" 
                                 alt="<?php echo esc_attr($img_alt); ?>">
                        </a>
                        
                        <!-- Image Hot Spots -->
                        <?php if ($hotspot_annotations && is_array($hotspot_annotations)) : ?>
                            <?php foreach ($hotspot_annotations as $hotspot) : 
                                if (isset($hotspot['hotspot_image']) && $hotspot['hotspot_image'] === $image['url']) :
                            ?>
                                <button class="image-hotspot" 
                                        style="left: <?php echo esc_attr($hotspot['position_x']); ?>%; top: <?php echo esc_attr($hotspot['position_y']); ?>%;"
                                        data-title="<?php echo esc_attr($hotspot['title']); ?>"
                                        data-description="<?php echo esc_attr($hotspot['description']); ?>"
                                        aria-label="<?php echo esc_attr($hotspot['title']); ?>">
                                    <span class="hotspot-marker">+</span>
                                </button>
                            <?php 
                                endif;
                            endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- 360° View -->
        <?php if (!empty($images_360) && is_array($images_360)) : ?>
            <div class="product-360-viewer" id="product-360-viewer" style="display: none;">
                <div class="viewer-360-container" data-total-frames="<?php echo esc_attr(count($images_360)); ?>">
                    <?php foreach ($images_360 as $index => $image_360) : 
                        if (is_array($image_360) && !empty($image_360['url'])) :
                            $active_class = ($index === 0) ? 'active' : '';
                            $img_360_alt = !empty($image_360['alt']) ? $image_360['alt'] : sprintf(__('%s - 360° View Frame %d', 'angola-b2b'), get_the_title(), $index + 1);
                    ?>
                        <img src="<?php echo esc_url($image_360['url']); ?>" 
                             alt="<?php echo esc_attr($img_360_alt); ?>"
                             class="frame-360 <?php echo esc_attr($active_class); ?>"
                             data-frame="<?php echo esc_attr($index); ?>"
                             loading="lazy">
                    <?php 
                        endif;
                    endforeach; ?>
                </div>
                <div class="viewer-360-controls">
                    <span class="control-hint"><?php esc_html_e('拖动旋转', 'angola-b2b'); ?></span>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Video -->
        <?php if ($product_video) : ?>
            <div class="product-video-viewer" id="product-video-viewer" style="display: none;">
                <video controls preload="metadata" 
                       aria-label="<?php echo esc_attr(sprintf(__('%s Product Video', 'angola-b2b'), get_the_title())); ?>">
                    <source src="<?php echo esc_url($product_video); ?>" type="video/mp4">
                    <?php esc_html_e('您的浏览器不支持视频播放。', 'angola-b2b'); ?>
                </video>
            </div>
        <?php endif; ?>
        
        <!-- Comparison Slider (Before/After) -->
        <?php if ($comparison_slider && !empty($comparison_slider['before_image']) && !empty($comparison_slider['after_image'])) : ?>
            <div class="product-comparison-viewer" id="product-comparison-viewer" style="display: none;">
                <div class="comparison-slider-container">
                    <div class="comparison-image comparison-before">
                        <?php if (is_array($comparison_slider['before_image']) && !empty($comparison_slider['before_image']['url'])) : 
                            $before_alt = !empty($comparison_slider['before_image']['alt']) ? $comparison_slider['before_image']['alt'] : __('Before', 'angola-b2b');
                        ?>
                            <img src="<?php echo esc_url($comparison_slider['before_image']['url']); ?>" 
                                 alt="<?php echo esc_attr($before_alt); ?>">
                            <span class="comparison-label"><?php esc_html_e('改进前', 'angola-b2b'); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="comparison-image comparison-after">
                        <?php if (is_array($comparison_slider['after_image']) && !empty($comparison_slider['after_image']['url'])) : 
                            $after_alt = !empty($comparison_slider['after_image']['alt']) ? $comparison_slider['after_image']['alt'] : __('After', 'angola-b2b');
                        ?>
                            <img src="<?php echo esc_url($comparison_slider['after_image']['url']); ?>" 
                                 alt="<?php echo esc_attr($after_alt); ?>">
                            <span class="comparison-label"><?php esc_html_e('改进后', 'angola-b2b'); ?></span>
                        <?php endif; ?>
                    </div>
                    <input type="range" min="0" max="100" value="50" 
                           class="comparison-slider" 
                           id="comparison-slider"
                           aria-label="<?php esc_attr_e('Comparison slider', 'angola-b2b'); ?>">
                    <div class="comparison-divider" style="left: 50%;"></div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Gallery Controls -->
        <div class="gallery-controls">
            <button class="gallery-control gallery-zoom" 
                    aria-label="<?php esc_attr_e('Zoom image', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-search"></span>
            </button>
            <button class="gallery-control gallery-fullscreen" 
                    aria-label="<?php esc_attr_e('Fullscreen', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-fullscreen-alt"></span>
            </button>
        </div>
        
    </div>
    
    <!-- Thumbnails & View Switcher -->
    <div class="product-gallery-nav">
        
        <!-- View Type Switcher -->
        <div class="view-type-switcher">
            <button class="view-type-btn active" data-view="gallery" 
                    aria-label="<?php esc_attr_e('Gallery view', 'angola-b2b'); ?>">
                <span class="dashicons dashicons-images-alt2"></span>
                <?php esc_html_e('图片', 'angola-b2b'); ?>
            </button>
            
            <?php if (!empty($images_360)) : ?>
                <button class="view-type-btn" data-view="360" 
                        aria-label="<?php esc_attr_e('360° view', 'angola-b2b'); ?>">
                    <span class="dashicons dashicons-update"></span>
                    <?php esc_html_e('360°', 'angola-b2b'); ?>
                </button>
            <?php endif; ?>
            
            <?php if ($product_video) : ?>
                <button class="view-type-btn" data-view="video" 
                        aria-label="<?php esc_attr_e('Video view', 'angola-b2b'); ?>">
                    <span class="dashicons dashicons-video-alt3"></span>
                    <?php esc_html_e('视频', 'angola-b2b'); ?>
                </button>
            <?php endif; ?>
            
            <?php if ($comparison_slider) : ?>
                <button class="view-type-btn" data-view="comparison" 
                        aria-label="<?php esc_attr_e('Comparison view', 'angola-b2b'); ?>">
                    <span class="dashicons dashicons-leftright"></span>
                    <?php esc_html_e('对比', 'angola-b2b'); ?>
                </button>
            <?php endif; ?>
        </div>
        
        <!-- Thumbnail Slider -->
        <?php if (!empty($all_images) && count($all_images) > 1) : ?>
            <div class="gallery-thumbnails swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($all_images as $index => $image) : 
                        $active_class = ($index === 0) ? 'active' : '';
                        $thumb_alt = !empty($image['alt']) ? $image['alt'] : sprintf(__('Thumbnail %d', 'angola-b2b'), $index + 1);
                    ?>
                        <div class="swiper-slide">
                            <button class="gallery-thumbnail <?php echo esc_attr($active_class); ?>" 
                                    data-index="<?php echo esc_attr($index); ?>"
                                    aria-label="<?php echo esc_attr($thumb_alt); ?>">
                                <img src="<?php echo esc_url($image['url']); ?>" 
                                     alt="<?php echo esc_attr($thumb_alt); ?>"
                                     loading="lazy">
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        <?php endif; ?>
        
    </div>
    
</div>

<!-- Hotspot Tooltip Template -->
<div id="hotspot-tooltip-template" style="display: none;">
    <div class="hotspot-tooltip">
        <h4 class="hotspot-title"></h4>
        <p class="hotspot-description"></p>
    </div>
</div>
