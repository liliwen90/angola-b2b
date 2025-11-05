<?php
/**
 * Services List Block Component
 * MSC-style services list display
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get custom args if passed via global
global $block_args;
if (isset($block_args) && is_array($block_args)) {
    $custom_args = $block_args;
} else {
    $custom_args = array();
}

// Parse arguments with defaults
$args = wp_parse_args($custom_args, array(
    'title' => '',
    'services' => array(),
    'columns' => 3, // 2, 3, 4
    'wrapper_class' => '',
));

// Try to get from ACF if not provided
if (empty($args['services']) && function_exists('get_field')) {
    $services_field = get_field('services_list');
    if ($services_field && is_array($services_field)) {
        $args['services'] = $services_field;
    }
}

// Don't output if no services
if (empty($args['services'])) {
    return;
}

$wrapper_classes = array('content-block', 'block-services-list');
if (!empty($args['columns'])) {
    $wrapper_classes[] = 'columns-' . esc_attr($args['columns']);
}
if (!empty($args['wrapper_class'])) {
    $wrapper_classes[] = esc_attr($args['wrapper_class']);
}
?>

<section class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" id="services">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h2 class="block-title"><?php echo esc_html($args['title']); ?></h2>
        <?php endif; ?>
        
        <div class="services-grid">
            <?php foreach ($args['services'] as $service) : 
                $service_title = '';
                $service_description = '';
                $service_icon = '';
                $service_link = '';
                
                // Handle different input formats
                if (is_array($service)) {
                    $service_title = isset($service['title']) ? $service['title'] : (isset($service['service_title']) ? $service['service_title'] : '');
                    $service_description = isset($service['description']) ? $service['description'] : (isset($service['service_description']) ? $service['service_description'] : '');
                    $service_icon = isset($service['icon']) ? $service['icon'] : '';
                    $service_link = isset($service['link']) ? $service['link'] : (isset($service['url']) ? $service['url'] : '');
                } elseif (is_string($service)) {
                    $service_title = $service;
                }
                
                if (empty($service_title)) {
                    continue;
                }
            ?>
                <div class="service-item">
                    <?php if (!empty($service_icon)) : ?>
                        <div class="service-icon">
                            <?php if (is_array($service_icon) && !empty($service_icon['url'])) : ?>
                                <img src="<?php echo esc_url($service_icon['url']); ?>" alt="<?php echo esc_attr($service_title); ?>">
                            <?php else : ?>
                                <span class="icon-placeholder"><?php echo esc_html(substr($service_title, 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="service-content">
                        <?php if (!empty($service_link)) : ?>
                            <h3 class="service-title"><a href="<?php echo esc_url($service_link); ?>"><?php echo esc_html($service_title); ?></a></h3>
                        <?php else : ?>
                            <h3 class="service-title"><?php echo esc_html($service_title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($service_description)) : ?>
                            <p class="service-description"><?php echo esc_html($service_description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

