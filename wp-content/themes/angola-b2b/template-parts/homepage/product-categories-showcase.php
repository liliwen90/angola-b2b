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

// Define product categories - matching the site's 4 main categories
$product_categories = array(
    array(
        'id' => 'construction-engineering',
        'title' => pll__('建筑工程'),
        'title_en' => 'Construction Engineering',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M3 7v1a3 3 0 0 0 3 3h1m0-4v4m0 0v11m0-11h5m0 0v11m0-11v-4l3 3m-3-3l3-3m2 18v-8a2 2 0 0 1 2-2h1"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-shipping-solutions-general.jpg?w=600',
        'link' => '#',
        'description' => '土方设备、混凝土设备、脚手架系统、起重设备'
    ),
    array(
        'id' => 'building-materials',
        'title' => pll__('建筑材料'),
        'title_en' => 'Building Materials',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-inland-transportation-solutions.jpg?w=600',
        'link' => '#',
        'description' => '钢材、水泥、木材、装饰材料'
    ),
    array(
        'id' => 'agricultural-machinery',
        'title' => pll__('农机农具'),
        'title_en' => 'Agricultural Machinery',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="7" cy="17" r="4"/><path d="M11 17h4m4 0a4 4 0 0 1-4-4V7m0 0V5a2 2 0 0 1 2-2h2m-4 2h4"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-air-cargo-solutions.jpg?w=600',
        'link' => '#',
        'description' => '动力机械、播种设备、收获设备、灌溉设备'
    ),
    array(
        'id' => 'industrial-equipment',
        'title' => pll__('工业设备'),
        'title_en' => 'Industrial Equipment',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 6.5V8m0 8v1.5m4.5-4.5H18m-12 0H4.5M7.757 7.757l-.707-.707m9.9 9.9l.707.707m0-9.9l.707-.707m-9.9 9.9l-.707.707M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/></svg>',
        'image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/homepage/solutions/msc-digital-business-solutions.jpg?w=600',
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
        
        <div class="product-categories-grid">
            <?php foreach ($product_categories as $category) : ?>
                <a href="<?php echo esc_url($category['link']); ?>" class="product-category-card">
                    <div class="category-card-image" style="background-image: url('<?php echo esc_url($category['image']); ?>');">
                        <div class="category-overlay"></div>
                    </div>
                    <div class="category-content">
                        <div class="category-icon">
                            <?php echo $category['icon']; ?>
                        </div>
                        <h3 class="category-title"><?php echo esc_html($category['title']); ?></h3>
                        <p class="category-description"><?php echo esc_html($category['description']); ?></p>
                        <span class="category-link-btn">
                            <?php esc_html_e('Explore Products', 'angola-b2b'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

