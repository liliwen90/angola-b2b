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

// Try pulling latest posts; fallback to demo items if none
$fallback_items = array(
    array(
        'category' => __t('news_events'),
        'date' => date('d/m/Y'),
        'title' => __t('news_title_1'),
        'excerpt' => __t('news_excerpt_1'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-1.jpg',
        'link' => '#',
    ),
    array(
        'category' => __t('news_events'),
        'date' => date('d/m/Y', strtotime('-3 day')),
        'title' => __t('news_title_2'),
        'excerpt' => __t('news_excerpt_2'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-2.jpg',
        'link' => '#',
    ),
    array(
        'category' => __t('news_company'),
        'date' => date('d/m/Y', strtotime('-7 day')),
        'title' => __t('news_title_3'),
        'excerpt' => __t('news_excerpt_3'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-3.jpg',
        'link' => '#',
    ),
    array(
        'category' => __t('news_sustainability'),
        'date' => date('d/m/Y', strtotime('-12 day')),
        'title' => __t('news_title_4'),
        'excerpt' => __t('news_excerpt_4'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-4.jpg',
        'link' => '#',
    ),
    array(
        'category' => __t('news_service'),
        'date' => date('d/m/Y', strtotime('-16 day')),
        'title' => __t('news_title_5'),
        'excerpt' => __t('news_excerpt_5'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-5.jpg',
        'link' => '#',
    ),
    array(
        'category' => __t('news_partnership'),
        'date' => date('d/m/Y', strtotime('-20 day')),
        'title' => __t('news_title_6'),
        'excerpt' => __t('news_excerpt_6'),
        'image' => ANGOLA_B2B_THEME_URI . '/assets/images/news/news-6.jpg',
        'link' => '#',
    ),
);

$news_items = array();

$query = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
    'meta_query' => array(
        array(
            'key' => 'post_lang',
            'value' => function_exists('angola_b2b_get_current_language') ? angola_b2b_get_current_language() : 'en',
        ),
    ),
));

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$image) {
            $image = ANGOLA_B2B_THEME_URI . '/assets/images/news/news-default.jpg';
        }
        $category = '';
        $cats = get_the_category();
        if (!empty($cats)) {
            $category = $cats[0]->name;
        }
        $news_items[] = array(
            'category' => $category ?: __t('news_company'),
            'date' => get_the_date('d/m/Y'),
            'title' => get_the_title(),
            'excerpt' => wp_trim_words(strip_tags(get_the_excerpt() ?: get_the_content()), 26, '...'),
            'image' => $image,
            'link' => get_permalink(),
        );
    }
    wp_reset_postdata();
} else {
    // Fallback to demo items if no posts yet
    $news_items = $fallback_items;
}
?>

<section class="news-carousel-section section-padding bg-light" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php _et('discover_latest_news'); ?></h2>
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
                                            <?php _et('read_more'); ?>
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
                    <?php _et('see_all_news'); ?>
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

