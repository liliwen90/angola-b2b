<?php
/**
 * News integration: seed demo posts and helpers
 *
 * @package Angola_B2B
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Seed 6 demo news posts.
 * When $force = true, ignore existing posts and seed anyway.
 * Otherwise: run once if there are no published posts.
 */
function angola_b2b_seed_demo_news_if_needed($force = false) {
    if (!is_admin()) {
        return;
    }
    if (!$force && get_option('angola_b2b_demo_news_seeded')) {
        return;
    }
    if (!$force) {
        $existing = wp_count_posts('post');
        if ($existing && intval($existing->publish) > 0) {
            update_option('angola_b2b_demo_news_seeded', 1);
            return;
        }
    }

    $titles = array(
        'Angola B2B参加国际贸易博览会',
        '农业设备新品发布，提升产能',
        '安哥拉仓储扩建启用',
        '绿色设备计划：更环保的解决方案',
        '24/7技术支持服务上线',
        '与行业领先制造商建立战略合作',
    );
    $placeholders = array(
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-1.jpg',
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-2.jpg',
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-3.jpg',
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-4.jpg',
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-5.jpg',
        ANGOLA_B2B_THEME_URI . '/assets/images/news/news-6.jpg',
    );
    $contents = array(
        '我们在卢安达国际贸易博览会上展示了建筑与工业设备的完整解决方案。',
        '全新农业机械系列正式发布，旨在提升农场生产效率并降低维护成本。',
        '位于卢安达的新仓储设施正式投入使用，加速交付与本地服务响应速度。',
        '推出低排放高效率的环保设备系列，助力客户实现可持续发展目标。',
        '全天候技术支持中心上线，保证客户业务连续性与设备稳定运行。',
        '与全球领先制造商达成战略合作，提供更优质的工业设备与服务。',
    );

    for ($i = 0; $i < 6; $i++) {
        $post_id = wp_insert_post(array(
            'post_title'   => $titles[$i],
            'post_content' => $contents[$i],
            'post_status'  => 'publish',
            'post_type'    => 'post',
        ));
        // Try to set a remote placeholder as featured (only stores URL in content, WP needs attachment to set featured image)
        // As a lightweight fallback, we append an image at the top of content.
        if ($post_id && !is_wp_error($post_id)) {
            $img = $placeholders[$i];
            $content = '<p><img src="' . esc_url($img) . '" alt=""></p>' . get_post_field('post_content', $post_id);
            wp_update_post(array('ID' => $post_id, 'post_content' => $content));
            // Set language meta (default zh) and a unique group id
            update_post_meta($post_id, 'post_lang', 'zh');
            update_post_meta($post_id, 'lang_group', 'grp_' . uniqid());
        }
    }
    update_option('angola_b2b_demo_news_seeded', 1);
}
add_action('admin_init', 'angola_b2b_seed_demo_news_if_needed');

/**
 * Admin trigger: ?angola_seed_news=1 will force-generate 6 demo posts once.
 * Only for administrators.
 */
function angola_b2b_seed_news_admin_trigger() {
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }
    if (isset($_GET['angola_seed_news']) && $_GET['angola_seed_news'] == '1') {
        angola_b2b_seed_demo_news_if_needed(true);
        add_action('admin_notices', function () {
            echo '<div class="notice notice-success is-dismissible"><p>已强制生成6篇假新闻（中文）。</p></div>';
        });
    }
}
add_action('admin_init', 'angola_b2b_seed_news_admin_trigger', 20);

/**
 * Ensure posts page link works in carousel.
 * If page_for_posts not set, create a "News" page and set it.
 */
function angola_b2b_ensure_posts_page() {
    if (get_option('page_for_posts')) {
        return;
    }
    // Create a page named "News" if not exists
    $page = get_page_by_path('news');
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title'   => 'News',
            'post_name'    => 'news',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ));
        if ($page_id && !is_wp_error($page_id)) {
            update_option('page_for_posts', $page_id);
        }
    } else {
        update_option('page_for_posts', $page->ID);
    }
}
add_action('admin_init', 'angola_b2b_ensure_posts_page');


