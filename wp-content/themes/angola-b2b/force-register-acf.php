<?php
/**
 * 强制注册ACF字段组工具
 * 直接在浏览器访问此文件，强制注册首页设置字段组
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
    <title>强制注册ACF字段组</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f0f1;
            padding: 40px 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h1 { color: #1d2327; margin-bottom: 20px; }
        .status {
            padding: 15px 20px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .error { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .info { background: #cfe2ff; color: #084298; border: 1px solid #b6d4fe; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2271b1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px 10px 0;
        }
        .btn:hover { background: #135e96; }
        .code {
            background: #1d2327;
            color: #f0f0f1;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 强制注册ACF字段组</h1>
        
        <?php
        if (isset($_POST['force_register'])) {
            echo '<div class="status info">正在执行强制注册...</div>';
            
            // 检查ACF是否可用
            if (!function_exists('acf_add_local_field_group')) {
                echo '<div class="status error">✗ ACF插件未安装或未激活</div>';
            } else {
                // 清除所有缓存
                if (function_exists('acf_get_store')) {
                    acf_get_store('field-groups')->remove('group_homepage_settings');
                    acf_get_store('fields')->reset();
                }
                wp_cache_flush();
                
                // 删除可能存在的空JSON文件
                $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
                if (file_exists($json_file)) {
                    $json_content = @file_get_contents($json_file);
                    if ($json_content !== false) {
                        $json_data = json_decode($json_content, true);
                        if (isset($json_data['fields']) && empty($json_data['fields'])) {
                            @unlink($json_file);
                            echo '<div class="status success">✓ 已删除空JSON文件</div>';
                        }
                    }
                }
                
                // 先检查字段组数据结构
                if (function_exists('angola_b2b_register_homepage_settings_fields')) {
                    // 尝试获取字段组数据（通过反射或直接调用）
                    echo '<div class="status info">检查字段组数据结构...</div>';
                    
                    // 创建一个测试函数来获取字段组数据
                    $test_data = array();
                    if (function_exists('angola_b2b_register_homepage_settings_fields')) {
                        // 临时修改函数以返回数据而不是注册
                        ob_start();
                        $register_result = angola_b2b_register_homepage_settings_fields();
                        ob_end_clean();
                    }
                }
                
                // 调用注册函数（捕获错误）
                if (function_exists('angola_b2b_register_homepage_settings_fields')) {
                    // 开启错误捕获
                    $old_error_handler = set_error_handler(function($errno, $errstr, $errfile, $errline) {
                        if (strpos($errfile, 'advanced-custom-fields') !== false) {
                            echo '<div class="status error">ACF插件警告：' . esc_html($errstr) . ' (文件: ' . esc_html(basename($errfile)) . ', 行: ' . $errline . ')</div>';
                        }
                        return false; // 继续执行默认错误处理
                    }, E_WARNING | E_NOTICE);
                    
                    try {
                        $register_result = angola_b2b_register_homepage_settings_fields();
                        echo '<div class="status success">✓ 已调用注册函数</div>';
                        echo '<div class="status info">函数返回值：' . ($register_result ? 'true' : 'false') . '</div>';
                    } catch (Exception $e) {
                        echo '<div class="status error">✗ 注册函数抛出异常：' . esc_html($e->getMessage()) . '</div>';
                    } catch (Error $e) {
                        echo '<div class="status error">✗ 注册函数抛出错误：' . esc_html($e->getMessage()) . '</div>';
                    } finally {
                        // 恢复错误处理
                        if ($old_error_handler) {
                            restore_error_handler();
                        }
                    }
                } else {
                    echo '<div class="status error">✗ 注册函数不存在</div>';
                }
                
                // 等待一下让ACF处理完成
                usleep(100000); // 0.1秒
                
                // 验证注册结果
                $group = acf_get_field_group('group_homepage_settings');
                if ($group) {
                    echo '<div class="status success">✓ 字段组已注册</div>';
                    echo '<div class="code">字段组基本信息：' . "\n";
                    echo 'Key: ' . (isset($group['key']) ? esc_html($group['key']) : '未设置') . "\n";
                    echo 'Title: ' . (isset($group['title']) ? esc_html($group['title']) : '未设置') . "\n";
                    echo 'Fields属性存在: ' . (isset($group['fields']) ? '是' : '否') . "\n";
                    echo '</div>';
                    
                    // 尝试获取字段
                    $fields = acf_get_fields($group);
                    echo '<div class="status info">字段数量：' . (is_array($fields) ? count($fields) : '无法获取（' . gettype($fields) . '）') . '</div>';
                    
                    if (is_array($fields) && count($fields) > 0) {
                        echo '<div class="status success">✓ 字段组包含字段，注册成功！</div>';
                        echo '<div class="code">';
                        echo '字段组Key: ' . esc_html($group['key']) . "\n";
                        echo '字段组标题: ' . esc_html($group['title']) . "\n";
                        echo '字段数量: ' . count($fields) . "\n";
                        echo "\n前5个字段：\n";
                        foreach (array_slice($fields, 0, 5) as $field) {
                            if (is_array($field)) {
                                echo '- ' . (isset($field['label']) ? esc_html($field['label']) : '无标签') . 
                                     ' (' . (isset($field['name']) ? esc_html($field['name']) : '无名称') . 
                                     ') [' . (isset($field['type']) ? esc_html($field['type']) : '无类型') . "]\n";
                            }
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="status error">✗ 字段组已注册但字段为空或无法获取</div>';
                        if (isset($group['fields'])) {
                            echo '<div class="code">字段组中的fields属性：' . print_r($group['fields'], true) . '</div>';
                        }
                    }
                } else {
                    echo '<div class="status error">✗ 字段组注册失败 - 无法获取字段组</div>';
                    
                    // 尝试列出所有字段组
                    $all_groups = acf_get_field_groups();
                    echo '<div class="status info">当前已注册的字段组数量：' . count($all_groups) . '</div>';
                    echo '<div class="code">已注册的字段组：' . "\n";
                    foreach ($all_groups as $g) {
                        echo '- ' . (isset($g['title']) ? esc_html($g['title']) : '无标题') . 
                             ' (' . (isset($g['key']) ? esc_html($g['key']) : '无key') . ")\n";
                    }
                    echo '</div>';
                }
            }
            
            echo '<hr style="margin: 30px 0;">';
        }
        ?>
        
        <form method="post">
            <p>点击下面的按钮强制注册"首页设置"字段组：</p>
            <button type="submit" name="force_register" class="btn">强制注册字段组</button>
        </form>
        
        <div style="margin-top: 30px;">
            <a href="<?php echo esc_url(admin_url('post.php?post=45&action=edit')); ?>" class="btn" target="_blank">打开首页设置页面</a>
            <a href="<?php echo esc_url(get_template_directory_uri() . '/debug-acf-fields.php'); ?>" class="btn" target="_blank">查看诊断工具</a>
        </div>
    </div>
</body>
</html>

