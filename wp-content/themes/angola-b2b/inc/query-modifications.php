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

