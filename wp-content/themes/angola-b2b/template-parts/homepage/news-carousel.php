<?php
/**
 * News Carousel Section
 * MSC-style "Discover the Latest News" carousel
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define news items (can be pulled from posts later)
$news_items = array(
    array(
        'id' => 'news-1',
        'category' => __('EVENTS', 'angola-b2b'),
        'date' => '05/11/2025',
        'title' => __('Angola B2B Participates in International Trade Fair 2025', 'angola-b2b'),
        'excerpt' => __('Angola B2B showcases its comprehensive range of construction and industrial equipment at the 2025 International Trade Fair in Luanda.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/news/msc-news-default.jpg?w=800',
        'link' => '#',
    ),
    array(
        'id' => 'news-2',
        'category' => __('EVENTS', 'angola-b2b'),
        'date' => '27/10/2025',
        'title' => __('New Agricultural Equipment Showcase at Farming Expo', 'angola-b2b'),
        'excerpt' => __('Visit our booth to discover the latest agricultural machinery and equipment designed to improve farm productivity.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800',
        'link' => '#',
    ),
    array(
        'id' => 'news-3',
        'category' => __('COMPANY NEWS', 'angola-b2b'),
        'date' => '20/10/2025',
        'title' => __('Angola B2B Expands Warehouse Facilities', 'angola-b2b'),
        'excerpt' => __('New 10,000 sq ft warehouse in Luanda strengthens our commitment to serving customers with faster delivery times.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/inland-services/msc-inland-services-solutions-hero.jpg?w=800',
        'link' => '#',
    ),
    array(
        'id' => 'news-4',
        'category' => __('SUSTAINABILITY', 'angola-b2b'),
        'date' => '15/10/2025',
        'title' => __('Green Equipment Initiative: Eco-Friendly Solutions', 'angola-b2b'),
        'excerpt' => __('Angola B2B introduces new line of environmentally friendly equipment with reduced emissions and improved efficiency.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/hero-ship-at-sunset.jpg?w=800',
        'link' => '#',
    ),
    array(
        'id' => 'news-5',
        'category' => __('NEW SERVICE', 'angola-b2b'),
        'date' => '10/10/2025',
        'title' => __('Introducing 24/7 Technical Support Service', 'angola-b2b'),
        'excerpt' => __('Our new round-the-clock technical support ensures your operations never stop, with expert assistance available anytime.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/digital/msc-digital-solutions-hero.jpg?w=800',
        'link' => '#',
    ),
    array(
        'id' => 'news-6',
        'category' => __('PARTNERSHIP', 'angola-b2b'),
        'date' => '05/10/2025',
        'title' => __('Strategic Partnership with Leading Manufacturer', 'angola-b2b'),
        'excerpt' => __('Angola B2B forms exclusive partnership to bring world-class industrial equipment to the Angolan market.', 'angola-b2b'),
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/project-cargo/msc-project-cargo-shipping-solutions-hero.jpg?w=800',
        'link' => '#',
    ),
);

$news_items = apply_filters('angola_b2b_news_carousel', $news_items);

if (empty($news_items)) {
    return;
}
?>

<section class="news-carousel-section section-padding bg-light" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e('Discover the Latest News About MSC', 'angola-b2b'); ?></h2>
        </div>
        
        <div class="news-carousel-wrapper">
            <!-- Swiper -->
            <div class="swiper news-swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($news_items as $news) : ?>
                        <div class="swiper-slide">
                            <article class="news-card">
                                <a href="<?php echo esc_url($news['link']); ?>" class="news-card-link">
                                    <!-- News Image -->
                                    <div class="news-image-wrapper">
                                        <img src="<?php echo esc_url($news['image']); ?>" 
                                             alt="<?php echo esc_attr($news['title']); ?>"
                                             loading="lazy">
                                        <div class="news-image-overlay"></div>
                                    </div>
                                    
                                    <!-- News Content -->
                                    <div class="news-content">
                                        <div class="news-meta">
                                            <span class="news-category"><?php echo esc_html($news['category']); ?></span>
                                            <time class="news-date" datetime="<?php echo esc_attr($news['date']); ?>">
                                                <?php echo esc_html($news['date']); ?>
                                            </time>
                                        </div>
                                        <h3 class="news-title"><?php echo esc_html($news['title']); ?></h3>
                                        <p class="news-excerpt"><?php echo esc_html($news['excerpt']); ?></p>
                                        <span class="news-read-more">
                                            <?php esc_html_e('Read More', 'angola-b2b'); ?>
                                            <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation -->
                <div class="swiper-button-prev news-prev">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="15 18 9 12 15 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="swiper-button-next news-next">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <polyline points="9 18 15 12 9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                
                <!-- Pagination -->
                <div class="swiper-pagination news-pagination"></div>
            </div>
            
            <!-- View All Link -->
            <div class="news-view-all">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-primary btn-lg">
                    <?php esc_html_e('See All News', 'angola-b2b'); ?>
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

