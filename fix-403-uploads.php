<?php
/**
 * 修复Uploads目录403错误问题
 * 上传到服务器WordPress根目录执行
 */

// WordPress环境初始化
if (!defined('ABSPATH')) {
    // 尝试多种方式加载WordPress
    $wp_load_paths = array(
        __DIR__ . '/wp-load.php',
        __DIR__ . '/wp-config.php',
        dirname(__FILE__) . '/wp-load.php',
        dirname(__FILE__) . '/wp-config.php',
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
        die('<h1>WordPress环境加载失败</h1><p>请确保此文件在WordPress根目录中。</p>');
    }
}

// 安全检查
if (!isset($_GET['force']) && function_exists('current_user_can') && !current_user_can('administrator')) {
    die('<h1>权限不足</h1><p>请以管理员身份登录WordPress后台，或在URL后添加?force=1参数。</p>');
}

echo "<h1>修复Uploads目录403错误</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .info { color: blue; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .code { background: #f4f4f4; padding: 10px; border-radius: 5px; margin: 10px 0; font-family: monospace; }
</style>";

// 检查上传目录
if (function_exists('wp_upload_dir')) {
    $upload_dir = wp_upload_dir();
    $uploads_path = $upload_dir['basedir'];
    $uploads_url = $upload_dir['baseurl'];
} else {
    // 如果WordPress函数不可用，使用默认路径
    $uploads_path = (defined('ABSPATH') ? ABSPATH : __DIR__ . '/') . 'wp-content/uploads';
    $uploads_url = (defined('WP_CONTENT_URL') ? WP_CONTENT_URL : '/wp-content') . '/uploads';
}

echo "<h2>1. 诊断Uploads目录权限</h2>";
echo "<table>";
echo "<tr><th>检查项</th><th>路径</th><th>状态</th><th>权限</th></tr>";

$checks = array(
    'wp-content' => (defined('ABSPATH') ? ABSPATH : __DIR__ . '/') . 'wp-content',
    'uploads' => $uploads_path,
    'uploads/2024' => $uploads_path . '/2024',
    'uploads/2025' => $uploads_path . '/2025'
);

foreach ($checks as $name => $path) {
    $exists = file_exists($path);
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    $readable = $exists ? is_readable($path) : false;
    $writable = $exists ? is_writable($path) : false;
    
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td>$path</td>";
    echo "<td>";
    if ($exists) {
        echo '<span class="success">✓ 存在</span>';
        if (!$readable) echo '<br><span class="error">✗ 不可读</span>';
        if (!$writable) echo '<br><span class="warning">⚠ 不可写</span>';
    } else {
        echo '<span class="error">✗ 不存在</span>';
    }
    echo "</td>";
    echo "<td>$perms</td>";
    echo "</tr>";
}
echo "</table>";

// 检查.htaccess文件
echo "<h2>2. 检查.htaccess配置</h2>";

$htaccess_files = array(
    (defined('ABSPATH') ? ABSPATH : __DIR__ . '/') . '.htaccess' => '根目录',
    $uploads_path . '/.htaccess' => 'uploads目录'
);

foreach ($htaccess_files as $file => $desc) {
    echo "<h3>$desc .htaccess 文件</h3>";
    if (file_exists($file)) {
        $content = file_get_contents($file);
        echo "<div class='code'>";
        echo "<strong>文件存在，内容：</strong><br>";
        echo "<pre>" . htmlspecialchars($content) . "</pre>";
        echo "</div>";
        
        // 检查是否有阻止访问的规则
        if (strpos($content, 'deny from all') !== false || strpos($content, 'Deny from all') !== false) {
            echo "<p class='error'>⚠️ 发现阻止访问的规则！</p>";
        }
    } else {
        echo "<p class='info'>文件不存在</p>";
    }
}

// 修复操作
echo "<h2>3. 修复操作</h2>";

if (isset($_GET['fix']) && $_GET['fix'] == '1') {
    echo "<h3>正在执行修复...</h3>";
    
    $fixed = array();
    $errors = array();
    
    // 1. 设置uploads目录权限
    if (is_dir($uploads_path)) {
        if (chmod($uploads_path, 0755)) {
            $fixed[] = "设置uploads目录权限为755";
        } else {
            $errors[] = "无法设置uploads目录权限";
        }
        
        // 递归设置子目录权限
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($uploads_path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        $count = 0;
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                chmod($file->getPathname(), 0755);
                $count++;
            } else {
                chmod($file->getPathname(), 0644);
                $count++;
            }
            if ($count > 1000) break; // 防止超时
        }
        $fixed[] = "处理了 $count 个文件/目录的权限";
    }
    
    // 2. 创建/修复uploads目录的.htaccess
    $uploads_htaccess = $uploads_path . '/.htaccess';
    $htaccess_content = "# Allow access to uploaded files
<Files ~ \"\\.(jpg|jpeg|png|gif|webp|svg|pdf|doc|docx|zip)$\">
    Order allow,deny
    Allow from all
</Files>

# Disable PHP execution
<Files *.php>
    deny from all
</Files>
";
    
    if (file_put_contents($uploads_htaccess, $htaccess_content)) {
        $fixed[] = "创建/更新uploads目录.htaccess文件";
        chmod($uploads_htaccess, 0644);
    } else {
        $errors[] = "无法写入uploads目录.htaccess文件";
    }
    
    // 3. 检查主.htaccess是否有问题
    $main_htaccess = (defined('ABSPATH') ? ABSPATH : __DIR__ . '/') . '.htaccess';
    if (file_exists($main_htaccess)) {
        $content = file_get_contents($main_htaccess);
        
        // 确保WordPress重写规则正确
        $wp_rules = "# BEGIN WordPress
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
";
        
        // 如果没有WordPress规则或规则不正确，添加它们
        if (strpos($content, '# BEGIN WordPress') === false) {
            if (file_put_contents($main_htaccess, $content . "\n" . $wp_rules)) {
                $fixed[] = "添加WordPress重写规则";
            }
        }
    }
    
    // 4. 测试修复结果
    echo "<h3>修复结果：</h3>";
    if ($fixed) {
        echo "<ul>";
        foreach ($fixed as $fix) {
            echo "<li class='success'>✓ $fix</li>";
        }
        echo "</ul>";
    }
    
    if ($errors) {
        echo "<h4>遇到的错误：</h4>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li class='error'>✗ $error</li>";
        }
        echo "</ul>";
    }
    
    echo "<p class='info'>修复完成！请清除任何缓存并刷新网站。</p>";
    echo "<p><a href='?' style='background: #0073aa; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;'>重新检查</a></p>";
    
} else {
    echo "<p class='warning'>发现403权限问题，需要修复。</p>";
    echo "<p><a href='?fix=1&force=1' onclick='return confirm(\"确定要执行修复操作吗？\")' style='background: #dc3232; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px; font-weight: bold;'>执行修复操作</a></p>";
}

// 4. 手动修复命令（备用方案）
echo "<h2>4. 手动修复命令（SSH方式）</h2>";
echo "<div class='code'>";
echo "<strong>通过SSH连接到服务器执行：</strong><br>";
echo "<pre>";
echo "# 连接到服务器\n";
echo "ssh root@8.208.30.159\n\n";
echo "# 进入网站目录\n";
echo "cd /www/wwwroot/www.unibroint.com\n\n";
echo "# 设置uploads目录权限\n";
echo "chown -R www:www wp-content/uploads/\n";
echo "find wp-content/uploads/ -type d -exec chmod 755 {} \\;\n";
echo "find wp-content/uploads/ -type f -exec chmod 644 {} \\;\n\n";
echo "# 创建uploads .htaccess\n";
echo "cat > wp-content/uploads/.htaccess << 'EOF'\n";
echo "# Allow access to uploaded files\n";
echo "<Files ~ \"\\\\.(jpg|jpeg|png|gif|webp|svg|pdf|doc|docx|zip)$\">\n";
echo "    Order allow,deny\n";
echo "    Allow from all\n";
echo "</Files>\n";
echo "\n";
echo "# Disable PHP execution\n";
echo "<Files *.php>\n";
echo "    deny from all\n";
echo "</Files>\n";
echo "EOF\n\n";
echo "# 设置.htaccess权限\n";
echo "chmod 644 wp-content/uploads/.htaccess\n";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<p class='info'>工具执行完成。403错误通常是权限问题，修复后图片应该能正常显示。</p>";
echo "<p><small>最后更新：" . date('Y-m-d H:i:s') . "</small></p>";
?>
