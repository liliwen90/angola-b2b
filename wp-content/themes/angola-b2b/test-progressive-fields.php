<?php
/**
 * 渐进式测试字段组
 * 逐步添加字段，找出导致问题的字段
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
    <title>渐进式测试字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
    </style>
</head>
<body>
    <h1>渐进式测试字段组</h1>
    <p>逐步添加字段，找出导致问题的字段</p>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 基础字段组结构
    $base_fields = array(
        array(
            'key' => 'field_tab_site_info',
            'label' => '站点信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_site_logo',
            'label' => '网站Logo',
            'name' => 'site_logo',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_site_title',
            'label' => '网站标题',
            'name' => 'site_title',
            'type' => 'text',
        ),
    );
    
    // 要测试的字段列表
    $fields_to_test = array(
        array(
            'key' => 'field_site_tagline',
            'label' => '网站副标题',
            'name' => 'site_tagline',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => 'Your trusted partner for quality products',
        ),
        array(
            'key' => 'field_tab_hero',
            'label' => 'Hero区域',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_hero_background_image',
            'label' => 'Hero背景图片',
            'name' => 'hero_background_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_hero_title',
            'label' => 'Hero标题',
            'name' => 'hero_title',
            'type' => 'text',
        ),
        array(
            'key' => 'field_hero_subtitle',
            'label' => 'Hero副标题/描述',
            'name' => 'hero_subtitle',
            'type' => 'textarea',
            'rows' => 3,
            'new_lines' => 'wpautop',
        ),
        array(
            'key' => 'field_tab_contact',
            'label' => '联系信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_contact_email',
            'label' => '联系邮箱',
            'name' => 'contact_email',
            'type' => 'email',
            'default_value' => 'info@example.com',
            'placeholder' => 'info@example.com',
        ),
        array(
            'key' => 'field_contact_phone',
            'label' => '联系电话',
            'name' => 'contact_phone',
            'type' => 'text',
            'default_value' => '+1 234 567 8900',
            'placeholder' => '+1 234 567 8900',
        ),
        array(
            'key' => 'field_tab_social',
            'label' => '社交媒体',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_social_facebook',
            'label' => 'Facebook链接',
            'name' => 'social_facebook',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://facebook.com/your-page',
        ),
        array(
            'key' => 'field_social_facebook_show',
            'label' => '显示Facebook',
            'name' => 'social_facebook_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ),
        array(
            'key' => 'field_tab_about',
            'label' => '关于我们',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_footer_about_us_content',
            'label' => 'English',
            'name' => 'footer_about_us_content',
            'type' => 'wysiwyg',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
    );
    
    $current_fields = $base_fields;
    $test_key = 'group_homepage_settings_progressive';
    $step = 1;
    
    foreach ($fields_to_test as $field) {
        echo '<div class="test-section">';
        echo '<h2>步骤 ' . $step . ': 添加字段 - ' . esc_html($field['label']) . ' (' . esc_html($field['type']) . ')</h2>';
        
        // 添加字段
        $current_fields[] = $field;
        
        // 构建字段组
        $field_group = array(
            'key' => $test_key . '_' . $step,
            'title' => '首页设置测试 - 步骤' . $step,
            'fields' => $current_fields,
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
        
        // 尝试注册
        $result = @acf_add_local_field_group($field_group);
        
        if ($result) {
            $group = acf_get_field_group($test_key . '_' . $step);
            $fields = acf_get_fields($group);
            $field_count = is_array($fields) ? count($fields) : 0;
            
            echo '<div class="success">✓ 成功！字段数量: ' . $field_count . '</div>';
            $step++;
        } else {
            echo '<div class="error">✗ 失败！问题字段: ' . esc_html($field['label']) . ' (' . esc_html($field['key']) . ')</div>';
            echo '<pre>' . esc_html(print_r($field, true)) . '</pre>';
            break;
        }
        
        echo '</div>';
    }
    
    if ($step > count($fields_to_test)) {
        echo '<div class="success">✓ 所有字段测试通过！问题可能不在字段定义中。</div>';
    }
    ?>
</body>
</html>

