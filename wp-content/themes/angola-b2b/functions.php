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
	'/inc/acf-fields.php',  // 非产品字段定义（首页、分类等）
	'/inc/product-fields-simple-v2.php', // 产品字段定义（4语言标题+富文本）⭐
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
	'/inc/custom-admin-layout.php',      // 自定义管理后台布局
	'/inc/product-editor-simple.php',    // 新版简洁产品编辑器
	'/inc/acf-sync-fields.php',          // ACF字段同步管理页面
	'/inc/news-integration.php',         // 新闻系统接入与种子数据
	'/inc/news-editor.php',              // 新闻编辑器（经典编辑器+插入媒体）
	'/inc/news-language.php',            // 新闻语言分离（post_lang/lang_group）
	'/one-click-fix-product-fields.php'  // 一键修复产品字段 ⭐
);

foreach ($includes as $file) {
    $filepath = ANGOLA_B2B_THEME_DIR . $file;
    if (file_exists($filepath)) {
        // 使用 @ 抑制错误，避免单个文件错误阻止主题加载
        @require_once $filepath;
    }
}

