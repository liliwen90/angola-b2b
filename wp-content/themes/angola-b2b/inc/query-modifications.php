<?php
/**
 * Query Modifications
 * Modify WordPress queries to include subcategories
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include subcategories in product category archive queries
 */
function angola_b2b_include_subcategories_in_query($query) {
    // Only modify main query on frontend
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // Only for product category taxonomy archives
    if (!is_tax('product_category')) {
        return;
    }
    
    // Get the current tax_query
    $tax_query = $query->get('tax_query');
    
    // WordPress automatically sets tax_query for taxonomy archives, but doesn't include children by default
    // We need to modify it to include children
    if (!empty($tax_query)) {
        foreach ($tax_query as $key => $tax_query_item) {
            if (isset($tax_query_item['taxonomy']) && $tax_query_item['taxonomy'] === 'product_category') {
                $tax_query[$key]['include_children'] = true;
            }
        }
        $query->set('tax_query', $tax_query);
    }
}
add_action('pre_get_posts', 'angola_b2b_include_subcategories_in_query');

/**
 * Force frontend search to include both products and news (posts).
 */
function angola_b2b_limit_search_to_products_and_news($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    // Include both products and news (posts) in search
    $query->set('post_type', array('product', 'post'));
    
    // Add language filter for news (posts) using post_lang meta
    $current_lang = angola_b2b_get_current_language();
    $meta_query = (array) $query->get('meta_query');
    
    // Language filter logic:
    // - For posts (news): must have post_lang matching current language
    // - For products: post_lang should NOT exist (products use ACF multilingual fields)
    $meta_query[] = array(
        'relation' => 'OR',
        // News posts: must have post_lang matching current language
        array(
            'key'     => 'post_lang',
            'value'   => $current_lang,
            'compare' => '=',
        ),
        // Products: post_lang should NOT exist
        array(
            'key'     => 'post_lang',
            'compare' => 'NOT EXISTS',
        ),
    );
    
    $query->set('meta_query', $meta_query);
}
add_action('pre_get_posts', 'angola_b2b_limit_search_to_products_and_news');

/**
 * Extend search to include multilingual fields for both products and news.
 * Products: search ACF multilingual fields based on current language.
 * News (posts): search WordPress native fields (post_title, post_content).
 * Language isolation is handled by meta_query in pre_get_posts.
 */
function angola_b2b_extend_search_meta($search, $query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return $search;
    }

    $post_types = (array) $query->get('post_type');
    $has_product = in_array('product', $post_types, true);
    $has_post = in_array('post', $post_types, true);
    
    if (!$has_product && !$has_post) {
        return $search;
    }

    $search_term = $query->get('s');
    if (empty($search_term)) {
        return $search;
    }

    // Get current language
    $current_lang = angola_b2b_get_current_language();
    
    global $wpdb;
    $like = '%' . $wpdb->esc_like($search_term) . '%';
    
    // Build search conditions
    $search_conditions = array();
    
    // For news (posts): always search WordPress native fields
    if ($has_post) {
        $search_conditions[] = $wpdb->prepare(
            "({$wpdb->posts}.post_type = 'post' AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s))",
            $like,
            $like
        );
    }
    
    // For products: search based on current language
    if ($has_product) {
        if ($current_lang === 'en') {
            // English: search WordPress native fields (post_title, post_content)
            $search_conditions[] = $wpdb->prepare(
                "({$wpdb->posts}.post_type = 'product' AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_content LIKE %s))",
                $like,
                $like
            );
        } else {
            // Other languages: search corresponding ACF fields
            $lang_suffix = '';
            if ($current_lang === 'pt') {
                $lang_suffix = '_pt';
            } elseif ($current_lang === 'zh') {
                $lang_suffix = '_zh';
            } elseif ($current_lang === 'zh_tw') {
                $lang_suffix = '_zh_tw';
            }
            
            if (!empty($lang_suffix)) {
                $meta_keys = array(
                    'title' . $lang_suffix,
                    'content' . $lang_suffix,
                    'short_description' . $lang_suffix,
                );
                
                $product_meta_conditions = array();
                foreach ($meta_keys as $meta_key) {
                    $product_meta_conditions[] = $wpdb->prepare(
                        "EXISTS (SELECT 1 FROM {$wpdb->postmeta} pm WHERE pm.post_id = {$wpdb->posts}.ID AND pm.meta_key = %s AND pm.meta_value LIKE %s)",
                        $meta_key,
                        $like
                    );
                }
                
                if (!empty($product_meta_conditions)) {
                    $search_conditions[] = "({$wpdb->posts}.post_type = 'product' AND (" . implode(' OR ', $product_meta_conditions) . "))";
                }
            }
        }
    }
    
    if (empty($search_conditions)) {
        return $search;
    }
    
    // Replace the default WordPress search with our language-specific search
    $search = ' AND (' . implode(' OR ', $search_conditions) . ')';
    
    return $search;
}
add_filter('posts_search', 'angola_b2b_extend_search_meta', 20, 2);


