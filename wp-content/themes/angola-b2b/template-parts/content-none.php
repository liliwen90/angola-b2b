<?php
/**
 * Template part for displaying a message when no content is found
 *
 * @package Angola_B2B
 */

?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('未找到内容', 'angola-b2b'); ?></h1>
    </header>

    <div class="page-content">
        <?php if (is_home() && current_user_can('publish_posts')) : ?>

            <p>
                <?php
                printf(
                    wp_kses(
                        /* translators: 1: link to WP admin new post page. */
                        __('准备发布您的第一篇文章吗？<a href="%1$s">从这里开始</a>。', 'angola-b2b'),
                        array(
                            'a' => array(
                                'href' => array(),
                            ),
                        )
                    ),
                    esc_url(admin_url('post-new.php'))
                );
                ?>
            </p>

        <?php elseif (is_search()) : ?>

            <p><?php esc_html_e('抱歉，没有找到与您的搜索相关的内容。请尝试使用其他关键词。', 'angola-b2b'); ?></p>
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

        <?php elseif (is_post_type_archive('product') || is_tax('product_category')) : ?>

            <p><?php esc_html_e('暂时没有产品。请稍后再来查看，或使用搜索功能。', 'angola-b2b'); ?></p>
            <div class="no-results-actions">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <span class="dashicons dashicons-admin-home"></span>
                    <?php esc_html_e('返回首页', 'angola-b2b'); ?>
                </a>
                <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-secondary">
                    <span class="dashicons dashicons-products"></span>
                    <?php esc_html_e('查看所有产品', 'angola-b2b'); ?>
                </a>
            </div>

        <?php else : ?>

            <p><?php esc_html_e('看起来这里什么都没有。也许试试搜索？', 'angola-b2b'); ?></p>
            <div class="search-form-container">
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>
    </div>
</section>
