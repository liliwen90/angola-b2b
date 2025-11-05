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
    if (is_tax('product_category')) {
        $term = get_queried_object();
        $args['title'] = $term->name ?? '';
        $args['subtitle'] = term_description() ?: '';
        
        // Try to get background image from ACF
        if (function_exists('get_field')) {
            $category_hero_image = get_field('category_hero_image', $term);
            if ($category_hero_image) {
                // ACF returns array with 'url' key, or just URL string
                if (is_array($category_hero_image) && isset($category_hero_image['url'])) {
                    $args['background_image'] = $category_hero_image['url'];
                } elseif (is_string($category_hero_image)) {
                    $args['background_image'] = $category_hero_image;
                } elseif (is_numeric($category_hero_image)) {
                    $args['background_image'] = wp_get_attachment_image_url($category_hero_image, 'hero-banner');
                }
            }
        }
        
        // Fallback: use category placeholder image
        if (empty($args['background_image']) && isset($term->slug)) {
            $args['background_image'] = angola_b2b_get_category_placeholder_image($term->slug);
        }
    } elseif (is_singular('product')) {
        $args['title'] = get_the_title();
        $args['subtitle'] = get_the_excerpt() ?: '';
        
        // Try to get background image from ACF
        if (function_exists('get_field')) {
            $product_hero_image = get_field('product_hero_image', get_the_ID());
            if ($product_hero_image) {
                // ACF returns array with 'url' key, or just URL string
                if (is_array($product_hero_image) && isset($product_hero_image['url'])) {
                    $args['background_image'] = $product_hero_image['url'];
                } elseif (is_string($product_hero_image)) {
                    $args['background_image'] = $product_hero_image;
                } elseif (is_numeric($product_hero_image)) {
                    $args['background_image'] = wp_get_attachment_image_url($product_hero_image, 'hero-banner');
                }
            }
        }
        
        // Fallback: use product featured image
        if (empty($args['background_image'])) {
            $args['background_image'] = get_the_post_thumbnail_url(get_the_ID(), 'hero-banner') ?: '';
        }
    } elseif (is_front_page()) {
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
            
            // Get CTA buttons
            $cta_primary_text = get_field('hero_cta_primary_text', 45) ?: get_field('hero_cta_primary_text') ?: '';
            $cta_primary_url = get_field('hero_cta_primary_url', 45) ?: get_field('hero_cta_primary_url') ?: '';
            if ($cta_primary_text && $cta_primary_url) {
                $args['cta_primary'] = array(
                    'text' => $cta_primary_text,
                    'url'  => $cta_primary_url,
                );
            }
            
            $cta_secondary_text = get_field('hero_cta_secondary_text', 45) ?: get_field('hero_cta_secondary_text') ?: '';
            $cta_secondary_url = get_field('hero_cta_secondary_url', 45) ?: get_field('hero_cta_secondary_url') ?: '';
            if ($cta_secondary_text && $cta_secondary_url) {
                $args['cta_secondary'] = array(
                    'text' => $cta_secondary_text,
                    'url'  => $cta_secondary_url,
                );
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
                    <span class="hero-title-line1">LEADER IN</span>
                    <span class="hero-title-line2"><?php echo esc_html($args['title']); ?></span>
                </h1>
            <?php endif; ?>
            
            <?php if ($args['subtitle']) : ?>
                <div class="hero-subtitle">
                    <?php echo wp_kses_post(wpautop($args['subtitle'])); ?>
                </div>
            <?php endif; ?>
            
            <?php if (is_front_page()) : ?>
                <!-- MSC-Style Quick Action Tabs -->
                <div class="hero-quick-actions">
                    <div class="quick-action-tabs">
                        <button class="quick-action-tab active" data-tab="quote">
                            <span><?php esc_html_e('GET QUOTE', 'unibro'); ?></span>
                        </button>
                        <button class="quick-action-tab" data-tab="contact">
                            <span><?php esc_html_e('CONTACTS', 'unibro'); ?></span>
                        </button>
                    </div>
                    
                    <div class="quick-action-content">
                        <!-- Quote Tab Content -->
                        <div class="action-panel active" data-panel="quote">
                            <form class="quote-form" action="<?php echo esc_url(home_url('/request-quote')); ?>" method="get">
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="text" 
                                               name="product_name" 
                                               class="form-control form-control-lg" 
                                               placeholder="<?php esc_attr_e('Product or Category', 'angola-b2b'); ?>"
                                               required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <span><?php esc_html_e('Get Quote', 'angola-b2b'); ?></span>
                                        <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Contact Tab Content -->
                        <div class="action-panel" data-panel="contact">
                            <div class="contact-quick-info">
                                <div class="contact-item">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>info@example.com</span>
                                </div>
                                <div class="contact-item">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>+1 234 567 8900</span>
                                </div>
                                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-lg">
                                    <?php esc_html_e('Contact Us', 'angola-b2b'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <?php if ((!empty($args['cta_primary']['text']) && !empty($args['cta_primary']['url'])) || 
                          (!empty($args['cta_secondary']['text']) && !empty($args['cta_secondary']['url']))) : ?>
                    <div class="hero-actions">
                        <?php if (!empty($args['cta_primary']['text']) && !empty($args['cta_primary']['url'])) : ?>
                            <a href="<?php echo esc_url($args['cta_primary']['url']); ?>" 
                               class="btn btn-primary btn-lg hero-cta-primary">
                                <?php echo esc_html($args['cta_primary']['text']); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($args['cta_secondary']['text']) && !empty($args['cta_secondary']['url'])) : ?>
                            <a href="<?php echo esc_url($args['cta_secondary']['url']); ?>" 
                               class="btn btn-secondary btn-lg hero-cta-secondary">
                                <?php echo esc_html($args['cta_secondary']['text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

