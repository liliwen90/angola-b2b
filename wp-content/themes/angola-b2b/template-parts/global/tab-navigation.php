<?php
/**
 * Tab Navigation Component
 * MSC-style page-internal tab navigation
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get custom args if passed via global
global $tab_nav_args;
if (isset($tab_nav_args) && is_array($tab_nav_args)) {
    $custom_args = $tab_nav_args;
} else {
    $custom_args = array();
}

// Parse arguments with defaults
$args = wp_parse_args($custom_args, array(
    'tabs' => array(),
    'wrapper_class' => '',
    'sticky' => false,
));

// If no tabs provided, try to get from ACF
if (empty($args['tabs'])) {
    if (function_exists('get_field')) {
        $enable_tabs = get_field('enable_tab_navigation');
        $tab_items = get_field('tab_items');
        
        if ($enable_tabs && $tab_items && is_array($tab_items)) {
            $args['tabs'] = array();
            foreach ($tab_items as $tab_item) {
                if (!empty($tab_item['tab_title']) && !empty($tab_item['tab_anchor_id'])) {
                    $args['tabs'][] = array(
                        'title' => $tab_item['tab_title'],
                        'anchor' => sanitize_title($tab_item['tab_anchor_id']),
                    );
                }
            }
        }
    }
}

// Fallback: Generate tabs from content sections if available
if (empty($args['tabs'])) {
    // Try to detect common sections
    $sections = array();
    
    if (is_singular('product')) {
        // Product page default tabs
        $sections = array(
            array('title' => pll__('概览', 'angola-b2b') ?: __('概览', 'angola-b2b'), 'anchor' => 'overview'),
            array('title' => pll__('规格', 'angola-b2b') ?: __('规格', 'angola-b2b'), 'anchor' => 'specifications'),
            array('title' => pll__('认证', 'angola-b2b') ?: __('认证', 'angola-b2b'), 'anchor' => 'certifications'),
            array('title' => pll__('案例', 'angola-b2b') ?: __('案例', 'angola-b2b'), 'anchor' => 'cases'),
        );
    } elseif (is_tax('product_category')) {
        // Category page default tabs
        $sections = array(
            array('title' => pll__('概览', 'angola-b2b') ?: __('概览', 'angola-b2b'), 'anchor' => 'overview'),
            array('title' => pll__('产品', 'angola-b2b') ?: __('产品', 'angola-b2b'), 'anchor' => 'products'),
            array('title' => pll__('优势', 'angola-b2b') ?: __('优势', 'angola-b2b'), 'anchor' => 'advantages'),
        );
    }
    
    if (!empty($sections)) {
        $args['tabs'] = $sections;
    }
}

// Don't output if no tabs
if (empty($args['tabs'])) {
    return;
}

$wrapper_classes = array('tab-navigation');
if ($args['sticky']) {
    $wrapper_classes[] = 'tab-navigation-sticky';
}
if (!empty($args['wrapper_class'])) {
    $wrapper_classes[] = $args['wrapper_class'];
}
?>

<nav class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" aria-label="<?php esc_attr_e('Page Navigation', 'angola-b2b'); ?>">
    <div class="tab-navigation-container">
        <ul class="tab-list" role="tablist">
            <?php foreach ($args['tabs'] as $index => $tab) : 
                $tab_id = 'tab-' . sanitize_title($tab['anchor']);
                $is_first = ($index === 0);
            ?>
                <li class="tab-item" role="presentation">
                    <a 
                        href="#<?php echo esc_attr($tab['anchor']); ?>" 
                        class="tab-link <?php echo $is_first ? 'active' : ''; ?>"
                        role="tab"
                        aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($tab_id); ?>"
                        data-tab="<?php echo esc_attr($tab['anchor']); ?>"
                    >
                        <?php echo esc_html($tab['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>

