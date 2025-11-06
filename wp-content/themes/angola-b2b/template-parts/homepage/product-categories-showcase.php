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
$logistics_image = get_option('angola_b2b_product_logistics_image', 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-shipping-solutions-general.jpg?w=600');

// Define product categories - matching the site's 5 main categories
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
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="20" width="18" height="24" rx="1"/><rect x="26" y="14" width="18" height="30" rx="1"/><line x1="8" y1="20" x2="8" y2="12"/><line x1="18" y1="20" x2="18" y2="16"/><circle cx="8" cy="10" r="2" fill="currentColor"/><circle cx="18" cy="14" r="2" fill="currentColor"/><rect x="8" y="26" width="4" height="6"/><rect x="14" y="26" width="4" height="6"/><rect x="8" y="34" width="4" height="6"/><rect x="14" y="34" width="4" height="6"/><rect x="30" y="20" width="4" height="6"/><rect x="36" y="20" width="4" height="6"/><rect x="30" y="28" width="4" height="6"/><rect x="36" y="28" width="4" height="6"/><rect x="30" y="36" width="4" height="6"/><rect x="36" y="36" width="4" height="6"/></svg>',
        'image' => $industrial_image,
        'link' => '#',
        'description' => '加工设备、电力设备、自动化设备、检测设备'
    ),
    array(
        'id' => 'logistics-customs',
        'title' => pll__('物流清关'),
        'title_en' => 'Logistics & Customs',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="10" y="18" width="28" height="20" rx="2"/><path d="M10 24h28M10 30h28"/><circle cx="16" cy="38" r="2" fill="currentColor"/><circle cx="32" cy="38" r="2" fill="currentColor"/><path d="M24 18V10l-6 4h12l-6-4z"/><line x1="18" y1="10" x2="30" y2="10"/></svg>',
        'image' => $logistics_image,
        'link' => '#',
        'description' => '国际运输、清关服务、仓储配送、供应链管理'
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

