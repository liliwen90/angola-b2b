<?php
/**
 * 直接创建字段组（绕过ACF注册函数）
 * 使用WordPress数据库API直接创建，避免ACF插件的null值错误
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
    <title>直接创建字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
        button { padding: 10px 20px; background: #2271b1; color: white; border: none; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #135e96; }
    </style>
</head>
<body>
    <h1>直接创建字段组（绕过ACF注册函数）</h1>
    <p>使用WordPress数据库API直接创建，避免ACF插件的null值错误</p>
    
    <?php
    if (!function_exists('acf_get_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    if (isset($_POST['create_direct'])) {
        echo '<div class="test-section">';
        echo '<h2>步骤1: 删除现有字段组</h2>';
        
        global $wpdb;
        
        // 查找现有字段组
        $existing_group_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field-group' AND post_name = %s",
            'group_homepage_settings'
        ));
        
        if ($existing_group_id) {
            // 删除所有字段
            $wpdb->delete(
                $wpdb->posts,
                array('post_parent' => $existing_group_id, 'post_type' => 'acf-field'),
                array('%d', '%s')
            );
            
            // 删除字段组
            wp_delete_post($existing_group_id, true);
            echo '<div class="info">✓ 已删除现有字段组 (ID: ' . $existing_group_id . ')</div>';
        } else {
            echo '<div class="info">✓ 没有现有字段组需要删除</div>';
        }
        
        // 清除缓存
        wp_cache_flush();
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->reset();
            acf_get_store('fields')->reset();
        }
        
        echo '<h2>步骤2: 直接创建字段组（使用WordPress API）</h2>';
        
        // 创建字段组post
        $group_post_id = wp_insert_post(array(
            'post_type' => 'acf-field-group',
            'post_status' => 'publish',
            'post_title' => '首页设置',
            'post_name' => 'group_homepage_settings',
            'post_content' => '',
            'post_excerpt' => '',
        ));
        
        if (is_wp_error($group_post_id)) {
            echo '<div class="error">✗ 创建字段组失败: ' . $group_post_id->get_error_message() . '</div>';
        } else {
            echo '<div class="success">✓ 字段组post创建成功 (ID: ' . $group_post_id . ')</div>';
            
            // 设置字段组的meta数据（ACF 6.x格式）
            // 注意：ACF 6.x使用序列化的数组格式存储meta数据
            update_post_meta($group_post_id, 'acf_key', 'group_homepage_settings');
            update_post_meta($group_post_id, 'acf_title', '首页设置');
            update_post_meta($group_post_id, 'acf_menu_order', 0);
            update_post_meta($group_post_id, 'acf_position', 'normal');
            update_post_meta($group_post_id, 'acf_style', 'seamless');
            update_post_meta($group_post_id, 'acf_label_placement', 'top');
            update_post_meta($group_post_id, 'acf_instruction_placement', 'label');
            update_post_meta($group_post_id, 'acf_hide_on_screen', serialize(array()));
            update_post_meta($group_post_id, 'acf_active', 1);
            update_post_meta($group_post_id, 'acf_description', '');
            update_post_meta($group_post_id, 'acf_show_in_rest', 0);
            
            // 设置location规则（ACF 6.x格式）
            $location = array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '45',
                    ),
                ),
            );
            update_post_meta($group_post_id, 'acf_location', serialize($location));
            
            // 同时设置带下划线的版本（兼容性）
            update_post_meta($group_post_id, '_acf_key', 'group_homepage_settings');
            update_post_meta($group_post_id, '_acf_title', '首页设置');
            update_post_meta($group_post_id, '_acf_menu_order', 0);
            update_post_meta($group_post_id, '_acf_position', 'normal');
            update_post_meta($group_post_id, '_acf_style', 'seamless');
            update_post_meta($group_post_id, '_acf_label_placement', 'top');
            update_post_meta($group_post_id, '_acf_instruction_placement', 'label');
            update_post_meta($group_post_id, '_acf_hide_on_screen', serialize(array()));
            update_post_meta($group_post_id, '_acf_active', 1);
            update_post_meta($group_post_id, '_acf_description', '');
            update_post_meta($group_post_id, '_acf_show_in_rest', 0);
            update_post_meta($group_post_id, '_acf_location', serialize($location));
            
            echo '<div class="success">✓ 字段组meta数据设置成功</div>';
            
            // 创建字段（只创建前3个作为测试）
            $fields = array(
                array(
                    'key' => 'field_tab_site_info',
                    'label' => '站点信息',
                    'name' => '',
                    'type' => 'tab',
                    'placement' => 'left',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_site_logo',
                    'label' => '网站Logo',
                    'name' => 'site_logo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_site_title',
                    'label' => '网站标题',
                    'name' => 'site_title',
                    'type' => 'text',
                ),
            );
            
            $field_order = 0;
            foreach ($fields as $field_data) {
                $field_post_id = wp_insert_post(array(
                    'post_type' => 'acf-field',
                    'post_status' => 'publish',
                    'post_title' => $field_data['label'],
                    'post_name' => $field_data['key'],
                    'post_parent' => $group_post_id,
                    'post_content' => '',
                    'menu_order' => $field_order++,
                ));
                
                if (!is_wp_error($field_post_id)) {
                    // 设置字段的meta数据（ACF 6.x格式）
                    // 基本字段属性
                    update_post_meta($field_post_id, 'acf_key', $field_data['key']);
                    update_post_meta($field_post_id, 'acf_name', $field_data['name']);
                    update_post_meta($field_post_id, 'acf_type', $field_data['type']);
                    update_post_meta($field_post_id, 'acf_label', $field_data['label']);
                    update_post_meta($field_post_id, 'acf_parent', $group_post_id);
                    
                    // 设置其他字段属性
                    foreach ($field_data as $key => $value) {
                        if (!in_array($key, array('label', 'key', 'name', 'type'))) {
                            // 对于数组值，需要序列化
                            if (is_array($value)) {
                                update_post_meta($field_post_id, 'acf_' . $key, serialize($value));
                            } else {
                                update_post_meta($field_post_id, 'acf_' . $key, $value);
                            }
                        }
                    }
                    
                    // 同时设置带下划线的版本（兼容性）
                    update_post_meta($field_post_id, '_acf_key', $field_data['key']);
                    update_post_meta($field_post_id, '_acf_name', $field_data['name']);
                    update_post_meta($field_post_id, '_acf_type', $field_data['type']);
                    update_post_meta($field_post_id, '_acf_label', $field_data['label']);
                    update_post_meta($field_post_id, '_acf_parent', $group_post_id);
                    
                    foreach ($field_data as $key => $value) {
                        if (!in_array($key, array('label', 'key', 'name', 'type'))) {
                            if (is_array($value)) {
                                update_post_meta($field_post_id, '_acf_' . $key, serialize($value));
                            } else {
                                update_post_meta($field_post_id, '_acf_' . $key, $value);
                            }
                        }
                    }
                    
                    echo '<div class="info">✓ 字段创建成功: ' . $field_data['label'] . ' (ID: ' . $field_post_id . ')</div>';
                } else {
                    echo '<div class="error">✗ 字段创建失败: ' . $field_data['label'] . ' - ' . $field_post_id->get_error_message() . '</div>';
                }
            }
            
            // 清除缓存，让ACF重新加载
            wp_cache_flush();
            if (function_exists('acf_get_store')) {
                acf_get_store('field-groups')->reset();
                acf_get_store('fields')->reset();
            }
            
            // 验证
            $registered_group = acf_get_field_group('group_homepage_settings');
            if ($registered_group) {
                $registered_fields = acf_get_fields($registered_group);
                echo '<div class="success">✓ 验证成功！字段组已注册，字段数量: ' . count($registered_fields) . '</div>';
                echo '<div class="info"><a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">现在可以打开首页设置页面查看</a></div>';
            } else {
                echo '<div class="error">✗ 验证失败：ACF无法识别字段组</div>';
            }
        }
        
        echo '</div>';
    }
    ?>
    
    <form method="post">
        <button type="submit" name="create_direct" value="1">直接创建字段组（绕过ACF注册函数）</button>
    </form>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
</body>
</html>

