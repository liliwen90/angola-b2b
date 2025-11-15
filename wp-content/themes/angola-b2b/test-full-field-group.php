<?php
/**
 * 测试完整字段组
 * 直接尝试注册完整的首页设置字段组
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
    <title>测试完整字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <h1>测试完整字段组</h1>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 先清除可能存在的字段组
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        acf_get_store('fields')->reset();
    }
    wp_cache_flush();
    
    // 直接调用注册函数
    echo '<div class="info">正在调用注册函数...</div>';
    
    // 捕获错误
    $errors = array();
    set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$errors) {
        if (strpos($errfile, 'advanced-custom-fields') !== false) {
            $errors[] = array(
                'file' => basename($errfile),
                'line' => $errline,
                'message' => $errstr
            );
        }
        return false;
    }, E_WARNING | E_NOTICE);
    
    // 调用注册函数
    if (function_exists('angola_b2b_register_homepage_settings_fields')) {
        $result = angola_b2b_register_homepage_settings_fields();
        restore_error_handler();
        
        echo '<div class="' . ($result ? 'success' : 'error') . '">';
        echo '函数返回值: ' . ($result ? 'true' : 'false');
        echo '</div>';
        
        if (!empty($errors)) {
            echo '<div class="error">捕获到的错误:</div>';
            foreach ($errors as $error) {
                echo '<div class="error">';
                echo $error['file'] . ':' . $error['line'] . ' - ' . $error['message'];
                echo '</div>';
            }
        }
        
        // 检查字段组是否注册成功
        $group = acf_get_field_group('group_homepage_settings');
        if ($group) {
            echo '<div class="success">✓ 字段组已注册</div>';
            $fields = acf_get_fields($group);
            $field_count = is_array($fields) ? count($fields) : 0;
            echo '<div class="info">字段数量: ' . $field_count . '</div>';
            
            if ($field_count > 0) {
                echo '<div class="success">✓ 字段组注册成功！</div>';
                echo '<h2>字段列表（前10个）:</h2>';
                echo '<pre>';
                foreach (array_slice($fields, 0, 10) as $field) {
                    echo '- ' . (isset($field['label']) ? $field['label'] : '无标签') . 
                         ' (' . (isset($field['name']) ? $field['name'] : '无名称') . 
                         ') [' . (isset($field['type']) ? $field['type'] : '无类型') . "]\n";
                }
                echo '</pre>';
            } else {
                echo '<div class="error">✗ 字段组已注册但字段为空</div>';
            }
        } else {
            echo '<div class="error">✗ 字段组未注册</div>';
        }
    } else {
        echo '<div class="error">注册函数不存在</div>';
    }
    ?>
</body>
</html>

