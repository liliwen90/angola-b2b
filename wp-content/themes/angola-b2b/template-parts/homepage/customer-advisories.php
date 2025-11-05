<?php
/**
 * Customer Advisories Section
 * MSC-style customer advisories list
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define advisories (can be pulled from custom post type later)
$advisories = array(
    array(
        'date' => '05/11/2025',
        'title' => __('New Product Lines Available - Premium Construction Equipment', 'angola-b2b'),
        'categories' => array(__('Product Update', 'angola-b2b'), __('Construction', 'angola-b2b')),
    ),
    array(
        'date' => '05/11/2025',
        'title' => __('Temporary Stock Adjustment - Agricultural Machinery', 'angola-b2b'),
        'categories' => array(__('Stock Update', 'angola-b2b'), __('Agriculture', 'angola-b2b')),
    ),
    array(
        'date' => '03/11/2025',
        'title' => __('Price Announcement - Industrial Equipment Catalog 2025', 'angola-b2b'),
        'categories' => array(__('Pricing', 'angola-b2b'), __('Industrial', 'angola-b2b')),
    ),
    array(
        'date' => '03/11/2025',
        'title' => __('New Service: Equipment Maintenance Plans', 'angola-b2b'),
        'categories' => array(__('Service Update', 'angola-b2b')),
    ),
    array(
        'date' => '30/10/2025',
        'title' => __('Extended Warranty Program Now Available', 'angola-b2b'),
        'categories' => array(__('Customer Advisory', 'angola-b2b'), __('Warranty', 'angola-b2b')),
    ),
    array(
        'date' => '29/10/2025',
        'title' => __('Delivery Schedule Changes for Holiday Season', 'angola-b2b'),
        'categories' => array(__('Logistics', 'angola-b2b'), __('Advisory', 'angola-b2b')),
    ),
    array(
        'date' => '16/10/2025',
        'title' => __('Updated Safety Standards for Construction Equipment', 'angola-b2b'),
        'categories' => array(__('Safety', 'angola-b2b'), __('Compliance', 'angola-b2b')),
    ),
    array(
        'date' => '13/10/2025',
        'title' => __('Special Promotion: End of Year Equipment Sale', 'angola-b2b'),
        'categories' => array(__('Promotion', 'angola-b2b'), __('Pricing', 'angola-b2b')),
    ),
);

$advisories = apply_filters('angola_b2b_customer_advisories', $advisories);

if (empty($advisories)) {
    return;
}
?>

<section class="customer-advisories-section section-padding bg-white" data-animate="fade-up">
    <div class="container">
        <div class="advisories-header">
            <div>
                <h2 class="section-title"><?php esc_html_e('CUSTOMER ADVISORIES', 'angola-b2b'); ?></h2>
            </div>
            <div class="advisories-header-link">
                <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="btn-link">
                    <?php esc_html_e('READ MORE', 'angola-b2b'); ?>
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
        
        <div class="advisories-list">
            <?php foreach ($advisories as $advisory) : ?>
                <article class="advisory-item">
                    <a href="#" class="advisory-link">
                        <div class="advisory-date">
                            <time datetime="<?php echo esc_attr($advisory['date']); ?>">
                                <?php echo esc_html($advisory['date']); ?>
                            </time>
                        </div>
                        
                        <div class="advisory-content">
                            <h3 class="advisory-title"><?php echo esc_html($advisory['title']); ?></h3>
                            
                            <?php if (!empty($advisory['categories'])) : ?>
                                <div class="advisory-categories">
                                    <?php foreach ($advisory['categories'] as $category) : ?>
                                        <span class="advisory-category"><?php echo esc_html($category); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="advisory-arrow">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <polyline points="9 18 15 12 9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

