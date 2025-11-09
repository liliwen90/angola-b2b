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
	'/inc/custom-multilingual.php',      // 自定义多语言系统（必须最先加载）
	'/inc/simple-translations.php',      // UI字符串翻译（保留用于按钮/标签）
	'/inc/theme-setup.php',
	'/inc/enqueue-scripts.php',
	'/inc/custom-post-types.php',
	'/inc/custom-taxonomies.php',
	'/inc/acf-fields.php',
	'/inc/acf-multilingual-fields.php',  // ACF多语言字段组定义
	'/inc/acf-filters.php',
	'/inc/acf-field-translations.php',   // ACF字段标签动态翻译
	'/inc/admin-customization.php',
	'/inc/admin-menu-simplification.php', // 管理菜单简化
	'/inc/user-role-manager.php',         // 用户角色管理
	'/inc/admin-tools.php',
	'/inc/ajax-handlers.php',
	'/inc/inquiry-system.php',
	'/inc/helpers.php',
	'/inc/query-modifications.php',
	'/inc/create-default-pages.php',     // 创建默认页面
);

foreach ($includes as $file) {
    $filepath = ANGOLA_B2B_THEME_DIR . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}

