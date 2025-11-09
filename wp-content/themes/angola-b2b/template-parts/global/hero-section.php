<?php
/**
 * Hero Section Component
 * Reusable hero banner component for homepage, category archives, and product pages
 * MSC-style full-width hero with background image, title, and optional CTA buttons
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get custom args if passed via set_query_var or global
global $hero_args;
if (isset($hero_args) && is_array($hero_args)) {
    $custom_args = $hero_args;
} else {
    $custom_args = array();
}

// Parse arguments with defaults
$args = wp_parse_args($custom_args, array(
    'background_image'   => '',
    'background_video'   => '',
    'title'              => '',
    'subtitle'           => '',
    'cta_primary'        => array('text' => '', 'url' => ''),
    'cta_secondary'      => array('text' => '', 'url' => ''),
    'overlay_opacity'    => 0.4,
    'height'             => 'large', // small: 40vh, medium: 50vh, large: 70vh, full: 100vh
    'parallax'           => false,
));

// If no title provided, try to get from context
if (empty($args['title'])) {
    // Note: Hero section removed from product category pages and single product pages
    // Only show hero on homepage and other pages that explicitly call it
    if (is_front_page()) {
        // Homepage: get from ACF settings (page ID 45)
        if (function_exists('get_field')) {
            $args['title'] = get_field('hero_title', 45) ?: get_field('hero_title') ?: '';
            $args['subtitle'] = get_field('hero_subtitle', 45) ?: get_field('hero_subtitle') ?: '';
            
            $hero_bg_image = get_field('hero_background_image', 45) ?: get_field('hero_background_image');
            if ($hero_bg_image) {
                // ACF returns array with 'url' key, or just URL string
                if (is_array($hero_bg_image) && isset($hero_bg_image['url'])) {
                    $args['background_image'] = $hero_bg_image['url'];
                } elseif (is_string($hero_bg_image)) {
                    $args['background_image'] = $hero_bg_image;
                } elseif (is_numeric($hero_bg_image)) {
                    $args['background_image'] = wp_get_attachment_image_url($hero_bg_image, 'hero-banner');
                }
            }
            
        }
        
        // Fallback: use default content if ACF fields are empty
        if (empty($args['title'])) {
            $args['title'] = esc_html__('Welcome to Unibro', 'unibro');
        }
        if (empty($args['subtitle'])) {
            $args['subtitle'] = esc_html__('Your trusted partner for quality products and reliable service', 'angola-b2b');
        }
        
        // Fallback: use MSC placeholder image
        if (empty($args['background_image'])) {
            $args['background_image'] = 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&h=1080&fit=crop';
        }
    } elseif (is_post_type_archive('product')) {
        // Product archive page (not category)
        $args['title'] = esc_html__('Products', 'angola-b2b');
        if (empty($args['background_image'])) {
            $args['background_image'] = 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&h=1080&fit=crop';
        }
    }
}

// Process background image
$background_image_url = '';
if (!empty($args['background_image'])) {
    // If it's a numeric ID, get attachment URL
    if (is_numeric($args['background_image'])) {
        $background_image_url = wp_get_attachment_image_url($args['background_image'], 'hero-banner');
    } elseif (is_array($args['background_image']) && isset($args['background_image']['url'])) {
        // ACF image field array
        $background_image_url = $args['background_image']['url'];
    } else {
        // Direct URL
        $background_image_url = $args['background_image'];
    }
}

// Determine height class
$height_class = 'hero-height-' . esc_attr($args['height']);

// Build hero ID for unique instances
$hero_id = 'hero-' . uniqid();

// Don't output if no content at all (should not happen with fallbacks, but keep as safety check)
if (empty($args['title']) && empty($args['subtitle']) && empty($background_image_url)) {
    return;
}
?>

<section class="hero-section <?php echo esc_attr($height_class); ?>" id="<?php echo esc_attr($hero_id); ?>" data-parallax="<?php echo $args['parallax'] ? 'true' : 'false'; ?>">
    <?php if ($background_image_url || $args['background_video']) : ?>
        <div class="hero-background">
            <?php if ($args['background_video']) : ?>
                <video class="hero-video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url($args['background_video']); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
            
            <?php if ($background_image_url) : ?>
                <div class="hero-image-wrapper">
                    <img src="<?php echo esc_url($background_image_url); ?>" 
                         alt="<?php echo esc_attr($args['title'] ?: ''); ?>"
                         class="hero-image"
                         loading="eager">
                </div>
            <?php endif; ?>
            
            <div class="hero-overlay" style="opacity: <?php echo esc_attr($args['overlay_opacity']); ?>;"></div>
        </div>
    <?php endif; ?>
    
    <div class="hero-content">
        <div class="hero-container">
            <?php if ($args['title']) : ?>
                <h1 class="hero-title">
                    <?php echo esc_html($args['title']); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ($args['subtitle']) : ?>
                <div class="hero-subtitle">
                    <?php echo wp_kses_post(wpautop($args['subtitle'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

