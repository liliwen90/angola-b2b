<?php
/**
 * Blog posts index (News List)
 *
 * @package Angola_B2B
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="archive-header" style="margin: 24px 0 16px;">
            <h1 class="section-title"><?php echo esc_html__('Latest News', 'angola-b2b'); ?></h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="news-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    $thumb = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    if (!$thumb) {
                        $thumb = ANGOLA_B2B_THEME_URI . '/assets/images/news/news-default.jpg';
                    }
                    $cats = get_the_category();
                    $cat_name = !empty($cats) ? $cats[0]->name : '';
                    ?>
                    <article class="news-card">
                        <a href="<?php the_permalink(); ?>" class="news-card-link">
                            <div class="news-image-wrapper">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                <div class="news-image-overlay"></div>
                            </div>
                            <div class="news-content">
                                <div class="news-meta">
                                    <?php if ($cat_name): ?>
                                        <span class="news-category"><?php echo esc_html($cat_name); ?></span>
                                    <?php endif; ?>
                                    <time class="news-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date('d/m/Y')); ?>
                                    </time>
                                </div>
                                <h2 class="news-title"><?php the_title(); ?></h2>
                                <p class="news-excerpt">
                                    <?php echo esc_html(wp_trim_words(strip_tags(get_the_excerpt() ?: get_the_content()), 26, '...')); ?>
                                </p>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" style="margin: 24px 0; text-align:center;">
                <?php
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('Previous', 'angola-b2b'),
                    'next_text' => __('Next', 'angola-b2b'),
                ));
                ?>
            </nav>

        <?php else : ?>
            <p><?php esc_html_e('No news found.', 'angola-b2b'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>


