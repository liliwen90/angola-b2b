<?php
/**
 * ACF Field Filters
 * Ensure relationship fields only show products, not taxonomy terms
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Filter ACF relationship field to ensure only products are shown
 */
function angola_b2b_filter_relationship_field_posts($args, $field, $post_id) {
    // Only filter banner_products field
    if ($field['name'] === 'banner_products') {
        // Ensure only product post type
        $args['post_type'] = array('product');
        
        // Ensure only published posts
        if (!isset($args['post_status'])) {
            $args['post_status'] = array('publish');
        }
        
        // Make sure we're not querying taxonomy terms
        $args['tax_query'] = array();
    }
    
    return $args;
}
add_filter('acf/fields/relationship/query', 'angola_b2b_filter_relationship_field_posts', 10, 3);

