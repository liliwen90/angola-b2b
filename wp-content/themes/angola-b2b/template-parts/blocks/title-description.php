<?php
/**
 * Title + Description Block Component
 * MSC-style title and description content block
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
    'description' => '',
    'alignment' => 'left', // left, center, right
    'size' => 'medium', // small, medium, large
    'wrapper_class' => '',
));

// Don't output if no content
if (empty($args['title']) && empty($args['description'])) {
    return;
}

$wrapper_classes = array('content-block', 'block-title-description');
if (!empty($args['alignment'])) {
    $wrapper_classes[] = 'align-' . esc_attr($args['alignment']);
}
if (!empty($args['size'])) {
    $wrapper_classes[] = 'size-' . esc_attr($args['size']);
}
if (!empty($args['wrapper_class'])) {
    $wrapper_classes[] = esc_attr($args['wrapper_class']);
}
?>

<section class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
    <div class="container">
        <div class="block-content">
            <?php if (!empty($args['title'])) : ?>
                <h2 class="block-title"><?php echo esc_html($args['title']); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($args['description'])) : ?>
                <div class="block-description">
                    <?php echo wp_kses_post(wpautop($args['description'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

