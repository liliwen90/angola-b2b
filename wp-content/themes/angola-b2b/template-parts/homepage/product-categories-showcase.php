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

// Get current language from custom multilingual system
$current_lang = angola_b2b_get_current_language();

// Fetch categories by their English slugs (we only have one set of categories now)
$logistics_term = get_term_by('slug', 'logistics', 'product_category');
$materials_term = get_term_by('slug', 'building-materials', 'product_category');
$agricultural_term = get_term_by('slug', 'agricultural-machinery', 'product_category');
$industrial_term = get_term_by('slug', 'industrial-equipment', 'product_category');
$construction_term = get_term_by('slug', 'construction-engineering', 'product_category');

// Helper function to get translated category name from ACF
function angola_b2b_get_category_name($term, $lang) {
    if (!$term) {
        return '';
    }
    
    // Default to English name
    $name = $term->name;
    
    // Try to get translated name from ACF fields
    if ($lang === 'pt' && function_exists('get_field')) {
        $pt_name = get_field('name_pt', $term);
        if (!empty($pt_name)) {
            $name = $pt_name;
        }
    } elseif ($lang === 'zh' && function_exists('get_field')) {
        $zh_name = get_field('name_zh', $term);
        if (!empty($zh_name)) {
            $name = $zh_name;
        }
    } elseif ($lang === 'zh_tw' && function_exists('get_field')) {
        $zh_tw_name = get_field('name_zh_tw', $term);
        if (!empty($zh_tw_name)) {
            $name = $zh_tw_name;
        }
    }
    
    return $name;
}

// Generate category links with language parameter
$logistics_link = $logistics_term && !is_wp_error(get_term_link($logistics_term)) ? add_query_arg('lang', $current_lang, get_term_link($logistics_term)) : '#';
$materials_link = $materials_term && !is_wp_error(get_term_link($materials_term)) ? add_query_arg('lang', $current_lang, get_term_link($materials_term)) : '#';
$agricultural_link = $agricultural_term && !is_wp_error(get_term_link($agricultural_term)) ? add_query_arg('lang', $current_lang, get_term_link($agricultural_term)) : '#';
$industrial_link = $industrial_term && !is_wp_error(get_term_link($industrial_term)) ? add_query_arg('lang', $current_lang, get_term_link($industrial_term)) : '#';
$construction_link = $construction_term && !is_wp_error(get_term_link($construction_term)) ? add_query_arg('lang', $current_lang, get_term_link($construction_term)) : '#';


// Define product categories - matching the site's 5 main categories with ACF translations
$product_categories = array(
    array(
        'id' => 'logistics-customs',
        'title' => angola_b2b_get_category_name($logistics_term, $current_lang),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="10" y="18" width="28" height="20" rx="2"/><path d="M10 24h28M10 30h28"/><circle cx="16" cy="38" r="2" fill="currentColor"/><circle cx="32" cy="38" r="2" fill="currentColor"/><path d="M24 18V10l-6 4h12l-6-4z"/><line x1="18" y1="10" x2="30" y2="10"/></svg>',
        'image' => $logistics_image,
        'link' => $logistics_link,
        'description' => __t('logistics_customs_desc')
    ),
    array(
        'id' => 'building-materials',
        'title' => angola_b2b_get_category_name($materials_term, $current_lang),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="6" width="12" height="12" rx="1"/><rect x="22" y="6" width="12" height="12" rx="1"/><rect x="38" y="6" width="4" height="12" rx="1"/><rect x="6" y="22" width="12" height="12" rx="1"/><rect x="22" y="22" width="12" height="12" rx="1"/><rect x="38" y="22" width="4" height="12" rx="1"/><rect x="6" y="38" width="12" height="4" rx="1"/><rect x="22" y="38" width="12" height="4" rx="1"/><rect x="38" y="38" width="4" height="4" rx="1"/></svg>',
        'image' => $materials_image,
        'link' => $materials_link,
        'description' => __t('building_materials_desc')
    ),
    array(
        'id' => 'agricultural-machinery',
        'title' => angola_b2b_get_category_name($agricultural_term, $current_lang),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="14" cy="36" r="6"/><circle cx="38" cy="36" r="6"/><path d="M20 36h12"/><rect x="16" y="12" width="20" height="16" rx="2"/><path d="M36 20h6v8h-6"/><line x1="8" y1="36" x2="4" y2="36"/><line x1="44" y1="36" x2="42" y2="36"/></svg>',
        'image' => $agricultural_image,
        'link' => $agricultural_link,
        'description' => __t('agricultural_machinery_desc')
    ),
    array(
        'id' => 'industrial-equipment',
        'title' => angola_b2b_get_category_name($industrial_term, $current_lang),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="20" width="18" height="24" rx="1"/><rect x="26" y="14" width="18" height="30" rx="1"/><line x1="8" y1="20" x2="8" y2="12"/><line x1="18" y1="20" x2="18" y2="16"/><circle cx="8" cy="10" r="2" fill="currentColor"/><circle cx="18" cy="14" r="2" fill="currentColor"/><rect x="8" y="26" width="4" height="6"/><rect x="14" y="26" width="4" height="6"/><rect x="8" y="34" width="4" height="6"/><rect x="14" y="34" width="4" height="6"/><rect x="30" y="20" width="4" height="6"/><rect x="36" y="20" width="4" height="6"/><rect x="30" y="28" width="4" height="6"/><rect x="36" y="28" width="4" height="6"/><rect x="30" y="36" width="4" height="6"/><rect x="36" y="36" width="4" height="6"/></svg>',
        'image' => $industrial_image,
        'link' => $industrial_link,
        'description' => __t('industrial_equipment_desc')
    ),
    array(
        'id' => 'construction-engineering',
        'title' => angola_b2b_get_category_name($construction_term, $current_lang),
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="32" width="32" height="12"/><rect x="16" y="20" width="16" height="12"/><rect x="20" y="8" width="8" height="12"/><line x1="4" y1="44" x2="44" y2="44"/></svg>',
        'image' => $construction_image,
        'link' => $construction_link,
        'description' => __t('construction_engineering_desc')
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
            <h2 class="section-title"><?php _et('our_products'); ?></h2>
            <p class="section-subtitle">
                <?php _et('our_products_intro'); ?>
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
                            <h3 class="category-title">
                                <?php echo esc_html($category['title']); ?>
                            </h3>
                            <p class="category-description"><?php echo esc_html($category['description']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

