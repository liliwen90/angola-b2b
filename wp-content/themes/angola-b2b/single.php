<?php
/**
 * The template for displaying single posts
 *
 * @package Angola_B2B
 */

// Debug output (only when WP_DEBUG is enabled)
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- SINGLE.PHP IS RUNNING (NOT single-product.php) -->';
    echo '<!-- Post Type: ' . esc_html(get_post_type()) . ' -->';
}

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (function_exists('angola_b2b_display_breadcrumbs')) { angola_b2b_display_breadcrumbs(); } ?>
        <?php
        while (have_posts()) :
            the_post();
            // Strict language separation for posts
            if (get_post_type() === 'post' && function_exists('angola_b2b_get_current_language')) {
                $current_lang = angola_b2b_get_current_language();
                $post_lang = get_post_meta(get_the_ID(), 'post_lang', true);
                if (empty($post_lang) || $post_lang !== $current_lang) {
                    status_header(404);
                    nocache_headers();
                    include get_query_template('404');
                    exit;
                }
            }
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    <div class="entry-meta">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                        <span class="byline"><?php esc_html_e('by', 'angola-b2b'); ?> <?php the_author(); ?></span>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'angola-b2b'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $categories_list = get_the_category_list(esc_html__(', ', 'angola-b2b'));
                    if ($categories_list) {
                        printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'angola-b2b') . '</span>', $categories_list);
                    }

                    $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'angola-b2b'));
                    if ($tags_list) {
                        printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'angola-b2b') . '</span>', $tags_list);
                    }
                    ?>
                </footer>
            </article>

            <?php
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'angola-b2b') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'angola-b2b') . '</span> <span class="nav-title">%title</span>',
            ));
            
        endwhile;
        ?>
    </div>
</main>

<?php
get_footer();

