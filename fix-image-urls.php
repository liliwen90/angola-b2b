<?php
/**
 * 修复图片URL问题的脚本
 * 请将此文件上传到服务器的WordPress根目录，然后在浏览器中访问运行
 */

// WordPress环境初始化
if (!defined('ABSPATH')) {
    // 尝试加载WordPress
    if (file_exists('./wp-load.php')) {
        require_once('./wp-load.php');
    } elseif (file_exists('../wp-load.php')) {
        require_once('../wp-load.php');
    } else {
        die('无法找到WordPress。请确保此文件在WordPress根目录中。');
    }
}

// 安全检查 - 只允许管理员运行
if (!current_user_can('administrator') && !isset($_GET['force'])) {
    die('权限不足。请以管理员身份登录WordPress后台，或在URL后添加?force=1参数。');
}

echo "<h1>图片URL问题诊断与修复工具</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { color: blue; }
    .warning { color: orange; font-weight: bold; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .code { background: #f4f4f4; padding: 10px; border-radius: 5px; margin: 10px 0; }
</style>";

// 1. 检查WordPress基本URL配置
echo "<h2>1. WordPress URL配置检查</h2>";
$site_url = get_option('siteurl');
$home_url = get_option('home');
echo "<table>";
echo "<tr><th>配置项</th><th>当前值</th><th>状态</th></tr>";
echo "<tr><td>Site URL</td><td>{$site_url}</td><td>" . 
     (strpos($site_url, 'unibroint.com') !== false ? '<span class="success">✓ 正确</span>' : '<span class="error">✗ 需要修复</span>') . "</td></tr>";
echo "<tr><td>Home URL</td><td>{$home_url}</td><td>" . 
     (strpos($home_url, 'unibroint.com') !== false ? '<span class="success">✓ 正确</span>' : '<span class="error">✗ 需要修复</span>') . "</td></tr>";
echo "</table>";

// 2. 检查uploads目录
echo "<h2>2. Uploads目录检查</h2>";
$upload_dir = wp_upload_dir();
echo "<table>";
echo "<tr><th>配置项</th><th>路径</th><th>状态</th></tr>";
echo "<tr><td>Upload Path</td><td>{$upload_dir['basedir']}</td><td>" . 
     (is_dir($upload_dir['basedir']) ? '<span class="success">✓ 存在</span>' : '<span class="error">✗ 不存在</span>') . "</td></tr>";
echo "<tr><td>Upload URL</td><td>{$upload_dir['baseurl']}</td><td>" . 
     (strpos($upload_dir['baseurl'], 'unibroint.com') !== false ? '<span class="success">✓ 正确</span>' : '<span class="error">✗ 需要修复</span>') . "</td></tr>";
echo "<tr><td>目录权限</td><td>-</td><td>" . 
     (is_writable($upload_dir['basedir']) ? '<span class="success">✓ 可写</span>' : '<span class="error">✗ 无写入权限</span>') . "</td></tr>";
echo "</table>";

// 3. 检查数据库中的图片URL
echo "<h2>3. 数据库图片URL检查</h2>";
global $wpdb;

// 检查附件表中的URL
$old_urls = $wpdb->get_results("
    SELECT ID, post_title, guid 
    FROM {$wpdb->posts} 
    WHERE post_type = 'attachment' 
    AND guid LIKE '%angola-b2b.local%' 
    LIMIT 10
");

echo "<h3>附件表中的旧URL (显示前10条)：</h3>";
if ($old_urls) {
    echo "<table>";
    echo "<tr><th>ID</th><th>标题</th><th>URL</th></tr>";
    foreach ($old_urls as $attachment) {
        echo "<tr><td>{$attachment->ID}</td><td>{$attachment->post_title}</td><td>{$attachment->guid}</td></tr>";
    }
    echo "</table>";
    echo "<p class='error'>发现 " . count($old_urls) . " 个需要修复的附件URL。</p>";
} else {
    echo "<p class='success'>✓ 附件表中未发现旧URL。</p>";
}

// 检查postmeta表中的ACF图片字段
$old_meta_urls = $wpdb->get_results("
    SELECT post_id, meta_key, meta_value 
    FROM {$wpdb->postmeta} 
    WHERE meta_value LIKE '%angola-b2b.local%' 
    AND (meta_key LIKE '%image%' OR meta_key LIKE '%photo%' OR meta_key LIKE '%picture%')
    LIMIT 10
");

echo "<h3>自定义字段中的旧URL (显示前10条)：</h3>";
if ($old_meta_urls) {
    echo "<table>";
    echo "<tr><th>文章ID</th><th>字段名</th><th>URL</th></tr>";
    foreach ($old_meta_urls as $meta) {
        echo "<tr><td>{$meta->post_id}</td><td>{$meta->meta_key}</td><td>" . substr($meta->meta_value, 0, 80) . "...</td></tr>";
    }
    echo "</table>";
    echo "<p class='error'>发现 " . count($old_meta_urls) . " 个需要修复的自定义字段URL。</p>";
} else {
    echo "<p class='success'>✓ 自定义字段中未发现旧URL。</p>";
}

// 4. 修复建议
echo "<h2>4. 修复操作</h2>";

if (isset($_GET['fix']) && $_GET['fix'] == '1') {
    echo "<h3>正在执行修复...</h3>";
    
    // 修复WordPress基本URL
    if (strpos($site_url, 'angola-b2b.local') !== false) {
        $new_site_url = str_replace('http://angola-b2b.local', 'https://www.unibroint.com', $site_url);
        update_option('siteurl', $new_site_url);
        echo "<p class='success'>✓ 已更新Site URL: {$new_site_url}</p>";
    }
    
    if (strpos($home_url, 'angola-b2b.local') !== false) {
        $new_home_url = str_replace('http://angola-b2b.local', 'https://www.unibroint.com', $home_url);
        update_option('home', $new_home_url);
        echo "<p class='success'>✓ 已更新Home URL: {$new_home_url}</p>";
    }
    
    // 修复附件URL
    $affected_attachments = $wpdb->query("
        UPDATE {$wpdb->posts} 
        SET guid = REPLACE(guid, 'http://angola-b2b.local', 'https://www.unibroint.com')
        WHERE post_type = 'attachment' 
        AND guid LIKE '%angola-b2b.local%'
    ");
    
    if ($affected_attachments > 0) {
        echo "<p class='success'>✓ 已修复 {$affected_attachments} 个附件URL。</p>";
    }
    
    // 修复postmeta中的URL
    $affected_meta = $wpdb->query("
        UPDATE {$wpdb->postmeta} 
        SET meta_value = REPLACE(meta_value, 'http://angola-b2b.local', 'https://www.unibroint.com')
        WHERE meta_value LIKE '%angola-b2b.local%'
    ");
    
    if ($affected_meta > 0) {
        echo "<p class='success'>✓ 已修复 {$affected_meta} 个自定义字段URL。</p>";
    }
    
    // 修复文章内容中的URL
    $affected_content = $wpdb->query("
        UPDATE {$wpdb->posts} 
        SET post_content = REPLACE(post_content, 'http://angola-b2b.local', 'https://www.unibroint.com')
        WHERE post_content LIKE '%angola-b2b.local%'
    ");
    
    if ($affected_content > 0) {
        echo "<p class='success'>✓ 已修复 {$affected_content} 篇文章内容中的URL。</p>";
    }
    
    echo "<h3 class='success'>修复完成！请刷新页面查看结果。</h3>";
    echo "<p><a href='?' style='background: #0073aa; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;'>重新检查</a></p>";
    
} else {
    echo "<p class='warning'>⚠️ 发现问题需要修复。</p>";
    echo "<p><strong>修复步骤：</strong></p>";
    echo "<ol>";
    echo "<li>确保你已经备份了数据库</li>";
    echo "<li>点击下面的按钮执行自动修复</li>";
    echo "<li>修复完成后，清除任何缓存插件的缓存</li>";
    echo "<li>检查网站前台图片显示是否正常</li>";
    echo "</ol>";
    
    echo "<p><a href='?fix=1' onclick='return confirm(\"确定要执行修复操作吗？请确保已经备份数据库。\")' style='background: #dc3232; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px; font-weight: bold;'>执行修复操作</a></p>";
    
    // 手动修复命令
    echo "<h3>手动修复命令（备选方案）：</h3>";
    echo "<div class='code'>";
    echo "<p>如果你更喜欢使用命令行，可以在服务器上执行以下MySQL命令：</p>";
    echo "<pre>";
    echo "# 1. 修复WordPress基本设置\n";
    echo "UPDATE wp_options SET option_value = 'https://www.unibroint.com' WHERE option_name = 'home';\n";
    echo "UPDATE wp_options SET option_value = 'https://www.unibroint.com' WHERE option_name = 'siteurl';\n\n";
    echo "# 2. 修复附件URL\n";
    echo "UPDATE wp_posts SET guid = REPLACE(guid, 'http://angola-b2b.local', 'https://www.unibroint.com') WHERE post_type = 'attachment';\n\n";
    echo "# 3. 修复自定义字段\n";
    echo "UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, 'http://angola-b2b.local', 'https://www.unibroint.com');\n\n";
    echo "# 4. 修复文章内容\n";
    echo "UPDATE wp_posts SET post_content = REPLACE(post_content, 'http://angola-b2b.local', 'https://www.unibroint.com');\n";
    echo "</pre>";
    echo "</div>";
}

// 5. 附加检查
echo "<h2>5. 附加检查建议</h2>";
echo "<ul>";
echo "<li>检查 <code>wp-config.php</code> 文件中是否定义了 WP_HOME 或 WP_SITEURL 常量</li>";
echo "<li>检查服务器的 <code>.htaccess</code> 文件是否正确配置</li>";
echo "<li>确保 uploads 目录的权限设置为 755 或 775</li>";
echo "<li>如果使用了缓存插件，请清除所有缓存</li>";
echo "<li>检查CDN设置（如果有的话）</li>";
echo "</ul>";

// 6. 测试图片显示
echo "<h2>6. 图片显示测试</h2>";
$test_images = $wpdb->get_results("
    SELECT ID, post_title, guid 
    FROM {$wpdb->posts} 
    WHERE post_type = 'attachment' 
    AND post_mime_type LIKE 'image/%' 
    LIMIT 3
");

if ($test_images) {
    echo "<p>以下是系统中的几张测试图片：</p>";
    foreach ($test_images as $image) {
        echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ddd;'>";
        echo "<p><strong>{$image->post_title}</strong></p>";
        echo "<p>URL: <a href='{$image->guid}' target='_blank'>{$image->guid}</a></p>";
        echo "<img src='{$image->guid}' style='max-width: 200px; max-height: 150px;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"block\";'>";
        echo "<p style='display:none; color:red;'>❌ 图片无法加载</p>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<p class='info'>脚本执行完成。如有问题请联系技术支持。</p>";
echo "<p><small>最后更新：" . date('Y-m-d H:i:s') . "</small></p>";
?>
