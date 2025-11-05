<?php
/**
 * Services Showcase Section
 * MSC-style service/solution tabs with image and description
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
        'id' => 'construction',
        'title' => __('Construction & Engineering', 'angola-b2b'),
        'description' => __('Comprehensive solutions for construction projects, from heavy machinery to building materials. We provide reliable equipment and materials that meet international standards.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/dry-cargo/msc-dry-cargo-shipping-solutions-hero.jpg?w=800',
        'features' => array(
            __('Heavy Equipment', 'angola-b2b'),
            __('Building Materials', 'angola-b2b'),
            __('Safety Equipment', 'angola-b2b'),
        ),
    ),
    array(
        'id' => 'agriculture',
        'title' => __('Agricultural Equipment', 'angola-b2b'),
        'description' => __('Modern agricultural machinery and equipment designed to improve farm productivity. From tractors to irrigation systems, we have everything you need for efficient farming.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800',
        'features' => array(
            __('Tractors & Machinery', 'angola-b2b'),
            __('Irrigation Systems', 'angola-b2b'),
            __('Harvesting Equipment', 'angola-b2b'),
        ),
    ),
    array(
        'id' => 'industrial',
        'title' => __('Industrial Equipment', 'angola-b2b'),
        'description' => __('Professional-grade industrial equipment for manufacturing and production. High-quality machines that deliver consistent performance and reliability.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/project-cargo/msc-project-cargo-shipping-solutions-hero.jpg?w=800',
        'features' => array(
            __('Power Generation', 'angola-b2b'),
            __('Manufacturing Tools', 'angola-b2b'),
            __('Quality Control', 'angola-b2b'),
        ),
    ),
);

$services = apply_filters('angola_b2b_services_showcase', $services);

if (empty($services)) {
    return;
}
?>

<section class="services-showcase-section" data-animate="fade-up">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php esc_html_e('Our Solutions', 'angola-b2b'); ?></h2>
            <p class="section-subtitle">
                <?php esc_html_e('Comprehensive products and services tailored to your business needs', 'angola-b2b'); ?>
            </p>
        </div>
        
        <div class="services-tabs-wrapper">
            <!-- Tab Navigation -->
            <div class="services-tabs">
                <?php foreach ($services as $index => $service) : ?>
                    <button class="service-tab <?php echo $index === 0 ? 'active' : ''; ?>" 
                            data-tab="service-<?php echo esc_attr($service['id']); ?>">
                        <span class="tab-number">0<?php echo esc_html($index + 1); ?></span>
                        <span class="tab-title"><?php echo esc_html($service['title']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Tab Content -->
            <div class="services-content">
                <?php foreach ($services as $index => $service) : ?>
                    <div class="service-panel <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-panel="service-<?php echo esc_attr($service['id']); ?>">
                        <div class="service-grid">
                            <div class="service-image-wrapper">
                                <img src="<?php echo esc_url($service['image']); ?>" 
                                     alt="<?php echo esc_attr($service['title']); ?>"
                                     class="service-image"
                                     loading="lazy">
                                <div class="image-overlay"></div>
                            </div>
                            
                            <div class="service-text">
                                <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>
                                <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                                
                                <?php if (!empty($service['features'])) : ?>
                                    <ul class="service-features">
                                        <?php foreach ($service['features'] as $feature) : ?>
                                            <li class="feature-item">
                                                <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <polyline points="20 6 9 17 4 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <span><?php echo esc_html($feature); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                
                                <div class="service-cta">
                                    <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" 
                                       class="btn btn-primary btn-lg">
                                        <?php esc_html_e('Explore Products', 'angola-b2b'); ?>
                                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <line x1="5" y1="12" x2="19" y2="12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <polyline points="12 5 19 12 12 19" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

