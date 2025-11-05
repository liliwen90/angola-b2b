<?php
/**
 * Image + Text Block Component
 * MSC-style image and text content block
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
    'image' => '',
    'image_position' => 'left', // left, right
    'title' => '',
    'content' => '',
    'cta' => array('text' => '', 'url' => ''),
    'wrapper_class' => '',
));

// Try to get from ACF if not provided
if (empty($args['image']) && function_exists('get_field')) {
    $image_field = get_field('block_image');
    if ($image_field) {
        if (is_array($image_field) && isset($image_field['url'])) {
            $args['image'] = $image_field['url'];
        } elseif (is_numeric($image_field)) {
            $args['image'] = wp_get_attachment_image_url($image_field, 'large');
        } elseif (is_string($image_field)) {
            $args['image'] = $image_field;
        }
    }
}

// Don't output if no content
if (empty($args['image']) && empty($args['title']) && empty($args['content'])) {
    return;
}

$wrapper_classes = array('content-block', 'block-image-text');
if (!empty($args['image_position'])) {
    $wrapper_classes[] = 'image-' . esc_attr($args['image_position']);
}
if (!empty($args['wrapper_class'])) {
    $wrapper_classes[] = esc_attr($args['wrapper_class']);
}
?>

<section class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
    <div class="container">
        <div class="block-image-text-wrapper">
            <?php if (!empty($args['image'])) : ?>
                <div class="block-image">
                    <img src="<?php echo esc_url($args['image']); ?>" alt="<?php echo esc_attr($args['title'] ?: ''); ?>">
                </div>
            <?php endif; ?>
            
            <div class="block-text-content">
                <?php if (!empty($args['title'])) : ?>
                    <h2 class="block-title"><?php echo esc_html($args['title']); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($args['content'])) : ?>
                    <div class="block-content">
                        <?php echo wp_kses_post(wpautop($args['content'])); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($args['cta']['text']) && !empty($args['cta']['url'])) : ?>
                    <div class="block-cta">
                        <a href="<?php echo esc_url($args['cta']['url']); ?>" class="btn btn-primary">
                            <?php echo esc_html($args['cta']['text']); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

