<?php
/**
 * 测试最小化字段组
 * 创建一个最简单的字段组来测试ACF是否正常工作
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
    <title>测试最小化字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>测试最小化字段组</h1>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 测试1: 最简单的字段组（无字段）
    echo '<h2>测试1: 最简单的字段组（无字段）</h2>';
    $test1 = array(
        'key' => 'group_test_minimal',
        'title' => '测试最小化',
        'fields' => array(),
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
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    );
    
    $result1 = @acf_add_local_field_group($test1);
    echo '<div class="' . ($result1 ? 'success' : 'error') . '">';
    echo '结果: ' . ($result1 ? '成功' : '失败');
    echo '</div>';
    
    // 测试2: 带一个简单字段
    echo '<h2>测试2: 带一个简单文本字段</h2>';
    $test2 = array(
        'key' => 'group_test_simple',
        'title' => '测试简单字段',
        'fields' => array(
            array(
                'key' => 'field_test_text',
                'label' => '测试文本',
                'name' => 'test_text',
                'type' => 'text',
            ),
        ),
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
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    );
    
    $result2 = @acf_add_local_field_group($test2);
    echo '<div class="' . ($result2 ? 'success' : 'error') . '">';
    echo '结果: ' . ($result2 ? '成功' : '失败');
    echo '</div>';
    
    // 测试3: 带tab字段
    echo '<h2>测试3: 带tab字段</h2>';
    $test3 = array(
        'key' => 'group_test_tab',
        'title' => '测试Tab字段',
        'fields' => array(
            array(
                'key' => 'field_tab_test',
                'label' => '测试Tab',
                'name' => '',
                'type' => 'tab',
                'placement' => 'left',
                'endpoint' => 0,
            ),
            array(
                'key' => 'field_test_after_tab',
                'label' => 'Tab后的字段',
                'name' => 'test_after_tab',
                'type' => 'text',
            ),
        ),
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
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
    );
    
    $result3 = @acf_add_local_field_group($test3);
    echo '<div class="' . ($result3 ? 'success' : 'error') . '">';
    echo '结果: ' . ($result3 ? '成功' : '失败');
    echo '</div>';
    
    // 检查已注册的测试字段组
    echo '<h2>已注册的测试字段组</h2>';
    $test_groups = array('group_test_minimal', 'group_test_simple', 'group_test_tab');
    foreach ($test_groups as $key) {
        $group = acf_get_field_group($key);
        if ($group) {
            echo '<div class="success">✓ ' . $key . ' 已注册</div>';
        } else {
            echo '<div class="error">✗ ' . $key . ' 未注册</div>';
        }
    }
    
    // 现在尝试注册实际的首页设置字段组（简化版）
    echo '<h2>测试4: 首页设置字段组（简化版 - 只有前3个字段）</h2>';
    
    $test4 = array(
        'key' => 'group_homepage_settings_test',
        'title' => '首页设置测试',
        'fields' => array(
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
        ),
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
    
    $result4 = @acf_add_local_field_group($test4);
    echo '<div class="' . ($result4 ? 'success' : 'error') . '">';
    echo '结果: ' . ($result4 ? '成功' : '失败');
    echo '</div>';
    
    if ($result4) {
        $group = acf_get_field_group('group_homepage_settings_test');
        if ($group) {
            $fields = acf_get_fields($group);
            echo '<div class="success">字段数量: ' . (is_array($fields) ? count($fields) : 0) . '</div>';
        }
    }
    ?>
</body>
</html>

