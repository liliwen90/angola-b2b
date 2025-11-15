<?php
/**
 * 清除WordPress所有缓存
 * 包括：对象缓存、瞬态缓存、主题缓存、浏览器缓存提示
 * 
 * 使用方法：
 * 1. 上传此文件到网站根目录
 * 2. 访问：https://www.unibroint.com/clear-wordpress-cache.php
 * 3. 查看清除结果
 * 4. 使用后请删除此文件
 */

// 加载WordPress
require_once(__DIR__ . '/wp-load.php');

// 必须是管理员
if (!current_user_can('manage_options')) {
    die('需要管理员权限');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>清除WordPress缓存</title>
    <style>
        body {
            font-family: 'Microsoft YaHei', Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f0f0f1;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #1d2327;
            border-bottom: 3px solid #2271b1;
            padding-bottom: 10px;
        }
        h2 {
            color: #2271b1;
            margin-top: 30px;
        }
        .success {
            background: #00a32a;
            color: white;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error {
            background: #d63638;
            color: white;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .warning {
            background: #dba617;
            color: white;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .info {
            background: #2271b1;
            color: white;
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2271b1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px 10px 0;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #135e96;
        }
        .btn-danger {
            background: #d63638;
        }
        .btn-danger:hover {
            background: #b32d2e;
        }
        code {
            background: #f0f0f1;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        ul {
            line-height: 2;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🧹 清除WordPress缓存</h1>

<?php

if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    echo "<h2>正在清除缓存...</h2>";
    
    $cleared = array();
    $errors = array();
    
    // 1. 清除WordPress对象缓存
    if (function_exists('wp_cache_flush')) {
        if (wp_cache_flush()) {
            $cleared[] = "✓ WordPress对象缓存已清除";
        } else {
            $errors[] = "✗ WordPress对象缓存清除失败";
        }
    } else {
        $cleared[] = "- WordPress对象缓存功能不可用（这是正常的）";
    }
    
    // 2. 清除所有瞬态缓存（Transients）
    global $wpdb;
    $transients_deleted = $wpdb->query(
        "DELETE FROM {$wpdb->options} 
         WHERE option_name LIKE '_transient_%' 
         OR option_name LIKE '_site_transient_%'"
    );
    
    if ($transients_deleted !== false) {
        $cleared[] = "✓ 已删除 {$transients_deleted} 个瞬态缓存";
    } else {
        $errors[] = "✗ 瞬态缓存清除失败";
    }
    
    // 3. 清除主题缓存
    delete_transient('angola_b2b_theme_cache');
    delete_transient('angola_b2b_homepage_cache');
    $cleared[] = "✓ 主题自定义缓存已清除";
    
    // 4. 清除重写规则缓存
    flush_rewrite_rules();
    $cleared[] = "✓ URL重写规则已刷新";
    
    // 5. 检查并清除缓存插件
    $cache_plugins = array();
    
    // W3 Total Cache
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        $cache_plugins[] = "✓ W3 Total Cache 已清除";
    }
    
    // WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
        $cache_plugins[] = "✓ WP Super Cache 已清除";
    }
    
    // WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
        $cache_plugins[] = "✓ WP Rocket 已清除";
    }
    
    // LiteSpeed Cache
    if (class_exists('LiteSpeed_Cache_API') && method_exists('LiteSpeed_Cache_API', 'purge_all')) {
        LiteSpeed_Cache_API::purge_all();
        $cache_plugins[] = "✓ LiteSpeed Cache 已清除";
    }
    
    if (empty($cache_plugins)) {
        $cleared[] = "- 未检测到缓存插件（这是正常的）";
    } else {
        $cleared = array_merge($cleared, $cache_plugins);
    }
    
    // 6. 清除OPcache（PHP代码缓存）
    if (function_exists('opcache_reset')) {
        if (opcache_reset()) {
            $cleared[] = "✓ PHP OPcache 已清除";
        } else {
            $errors[] = "✗ PHP OPcache 清除失败";
        }
    } else {
        $cleared[] = "- PHP OPcache 未启用（这是正常的）";
    }
    
    // 显示结果
    echo "<h3>清除结果：</h3>";
    
    foreach ($cleared as $msg) {
        echo "<div class='success'>{$msg}</div>";
    }
    
    foreach ($errors as $msg) {
        echo "<div class='error'>{$msg}</div>";
    }
    
    echo "<div class='info'>";
    echo "<h3>📋 下一步操作：</h3>";
    echo "<ol>";
    echo "<li><strong>清除浏览器缓存：</strong>";
    echo "<ul>";
    echo "<li>按 <code>Ctrl + Shift + Delete</code></li>";
    echo "<li>选择 '缓存的图像和文件'</li>";
    echo "<li>点击 '清除数据'</li>";
    echo "</ul></li>";
    echo "<li><strong>强制刷新页面：</strong> 按 <code>Ctrl + Shift + R</code> 或 <code>Ctrl + F5</code></li>";
    echo "<li><strong>访问首页：</strong> <a href='" . home_url() . "' target='_blank'>" . home_url() . "</a></li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<a href='" . home_url() . "' class='btn'>查看首页</a>";
    echo "<a href='?action=clear' class='btn'>再次清除</a>";
    
} else {
    // 显示信息和清除按钮
    echo "<div class='info'>";
    echo "<h3>📖 此工具将清除以下缓存：</h3>";
    echo "<ul>";
    echo "<li>WordPress对象缓存（Object Cache）</li>";
    echo "<li>瞬态缓存（Transients）</li>";
    echo "<li>主题自定义缓存</li>";
    echo "<li>URL重写规则缓存</li>";
    echo "<li>缓存插件（如果安装）</li>";
    echo "<li>PHP OPcache（如果启用）</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='warning'>";
    echo "<strong>⚠️ 注意：</strong>清除缓存后，网站可能会暂时变慢，因为需要重新生成缓存。";
    echo "</div>";
    
    echo "<a href='?action=clear' class='btn'>🧹 立即清除所有缓存</a>";
}

?>

    <h2>🌐 浏览器缓存清除指南</h2>
    
    <h3>Chrome / Edge：</h3>
    <ol>
        <li>按 <code>Ctrl + Shift + Delete</code></li>
        <li>时间范围选择 "全部"</li>
        <li>勾选 "缓存的图像和文件"</li>
        <li>点击 "清除数据"</li>
        <li>按 <code>Ctrl + Shift + R</code> 强制刷新页面</li>
    </ol>
    
    <h3>Firefox：</h3>
    <ol>
        <li>按 <code>Ctrl + Shift + Delete</code></li>
        <li>时间范围选择 "全部"</li>
        <li>勾选 "缓存"</li>
        <li>点击 "立即清除"</li>
        <li>按 <code>Ctrl + Shift + R</code> 强制刷新页面</li>
    </ol>
    
    <h3>Safari：</h3>
    <ol>
        <li>Safari → 偏好设置 → 高级</li>
        <li>勾选 "在菜单栏中显示开发菜单"</li>
        <li>开发 → 清空缓存</li>
        <li>按 <code>Cmd + Shift + R</code> 强制刷新页面</li>
    </ol>

    <div class="warning" style="margin-top: 30px;">
        <strong>🔒 安全提示：</strong>使用完毕后，请删除此文件！
        <br>
        <code>rm clear-wordpress-cache.php</code>
    </div>

</div>
</body>
</html>
