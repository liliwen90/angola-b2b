<?php
/**
 * Statistics Section - Company Stats Display
 * MSC-style statistics showcase with animated counters
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get statistics from ACF or use defaults
$stats = array(
    array(
        'number' => '200',
        'suffix' => '+',
        'label' => __('Employees', 'angola-b2b'),
        'icon' => '<svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    ),
    array(
        'number' => '50',
        'suffix' => '+',
        'label' => __('Vehicles', 'angola-b2b'),
        'icon' => '<svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 17h18M3 17v-7l3-5h12l3 5v7M3 17h18M7 14h.01M17 14h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    ),
    array(
        'number' => '5000',
        'suffix' => '+',
        'label' => __('Products Delivered', 'angola-b2b'),
        'icon' => '<svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><polyline points="3.27 6.96 12 12.01 20.73 6.96" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><line x1="12" y1="22.08" x2="12" y2="12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    ),
    array(
        'number' => '15',
        'suffix' => '+',
        'label' => __('Years Experience', 'angola-b2b'),
        'icon' => '<svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><polyline points="12 6 12 12 16 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
    ),
);

// Allow filtering/customization of stats
$stats = apply_filters('angola_b2b_statistics', $stats);

if (empty($stats)) {
    return;
}
?>

<section class="statistics-section" data-animate="fade-up">
    <div class="container">
        <div class="statistics-grid">
            <?php foreach ($stats as $index => $stat) : ?>
                <div class="stat-item" data-delay="<?php echo esc_attr($index * 100); ?>">
                    <?php if (!empty($stat['icon'])) : ?>
                        <div class="stat-icon-wrapper">
                            <?php echo wp_kses_post($stat['icon']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="stat-content">
                        <div class="stat-number-wrapper">
                            <span class="stat-number" 
                                  data-target="<?php echo esc_attr($stat['number']); ?>"
                                  data-suffix="<?php echo esc_attr($stat['suffix'] ?? ''); ?>">
                                0
                            </span>
                        </div>
                        
                        <p class="stat-label"><?php echo esc_html($stat['label']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

