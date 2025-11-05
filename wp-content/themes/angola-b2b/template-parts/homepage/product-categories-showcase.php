<?php
/**
 * Product Categories Showcase Section
 * MSC-style full-width category cards with background images
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get custom images from database
$construction_image = get_option('angola_b2b_product_construction_image', 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-shipping-solutions-general.jpg?w=600');
$materials_image = get_option('angola_b2b_product_materials_image', 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-inland-transportation-solutions.jpg?w=600');
$agricultural_image = get_option('angola_b2b_product_agricultural_image', 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-air-cargo-solutions.jpg?w=600');
$industrial_image = get_option('angola_b2b_product_industrial_image', 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-digital-business-solutions.jpg?w=600');

// Define product categories - matching the site's 4 main categories
$product_categories = array(
    array(
        'id' => 'construction-engineering',
        'title' => pll__('建筑工程'),
        'title_en' => 'Construction Engineering',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="32" width="32" height="12"/><rect x="16" y="20" width="16" height="12"/><rect x="20" y="8" width="8" height="12"/><line x1="4" y1="44" x2="44" y2="44"/></svg>',
        'image' => $construction_image,
        'link' => '#',
        'description' => '土方设备、混凝土设备、脚手架系统、起重设备'
    ),
    array(
        'id' => 'building-materials',
        'title' => pll__('建筑材料'),
        'title_en' => 'Building Materials',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="1"/><rect x="22" y="6" width="12" height="12" rx="1"/><rect x="38" y="6" width="4" height="12" rx="1"/><rect x="6" y="22" width="12" height="12" rx="1"/><rect x="22" y="22" width="12" height="12" rx="1"/><rect x="38" y="22" width="4" height="12" rx="1"/><rect x="6" y="38" width="12" height="4" rx="1"/><rect x="22" y="38" width="12" height="4" rx="1"/><rect x="38" y="38" width="4" height="4" rx="1"/></svg>',
        'image' => $materials_image,
        'link' => '#',
        'description' => '钢材、水泥、木材、装饰材料'
    ),
    array(
        'id' => 'agricultural-machinery',
        'title' => pll__('农机农具'),
        'title_en' => 'Agricultural Machinery',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="14" cy="36" r="6"/><circle cx="38" cy="36" r="6"/><path d="M20 36h12"/><rect x="16" y="12" width="20" height="16" rx="2"/><path d="M36 20h6v8h-6"/><line x1="8" y1="36" x2="4" y2="36"/><line x1="44" y1="36" x2="42" y2="36"/></svg>',
        'image' => $agricultural_image,
        'link' => '#',
        'description' => '动力机械、播种设备、收获设备、灌溉设备'
    ),
    array(
        'id' => 'industrial-equipment',
        'title' => pll__('工业设备'),
        'title_en' => 'Industrial Equipment',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="24" cy="24" r="8"/><circle cx="24" cy="24" r="3"/><line x1="24" y1="4" x2="24" y2="10"/><line x1="24" y1="38" x2="24" y2="44"/><line x1="44" y1="24" x2="38" y2="24"/><line x1="10" y1="24" x2="4" y2="24"/><line x1="38.5" y1="9.5" x2="34.5" y2="13.5"/><line x1="13.5" y1="34.5" x2="9.5" y2="38.5"/><line x1="38.5" y1="38.5" x2="34.5" y2="34.5"/><line x1="13.5" y1="13.5" x2="9.5" y2="9.5"/></svg>',
        'image' => $industrial_image,
        'link' => '#',
        'description' => '加工设备、电力设备、自动化设备、检测设备'
    ),
);

$product_categories = apply_filters('angola_b2b_product_categories', $product_categories);

if (empty($product_categories)) {
    return;
}
?>

<section class="product-categories-showcase section-padding" data-animate="fade-up">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title"><?php esc_html_e('Our Products', 'angola-b2b'); ?></h2>
            <p class="section-subtitle">
                <?php esc_html_e('We specialize in sourcing premium quality products from China and delivering them to Angola. Our extensive product range covers construction, agriculture, and industrial sectors, providing reliable solutions for your business needs.', 'angola-b2b'); ?>
            </p>
        </div>
        
        <div class="product-categories-wrapper">
            <!-- Background Images Container -->
            <div class="categories-background">
                <?php foreach ($product_categories as $index => $category) : ?>
                    <div class="category-bg-image <?php echo $index === 0 ? 'active' : ''; ?>" 
                         data-category="<?php echo esc_attr($category['id']); ?>"
                         style="background-image: url('<?php echo esc_url($category['image']); ?>');">
                    </div>
                <?php endforeach; ?>
                <div class="category-bg-overlay"></div>
            </div>
            
            <!-- Category Cards -->
            <div class="product-categories-grid">
                <?php foreach ($product_categories as $category) : ?>
                    <a href="<?php echo esc_url($category['link']); ?>" 
                       class="product-category-card" 
                       data-category="<?php echo esc_attr($category['id']); ?>">
                        <div class="category-content">
                            <div class="category-icon">
                                <?php echo $category['icon']; ?>
                            </div>
                            <h3 class="category-title"><?php echo esc_html($category['title']); ?></h3>
                            <p class="category-description"><?php echo esc_html($category['description']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

