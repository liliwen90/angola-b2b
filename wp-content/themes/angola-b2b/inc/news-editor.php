<?php
/**
 * News editor configuration for posts
 *
 * - Force Classic Editor for posts (with Add Media)
 * - Ensure media buttons are enabled
 *
 * @package Angola_B2B
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable block editor for default posts to provide classic TinyMCE + Add Media.
 */
function angola_b2b_use_classic_editor_for_posts($use_block_editor, $post_type) {
    if ($post_type === 'post') {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'angola_b2b_use_classic_editor_for_posts', 10, 2);

/**
 * Ensure media buttons are shown in classic editor for posts.
 */
function angola_b2b_enable_media_buttons($editor_settings, $editor_id) {
    if (in_array($editor_id, array('content', 'post-content'), true)) {
        $editor_settings['media_buttons'] = true;
    }
    return $editor_settings;
}
add_filter('wp_editor_settings', 'angola_b2b_enable_media_buttons', 10, 2);


