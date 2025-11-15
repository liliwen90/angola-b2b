<?php
/**
 * 图片文件深度诊断工具
 * 专门检查uploads文件夹中的实际文件问题
 */

// WordPress环境初始化
if (!defined('ABSPATH')) {
    if (file_exists('./wp-load.php')) {
        require_once('./wp-load.php');
    } elseif (file_exists('../wp-load.php')) {
        require_once('../wp-load.php');
    } else {
        die('无法找到WordPress。请确保此文件在WordPress根目录中。');
    }
}

// 安全检查
if (!current_user_can('administrator') && !isset($_GET['force'])) {
    die('权限不足。请以管理员身份登录WordPress后台，或在URL后添加?force=1参数。');
}

echo "<h1>图片文件深度诊断工具</h1>";
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
    .file-test { margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
</style>";

// 1. 检查uploads目录结构
echo "<h2>1. Uploads目录结构分析</h2>";
$upload_dir = wp_upload_dir();
$uploads_path = $upload_dir['basedir'];
$uploads_url = $upload_dir['baseurl'];

echo "<p><strong>Uploads基本路径:</strong></p>";
echo "<table>";
echo "<tr><th>类型</th><th>路径</th><th>状态</th></tr>";
echo "<tr><td>物理路径</td><td>{$uploads_path}</td><td>" . 
     (is_dir($uploads_path) ? '<span class="success">✓ 存在</span>' : '<span class="error">✗ 不存在</span>') . "</td></tr>";
echo "<tr><td>URL路径</td><td>{$uploads_url}</td><td>基础URL</td></tr>";
echo "</table>";

// 检查目录内容
echo "<h3>目录内容扫描（最近文件）：</h3>";
if (is_dir($uploads_path)) {
    $files = array();
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploads_path),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file->getFilename())) {
            $files[] = array(
                'path' => $file->getPathname(),
                'relative' => str_replace($uploads_path, '', $file->getPathname()),
                'size' => $file->getSize(),
                'modified' => $file->getMTime(),
                'permissions' => substr(sprintf('%o', $file->getPerms()), -4)
            );
        }
    }
    
    // 按修改时间排序，显示最新的10个文件
    usort($files, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });
    
    if ($files) {
        echo "<table>";
        echo "<tr><th>文件路径</th><th>大小</th><th>权限</th><th>修改时间</th><th>URL测试</th></tr>";
        
        $count = 0;
        foreach ($files as $file) {
            if ($count >= 10) break;
            
            $relative_url = str_replace(DIRECTORY_SEPARATOR, '/', $file['relative']);
            $file_url = $uploads_url . $relative_url;
            
            echo "<tr>";
            echo "<td>" . esc_html($file['relative']) . "</td>";
            echo "<td>" . size_format($file['size']) . "</td>";
            echo "<td>" . $file['permissions'] . "</td>";
            echo "<td>" . date('Y-m-d H:i:s', $file['modified']) . "</td>";
            echo "<td><a href='{$file_url}' target='_blank' style='color: blue;'>测试链接</a></td>";
            echo "</tr>";
            
            $count++;
        }
        echo "</table>";
        
        echo "<p class='info'>找到 " . count($files) . " 个图片文件，显示最新的 " . min(10, count($files)) . " 个。</p>";
    } else {
        echo "<p class='error'>❌ 未找到任何图片文件！这可能是问题的根源。</p>";
    }
} else {
    echo "<p class='error'>❌ Uploads目录不存在！</p>";
}

// 2. 检查数据库中的具体图片记录
echo "<h2>2. 数据库图片记录详细分析</h2>";
global $wpdb;

$attachments = $wpdb->get_results("
    SELECT p.ID, p.post_title, p.post_name, p.guid, p.post_date,
           pm1.meta_value as file_path,
           pm2.meta_value as attachment_metadata
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = '_wp_attached_file'
    LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_wp_attachment_metadata'
    WHERE p.post_type = 'attachment'
    AND p.post_mime_type LIKE 'image/%'
    ORDER BY p.post_date DESC
    LIMIT 10
");

if ($attachments) {
    echo "<h3>最新的10个图片附件记录：</h3>";
    
    foreach ($attachments as $attachment) {
        echo "<div class='file-test'>";
        echo "<h4>图片ID: {$attachment->ID} - {$attachment->post_title}</h4>";
        
        echo "<table>";
        echo "<tr><th>属性</th><th>值</th><th>状态检查</th></tr>";
        echo "<tr><td>GUID</td><td>{$attachment->guid}</td><td>";
        
        // 测试URL是否可访问
        $response = wp_remote_head($attachment->guid);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) == 200) {
            echo '<span class="success">✓ URL可访问</span>';
        } else {
            echo '<span class="error">✗ URL无法访问</span>';
        }
        echo "</td></tr>";
        
        echo "<tr><td>文件路径</td><td>{$attachment->file_path}</td><td>";
        
        // 检查实际文件是否存在
        $full_file_path = $uploads_path . '/' . $attachment->file_path;
        if (file_exists($full_file_path)) {
            echo '<span class="success">✓ 文件存在</span>';
            echo "<br>大小: " . size_format(filesize($full_file_path));
            echo "<br>权限: " . substr(sprintf('%o', fileperms($full_file_path)), -4);
        } else {
            echo '<span class="error">✗ 文件不存在</span>';
        }
        echo "</td></tr>";
        
        echo "<tr><td>上传时间</td><td>{$attachment->post_date}</td><td>-</td></tr>";
        echo "</table>";
        
        // 显示实际图片（如果可以）
        echo "<p><strong>预览测试:</strong></p>";
        echo "<img src='{$attachment->guid}' style='max-width: 200px; max-height: 150px; border: 1px solid #ddd;' ";
        echo "onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"block\";' />";
        echo "<p style='display:none; color:red;'>❌ 图片加载失败</p>";
        
        echo "</div>";
    }
} else {
    echo "<p class='warning'>⚠️ 数据库中没有找到图片附件记录。</p>";
}

// 3. 服务器配置检查
echo "<h2>3. 服务器配置检查</h2>";

echo "<h3>PHP配置:</h3>";
echo "<table>";
echo "<tr><th>配置项</th><th>当前值</th><th>建议</th></tr>";
echo "<tr><td>upload_max_filesize</td><td>" . ini_get('upload_max_filesize') . "</td><td>至少32M</td></tr>";
echo "<tr><td>post_max_size</td><td>" . ini_get('post_max_size') . "</td><td>至少32M</td></tr>";
echo "<tr><td>memory_limit</td><td>" . ini_get('memory_limit') . "</td><td>至少256M</td></tr>";
echo "<tr><td>max_execution_time</td><td>" . ini_get('max_execution_time') . "</td><td>至少60s</td></tr>";
echo "</table>";

// 4. 文件权限诊断
echo "<h2>4. 文件权限诊断</h2>";

$perm_checks = array(
    'uploads根目录' => $uploads_path,
    '2025目录' => $uploads_path . '/2025',
    '当月目录' => $uploads_path . '/' . date('Y/m')
);

echo "<table>";
echo "<tr><th>目录</th><th>路径</th><th>存在</th><th>可读</th><th>可写</th><th>权限</th></tr>";

foreach ($perm_checks as $name => $path) {
    $exists = is_dir($path);
    $readable = $exists ? is_readable($path) : false;
    $writable = $exists ? is_writable($path) : false;
    $perms = $exists ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    
    echo "<tr>";
    echo "<td>{$name}</td>";
    echo "<td>{$path}</td>";
    echo "<td>" . ($exists ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
    echo "<td>" . ($readable ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
    echo "<td>" . ($writable ? '<span class="success">✓</span>' : '<span class="error">✗</span>') . "</td>";
    echo "<td>{$perms}</td>";
    echo "</tr>";
}
echo "</table>";

// 5. 修复建议
echo "<h2>5. 问题诊断与修复建议</h2>";

$issues = array();
$fixes = array();

// 检查uploads目录是否为空
if (empty($files)) {
    $issues[] = "uploads目录中没有图片文件";
    $fixes[] = "重新上传uploads.zip文件到服务器并正确解压到wp-content/uploads/目录";
}

// 检查数据库记录与实际文件的匹配
$missing_files = 0;
if ($attachments) {
    foreach ($attachments as $attachment) {
        $full_file_path = $uploads_path . '/' . $attachment->file_path;
        if (!file_exists($full_file_path)) {
            $missing_files++;
        }
    }
}

if ($missing_files > 0) {
    $issues[] = "数据库中有 {$missing_files} 个图片记录，但对应的文件不存在";
    $fixes[] = "检查uploads文件夹是否完整上传，可能需要重新上传图片文件";
}

// 显示问题和解决方案
if ($issues) {
    echo "<div class='error'>";
    echo "<h3>发现的问题:</h3>";
    echo "<ul>";
    foreach ($issues as $issue) {
        echo "<li>{$issue}</li>";
    }
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h3>建议的修复步骤:</h3>";
    echo "<ol>";
    foreach ($fixes as $fix) {
        echo "<li>{$fix}</li>";
    }
    echo "</ol>";
    echo "</div>";
} else {
    echo "<p class='success'>✓ 未发现明显问题，图片应该能正常显示。</p>";
}

// 6. 手动修复命令
echo "<h2>6. 手动修复命令</h2>";

echo "<div class='code'>";
echo "<h3>如果需要手动修复文件权限:</h3>";
echo "<pre>";
echo "# SSH连接到服务器后执行\n";
echo "cd /www/wwwroot/www.unibroint.com\n\n";
echo "# 设置uploads目录权限\n";
echo "chown -R www:www wp-content/uploads/\n";
echo "find wp-content/uploads/ -type d -exec chmod 755 {} \\;\n";
echo "find wp-content/uploads/ -type f -exec chmod 644 {} \\;\n\n";
echo "# 如果需要重新上传uploads文件\n";
echo "cd wp-content/\n";
echo "# 删除现有uploads（备份后）\n";
echo "mv uploads uploads-backup-$(date +%Y%m%d)\n";
echo "# 解压新的uploads.zip\n";
echo "unzip uploads.zip\n";
echo "# 设置权限\n";
echo "chown -R www:www uploads/\n";
echo "chmod -R 755 uploads/\n";
echo "</pre>";
echo "</div>";

echo "<div class='code'>";
echo "<h3>测试图片URL的curl命令:</h3>";
echo "<pre>";
if ($attachments) {
    foreach (array_slice($attachments, 0, 3) as $attachment) {
        echo "curl -I \"{$attachment->guid}\"\n";
    }
}
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<p class='info'>诊断完成。请根据上述结果进行相应的修复操作。</p>";
echo "<p><small>最后执行：" . date('Y-m-d H:i:s') . "</small></p>";
?>
