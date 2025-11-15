<?php
/**
 * 测试本地字段组注册
 * 诊断为什么字段组注册失败
 */

// 加载WordPress
// Local by Flywheel 站点路径
$local_site_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/local-site/angola-b2b/app/public';
$wp_load_paths = array(
    $local_site_path . '/wp-load.php',  // Local by Flywheel 标准路径
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',  // 标准WordPress路径
    dirname(__FILE__) . '/../../../../wp-load.php',
    dirname(__FILE__) . '/../../../wp-load.php',
    'F:/011 Projects/UnibroWeb/Unirbro/local-site/angola-b2b/app/public/wp-load.php',  // 绝对路径
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
    echo '<h2>无法找到 wp-load.php 文件</h2>';
    echo '<p>尝试的路径：</p><ul>';
    foreach ($wp_load_paths as $path) {
        $exists = file_exists($path) ? '✓ 存在' : '✗ 不存在';
        echo '<li>' . $exists . ': ' . htmlspecialchars($path) . '</li>';
    }
    echo '</ul>';
    echo '<p>请检查 Local by Flywheel 站点路径是否正确。</p>';
    exit;
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
    <title>测试本地字段组注册</title>
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
    <h1>测试本地字段组注册</h1>
    
    <?php
    echo '<div class="test-section">';
    echo '<h2>1. 检查ACF插件</h2>';
    if (function_exists('acf_add_local_field_group')) {
        echo '<div class="success">✓ ACF插件已加载</div>';
        if (defined('ACF_VERSION')) {
            echo '<div class="info">ACF版本: ' . ACF_VERSION . '</div>';
        }
    } else {
        echo '<div class="error">✗ ACF插件未加载</div>';
        exit;
    }
    
    echo '<h2>2. 检查函数是否存在</h2>';
    if (function_exists('angola_b2b_register_homepage_settings_fields')) {
        echo '<div class="success">✓ 注册函数存在</div>';
    } else {
        echo '<div class="error">✗ 注册函数不存在</div>';
        exit;
    }
    
    echo '<h2>3. 检查现有字段组</h2>';
    $existing_group = acf_get_field_group('group_homepage_settings');
    if ($existing_group) {
        echo '<div class="info">字段组已存在</div>';
        $existing_fields = acf_get_fields($existing_group);
        echo '<div class="info">现有字段数量: ' . count($existing_fields) . '</div>';
        
        if (count($existing_fields) < 10) {
            echo '<div class="error">✗ 字段数量不足，需要重新注册</div>';
        } else {
            echo '<div class="success">✓ 字段数量正常</div>';
        }
    } else {
        echo '<div class="info">字段组不存在</div>';
    }
    
    echo '<h2>4. 清除缓存并重新注册</h2>';
    
    // 清除缓存
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        acf_get_store('fields')->reset();
    }
    wp_cache_flush();
    echo '<div class="info">✓ 缓存已清除</div>';
    
    // 调用注册函数
    echo '<div class="info">正在调用注册函数...</div>';
    ob_start();
    $result = angola_b2b_register_homepage_settings_fields();
    $output = ob_get_clean();
    
    if ($output) {
        echo '<div class="error">输出捕获:</div>';
        echo '<pre>' . esc_html($output) . '</pre>';
    }
    
    if ($result) {
        echo '<div class="success">✓ 注册函数返回true</div>';
    } else {
        echo '<div class="error">✗ 注册函数返回false</div>';
    }
    
    echo '<h2>5. 验证注册结果</h2>';
    $registered_group = acf_get_field_group('group_homepage_settings');
    if ($registered_group) {
        echo '<div class="success">✓ 字段组已注册</div>';
        $registered_fields = acf_get_fields($registered_group);
        echo '<div class="info">字段数量: ' . count($registered_fields) . '</div>';
        
        if (count($registered_fields) > 0) {
            echo '<div class="success">✓ 字段注册成功！</div>';
            echo '<div class="info">前5个字段:</div>';
            echo '<ul>';
            foreach (array_slice($registered_fields, 0, 5) as $field) {
                echo '<li>' . esc_html($field['label']) . ' (' . esc_html($field['type']) . ')</li>';
            }
            echo '</ul>';
        } else {
            echo '<div class="error">✗ 字段组存在但没有字段</div>';
        }
    } else {
        echo '<div class="error">✗ 字段组未注册</div>';
    }
    
    echo '</div>';
    ?>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a>
    </div>
</body>
</html>

