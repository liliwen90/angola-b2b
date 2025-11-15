<?php
/**
 * 从JSON文件创建字段组
 * 如果本地有导出的JSON文件，可以直接使用
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
    <title>从JSON创建字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 400px; overflow-y: auto; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
        textarea { width: 100%; min-height: 300px; background: #0a0a0a; color: #f0f0f1; border: 1px solid #3c434a; padding: 10px; font-family: monospace; }
        button { padding: 10px 20px; background: #2271b1; color: white; border: none; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>从JSON创建字段组</h1>
    <p>如果您在本地WordPress后台导出了字段组JSON，可以在这里粘贴并创建</p>
    
    <?php
    if (!function_exists('acf_get_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 检查是否有上传的JSON文件
    $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
    if (file_exists($json_file)) {
        echo '<div class="info">发现JSON文件: ' . $json_file . '</div>';
        $json_content = file_get_contents($json_file);
        $json_data = json_decode($json_content, true);
        if ($json_data) {
            echo '<div class="success">✓ JSON文件格式正确</div>';
        } else {
            echo '<div class="error">✗ JSON文件格式错误</div>';
        }
    } else {
        echo '<div class="info">未找到JSON文件，您可以手动粘贴JSON内容</div>';
    }
    
    if (isset($_POST['create_from_json']) && !empty($_POST['json_content'])) {
        echo '<div class="test-section">';
        echo '<h2>从JSON创建字段组</h2>';
        
        $json_content = stripslashes($_POST['json_content']);
        $json_data = json_decode($json_content, true);
        
        if (!$json_data) {
            echo '<div class="error">✗ JSON格式错误: ' . json_last_error_msg() . '</div>';
        } else {
            echo '<div class="success">✓ JSON格式正确</div>';
            echo '<div class="info">字段组Key: ' . (isset($json_data['key']) ? $json_data['key'] : '未知') . '</div>';
            echo '<div class="info">字段数量: ' . (isset($json_data['fields']) ? count($json_data['fields']) : 0) . '</div>';
            
            // 删除现有字段组
            global $wpdb;
            $existing_group_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field-group' AND post_name = %s",
                $json_data['key']
            ));
            
            if ($existing_group_id) {
                // 删除所有字段
                $wpdb->delete(
                    $wpdb->posts,
                    array('post_parent' => $existing_group_id, 'post_type' => 'acf-field'),
                    array('%d', '%s')
                );
                wp_delete_post($existing_group_id, true);
                echo '<div class="info">✓ 已删除现有字段组</div>';
            }
            
            // 使用ACF的导入功能
            // 但ACF没有直接的导入函数，我们需要手动创建
            
            // 创建字段组post
            $group_post_id = wp_insert_post(array(
                'post_type' => 'acf-field-group',
                'post_status' => 'publish',
                'post_title' => $json_data['title'],
                'post_name' => $json_data['key'],
                'post_content' => '',
                'post_excerpt' => '',
            ));
            
            if (is_wp_error($group_post_id)) {
                echo '<div class="error">✗ 创建字段组失败: ' . $group_post_id->get_error_message() . '</div>';
            } else {
                echo '<div class="success">✓ 字段组post创建成功 (ID: ' . $group_post_id . ')</div>';
                
                // 设置所有meta数据（直接从JSON）
                foreach ($json_data as $key => $value) {
                    if ($key !== 'fields' && $key !== 'key' && $key !== 'title') {
                        if (is_array($value)) {
                            update_post_meta($group_post_id, 'acf_' . $key, $value);
                            update_post_meta($group_post_id, '_acf_' . $key, $value);
                        } else {
                            update_post_meta($group_post_id, 'acf_' . $key, $value);
                            update_post_meta($group_post_id, '_acf_' . $key, $value);
                        }
                    }
                }
                
                // 设置基本属性
                update_post_meta($group_post_id, 'acf_key', $json_data['key']);
                update_post_meta($group_post_id, 'acf_title', $json_data['title']);
                update_post_meta($group_post_id, '_acf_key', $json_data['key']);
                update_post_meta($group_post_id, '_acf_title', $json_data['title']);
                
                echo '<div class="success">✓ 字段组meta数据设置成功</div>';
                
                // 创建字段
                if (isset($json_data['fields']) && is_array($json_data['fields'])) {
                    $field_count = 0;
                    foreach ($json_data['fields'] as $field_data) {
                        $field_post_id = wp_insert_post(array(
                            'post_type' => 'acf-field',
                            'post_status' => 'publish',
                            'post_title' => $field_data['label'],
                            'post_name' => $field_data['key'],
                            'post_parent' => $group_post_id,
                            'post_content' => '',
                            'menu_order' => $field_count++,
                        ));
                        
                        if (!is_wp_error($field_post_id)) {
                            // 设置所有字段meta数据
                            foreach ($field_data as $key => $value) {
                                if ($key !== 'label') {
                                    update_post_meta($field_post_id, 'acf_' . $key, $value);
                                    update_post_meta($field_post_id, '_acf_' . $key, $value);
                                }
                            }
                            update_post_meta($field_post_id, 'acf_label', $field_data['label']);
                            update_post_meta($field_post_id, '_acf_label', $field_data['label']);
                            update_post_meta($field_post_id, 'acf_parent', $group_post_id);
                            update_post_meta($field_post_id, '_acf_parent', $group_post_id);
                            
                            echo '<div class="info">✓ 字段创建: ' . $field_data['label'] . '</div>';
                        }
                    }
                    echo '<div class="success">✓ 所有字段创建完成 (共 ' . count($json_data['fields']) . ' 个字段)</div>';
                }
                
                // 清除缓存
                wp_cache_flush();
                if (function_exists('acf_get_store')) {
                    acf_get_store('field-groups')->reset();
                    acf_get_store('fields')->reset();
                }
                
                // 验证
                $registered_group = acf_get_field_group($json_data['key']);
                if ($registered_group) {
                    $registered_fields = acf_get_fields($registered_group);
                    echo '<div class="success">✓ 验证成功！字段组已注册，字段数量: ' . count($registered_fields) . '</div>';
                    echo '<div class="info"><a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">现在可以打开首页设置页面查看</a></div>';
                } else {
                    echo '<div class="error">✗ 验证失败：ACF无法识别字段组</div>';
                }
            }
        }
        
        echo '</div>';
    }
    ?>
    
    <div class="test-section">
        <h2>方法1: 粘贴JSON内容</h2>
        <p>在本地WordPress后台：自定义字段 → 字段组 → 首页设置 → 导出 → 复制JSON内容，然后粘贴到下面：</p>
        <form method="post">
            <textarea name="json_content" placeholder="粘贴JSON内容 here..."><?php
                if (file_exists($json_file)) {
                    echo esc_textarea(file_get_contents($json_file));
                }
            ?></textarea>
            <br>
            <button type="submit" name="create_from_json" value="1">从JSON创建字段组</button>
        </form>
    </div>
    
    <div class="test-section">
        <h2>方法2: 上传JSON文件</h2>
        <p>将JSON文件上传到: <code>wp-content/themes/angola-b2b/acf-json/group_homepage_settings.json</code></p>
        <p>然后刷新此页面，工具会自动检测并显示JSON内容</p>
    </div>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
</body>
</html>

