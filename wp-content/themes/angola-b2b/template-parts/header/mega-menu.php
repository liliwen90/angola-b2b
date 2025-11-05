<?php
/**
 * Mega Menu Navigation
 * MSC-style large dropdown menu with images and subcategories
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get main categories with their subcategories
$categories_data = angola_b2b_get_main_categories_with_children();

if (empty($categories_data)) {
    return;
}

// Get current category for highlighting
$current_term_id = 0;
if (is_tax('product_category')) {
    $queried_object = get_queried_object();
    if ($queried_object && isset($queried_object->term_id)) {
        $current_term_id = $queried_object->term_id;
    }
}
?>

<nav class="mega-menu-navigation" id="mega-menu-navigation" aria-label="<?php esc_attr_e('Main Navigation', 'angola-b2b'); ?>">
    <ul class="mega-menu-list">
        <?php foreach ($categories_data as $category_data) : 
            $parent = $category_data['parent'];
            $subcategories = $category_data['subcategories'];
            $category_image = $category_data['image'];
            $category_url = $category_data['url'];
            
            // Check if current page belongs to this category
            $is_current = ($current_term_id === $parent->term_id);
            $has_current_child = false;
            if (!empty($subcategories)) {
                foreach ($subcategories as $subcat) {
                    if ($current_term_id === $subcat->term_id) {
                        $has_current_child = true;
                        break;
                    }
                }
            }
            $is_active = $is_current || $has_current_child;
            
            // Generate unique ID for this menu item
            $menu_item_id = 'mega-menu-' . $parent->term_id;
        ?>
            <li class="mega-menu-item <?php echo $is_active ? 'active' : ''; ?>" 
                data-menu-id="<?php echo esc_attr($menu_item_id); ?>">
                <a href="<?php echo esc_url($category_url); ?>" 
                   class="mega-menu-link"
                   aria-expanded="false"
                   aria-controls="<?php echo esc_attr($menu_item_id); ?>">
                    <?php echo esc_html($parent->name); ?>
                </a>
                
                <?php if (!empty($subcategories)) : ?>
                    <div class="mega-menu-dropdown" 
                         id="<?php echo esc_attr($menu_item_id); ?>"
                         aria-hidden="true">
                        <div class="mega-menu-container">
                            <div class="mega-menu-content">
                                <!-- Category Image -->
                                <div class="mega-menu-image">
                                    <?php if ($category_image) : ?>
                                        <img src="<?php echo esc_url($category_image); ?>" 
                                             alt="<?php echo esc_attr($parent->name); ?>"
                                             loading="lazy">
                                    <?php else : ?>
                                        <div class="mega-menu-image-placeholder">
                                            <span><?php esc_html_e('No Image', 'angola-b2b'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Subcategories List -->
                                <div class="mega-menu-links">
                                    <h3 class="mega-menu-category-title">
                                        <a href="<?php echo esc_url($category_url); ?>">
                                            <?php echo esc_html($parent->name); ?>
                                        </a>
                                    </h3>
                                    
                                    <ul class="mega-menu-subcategories">
                                        <?php foreach ($subcategories as $subcat) : 
                                            $is_subcat_current = ($current_term_id === $subcat->term_id);
                                        ?>
                                            <li class="mega-menu-subcategory-item <?php echo $is_subcat_current ? 'current' : ''; ?>">
                                                <a href="<?php echo esc_url(get_term_link($subcat)); ?>">
                                                    <?php echo esc_html($subcat->name); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    
                                    <a href="<?php echo esc_url($category_url); ?>" class="mega-menu-view-all">
                                        <?php esc_html_e('View All', 'angola-b2b'); ?>
                                        <span class="mega-menu-arrow">→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

