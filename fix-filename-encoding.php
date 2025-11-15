<?php
/**
 * 文件名编码修复工具
 * 专门解决uploads目录中文件名乱码问题
 */

// WordPress环境初始化
if (!defined('ABSPATH')) {
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
if (!current_user_can('administrator') && !isset($_GET['force'])) {
    die('<h1>权限不足</h1><p>请以管理员身份登录WordPress后台，或在URL后添加?force=1参数。</p>');
}

echo "<h1>文件名编码修复工具</h1>";
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
    .file-analysis { margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
</style>";

// 获取uploads目录
$upload_dir = wp_upload_dir();
$uploads_path = $upload_dir['basedir'];

echo "<h2>1. 文件名编码问题诊断</h2>";

// 分析可能的乱码文件
$suspicious_files = array();
$total_files = 0;

if (is_dir($uploads_path)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($uploads_path),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $filename = $file->getFilename();
            $total_files++;
            
            // 检测可能的乱码文件名
            // 1. 包含奇怪的字符组合
            // 2. 看起来像是编码错误的模式
            if (preg_match('/[àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ]|i-\d+x\d+|\?{2,}|[^\x00-\x7F]{3,}/', $filename) ||
                mb_detect_encoding($filename, 'UTF-8', true) === false ||
                strpos($filename, '?') !== false) {
                
                $suspicious_files[] = array(
                    'path' => $file->getPathname(),
                    'filename' => $filename,
                    'relative' => str_replace($uploads_path, '', $file->getPathname()),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime(),
                    'encoding' => mb_detect_encoding($filename, array('UTF-8', 'GB2312', 'GBK', 'ASCII'), true)
                );
            }
        }
    }
}

echo "<div class='info'>";
echo "<p><strong>扫描结果：</strong></p>";
echo "<ul>";
echo "<li>总文件数：{$total_files}</li>";
echo "<li>可疑乱码文件：" . count($suspicious_files) . "</li>";
echo "</ul>";
echo "</div>";

if ($suspicious_files) {
    echo "<h3>发现的可疑文件：</h3>";
    echo "<table>";
    echo "<tr><th>文件路径</th><th>当前文件名</th><th>检测编码</th><th>文件大小</th><th>修改时间</th></tr>";
    
    foreach (array_slice($suspicious_files, 0, 20) as $file) {
        echo "<tr>";
        echo "<td>" . esc_html($file['relative']) . "</td>";
        echo "<td>" . esc_html($file['filename']) . "</td>";
        echo "<td>" . ($file['encoding'] ?: '未知') . "</td>";
        echo "<td>" . size_format($file['size']) . "</td>";
        echo "<td>" . date('Y-m-d H:i:s', $file['modified']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if (count($suspicious_files) > 20) {
        echo "<p class='info'>显示前20个，共找到 " . count($suspicious_files) . " 个可疑文件。</p>";
    }
} else {
    echo "<p class='success'>✓ 未发现明显的文件名编码问题。</p>";
}

// 检查数据库中的图片记录
echo "<h2>2. 数据库图片记录检查</h2>";
global $wpdb;

$db_issues = array();
$attachments = $wpdb->get_results("
    SELECT p.ID, p.post_title, p.guid, pm.meta_value as file_path
    FROM {$wpdb->posts} p
    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
    WHERE p.post_type = 'attachment'
    AND p.post_mime_type LIKE 'image/%'
    ORDER BY p.post_date DESC
    LIMIT 50
");

if ($attachments) {
    echo "<h3>数据库与实际文件对比：</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>数据库记录</th><th>实际文件状态</th><th>问题类型</th></tr>";
    
    foreach ($attachments as $attachment) {
        $file_path = $uploads_path . '/' . $attachment->file_path;
        $status = '';
        $issue_type = '';
        
        if (empty($attachment->file_path)) {
            $status = '<span class="error">❌ 无文件路径</span>';
            $issue_type = '缺少文件路径';
            $db_issues[] = $attachment->ID;
        } elseif (!file_exists($file_path)) {
            $status = '<span class="error">❌ 文件不存在</span>';
            $issue_type = '文件丢失';
            $db_issues[] = $attachment->ID;
        } elseif (preg_match('/[^\x00-\x7F]/', basename($file_path))) {
            $status = '<span class="warning">⚠️ 可能存在编码问题</span>';
            $issue_type = '文件名编码';
        } else {
            $status = '<span class="success">✓ 正常</span>';
            $issue_type = '-';
        }
        
        echo "<tr>";
        echo "<td>{$attachment->ID}</td>";
        echo "<td>" . esc_html($attachment->file_path) . "</td>";
        echo "<td>{$status}</td>";
        echo "<td>{$issue_type}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    if ($db_issues) {
        echo "<p class='warning'>发现 " . count($db_issues) . " 条数据库记录存在问题。</p>";
    }
}

// 编码问题分析
echo "<h2>3. 编码问题分析与原因</h2>";

echo "<div class='file-analysis'>";
echo "<h3>常见乱码原因：</h3>";
echo "<ul>";
echo "<li><strong>压缩/解压编码不匹配</strong>：Windows中文环境压缩 → Linux UTF-8环境解压</li>";
echo "<li><strong>FTP传输编码问题</strong>：传输过程中字符编码转换错误</li>";
echo "<li><strong>服务器语言环境</strong>：服务器LANG环境设置不支持中文</li>";
echo "<li><strong>解压软件差异</strong>：不同解压软件对文件名编码处理方式不同</li>";
echo "</ul>";
echo "</div>";

echo "<div class='file-analysis'>";
echo "<h3>检查服务器环境：</h3>";
echo "<table>";
echo "<tr><th>环境变量</th><th>当前值</th><th>建议值</th></tr>";
echo "<tr><td>locale</td><td>" . (function_exists('exec') ? exec('locale 2>/dev/null') ?: '无法检测' : '无法检测') . "</td><td>en_US.UTF-8</td></tr>";
echo "<tr><td>LANG</td><td>" . (getenv('LANG') ?: '未设置') . "</td><td>en_US.UTF-8</td></tr>";
echo "<tr><td>LC_ALL</td><td>" . (getenv('LC_ALL') ?: '未设置') . "</td><td>en_US.UTF-8</td></tr>";
echo "</table>";
echo "</div>";

// 修复建议和操作
echo "<h2>4. 修复建议与操作</h2>";

if ($suspicious_files || $db_issues) {
    echo "<div class='warning'>";
    echo "<h3>⚠️ 发现问题，建议修复操作：</h3>";
    echo "</div>";
    
    if (isset($_GET['fix']) && $_GET['fix'] == '1') {
        echo "<h3>正在执行修复...</h3>";
        
        // 修复操作（谨慎进行）
        $fixed_count = 0;
        $error_count = 0;
        
        // 清理数据库中的无效记录
        if ($db_issues) {
            foreach ($db_issues as $attachment_id) {
                $attachment = get_post($attachment_id);
                if ($attachment && empty(get_attached_file($attachment_id))) {
                    // 只删除真正无文件的记录
                    // wp_delete_attachment($attachment_id, true);
                    // $fixed_count++; // 取消注释以启用删除
                    echo "<p class='info'>发现无文件记录 ID: {$attachment_id} (未自动删除，请手动处理)</p>";
                }
            }
        }
        
        echo "<p class='success'>修复完成。处理了 {$fixed_count} 个问题，遇到 {$error_count} 个错误。</p>";
    } else {
        echo "<p><a href='?fix=1&force=1' onclick='return confirm(\"确定要执行修复操作吗？请先备份数据库！\")' style='background: #dc3232; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px; font-weight: bold;'>执行修复操作</a></p>";
    }
} else {
    echo "<p class='success'>✓ 未发现需要修复的编码问题。</p>";
}

// 预防措施
echo "<h2>5. 预防文件名乱码的最佳实践</h2>";

echo "<div class='code'>";
echo "<h3>🔧 服务器端预防措施：</h3>";
echo "<pre>";
echo "# 1. 设置正确的服务器locale\n";
echo "export LANG=en_US.UTF-8\n";
echo "export LC_ALL=en_US.UTF-8\n\n";

echo "# 2. 解压时指定编码\n";
echo "# 对于从Windows传来的zip文件\n";
echo "unzip -O CP936 filename.zip  # 中文Windows编码\n";
echo "# 或\n";
echo "unzip -O GBK filename.zip\n\n";

echo "# 3. 使用7zip解压（更好的编码支持）\n";
echo "7za x filename.zip\n\n";

echo "# 4. 文件名批量转码（如需要）\n";
echo "convmv -f gbk -t utf8 --notest -r /path/to/uploads/\n";
echo "</pre>";
echo "</div>";

echo "<div class='code'>";
echo "<h3>📋 操作流程建议：</h3>";
echo "<ol>";
echo "<li><strong>避免中文文件名</strong>：上传前将文件重命名为英文</li>";
echo "<li><strong>正确压缩</strong>：使用7-Zip等支持UTF-8的压缩软件</li>";
echo "<li><strong>正确解压</strong>：在服务器上使用正确的解压命令</li>";
echo "<li><strong>批量重命名</strong>：使用脚本批量将文件名改为英文+数字</li>";
echo "<li><strong>测试验证</strong>：解压后立即检查文件名是否正确</li>";
echo "</ol>";
echo "</div>";

// 文件名标准化建议
echo "<h3>📝 文件命名规范建议：</h3>";
echo "<div class='code'>";
echo "<pre>";
echo "建议的文件命名格式：\n";
echo "产品图片：product-001.jpg, product-002.png\n";
echo "分类图片：category-建筑材料.jpg → category-construction.jpg\n";
echo "新闻图片：news-20241113-001.jpg\n";
echo "其他资源：company-logo.png, hero-bg.jpg\n\n";

echo "避免使用：\n";
echo "❌ 中文字符：产品图片.jpg\n";
echo "❌ 特殊字符：product@#$.jpg\n";
echo "❌ 空格：product image.jpg\n";
echo "❌ 大写字母：PRODUCT.JPG\n\n";

echo "推荐使用：\n";
echo "✅ 英文小写：product.jpg\n";
echo "✅ 数字编号：product-001.jpg\n";
echo "✅ 连字符分隔：product-image.jpg\n";
echo "✅ 统一格式：consistent-naming.jpg\n";
echo "</pre>";
echo "</div>";

// 技术解决方案
echo "<h2>6. 技术解决方案</h2>";

echo "<div class='code'>";
echo "<h3>WordPress自动处理中文文件名：</h3>";
echo "<pre>";
echo "// 在functions.php中添加：\n";
echo "function sanitize_chinese_filename(\$filename) {\n";
echo "    // 转换中文为拼音或移除特殊字符\n";
echo "    \$filename = preg_replace('/[^\\w\\-\\.]+/u', '-', \$filename);\n";
echo "    return \$filename;\n";
echo "}\n";
echo "add_filter('sanitize_file_name', 'sanitize_chinese_filename');\n";
echo "</pre>";
echo "</div>";

echo "<hr>";
echo "<p class='info'>文件名编码问题诊断完成。</p>";
echo "<p><strong>建议：</strong>重新整理uploads文件夹，使用英文文件名，可以彻底解决此类问题。</p>";
echo "<p><small>最后更新：" . date('Y-m-d H:i:s') . "</small></p>";
?>
