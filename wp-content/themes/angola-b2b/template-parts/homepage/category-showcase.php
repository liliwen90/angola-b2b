<?php
/**
 * Homepage Category Showcase / Network Carousel
 * MSC-style full-width network carousel showcasing categories
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define network/category showcase items (MSC-style)
$network_items = array(
    array(
        'id' => 'east-west-network',
        'title' => __('A Unique and Holistic East / West Network', 'angola-b2b'),
        'subtitle' => __('Ship with Angola B2B Standalone Network and enjoy extensive coverage, customized solutions and reliable transit times.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/network-carousel/MSC-container-vessel-top-view.jpg?w=1920',
        'link_text' => __('LEARN MORE', 'angola-b2b'),
        'link_url' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'transatlantic-services',
        'title' => __('Enjoy Full Coverage With Transatlantic Services', 'angola-b2b'),
        'subtitle' => __('Comprehensive coverage connecting continents. Reliable service across major trade routes.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/network-carousel/MSC-vessel-connecting-continents.jpg?w=1920',
        'link_text' => __('LEARN MORE NOW!', 'angola-b2b'),
        'link_url' => get_post_type_archive_link('product'),
    ),
);

$network_items = apply_filters('angola_b2b_network_carousel', $network_items);

if (empty($network_items)) {
    return;
}
?>

<section class="network-carousel-section" data-animate="fade-up">
    <div class="network-carousel-wrapper">
        <!-- Swiper -->
        <div class="swiper network-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($network_items as $item) : ?>
                    <div class="swiper-slide">
                        <div class="network-slide">
                            <!-- Background Image -->
                            <div class="network-background">
                                <img src="<?php echo esc_url($item['image']); ?>" 
                                     alt="<?php echo esc_attr($item['title']); ?>"
                                     loading="lazy">
                                <div class="network-overlay"></div>
                            </div>
                            
                            <!-- Content -->
                            <div class="network-content">
                                <div class="container">
                                    <div class="network-text">
                                        <h2 class="network-title"><?php echo esc_html($item['title']); ?></h2>
                                        <p class="network-subtitle"><?php echo esc_html($item['subtitle']); ?></p>
                                        <div class="network-cta">
                                            <a href="<?php echo esc_url($item['link_url']); ?>" class="btn btn-primary btn-lg">
                                                <?php echo esc_html($item['link_text']); ?>
                                                <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                    <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination Bullets -->
            <div class="swiper-pagination network-pagination"></div>
        </div>
    </div>
</section>
