<?php
/**
 * Template part for displaying posts
 *
 * @package Angola_B2B
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;

        if ('post' === get_post_type()) :
            ?>
            <div class="entry-meta">
                <span class="posted-on">
                    <span class="dashicons dashicons-calendar-alt" aria-hidden="true"></span>
                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </span>
                <span class="byline">
                    <span class="dashicons dashicons-admin-users" aria-hidden="true"></span>
                    <span class="author vcard">
                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </span>
                </span>
                <?php
                $categories = get_the_category();
                if ($categories) :
                    ?>
                    <span class="cat-links">
                        <span class="dashicons dashicons-category" aria-hidden="true"></span>
                        <?php
                        $category_links = array();
                        foreach ($categories as $category) {
                            $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo implode(', ', $category_links);
                        ?>
                    </span>
                <?php endif; ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('post-thumbnail', array(
                    'alt' => the_title_attribute(array('echo' => false)),
                ));
                ?>
            </a>
        </div><!-- .post-thumbnail -->
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content(sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('继续阅读<span class="screen-reader-text"> "%s"</span>', 'angola-b2b'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ));

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('页面:', 'angola-b2b'),
                'after'  => '</div>',
            ));
        } else {
            the_excerpt();
        }
        ?>
    </div><!-- .entry-content -->

    <?php if (is_singular()) : ?>
        <footer class="entry-footer">
            <?php
            $tags = get_the_tags();
            if ($tags) :
                ?>
                <div class="tags-links">
                    <span class="dashicons dashicons-tag" aria-hidden="true"></span>
                    <?php
                    $tag_links = array();
                    foreach ($tags as $tag) {
                        $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                    }
                    echo implode(', ', $tag_links);
                    ?>
                </div>
            <?php endif; ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

