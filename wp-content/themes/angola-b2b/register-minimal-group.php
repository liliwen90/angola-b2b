<?php
/**
 * 最简化字段组注册测试
 * 逐步添加属性，找出导致问题的属性
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
    <title>最简化字段组注册测试</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
        button { padding: 10px 20px; background: #2271b1; color: white; border: none; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>最简化字段组注册测试</h1>
    <p>逐步添加属性，找出导致问题的属性</p>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 清除现有字段组
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        acf_get_store('fields')->reset();
    }
    wp_cache_flush();
    
    $test_step = isset($_GET['step']) ? intval($_GET['step']) : 1;
    
    echo '<div class="test-section">';
    echo '<h2>测试步骤 ' . $test_step . '</h2>';
    
    // 基础字段（渐进式测试中成功的字段）
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
        array(
            'key' => 'field_site_tagline',
            'label' => '网站副标题',
            'name' => 'site_tagline',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => 'Your trusted partner for quality products',
        ),
    );
    
    // 根据步骤构建字段组
    $field_group_data = array(
        'key' => 'group_homepage_settings',
        'title' => '首页设置',
        'fields' => $base_fields,
    );
    
    // 步骤1: 只有基本属性
    if ($test_step == 1) {
        // 只有key、title、fields
    }
    // 步骤2: 添加location
    elseif ($test_step == 2) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
    }
    // 步骤3: 添加更多属性
    elseif ($test_step == 3) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
        $field_group_data['menu_order'] = 0;
        $field_group_data['position'] = 'normal';
    }
    // 步骤4: 添加所有属性
    elseif ($test_step == 4) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
        $field_group_data['menu_order'] = 0;
        $field_group_data['position'] = 'normal';
        $field_group_data['style'] = 'seamless';
        $field_group_data['label_placement'] = 'top';
        $field_group_data['instruction_placement'] = 'label';
        $field_group_data['hide_on_screen'] = array();
    }
    // 步骤5: 添加active和description
    elseif ($test_step == 5) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
        $field_group_data['menu_order'] = 0;
        $field_group_data['position'] = 'normal';
        $field_group_data['style'] = 'seamless';
        $field_group_data['label_placement'] = 'top';
        $field_group_data['instruction_placement'] = 'label';
        $field_group_data['hide_on_screen'] = array();
        $field_group_data['active'] = true;
        $field_group_data['description'] = '';
    }
    // 步骤6: 添加show_in_rest
    elseif ($test_step == 6) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
        $field_group_data['menu_order'] = 0;
        $field_group_data['position'] = 'normal';
        $field_group_data['style'] = 'seamless';
        $field_group_data['label_placement'] = 'top';
        $field_group_data['instruction_placement'] = 'label';
        $field_group_data['hide_on_screen'] = array();
        $field_group_data['active'] = true;
        $field_group_data['description'] = '';
        $field_group_data['show_in_rest'] = false;
    }
    // 步骤7: 添加所有字段
    elseif ($test_step == 7) {
        // 添加所有字段
        $all_fields = $base_fields;
        
        // Hero区域
        $all_fields[] = array(
            'key' => 'field_tab_hero',
            'label' => 'Hero区域',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        );
        $all_fields[] = array(
            'key' => 'field_hero_background_image',
            'label' => 'Hero背景图片',
            'name' => 'hero_background_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        );
        $all_fields[] = array(
            'key' => 'field_hero_title',
            'label' => 'Hero标题',
            'name' => 'hero_title',
            'type' => 'text',
        );
        $all_fields[] = array(
            'key' => 'field_hero_subtitle',
            'label' => 'Hero副标题/描述',
            'name' => 'hero_subtitle',
            'type' => 'textarea',
            'rows' => 3,
            'new_lines' => 'wpautop',
        );
        
        $field_group_data['fields'] = $all_fields;
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
        $field_group_data['menu_order'] = 0;
        $field_group_data['position'] = 'normal';
        $field_group_data['style'] = 'seamless';
        $field_group_data['label_placement'] = 'top';
        $field_group_data['instruction_placement'] = 'label';
        $field_group_data['hide_on_screen'] = array();
        $field_group_data['active'] = true;
        $field_group_data['description'] = '';
        $field_group_data['show_in_rest'] = false;
    }
    
    echo '<div class="info">字段数量: ' . count($field_group_data['fields']) . '</div>';
    echo '<div class="info">字段组属性: ' . implode(', ', array_keys($field_group_data)) . '</div>';
    
    // 尝试注册
    ob_start();
    $result = acf_add_local_field_group($field_group_data);
    $output = ob_get_clean();
    
    if ($output) {
        echo '<div class="error">输出捕获:</div>';
        echo '<pre>' . esc_html($output) . '</pre>';
    }
    
    if ($result) {
        echo '<div class="success">✓ 步骤 ' . $test_step . ' 成功！</div>';
        
        // 验证注册
        $registered_group = acf_get_field_group('group_homepage_settings');
        if ($registered_group) {
            $registered_fields = acf_get_fields($registered_group);
            echo '<div class="success">✓ 验证成功！已注册字段数量: ' . count($registered_fields) . '</div>';
            
            if ($test_step < 7) {
                echo '<div class="info"><a href="?step=' . ($test_step + 1) . '" style="color: #72aee6;">继续下一步</a></div>';
            } else {
                echo '<div class="success">所有步骤完成！现在可以尝试注册完整字段组。</div>';
            }
        } else {
            echo '<div class="error">✗ 验证失败：无法获取已注册的字段组</div>';
        }
    } else {
        echo '<div class="error">✗ 步骤 ' . $test_step . ' 失败</div>';
        echo '<div class="info">问题可能出现在这一步添加的属性中</div>';
    }
    
    echo '</div>';
    
    // 显示字段组数据结构（用于调试）
    echo '<div class="test-section">';
    echo '<h3>字段组数据结构（调试）</h3>';
    echo '<pre>' . esc_html(print_r($field_group_data, true)) . '</pre>';
    echo '</div>';
    ?>
    
    <div style="margin-top: 30px;">
        <a href="?step=1" style="color: #72aee6;">重新开始测试</a> | 
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
</body>
</html>

