<?php
/**
 * 修复上传目录权限
 * 上传到服务器后，在浏览器访问一次即可
 * 完成后删除此文件
 */

// 加载WordPress
require_once('wp-load.php');

// 检查管理员权限
if (!current_user_can('manage_options')) {
    die('需要管理员权限');
}

// 获取上传目录
$upload_dir = wp_upload_dir();
$base_dir = $upload_dir['basedir'];

echo '<h1>修复上传目录权限</h1>';
echo '<p>基础目录: ' . $base_dir . '</p>';

// 创建2025/11目录
$year_month_dir = $base_dir . '/2025/11';

if (!file_exists($year_month_dir)) {
    if (wp_mkdir_p($year_month_dir)) {
        echo '<p style="color: green;">✅ 成功创建目录: ' . $year_month_dir . '</p>';
    } else {
        echo '<p style="color: red;">❌ 创建目录失败: ' . $year_month_dir . '</p>';
        echo '<p>请通过SSH/FTP手动创建并设置权限为755</p>';
    }
} else {
    echo '<p style="color: blue;">ℹ️ 目录已存在: ' . $year_month_dir . '</p>';
}

// 检查权限
if (is_writable($year_month_dir)) {
    echo '<p style="color: green;">✅ 目录可写</p>';
} else {
    echo '<p style="color: red;">❌ 目录不可写</p>';
    echo '<p>请通过SSH运行: chmod 755 ' . $year_month_dir . '</p>';
}

// 测试创建文件
$test_file = $year_month_dir . '/test-write.txt';
if (file_put_contents($test_file, 'test')) {
    echo '<p style="color: green;">✅ 写入测试成功</p>';
    unlink($test_file);
} else {
    echo '<p style="color: red;">❌ 写入测试失败</p>';
}

echo '<hr>';
echo '<p><a href="' . admin_url('upload.php') . '">返回媒体库</a></p>';
echo '<p style="color: red;">完成后请删除此文件: fix-uploads-permissions.php</p>';
