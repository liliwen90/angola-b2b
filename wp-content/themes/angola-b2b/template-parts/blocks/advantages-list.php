<?php
/**
 * Advantages List Block Component
 * MSC-style advantages/features list display
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
    'advantages' => array(),
    'layout' => 'grid', // grid, list
    'columns' => 3, // 2, 3, 4
    'wrapper_class' => '',
));

// Try to get from ACF if not provided
if (empty($args['advantages']) && function_exists('get_field')) {
    $advantages_field = get_field('advantages_list');
    if ($advantages_field && is_array($advantages_field)) {
        $args['advantages'] = $advantages_field;
    }
}

// Don't output if no advantages
if (empty($args['advantages'])) {
    return;
}

$wrapper_classes = array('content-block', 'block-advantages-list');
if (!empty($args['layout'])) {
    $wrapper_classes[] = 'layout-' . esc_attr($args['layout']);
}
if (!empty($args['columns'])) {
    $wrapper_classes[] = 'columns-' . esc_attr($args['columns']);
}
if (!empty($args['wrapper_class'])) {
    $wrapper_classes[] = esc_attr($args['wrapper_class']);
}
?>

<section class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" id="advantages">
    <div class="container">
        <?php if (!empty($args['title'])) : ?>
            <h2 class="block-title"><?php echo esc_html($args['title']); ?></h2>
        <?php endif; ?>
        
        <div class="advantages-grid">
            <?php foreach ($args['advantages'] as $index => $advantage) : 
                $advantage_title = '';
                $advantage_description = '';
                $advantage_icon = '';
                
                // Handle different input formats
                if (is_array($advantage)) {
                    $advantage_title = isset($advantage['title']) ? $advantage['title'] : (isset($advantage['advantage_title']) ? $advantage['advantage_title'] : '');
                    $advantage_description = isset($advantage['description']) ? $advantage['description'] : (isset($advantage['advantage_description']) ? $advantage['advantage_description'] : '');
                    $advantage_icon = isset($advantage['icon']) ? $advantage['icon'] : '';
                } elseif (is_string($advantage)) {
                    $advantage_title = $advantage;
                }
                
                if (empty($advantage_title)) {
                    continue;
                }
            ?>
                <div class="advantage-item">
                    <?php if (!empty($advantage_icon)) : ?>
                        <div class="advantage-icon">
                            <?php if (is_array($advantage_icon) && !empty($advantage_icon['url'])) : ?>
                                <img src="<?php echo esc_url($advantage_icon['url']); ?>" alt="<?php echo esc_attr($advantage_title); ?>">
                            <?php else : ?>
                                <span class="icon-placeholder"><?php echo esc_html(substr($advantage_title, 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="advantage-content">
                        <h3 class="advantage-title"><?php echo esc_html($advantage_title); ?></h3>
                        <?php if (!empty($advantage_description)) : ?>
                            <p class="advantage-description"><?php echo esc_html($advantage_description); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

