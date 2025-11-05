<?php
/**
 * Services Showcase Section - Carousel Version
 * MSC-style horizontal carousel with service/solution cards
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Fetch services from database
$services_query = new WP_Query(array(
    'post_type' => 'service',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish',
));

$services = array();
if ($services_query->have_posts()) {
    while ($services_query->have_posts()) {
        $services_query->the_post();
        
        // Get featured image
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$image_url) {
            // Use custom default image if set, otherwise use MSC placeholder
            $custom_default = get_option('angola_b2b_service_default_image', '');
            $image_url = $custom_default ? $custom_default : 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/dry-cargo/msc-dry-cargo-shipping-solutions-hero.jpg?w=800';
        }
        
        // Get ACF fields
        $icon = get_field('service_icon');
        $link = get_field('service_link');
        $features = get_field('service_features');
        
        // Build features array
        $features_array = array();
        if ($features) {
            foreach ($features as $feature) {
                if (!empty($feature['feature_text'])) {
                    $features_array[] = $feature['feature_text'];
                }
            }
        }
        
        $services[] = array(
            'id' => 'service-' . get_the_ID(),
            'title' => get_the_title(),
            'description' => get_the_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30),
            'image' => $image_url,
            'icon' => $icon,
            'link_url' => $link ? $link : get_permalink(),
            'link_text' => __('Learn More', 'angola-b2b'),
            'features' => $features_array,
        );
    }
    wp_reset_postdata();
}

$services = apply_filters('angola_b2b_services_showcase', $services);

if (empty($services)) {
    return;
}
?>

<section class="services-showcase-section section-padding bg-light" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e('Our Services', 'angola-b2b'); ?></h2>
            <p class="section-subtitle">
                <?php esc_html_e('Beyond supplying quality products, we provide comprehensive trade services including logistics coordination, customs clearance support, and after-sales service to ensure smooth business operations between China and Angola.', 'angola-b2b'); ?>
            </p>
        </div>
        
        <div class="services-carousel-wrapper">
            <!-- Swiper -->
            <div class="swiper services-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($services as $service) : ?>
                        <div class="swiper-slide">
                            <div class="service-card">
                                <div class="service-card-inner">
                                    <!-- Service Image -->
                                    <div class="service-image-wrapper">
                                        <img src="<?php echo esc_url($service['image']); ?>" 
                                             alt="<?php echo esc_attr($service['title']); ?>"
                                             loading="lazy">
                                        <div class="service-image-overlay"></div>
                                    </div>
                                    
                                    <!-- Service Content -->
                                    <div class="service-content">
                                        <?php if (!empty($service['icon'])) : ?>
                                            <div class="service-icon">
                                                <?php echo $service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            </div>
                                        <?php endif; ?>
                                        <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                                        <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                                        <?php if (!empty($service['features'])) : ?>
                                            <ul class="service-features">
                                                <?php foreach ($service['features'] as $feature) : ?>
                                                    <li><?php echo esc_html($feature); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <div class="service-link">
                                            <a href="<?php echo esc_url($service['link_url']); ?>" class="btn-link">
                                                <?php echo esc_html($service['link_text']); ?>
                                                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation -->
                <div class="swiper-button-prev services-prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="15 18 9 12 15 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="swiper-button-next services-next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="9 18 15 12 9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <!-- Pagination -->
                <div class="swiper-pagination services-pagination"></div>
            </div>
            
            <!-- View All Link -->
            <div class="services-view-all">
                <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-outline-primary btn-lg">
                    <?php esc_html_e('See All Solutions', 'angola-b2b'); ?>
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
