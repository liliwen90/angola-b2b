<?php
/**
 * Industries Carousel Section
 * MSC-style "Your Shipping Needs Met" - horizontal industry carousel
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Fetch industries from database
$industries_query = new WP_Query(array(
    'post_type' => 'industry',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'publish',
));

$industries = array();
if ($industries_query->have_posts()) {
    while ($industries_query->have_posts()) {
        $industries_query->the_post();
        
        // Get featured image
        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$image_url) {
            // Use custom default image if set, otherwise use MSC placeholder
            $custom_default = get_option('angola_b2b_industry_default_image', '');
            $image_url = $custom_default ? $custom_default : 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800';
        }
        
        // Get ACF fields
        $link = get_field('industry_link');
        
        $industries[] = array(
            'id' => 'industry-' . get_the_ID(),
            'title' => get_the_title(),
            'description' => get_the_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30),
            'image' => $image_url,
            'link' => $link ? $link : get_permalink(),
        );
    }
    wp_reset_postdata();
}

$industries = apply_filters('angola_b2b_industries_carousel', $industries);

if (empty($industries)) {
    return;
}
?>

<section class="industries-carousel-section section-padding bg-white" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e('Your Shipping Needs Met', 'angola-b2b'); ?></h2>
            <p class="section-subtitle">
                <?php esc_html_e('At MSC we pride ourselves on being a global container shipping company that delivers tailored solutions designed to meet the specific needs of each of our customers.', 'angola-b2b'); ?>
            </p>
        </div>
        
        <div class="industries-carousel-wrapper">
            <!-- Swiper -->
            <div class="swiper industries-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($industries as $industry) : ?>
                        <div class="swiper-slide">
                            <div class="industry-card">
                                <a href="<?php echo esc_url(is_wp_error($industry['link']) ? get_post_type_archive_link('product') : $industry['link']); ?>" class="industry-card-link">
                                    <!-- Industry Image -->
                                    <div class="industry-image-wrapper">
                                        <img src="<?php echo esc_url($industry['image']); ?>" 
                                             alt="<?php echo esc_attr($industry['title']); ?>"
                                             loading="lazy">
                                        <div class="industry-image-overlay"></div>
                                    </div>
                                    
                                    <!-- Industry Content -->
                                    <div class="industry-content">
                                        <h3 class="industry-title"><?php echo esc_html($industry['title']); ?></h3>
                                        <p class="industry-description"><?php echo esc_html($industry['description']); ?></p>
                                        <span class="industry-link-text">
                                            <?php esc_html_e('Read More', 'angola-b2b'); ?>
                                            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation -->
                <div class="swiper-button-prev industries-prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="15 18 9 12 15 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="swiper-button-next industries-next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="9 18 15 12 9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <!-- Pagination -->
                <div class="swiper-pagination industries-pagination"></div>
            </div>
        </div>
    </div>
</section>

