<?php
/**
 * 强制修复Tab标签显示问题
 * 清除ACF缓存并重新注册字段组
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
    <title>强制修复Tab标签</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 500px; overflow-y: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #2271b1; color: white; text-decoration: none; border-radius: 3px; margin: 10px 5px; }
        .btn:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>强制修复Tab标签显示问题</h1>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 1. 删除JSON文件
    echo '<h2>步骤1: 删除JSON文件</h2>';
    $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
    if (file_exists($json_file)) {
        if (@unlink($json_file)) {
            echo '<div class="success">✓ 已删除JSON文件</div>';
        } else {
            echo '<div class="error">✗ 无法删除JSON文件</div>';
        }
    } else {
        echo '<div class="info">JSON文件不存在，跳过</div>';
    }
    
    // 2. 清除ACF缓存
    echo '<h2>步骤2: 清除ACF缓存</h2>';
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        acf_get_store('fields')->reset();
        echo '<div class="success">✓ ACF缓存已清除</div>';
    }
    wp_cache_flush();
    echo '<div class="success">✓ WordPress缓存已清除</div>';
    
    // 3. 删除数据库中的字段组（如果存在）
    echo '<h2>步骤3: 删除数据库中的字段组</h2>';
    $existing_group = acf_get_field_group('group_homepage_settings');
    if ($existing_group && isset($existing_group['ID']) && $existing_group['ID'] > 0) {
        $post_id = $existing_group['ID'];
        if (wp_delete_post($post_id, true)) {
            echo '<div class="success">✓ 已删除数据库中的字段组 (ID: ' . $post_id . ')</div>';
        } else {
            echo '<div class="error">✗ 无法删除数据库中的字段组</div>';
        }
    } else {
        echo '<div class="info">数据库中没有字段组，跳过</div>';
    }
    
    // 4. 重新注册字段组
    echo '<h2>步骤4: 重新注册字段组</h2>';
    require_once(get_template_directory() . '/inc/acf-fields.php');
    
    // 再次清除缓存
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        acf_get_store('fields')->reset();
    }
    wp_cache_flush();
    
    echo '<div class="info">正在调用注册函数...</div>';
    $result = angola_b2b_register_homepage_settings_fields();
    
    if ($result) {
        echo '<div class="success">✓ 字段组注册成功</div>';
    } else {
        echo '<div class="error">✗ 字段组注册失败</div>';
    }
    
    // 5. 验证注册结果
    echo '<h2>步骤5: 验证注册结果</h2>';
    $group = acf_get_field_group('group_homepage_settings');
    if ($group) {
        echo '<div class="success">✓ 字段组已注册</div>';
        $fields = acf_get_fields($group);
        if (!empty($fields)) {
            echo '<div class="success">✓ 字段数量: ' . count($fields) . '</div>';
            
            // 检查Tab字段的label
            echo '<h3>Tab字段检查:</h3>';
            $tab_count = 0;
            foreach ($fields as $field) {
                if ($field['type'] === 'tab') {
                    $tab_count++;
                    $label = isset($field['label']) ? $field['label'] : '(空)';
                    $key = isset($field['key']) ? $field['key'] : '(无key)';
                    if (!empty($label)) {
                        echo '<div class="success">✓ Tab ' . $tab_count . ': ' . esc_html($key) . ' → ' . esc_html($label) . '</div>';
                    } else {
                        echo '<div class="error">✗ Tab ' . $tab_count . ': ' . esc_html($key) . ' → label为空！</div>';
                    }
                }
            }
            if ($tab_count === 0) {
                echo '<div class="error">✗ 没有找到Tab字段</div>';
            }
        } else {
            echo '<div class="error">✗ 字段组没有字段</div>';
        }
    } else {
        echo '<div class="error">✗ 字段组未注册</div>';
    }
    ?>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" class="btn">打开首页设置页面</a>
        <a href="/wp-admin/admin.php?page=acf-tools" class="btn">ACF工具页面</a>
    </div>
</body>
</html>

