<?php
/**
 * Homepage Category Showcase Section
 * Displays main product categories in MSC-style card layout with background images
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define main categories with MSC placeholder images
$categories_showcase = array(
    array(
        'id'    => 'construction-engineering',
        'name'  => '建筑工程',
        'slug'  => '建筑工程',
        'icon'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor"><rect x="8" y="24" width="48" height="32" stroke-width="2"/><rect x="16" y="32" width="8" height="8" stroke-width="2"/><rect x="28" y="32" width="8" height="8" stroke-width="2"/><rect x="40" y="32" width="8" height="8" stroke-width="2"/><rect x="16" y="44" width="8" height="8" stroke-width="2"/><rect x="28" y="44" width="8" height="8" stroke-width="2"/><rect x="40" y="44" width="8" height="8" stroke-width="2"/><path d="M8 24L32 8L56 24" stroke-width="2"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/dry-cargo/msc-dry-cargo-shipping-solutions-hero.jpg?w=800',
    ),
    array(
        'id'    => 'building-materials',
        'name'  => '建筑材料',
        'slug'  => '建筑材料',
        'icon'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor"><rect x="12" y="12" width="16" height="16" stroke-width="2"/><rect x="36" y="12" width="16" height="16" stroke-width="2"/><rect x="12" y="36" width="16" height="16" stroke-width="2"/><rect x="36" y="36" width="16" height="16" stroke-width="2"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/card-inland-transportation-and-logistics-solutions.jpg?w=800',
    ),
    array(
        'id'    => 'agricultural-equipment',
        'name'  => '农机农具',
        'slug'  => '农机农具',
        'icon'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor"><circle cx="20" cy="40" r="12" stroke-width="2"/><circle cx="44" cy="40" r="12" stroke-width="2"/><rect x="16" y="16" width="32" height="16" stroke-width="2"/><path d="M24 16V8L32 4L40 8V16" stroke-width="2"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800',
    ),
    array(
        'id'    => 'industrial-equipment',
        'name'  => '工业设备',
        'slug'  => '工业设备',
        'icon'  => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor"><rect x="12" y="20" width="40" height="32" stroke-width="2"/><circle cx="24" cy="36" r="6" stroke-width="2"/><circle cx="40" cy="36" r="6" stroke-width="2"/><path d="M20 20V12L32 8L44 12V20" stroke-width="2"/><rect x="28" y="44" width="8" height="8" stroke-width="2"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/project-cargo/msc-project-cargo-shipping-solutions-hero.jpg?w=800',
    ),
);
?>

<section class="category-showcase-section">
    <div class="category-showcase-grid">
        <?php foreach ($categories_showcase as $category) : 
            // Get category URL
            $category_url = get_term_link($category['slug'], 'product_category');
            if (is_wp_error($category_url)) {
                $category_url = '#';
            }
        ?>
            <article class="category-showcase-card" data-category="<?php echo esc_attr($category['id']); ?>">
                <a href="<?php echo esc_url($category_url); ?>" class="category-card-link">
                    <div class="category-card-background">
                        <img src="<?php echo esc_url($category['image']); ?>" 
                             alt="<?php echo esc_attr($category['name']); ?>"
                             loading="lazy">
                        <div class="category-card-overlay"></div>
                    </div>
                    
                    <div class="category-card-content">
                        <div class="category-card-icon">
                            <?php echo $category['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                        <h2 class="category-card-title"><?php echo esc_html($category['name']); ?></h2>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

