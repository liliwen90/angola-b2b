<?php
/**
 * Helper Functions
 * Utility functions used throughout the theme
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get product categories for display
 */
function angola_b2b_get_product_categories($args = array()) {
    $defaults = array(
        'taxonomy'   => 'product_category',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    );
    
    $args = wp_parse_args($args, $defaults);
    $categories = get_terms($args);
    
    if (is_wp_error($categories)) {
        return array();
    }
    
    return $categories;
}

/**
 * Check if product is featured
 */
function angola_b2b_is_featured_product($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $is_featured = get_post_meta($post_id, 'product_featured', true);
    return ($is_featured === '1' || $is_featured === 1);
}

/**
 * Display featured badge
 */
function angola_b2b_featured_badge() {
    if (angola_b2b_is_featured_product()) {
        echo '<span class="featured-badge">' . esc_html__('推荐', 'angola-b2b') . '</span>';
    }
}

/**
 * Get product specifications
 */
function angola_b2b_get_specifications($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!function_exists('have_rows')) {
        return array();
    }
    
    $specs = array();
    
    if (have_rows('specifications', $post_id)) {
        while (have_rows('specifications', $post_id)) {
            the_row();
            $specs[] = array(
                'name'  => get_sub_field('spec_name'),
                'value' => get_sub_field('spec_value'),
                'icon'  => get_sub_field('spec_icon'),
                'level' => get_sub_field('spec_level'),
            );
        }
    }
    
    return $specs;
}

/**
 * Get product gallery images
 */
function angola_b2b_get_gallery_images($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $gallery = get_field('product_gallery', $post_id);
    
    if (empty($gallery) || !is_array($gallery)) {
        return array();
    }
    
    return $gallery;
}

/**
 * Get product 360 images
 */
function angola_b2b_get_360_images($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $images_360 = get_field('product_360_images', $post_id);
    
    if (empty($images_360) || !is_array($images_360)) {
        return array();
    }
    
    return $images_360;
}

/**
 * Format number with animation data attribute
 */
function angola_b2b_animated_number($number, $suffix = '', $decimals = 0) {
    if (!is_numeric($number)) {
        return $number;
    }
    
    return sprintf(
        '<span class="animated-number" data-target="%s" data-decimals="%d">0</span>%s',
        esc_attr($number),
        absint($decimals),
        esc_html($suffix)
    );
}

/**
 * Get reading time estimate
 */
function angola_b2b_get_reading_time($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words/minute
    
    return max(1, $reading_time);
}

/**
 * Truncate text with proper handling of multibyte characters
 */
function angola_b2b_truncate_text($text, $length = 100, $more = '...') {
    $text = wp_strip_all_tags($text);
    
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    return mb_substr($text, 0, $length) . $more;
}

/**
 * Get social share links
 */
function angola_b2b_get_social_share_links($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    
    return array(
        'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($url),
        'twitter'   => 'https://twitter.com/intent/tweet?url=' . rawurlencode($url) . '&text=' . rawurlencode($title),
        'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode($url) . '&title=' . rawurlencode($title),
        'whatsapp'  => 'https://wa.me/?text=' . rawurlencode($title . ' ' . $url),
        'email'     => 'mailto:?subject=' . rawurlencode($title) . '&body=' . rawurlencode($url),
    );
}

/**
 * Display social share buttons
 */
function angola_b2b_social_share_buttons($post_id = 0) {
    $links = angola_b2b_get_social_share_links($post_id);
    
    ob_start();
    ?>
    <div class="social-share-buttons">
        <span class="share-label"><?php esc_html_e('分享:', 'angola-b2b'); ?></span>
        <?php foreach ($links as $network => $link) : ?>
            <a href="<?php echo esc_url($link); ?>" 
               class="share-button share-<?php echo esc_attr($network); ?>"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php echo esc_attr(sprintf(__('Share on %s', 'angola-b2b'), ucfirst($network))); ?>">
                <span class="dashicons dashicons-<?php echo esc_attr($network); ?>"></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Get breadcrumbs
 */
function angola_b2b_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    $breadcrumbs[] = array(
        'title' => __('首页', 'angola-b2b'),
        'url'   => home_url('/'),
    );
    
    if (is_singular('product')) {
        $breadcrumbs[] = array(
            'title' => __('产品', 'angola-b2b'),
            'url'   => get_post_type_archive_link('product'),
        );
        
        $terms = get_the_terms(get_the_ID(), 'product_category');
        if ($terms && !is_wp_error($terms)) {
            $term = array_shift($terms);
            $term_link = get_term_link($term);
            if (!is_wp_error($term_link)) {
                $breadcrumbs[] = array(
                    'title' => $term->name,
                    'url'   => $term_link,
                );
            }
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    } elseif (is_post_type_archive('product')) {
        $breadcrumbs[] = array(
            'title' => __('产品', 'angola-b2b'),
            'url'   => '',
        );
    } elseif (is_tax('product_category')) {
        $term = get_queried_object();
        $breadcrumbs[] = array(
            'title' => __('产品', 'angola-b2b'),
            'url'   => get_post_type_archive_link('product'),
        );
        if ($term && isset($term->name)) {
            $breadcrumbs[] = array(
                'title' => $term->name,
                'url'   => '',
            );
        }
    } elseif (is_page()) {
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    }
    
    if (empty($breadcrumbs)) {
        return;
    }
    
    ob_start();
    ?>
    <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'angola-b2b'); ?>">
        <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($breadcrumbs as $index => $crumb) : ?>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <?php if (!empty($crumb['url'])) : ?>
                        <a href="<?php echo esc_url($crumb['url']); ?>" itemprop="item">
                            <span itemprop="name"><?php echo esc_html($crumb['title']); ?></span>
                        </a>
                    <?php else : ?>
                        <span itemprop="name"><?php echo esc_html($crumb['title']); ?></span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
    return ob_get_clean();
}

/**
 * Get asset URL with version
 */
function angola_b2b_asset_url($path) {
    $file_path = ANGOLA_B2B_THEME_DIR . '/' . ltrim($path, '/');
    $file_url = ANGOLA_B2B_THEME_URI . '/' . ltrim($path, '/');
    
    if (file_exists($file_path)) {
        $version = filemtime($file_path);
        return add_query_arg('ver', $version, $file_url);
    }
    
    return $file_url;
}

/**
 * Check if current page is a product-related page
 */
function angola_b2b_is_product_page() {
    return (is_singular('product') || is_post_type_archive('product') || is_tax('product_category') || is_tax('product_tag'));
}

