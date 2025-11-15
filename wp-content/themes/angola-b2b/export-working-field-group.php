<?php
/**
 * 导出成功注册的字段组结构
 * 用于对比和复制
 */

// 加载WordPress
$wp_load_paths = array(
    dirname(dirname(dirname(dirname(__FILE__)))) . '/local-site/angola-b2b/app/public/wp-load.php',
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',
    'F:/011 Projects/UnibroWeb/Unirbro/local-site/angola-b2b/app/public/wp-load.php',
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
    <title>导出成功字段组结构</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 600px; overflow-y: auto; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>导出成功字段组结构</h1>
    
    <?php
    if (!function_exists('acf_get_field_groups')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 获取所有已注册的字段组
    $all_groups = acf_get_field_groups();
    
    if (empty($all_groups)) {
        echo '<div class="error">没有找到已注册的字段组</div>';
        exit;
    }
    
    echo '<div class="success">找到 ' . count($all_groups) . ' 个字段组</div>';
    
    // 找到第一个有字段的字段组
    $working_group = null;
    foreach ($all_groups as $group) {
        $fields = acf_get_fields($group);
        if (!empty($fields) && count($fields) > 0) {
            $working_group = $group;
            break;
        }
    }
    
    if (!$working_group) {
        echo '<div class="error">没有找到有字段的字段组</div>';
        exit;
    }
    
    $working_fields = acf_get_fields($working_group);
    
    echo '<h2>成功字段组: ' . $working_group['title'] . '</h2>';
    echo '<div class="success">字段数量: ' . count($working_fields) . '</div>';
    
    echo '<h3>字段组完整结构:</h3>';
    echo '<pre>' . esc_html(print_r($working_group, true)) . '</pre>';
    
    echo '<h3>第一个字段完整结构:</h3>';
    if (!empty($working_fields)) {
        echo '<pre>' . esc_html(print_r($working_fields[0], true)) . '</pre>';
    }
    
    // 生成PHP代码
    echo '<h3>生成的PHP代码（用于复制）:</h3>';
    echo '<pre>';
    echo '// 字段组数据' . "\n";
    echo '$field_group_data = ' . var_export($working_group, true) . ';' . "\n\n";
    
    if (!empty($working_fields)) {
        echo '// 第一个字段数据' . "\n";
        echo '$sample_field = ' . var_export($working_fields[0], true) . ';' . "\n";
    }
    echo '</pre>';
    ?>
</body>
</html>

