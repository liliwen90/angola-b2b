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
require_once ANGOLA_B2B_THEME_DIR . '/inc/theme-setup.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/enqueue-scripts.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/custom-post-types.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/custom-taxonomies.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/acf-fields.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/admin-customization.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/multilingual.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/ajax-handlers.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/inquiry-system.php';
require_once ANGOLA_B2B_THEME_DIR . '/inc/helpers.php';

