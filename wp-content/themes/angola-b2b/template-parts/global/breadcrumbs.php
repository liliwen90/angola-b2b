<?php
/**
 * Breadcrumbs Component
 * MSC-style breadcrumb navigation
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Don't show on homepage
if (is_front_page()) {
    return;
}

/**
 * Helper function to get translated category name
 */
function angola_b2b_get_translated_term_name($term) {
    if (!$term) {
        return '';
    }
    
    $current_lang = angola_b2b_get_current_language();
    $translated_name = $term->name; // Default to English name
    
    // Try to get translated name from ACF fields
    if ($current_lang === 'pt' && function_exists('get_field')) {
        $pt_name = get_field('name_pt', $term);
        if (!empty($pt_name)) {
            $translated_name = $pt_name;
        }
    } elseif ($current_lang === 'zh' && function_exists('get_field')) {
        $zh_name = get_field('name_zh', $term);
        if (!empty($zh_name)) {
            $translated_name = $zh_name;
        }
    } elseif ($current_lang === 'zh_tw' && function_exists('get_field')) {
        $zh_tw_name = get_field('name_zh_tw', $term);
        if (!empty($zh_tw_name)) {
            $translated_name = $zh_tw_name;
        }
    }
    
    return $translated_name;
}

$breadcrumbs = array();

// Always start with home
$breadcrumbs[] = array(
    'title' => __t('home'),
    'url'   => home_url('/'),
);

// Product single page
if (is_singular('product')) {
    $breadcrumbs[] = array(
        'title' => __t('products'),
        'url'   => get_post_type_archive_link('product'),
    );
    
    // Get product category
    $terms = get_the_terms(get_the_ID(), 'product_category');
    if ($terms && !is_wp_error($terms)) {
        $term = array_shift($terms);
        
        // Include parent categories if any
        $term_chain = array();
        $current_term = $term;
        while ($current_term && $current_term->parent) {
            $parent_term = get_term($current_term->parent, 'product_category');
            if ($parent_term && !is_wp_error($parent_term)) {
                array_unshift($term_chain, $parent_term);
                $current_term = $parent_term;
            } else {
                break;
            }
        }
        
        // Add parent categories
        foreach ($term_chain as $parent_term) {
            $term_link = get_term_link($parent_term);
            if (!is_wp_error($term_link)) {
                $breadcrumbs[] = array(
                    'title' => angola_b2b_get_translated_term_name($parent_term),
                    'url'   => $term_link,
                );
            }
        }
        
        // Add current category
        $term_link = get_term_link($term);
        if (!is_wp_error($term_link)) {
            $breadcrumbs[] = array(
                'title' => angola_b2b_get_translated_term_name($term),
                'url'   => $term_link,
            );
        }
    }
    
    // Current product (no link)
    $breadcrumbs[] = array(
        'title' => get_the_title(),
        'url'   => '',
    );
} 
// Product archive page
elseif (is_post_type_archive('product')) {
    $breadcrumbs[] = array(
        'title' => __t('products'),
        'url'   => '',
    );
} 
// Product category taxonomy
elseif (is_tax('product_category')) {
    $term = get_queried_object();
    
    $breadcrumbs[] = array(
        'title' => __t('products'),
        'url'   => get_post_type_archive_link('product'),
    );
    
    // Include parent categories if any
    if ($term && isset($term->term_id) && $term->parent) {
        $term_chain = array();
        $current_term = $term;
        while ($current_term && $current_term->parent) {
            $parent_term = get_term($current_term->parent, 'product_category');
            if ($parent_term && !is_wp_error($parent_term)) {
                array_unshift($term_chain, $parent_term);
                $current_term = $parent_term;
            } else {
                break;
            }
        }
        
        // Add parent categories
        foreach ($term_chain as $parent_term) {
            $term_link = get_term_link($parent_term);
            if (!is_wp_error($term_link)) {
                $breadcrumbs[] = array(
                    'title' => angola_b2b_get_translated_term_name($parent_term),
                    'url'   => $term_link,
                );
            }
        }
    }
    
    // Current category (no link)
    if ($term && isset($term->name)) {
        $breadcrumbs[] = array(
            'title' => angola_b2b_get_translated_term_name($term),
            'url'   => '',
        );
    }
} 
// Regular page
elseif (is_page()) {
    $page = get_queried_object();
    
    // Include parent pages if any
    if ($page && isset($page->post_parent) && $page->post_parent) {
        $parent_ids = array();
        $current_id = $page->post_parent;
        
        while ($current_id) {
            $parent_ids[] = $current_id;
            $parent = get_post($current_id);
            if ($parent && $parent->post_parent) {
                $current_id = $parent->post_parent;
            } else {
                break;
            }
        }
        
        // Add parent pages (from top to bottom)
        $parent_ids = array_reverse($parent_ids);
        foreach ($parent_ids as $parent_id) {
            $parent = get_post($parent_id);
            if ($parent) {
                $breadcrumbs[] = array(
                    'title' => $parent->post_title,
                    'url'   => get_permalink($parent_id),
                );
            }
        }
    }
    
    // Current page (no link)
    $breadcrumbs[] = array(
        'title' => get_the_title(),
        'url'   => '',
    );
}

// Don't output if only home
if (count($breadcrumbs) <= 1) {
    return;
}
?>

<nav class="breadcrumbs" aria-label="<?php echo esc_attr(__t('breadcrumb')); ?>">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumbs as $index => $crumb) : ?>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if (!empty($crumb['url'])) : ?>
                    <a href="<?php echo esc_url($crumb['url']); ?>" itemprop="item" class="breadcrumb-link">
                        <span itemprop="name"><?php echo esc_html($crumb['title']); ?></span>
                    </a>
                <?php else : ?>
                    <span class="breadcrumb-current" itemprop="name"><?php echo esc_html($crumb['title']); ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo esc_attr($index + 1); ?>">
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

