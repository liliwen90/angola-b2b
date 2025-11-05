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

// Define industries (can be customized via ACF later)
$industries = array(
    array(
        'id' => 'agriculture',
        'title' => __('Agriculture', 'angola-b2b'),
        'description' => __('With global sourcing an everyday reality, MSC connects the growers, farmers and producers of agricultural products around the world with their key markets.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800',
        'link' => get_term_link('农机农具', 'product_category'),
    ),
    array(
        'id' => 'fruit',
        'title' => __('Fruit', 'angola-b2b'),
        'description' => __('Whether you\'re shipping apples or avocados, our world-leading reefer fleet is equipped with the technology you need to keep your fruit in perfect condition.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/fruit/msc-fruit-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'pharmaceuticals',
        'title' => __('Pharmaceuticals', 'angola-b2b'),
        'description' => __('More and more pharmaceutical companies are turning to sea transport to deliver medicines and other essential goods quickly and safely to the places where they are needed.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/pharma/msc-pharma-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'car-parts',
        'title' => __('Car Parts', 'angola-b2b'),
        'description' => __('Whether you are shipping production or service parts, a reliable and experienced shipping partner is a vital link in your uninterruptible supply chain.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/automotive/msc-automotive-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'mining',
        'title' => __('Mining & Minerals', 'angola-b2b'),
        'description' => __('For decades MSC has been successfully connecting the minerals extraction industries with customer markets around the world – offering fast transit times across all key trade lanes.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/mining/msc-mining-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'plastics',
        'title' => __('Plastics & Rubber Products', 'angola-b2b'),
        'description' => __('Transported to and from every major trade lane, plastic and rubber goods are at the very centre of most modern global supply chains.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/plastics/msc-plastics-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'chemicals',
        'title' => __('Chemicals & Petrochemicals', 'angola-b2b'),
        'description' => __('MSC provides careful, precise and robust processes to safely transport hazardous and dangerous goods, such as chemicals and petrochemicals.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/chemicals/msc-chemicals-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'food-beverage',
        'title' => __('Food & Beverages', 'angola-b2b'),
        'description' => __('Thanks to its decades of experience servicing the food and beverage industries, MSC understands the unique needs of the sector.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/food-beverage/msc-food-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'forestry',
        'title' => __('Pulp, Paper & Forestry Products', 'angola-b2b'),
        'description' => __('Using our knowledge in transportation and logistics we can provide versatile solutions for your pulp, paper and forest products.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/forestry/msc-forestry-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
    array(
        'id' => 'retail',
        'title' => __('Retail', 'angola-b2b'),
        'description' => __('Retailers rely on efficient global product sourcing and a flexible and robust "just-in-time" supply chain.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/retail/msc-retail-shipping-solutions-hero.jpg?w=800',
        'link' => get_post_type_archive_link('product'),
    ),
);

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

