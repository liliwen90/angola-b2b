<?php
/**
 * Angola B2B Theme Functions
 * 
 * @package Angola_B2B
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('ANGOLA_B2B_VERSION', '1.0.0');
define('ANGOLA_B2B_THEME_DIR', get_template_directory());
define('ANGOLA_B2B_THEME_URI', get_template_directory_uri());

// Include required files
$includes = array(
    '/inc/theme-setup.php',
    '/inc/enqueue-scripts.php',
    '/inc/custom-post-types.php',
    '/inc/custom-taxonomies.php',
    '/inc/acf-fields.php',
    '/inc/admin-customization.php',
    '/inc/admin-tools.php',
    '/inc/multilingual.php',
    '/inc/ajax-handlers.php',
    '/inc/inquiry-system.php',
    '/inc/helpers.php',
    '/inc/query-modifications.php',
);

foreach ($includes as $file) {
    $filepath = ANGOLA_B2B_THEME_DIR . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}

