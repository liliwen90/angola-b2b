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
 * Get main product categories (parent = 0) with their subcategories
 * Used for navigation menu
 */
function angola_b2b_get_main_categories_with_children() {
    // Get all parent categories (parent = 0)
    $parent_categories = get_terms(array(
        'taxonomy'   => 'product_category',
        'parent'     => 0,
        'hide_empty' => false, // Show even if empty for navigation
        'orderby'    => 'term_order',
        'order'      => 'ASC',
    ));
    
    if (is_wp_error($parent_categories) || empty($parent_categories)) {
        return array();
    }
    
    // Filter out old Chinese category names - only show English slugs
    $excluded_names = array('建筑工程', '建筑材料', '农机农具', '工业设备');
    
    $categories_data = array();
    
    foreach ($parent_categories as $parent) {
        // Skip excluded categories
        if (in_array($parent->name, $excluded_names)) {
            continue;
        }
        // Get subcategories for this parent
        $subcategories = get_terms(array(
            'taxonomy'   => 'product_category',
            'parent'     => $parent->term_id,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));
        
        // Get category image (from ACF or term meta)
        $category_image = '';
        if (function_exists('get_field')) {
            $category_image = get_field('category_nav_image', 'product_category_' . $parent->term_id);
        }
        
        // Fallback: use MSC placeholder images based on category name
        if (empty($category_image)) {
            $category_image = angola_b2b_get_category_placeholder_image($parent->slug);
        }
        
        $categories_data[] = array(
            'parent'        => $parent,
            'subcategories' => is_wp_error($subcategories) ? array() : $subcategories,
            'image'         => $category_image,
            'url'           => get_term_link($parent),
        );
    }
    
    return $categories_data;
}

/**
 * Get placeholder image URL for category navigation
 * Uses MSC website images as placeholders
 */
function angola_b2b_get_category_placeholder_image($category_slug) {
    // Map category slugs to placeholder images (using Unsplash)
    $placeholder_images = array(
        // English slugs
        'construction-engineering' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&h=300&fit=crop',
        'building-materials' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&h=300&fit=crop',
        'agricultural-machinery' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=400&h=300&fit=crop',
        'industrial-equipment' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&h=300&fit=crop',
        // Chinese slugs (fallback)
        '建筑工程' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=400&h=300&fit=crop',
        '建筑材料' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=400&h=300&fit=crop',
        '农机农具' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=400&h=300&fit=crop',
        '工业设备' => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&h=300&fit=crop',
    );
    
    // Try exact match first
    if (isset($placeholder_images[$category_slug])) {
        return $placeholder_images[$category_slug];
    }
    
    // Try partial match (contains) - case insensitive
    $category_slug_lower = strtolower($category_slug);
    foreach ($placeholder_images as $key => $url) {
        $key_lower = strtolower($key);
        if (strpos($category_slug_lower, $key_lower) !== false || strpos($key_lower, $category_slug_lower) !== false) {
            return $url;
        }
    }
    
    // Try matching by category name keywords
    if (stripos($category_slug, 'construction') !== false || stripos($category_slug, '工程') !== false) {
        return $placeholder_images['construction-engineering'];
    }
    if (stripos($category_slug, 'building') !== false || stripos($category_slug, '材料') !== false) {
        return $placeholder_images['building-materials'];
    }
    if (stripos($category_slug, 'agricultural') !== false || stripos($category_slug, '农机') !== false) {
        return $placeholder_images['agricultural-machinery'];
    }
    if (stripos($category_slug, 'industrial') !== false || stripos($category_slug, '工业') !== false) {
        return $placeholder_images['industrial-equipment'];
    }
    
    // Default placeholder
    return 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=400&h=300&fit=crop';
}

/**
 * Display Hero Section Component
 * Helper function to easily include hero section with arguments
 *
 * @param array $args Hero section arguments
 */
function angola_b2b_display_hero_section($args = array()) {
    global $hero_args;
    $hero_args = $args;
    get_template_part('template-parts/global/hero-section');
    unset($hero_args);
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
 * Get product specifications (Simplified version)
 * Only basic name-value pairs, no icons or levels
 */
function angola_b2b_get_specifications($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $specs = array();
    
    // Simple version: collect from 8 fixed fields
    for ($i = 1; $i <= 8; $i++) {
        $spec_name = get_field('spec_name_' . $i, $post_id);
        if (!empty($spec_name)) {
            $specs[] = array(
                'name'  => $spec_name,
                'value' => get_field('spec_value_' . $i, $post_id),
            );
        }
    }
    
    return $specs;
}

/**
 * Get product gallery images (Simplified version)
 * Collect from 5 image fields - enough for most products
 */
function angola_b2b_get_gallery_images($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $gallery = array();
    
    // Try multiple possible field naming conventions
    $field_names = array(
        'product_image_',      // product_image_1, product_image_2, etc.
        'product_gallery_',    // product_gallery_1, product_gallery_2, etc.
        'chan_pin_tu_pian_',   // 产品图片_1 (possible Chinese pinyin)
    );
    
    // Collect from 5 image fields
    for ($i = 1; $i <= 5; $i++) {
        foreach ($field_names as $prefix) {
            $field_name = $prefix . $i;
            $image = get_field($field_name, $post_id);
            
            if (!empty($image)) {
                // Handle different ACF return formats
                if (is_array($image)) {
                    // Image Array format
                    if (!empty($image['url'])) {
                        $gallery[] = $image;
                        break; // Found image, stop trying other prefixes
                    }
                } elseif (is_numeric($image)) {
                    // Image ID format
                    $image_array = wp_get_attachment_image_src($image, 'full');
                    if ($image_array) {
                        $gallery[] = array(
                            'id'  => $image,
                            'url' => $image_array[0],
                            'alt' => get_post_meta($image, '_wp_attachment_image_alt', true),
                        );
                        break; // Found image, stop trying other prefixes
                    }
                } elseif (is_string($image)) {
                    // Image URL format (direct URL string)
                    $gallery[] = array(
                        'url' => $image,
                        'alt' => get_the_title($post_id),
                    );
                    break; // Found image, stop trying other prefixes
                }
            }
        }
    }
    
    return $gallery;
}

/**
 * Get product 360 images
 * DEPRECATED: 360-degree rotation feature removed for simplicity
 * Keeping function for backward compatibility, returns empty array
 */
function angola_b2b_get_360_images($post_id = 0) {
    // Feature removed - no longer collecting 360 images
    return array();
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
        <span class="share-label"><?php _et('share'); ?>:</span>
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
 * Display Breadcrumbs Component
 * Helper function to easily include breadcrumbs component
 *
 * @package Angola_B2B
 */
function angola_b2b_display_breadcrumbs() {
    get_template_part('template-parts/global/breadcrumbs');
}

/**
 * Display Tab Navigation Component
 * Helper function to easily include tab navigation component
 *
 * @param array $args Tab navigation arguments
 * @package Angola_B2B
 */
function angola_b2b_display_tab_navigation($args = array()) {
    global $tab_nav_args;
    $tab_nav_args = $args;
    get_template_part('template-parts/global/tab-navigation');
    unset($tab_nav_args);
}

/**
 * Display Title + Description Block
 * Helper function to display title and description content block
 *
 * @param array $args Block arguments
 * @package Angola_B2B
 */
function angola_b2b_display_title_description($args = array()) {
    global $block_args;
    $block_args = $args;
    get_template_part('template-parts/blocks/title-description');
    unset($block_args);
}

/**
 * Display Advantages List Block
 * Helper function to display advantages/features list block
 *
 * @param array $args Block arguments
 * @package Angola_B2B
 */
function angola_b2b_display_advantages_list($args = array()) {
    global $block_args;
    $block_args = $args;
    get_template_part('template-parts/blocks/advantages-list');
    unset($block_args);
}

/**
 * Display Image + Text Block
 * Helper function to display image and text content block
 *
 * @param array $args Block arguments
 * @package Angola_B2B
 */
function angola_b2b_display_image_text($args = array()) {
    global $block_args;
    $block_args = $args;
    get_template_part('template-parts/blocks/image-text');
    unset($block_args);
}

/**
 * Display Services List Block
 * Helper function to display services list block
 *
 * @param array $args Block arguments
 * @package Angola_B2B
 */
function angola_b2b_display_services_list($args = array()) {
    global $block_args;
    $block_args = $args;
    get_template_part('template-parts/blocks/services-list');
    unset($block_args);
}

/**
 * Get breadcrumbs (deprecated - use angola_b2b_display_breadcrumbs instead)
 * Kept for backward compatibility
 * 
 * @deprecated Use angola_b2b_display_breadcrumbs() instead
 */
function angola_b2b_breadcrumbs() {
    ob_start();
    angola_b2b_display_breadcrumbs();
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

