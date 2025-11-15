<?php
/**
 * 强制清除并注册首页设置字段组
 * 完全清除所有ACF缓存和字段组，然后重新注册
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
    <title>强制清除并注册</title>
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
    <h1>强制清除并注册首页设置字段组</h1>
    <p>完全清除所有ACF缓存和字段组，然后重新注册</p>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    if (isset($_POST['force_register'])) {
        echo '<div class="test-section">';
        echo '<h2>步骤1: 完全清除ACF缓存和字段组</h2>';
        
        // 1. 清除ACF存储
        if (function_exists('acf_get_store')) {
            $store = acf_get_store('field-groups');
            if ($store) {
                $store->remove('group_homepage_settings');
                echo '<div class="info">✓ 已从ACF存储中移除字段组</div>';
            }
            
            $fields_store = acf_get_store('fields');
            if ($fields_store) {
                $fields_store->reset();
                echo '<div class="info">✓ 已重置字段存储</div>';
            }
        }
        
        // 2. 清除WordPress缓存
        wp_cache_flush();
        echo '<div class="info">✓ 已清除WordPress缓存</div>';
        
        // 3. 删除JSON文件（如果存在）
        $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
        if (file_exists($json_file)) {
            @unlink($json_file);
            echo '<div class="info">✓ 已删除JSON文件</div>';
        }
        
        // 4. 从数据库删除字段组（如果存在）
        global $wpdb;
        $deleted = $wpdb->delete(
            $wpdb->posts,
            array(
                'post_type' => 'acf-field-group',
                'post_name' => 'group_homepage_settings'
            ),
            array('%s', '%s')
        );
        if ($deleted) {
            echo '<div class="info">✓ 已从数据库删除字段组 (删除了 ' . $deleted . ' 条记录)</div>';
        }
        
        // 5. 删除所有相关字段
        $deleted_fields = $wpdb->delete(
            $wpdb->posts,
            array(
                'post_type' => 'acf-field',
                'post_parent' => (int) $wpdb->get_var($wpdb->prepare(
                    "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field-group' AND post_name = %s",
                    'group_homepage_settings'
                ))
            ),
            array('%s')
        );
        if ($deleted_fields) {
            echo '<div class="info">✓ 已删除相关字段 (删除了 ' . $deleted_fields . ' 条记录)</div>';
        }
        
        // 6. 再次清除缓存
        wp_cache_flush();
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->reset();
            acf_get_store('fields')->reset();
        }
        
        echo '<div class="success">✓ 清除完成！</div>';
        
        echo '<h2>步骤2: 注册字段组（使用最简化的方法）</h2>';
        
        // 只注册前3个字段（最基础的）
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
        
        $field_group_data = array(
            'key' => 'group_homepage_settings',
            'title' => '首页设置',
            'fields' => $fields,
            'location' => array(
                array(
                    array(
                        'param' => 'page',
                        'operator' => '==',
                        'value' => '45',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),
            'active' => true,
            'description' => '',
            'show_in_rest' => false,
        );
        
        echo '<div class="info">字段数量: ' . count($fields) . '</div>';
        
        // 捕获所有输出和错误
        ob_start();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $result = acf_add_local_field_group($field_group_data);
        
        $output = ob_get_clean();
        
        if ($output) {
            echo '<div class="error">输出捕获:</div>';
            echo '<pre>' . esc_html($output) . '</pre>';
        }
        
        if ($result) {
            echo '<div class="success">✓ 字段组注册成功！</div>';
            
            // 验证
            $registered_group = acf_get_field_group('group_homepage_settings');
            if ($registered_group) {
                $registered_fields = acf_get_fields($registered_group);
                echo '<div class="success">✓ 验证成功！已注册字段数量: ' . count($registered_fields) . '</div>';
                echo '<div class="info"><a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">现在可以打开首页设置页面查看</a></div>';
            } else {
                echo '<div class="error">✗ 验证失败：无法获取已注册的字段组</div>';
            }
        } else {
            echo '<div class="error">✗ 字段组注册失败</div>';
            
            // 检查ACF版本
            if (defined('ACF_VERSION')) {
                echo '<div class="info">ACF版本: ' . ACF_VERSION . '</div>';
            }
            
            // 检查PHP版本
            echo '<div class="info">PHP版本: ' . PHP_VERSION . '</div>';
            
            // 检查是否有其他错误
            $last_error = error_get_last();
            if ($last_error) {
                echo '<div class="error">最后错误:</div>';
                echo '<pre>' . esc_html(print_r($last_error, true)) . '</pre>';
            }
        }
        
        echo '</div>';
    }
    ?>
    
    <form method="post">
        <button type="submit" name="force_register" value="1">强制清除并注册（最简化版本）</button>
    </form>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
    
    <div class="test-section" style="margin-top: 30px;">
        <h3>调试信息</h3>
        <?php
        echo '<div class="info">ACF版本: ' . (defined('ACF_VERSION') ? ACF_VERSION : '未知') . '</div>';
        echo '<div class="info">PHP版本: ' . PHP_VERSION . '</div>';
        echo '<div class="info">WordPress版本: ' . get_bloginfo('version') . '</div>';
        
        // 检查字段组是否存在
        $existing_group = acf_get_field_group('group_homepage_settings');
        if ($existing_group) {
            echo '<div class="error">字段组已存在（可能已损坏）</div>';
            echo '<pre>' . esc_html(print_r($existing_group, true)) . '</pre>';
        } else {
            echo '<div class="success">字段组不存在（可以注册）</div>';
        }
        ?>
    </div>
</body>
</html>

