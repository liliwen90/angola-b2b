<?php
/**
 * 分步注册首页设置字段组
 * 使用与渐进式测试相同的方法，但一次性注册所有字段
 */

// 加载WordPress
$wp_load_paths = array(
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',
    '/www/wwwroot/www.unibroint.com/wp-load.php',
    dirname(__FILE__) . '/../../../../wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('无法找到 wp-load.php 文件。');
}

// 检查权限
if (!current_user_can('manage_options')) {
    wp_die('您没有权限访问此页面。');
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>分步注册首页设置字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
        button { padding: 10px 20px; background: #2271b1; color: white; border: none; cursor: pointer; font-size: 16px; }
        button:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>分步注册首页设置字段组</h1>
    <p>使用与渐进式测试相同的方法，一次性注册所有字段</p>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    if (isset($_POST['register'])) {
        echo '<div class="test-section">';
        echo '<h2>开始注册...</h2>';
        
        // 清除现有字段组
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->remove('group_homepage_settings');
            acf_get_store('fields')->reset();
        }
        wp_cache_flush();
        
        // 构建字段数组（使用与渐进式测试完全相同的方法）
        $fields = array();
        
        // 基础字段
        $fields[] = array(
            'key' => 'field_tab_site_info',
            'label' => '站点信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_site_logo',
            'label' => '网站Logo',
            'name' => 'site_logo',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        );
        $fields[] = array(
            'key' => 'field_site_title',
            'label' => '网站标题',
            'name' => 'site_title',
            'type' => 'text',
        );
        $fields[] = array(
            'key' => 'field_site_tagline',
            'label' => '网站副标题',
            'name' => 'site_tagline',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => 'Your trusted partner for quality products',
        );
        
        // Hero区域
        $fields[] = array(
            'key' => 'field_tab_hero',
            'label' => 'Hero区域',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_hero_background_image',
            'label' => 'Hero背景图片',
            'name' => 'hero_background_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        );
        $fields[] = array(
            'key' => 'field_hero_title',
            'label' => 'Hero标题',
            'name' => 'hero_title',
            'type' => 'text',
        );
        $fields[] = array(
            'key' => 'field_hero_subtitle',
            'label' => 'Hero副标题/描述',
            'name' => 'hero_subtitle',
            'type' => 'textarea',
            'rows' => 3,
            'new_lines' => 'wpautop',
        );
        
        // 联系信息
        $fields[] = array(
            'key' => 'field_tab_contact',
            'label' => '联系信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_contact_email',
            'label' => '联系邮箱',
            'name' => 'contact_email',
            'type' => 'email',
            'default_value' => 'info@example.com',
            'placeholder' => 'info@example.com',
        );
        $fields[] = array(
            'key' => 'field_contact_phone',
            'label' => '联系电话',
            'name' => 'contact_phone',
            'type' => 'text',
            'default_value' => '+1 234 567 8900',
            'placeholder' => '+1 234 567 8900',
        );
        
        // 社交媒体
        $fields[] = array(
            'key' => 'field_tab_social',
            'label' => '社交媒体',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_social_facebook',
            'label' => 'Facebook链接',
            'name' => 'social_facebook',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://facebook.com/your-page',
        );
        $fields[] = array(
            'key' => 'field_social_facebook_show',
            'label' => '显示Facebook',
            'name' => 'social_facebook_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        );
        $fields[] = array(
            'key' => 'field_social_twitter',
            'label' => 'Twitter链接',
            'name' => 'social_twitter',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://twitter.com/your-account',
        );
        $fields[] = array(
            'key' => 'field_social_twitter_show',
            'label' => '显示Twitter',
            'name' => 'social_twitter_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        );
        $fields[] = array(
            'key' => 'field_social_linkedin',
            'label' => 'LinkedIn链接',
            'name' => 'social_linkedin',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://linkedin.com/company/your-company',
        );
        $fields[] = array(
            'key' => 'field_social_linkedin_show',
            'label' => '显示LinkedIn',
            'name' => 'social_linkedin_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        );
        $fields[] = array(
            'key' => 'field_social_whatsapp',
            'label' => 'WhatsApp号码',
            'name' => 'social_whatsapp',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '+8615319996326',
        );
        $fields[] = array(
            'key' => 'field_social_whatsapp_show',
            'label' => '显示WhatsApp',
            'name' => 'social_whatsapp_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        );
        
        // 关于我们
        $fields[] = array(
            'key' => 'field_tab_about',
            'label' => '关于我们',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_about_us_content',
            'label' => 'English',
            'name' => 'footer_about_us_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_about_us_content_pt',
            'label' => 'Português',
            'name' => 'footer_about_us_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_about_us_content_zh',
            'label' => '简体中文',
            'name' => 'footer_about_us_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_about_us_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_about_us_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        
        // 我们的服务
        $fields[] = array(
            'key' => 'field_tab_services',
            'label' => '我们的服务',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_services_content',
            'label' => 'English',
            'name' => 'footer_services_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_services_content_pt',
            'label' => 'Português',
            'name' => 'footer_services_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_services_content_zh',
            'label' => '简体中文',
            'name' => 'footer_services_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_services_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_services_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        
        // 招聘
        $fields[] = array(
            'key' => 'field_tab_careers',
            'label' => '招聘',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_careers_content',
            'label' => 'English',
            'name' => 'footer_careers_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_careers_content_pt',
            'label' => 'Português',
            'name' => 'footer_careers_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_careers_content_zh',
            'label' => '简体中文',
            'name' => 'footer_careers_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        $fields[] = array(
            'key' => 'field_footer_careers_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_careers_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        );
        
        echo '<div class="info">字段数量: ' . count($fields) . '</div>';
        
        // 构建字段组数据
        $field_group_data = array(
            'key' => 'group_homepage_settings',
            'title' => '首页设置',
            'fields' => $fields,
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '45',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
        );
        
        // 注册字段组
        echo '<div class="info">正在注册字段组...</div>';
        
        ob_start();
        $result = acf_add_local_field_group($field_group_data);
        $output = ob_get_clean();
        
        if ($output) {
            echo '<div class="error">输出捕获:</div>';
            echo '<pre>' . esc_html($output) . '</pre>';
        }
        
        if ($result) {
            echo '<div class="success">✓ 字段组注册成功！</div>';
            
            // 验证注册
            $registered_group = acf_get_field_group('group_homepage_settings');
            if ($registered_group) {
                $registered_fields = acf_get_fields($registered_group);
                echo '<div class="success">✓ 验证成功！已注册字段数量: ' . count($registered_fields) . '</div>';
            } else {
                echo '<div class="error">✗ 验证失败：无法获取已注册的字段组</div>';
            }
        } else {
            echo '<div class="error">✗ 字段组注册失败</div>';
        }
        
        echo '</div>';
    }
    ?>
    
    <form method="post">
        <button type="submit" name="register" value="1">注册首页设置字段组</button>
    </form>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
</body>
</html>

