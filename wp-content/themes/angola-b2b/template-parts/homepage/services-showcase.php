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

// Define services (can be customized via ACF later)
$services = array(
    array(
        'id' => 'shipping-solutions',
        'title' => __('Shipping Solutions', 'angola-b2b'),
        'description' => __('Comprehensive shipping solutions for all your cargo needs. From dry containers to specialized transport, we ensure your goods reach their destination safely.', 'angola-b2b'),
        'image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=600&h=400&fit=crop',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18h18M3 6h18M5 6v12M19 6v12M9 6v12M15 6v12"/></svg>',
    ),
    array(
        'id' => 'inland-transportation',
        'title' => __('Inland Transportation & Logistics', 'angola-b2b'),
        'description' => __('Seamless inland transportation and logistics services. Door-to-door delivery solutions that keep your supply chain moving efficiently.', 'angola-b2b'),
        'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&h=400&fit=crop',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 6h15v9H1V6zM16 8h5l3 3v4h-3M5.5 18a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM18.5 18a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/></svg>',
    ),
    array(
        'id' => 'air-cargo',
        'title' => __('Air Cargo Solutions', 'angola-b2b'),
        'description' => __('Fast and reliable air cargo services for time-sensitive shipments. Global reach with express delivery options for urgent needs.', 'angola-b2b'),
        'image' => 'https://images.unsplash.com/photo-1464037866556-6812c9d1c72e?w=600&h=400&fit=crop',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>',
    ),
    array(
        'id' => 'digital-solutions',
        'title' => __('Digital Business Solutions', 'angola-b2b'),
        'description' => __('Advanced digital tools and platforms to streamline your operations. Real-time tracking, automated documentation, and seamless integration.', 'angola-b2b'),
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
    ),
    array(
        'id' => 'cargo-protection',
        'title' => __('Cargo Cover Solutions', 'angola-b2b'),
        'description' => __('Comprehensive insurance and protection plans for your valuable cargo. Peace of mind with every shipment, backed by trusted coverage.', 'angola-b2b'),
        'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=600&h=400&fit=crop',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
    ),
);

$services = apply_filters('angola_b2b_services_showcase', $services);

if (empty($services)) {
    return;
}
?>

<section class="services-showcase-section section-padding bg-light" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e('Our Solutions', 'angola-b2b'); ?></h2>
            <p class="section-subtitle">
                <?php esc_html_e('As well as being a global leader in container shipping, our worldwide teams of industry specific experts mean we can offer our customers round-the-clock personalised service.', 'angola-b2b'); ?>
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
                                        <div class="service-icon">
                                            <?php echo $service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        </div>
                                        <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                                        <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                                        <div class="service-link">
                                            <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn-link">
                                                <?php esc_html_e('Learn More', 'angola-b2b'); ?>
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
