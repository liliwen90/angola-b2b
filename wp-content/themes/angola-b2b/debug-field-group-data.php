<?php
/**
 * 调试字段组数据结构
 * 直接输出字段组数据的完整结构，帮助定位问题
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>调试字段组数据结构</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: monospace;
            background: #1d2327;
            color: #f0f0f1;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        h1 { color: #f0f0f1; margin-bottom: 20px; font-size: 18px; }
        pre {
            background: #0a0a0a;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            border: 1px solid #3c434a;
            margin: 10px 0;
        }
        .section {
            margin: 20px 0;
            padding: 15px;
            background: #2c3338;
            border-radius: 4px;
        }
        .error { color: #f0b849; }
        .success { color: #00a32a; }
        .info { color: #72aee6; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 字段组数据结构调试</h1>
        
        <?php
        // 检查ACF是否可用
        if (!function_exists('acf_add_local_field_group')) {
            echo '<div class="error">ACF插件未安装或未激活</div>';
            exit;
        }
        
        // 尝试获取字段组数据
        // 我们需要直接访问函数内部的字段组数据
        // 由于函数不返回数据，我们需要通过反射或直接复制代码
        
        // 方法：直接复制字段组定义代码
        require_once(get_template_directory() . '/inc/acf-fields.php');
        
        // 创建一个函数来获取字段组数据（不注册）
        function get_homepage_field_group_data() {
            // 复制字段组定义
            $field_group_data = array(
                'key' => 'group_homepage_settings',
                'title' => '首页设置',
                'fields' => array(
                    // Tab: 站点信息
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
                        'instructions' => '上传网站Logo图片（建议尺寸：200x60px，透明背景PNG格式）',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
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
            
            return $field_group_data;
        }
        
        // 获取完整字段组数据
        $full_data = array();
        if (function_exists('angola_b2b_register_homepage_settings_fields')) {
            // 尝试通过反射获取
            try {
                $reflection = new ReflectionFunction('angola_b2b_register_homepage_settings_fields');
                $filename = $reflection->getFileName();
                $start_line = $reflection->getStartLine();
                $end_line = $reflection->getEndLine();
                
                echo '<div class="section">';
                echo '<h2>函数信息</h2>';
                echo '<pre>';
                echo "文件: {$filename}\n";
                echo "起始行: {$start_line}\n";
                echo "结束行: {$end_line}\n";
                echo '</pre>';
                echo '</div>';
            } catch (Exception $e) {
                echo '<div class="error">无法获取函数信息: ' . $e->getMessage() . '</div>';
            }
        }
        
        // 读取并解析函数文件
        $acf_fields_file = get_template_directory() . '/inc/acf-fields.php';
        if (file_exists($acf_fields_file)) {
            $file_content = file_get_contents($acf_fields_file);
            
            // 查找字段组定义
            if (preg_match('/\$field_group_data\s*=\s*array\s*\(([\s\S]*?)\)\s*;/', $file_content, $matches)) {
                echo '<div class="section">';
                echo '<h2>字段组数据定义（原始代码）</h2>';
                echo '<pre>' . esc_html($matches[0]) . '</pre>';
                echo '</div>';
            }
        }
        
        // 尝试执行函数并捕获数据
        echo '<div class="section">';
        echo '<h2>尝试构建字段组数据</h2>';
        
        // 创建一个测试函数来构建数据
        $test_data = array();
        
        // 读取完整的字段定义
        $fields_file = get_template_directory() . '/inc/acf-fields.php';
        if (file_exists($fields_file)) {
            include($fields_file);
            
            // 尝试通过临时修改函数来获取数据
            // 创建一个包装函数
            $original_function = 'angola_b2b_register_homepage_settings_fields';
            if (function_exists($original_function)) {
                // 使用输出缓冲捕获
                ob_start();
                $result = call_user_func($original_function);
                $output = ob_get_clean();
                
                echo '<div class="info">函数执行结果: ' . ($result ? 'true' : 'false') . '</div>';
                if ($output) {
                    echo '<pre>' . esc_html($output) . '</pre>';
                }
            }
        }
        
        echo '</div>';
        
        // 检查字段组是否已注册
        echo '<div class="section">';
        echo '<h2>检查已注册的字段组</h2>';
        $group = acf_get_field_group('group_homepage_settings');
        if ($group) {
            echo '<div class="success">字段组已注册</div>';
            echo '<pre>' . print_r($group, true) . '</pre>';
            
            // 检查字段
            $fields = acf_get_fields($group);
            echo '<h3>字段列表</h3>';
            echo '<pre>' . print_r($fields, true) . '</pre>';
        } else {
            echo '<div class="error">字段组未注册</div>';
        }
        echo '</div>';
        
        // 对比成功的字段组
        echo '<div class="section">';
        echo '<h2>对比：成功的字段组（分类Hero设置）</h2>';
        $success_group = acf_get_field_group('group_category_hero');
        if ($success_group) {
            echo '<pre>' . print_r($success_group, true) . '</pre>';
            
            // 检查字段
            $success_fields = acf_get_fields($success_group);
            echo '<h3>字段列表</h3>';
            echo '<pre>' . print_r($success_fields, true) . '</pre>';
        }
        echo '</div>';
        
        // 检查字段组数据中的null值
        echo '<div class="section">';
        echo '<h2>检查数据中的null值</h2>';
        
        // 手动构建一个最小化的字段组数据来测试
        $minimal_data = array(
            'key' => 'group_homepage_settings_test',
            'title' => '首页设置测试',
            'fields' => array(
                array(
                    'key' => 'field_test',
                    'label' => '测试字段',
                    'name' => 'test_field',
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
            'hide_on_screen' => array(),
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
        );
        
        echo '<h3>最小化测试数据</h3>';
        echo '<pre>' . print_r($minimal_data, true) . '</pre>';
        
        // 检查是否有null值
        function check_for_nulls($data, $path = '') {
            $nulls = array();
            foreach ($data as $key => $value) {
                $current_path = $path ? $path . '.' . $key : $key;
                if (is_null($value)) {
                    $nulls[] = $current_path;
                } elseif (is_array($value)) {
                    $nulls = array_merge($nulls, check_for_nulls($value, $current_path));
                }
            }
            return $nulls;
        }
        
        $null_values = check_for_nulls($minimal_data);
        if (empty($null_values)) {
            echo '<div class="success">最小化数据中没有null值</div>';
        } else {
            echo '<div class="error">发现null值: ' . implode(', ', $null_values) . '</div>';
        }
        
        // 尝试注册最小化字段组
        echo '<h3>尝试注册最小化字段组</h3>';
        $test_result = acf_add_local_field_group($minimal_data);
        echo '<div class="' . ($test_result ? 'success' : 'error') . '">';
        echo '注册结果: ' . ($test_result ? '成功' : '失败');
        echo '</div>';
        
        if ($test_result) {
            $test_group = acf_get_field_group('group_homepage_settings_test');
            if ($test_group) {
                echo '<div class="success">最小化字段组注册成功！</div>';
                echo '<pre>' . print_r($test_group, true) . '</pre>';
            }
        }
        
        echo '</div>';
        ?>
    </div>
</body>
</html>

