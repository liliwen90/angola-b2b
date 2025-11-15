<?php
/**
 * 修复Tab字段渲染问题
 * 直接检查并修复ACF字段组中Tab字段的数据
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
    <title>修复Tab字段渲染</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body>
    <h1>修复Tab字段渲染问题</h1>
    
    <?php
    if (!function_exists('acf_get_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 1. 获取字段组
    $group = acf_get_field_group('group_homepage_settings');
    if (!$group) {
        echo '<div class="error">字段组未注册</div>';
        exit;
    }
    
    echo '<h2>步骤1: 检查当前字段组</h2>';
    echo '<div class="success">✓ 字段组已找到</div>';
    
    // 2. 获取所有字段
    $fields = acf_get_fields($group);
    if (empty($fields)) {
        echo '<div class="error">字段组没有字段</div>';
        exit;
    }
    
    echo '<h2>步骤2: 检查Tab字段</h2>';
    $tab_fields_found = 0;
    $tab_fields_fixed = 0;
    
    foreach ($fields as $index => &$field) {
        if ($field['type'] === 'tab') {
            $tab_fields_found++;
            echo '<div class="info">检查Tab字段 #' . $tab_fields_found . ': ' . esc_html($field['key']) . '</div>';
            
            // 检查label
            $has_label = isset($field['label']) && !empty($field['label']);
            echo '  - label存在: ' . ($has_label ? '✓' : '✗') . '<br>';
            if ($has_label) {
                echo '  - label值: "' . esc_html($field['label']) . '"<br>';
            }
            
            // 检查其他关键属性
            $needs_fix = false;
            if (!$has_label) {
                $needs_fix = true;
                echo '  <div class="error">  ✗ label缺失或为空，需要修复</div>';
            }
            
            // 确保所有必需属性存在
            if (!isset($field['placement'])) {
                $field['placement'] = 'left';
                $needs_fix = true;
                echo '  <div class="info">  + 添加placement: left</div>';
            }
            if (!isset($field['endpoint'])) {
                $field['endpoint'] = 0;
                $needs_fix = true;
                echo '  <div class="info">  + 添加endpoint: 0</div>';
            }
            
            // 如果label缺失，尝试从key推断
            if (!$has_label) {
                $key_parts = explode('_', $field['key']);
                $last_part = end($key_parts);
                $label_map = array(
                    'site_info' => '站点信息',
                    'hero' => 'Hero区域',
                    'contact' => '联系信息',
                    'social' => '社交媒体',
                    'about' => '关于我们',
                    'services' => '我们的服务',
                    'careers' => '招聘',
                );
                if (isset($label_map[$last_part])) {
                    $field['label'] = $label_map[$last_part];
                    echo '  <div class="success">  ✓ 从key推断label: "' . esc_html($field['label']) . '"</div>';
                    $needs_fix = true;
                }
            }
            
            if ($needs_fix) {
                $tab_fields_fixed++;
            }
        }
    }
    unset($field); // 解除引用
    
    echo '<div class="info">找到 ' . $tab_fields_found . ' 个Tab字段，需要修复 ' . $tab_fields_fixed . ' 个</div>';
    
    // 3. 如果字段被修改，需要重新注册字段组
    if ($tab_fields_fixed > 0) {
        echo '<h2>步骤3: 重新注册字段组</h2>';
        
        // 清除缓存
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->remove('group_homepage_settings');
            acf_get_store('fields')->reset();
        }
        wp_cache_flush();
        echo '<div class="success">✓ 缓存已清除</div>';
        
        // 更新字段组数据
        $group['fields'] = $fields;
        
        // 尝试直接更新字段组
        // 注意：acf_update_field_group需要字段组有ID，但我们的字段组是PHP注册的
        // 所以我们需要重新注册
        require_once(get_template_directory() . '/inc/acf-fields.php');
        
        // 再次清除缓存
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->remove('group_homepage_settings');
            acf_get_store('fields')->reset();
        }
        wp_cache_flush();
        
        // 重新注册
        $result = angola_b2b_register_homepage_settings_fields();
        
        if ($result) {
            echo '<div class="success">✓ 字段组重新注册成功</div>';
        } else {
            echo '<div class="error">✗ 字段组重新注册失败</div>';
        }
        
        // 验证
        $updated_group = acf_get_field_group('group_homepage_settings');
        if ($updated_group) {
            $updated_fields = acf_get_fields($updated_group);
            $updated_tabs = 0;
            $updated_tabs_with_label = 0;
            foreach ($updated_fields as $f) {
                if ($f['type'] === 'tab') {
                    $updated_tabs++;
                    if (isset($f['label']) && !empty($f['label'])) {
                        $updated_tabs_with_label++;
                    }
                }
            }
            echo '<div class="info">验证结果: ' . $updated_tabs_with_label . ' / ' . $updated_tabs . ' 个Tab字段有label</div>';
        }
    } else {
        echo '<h2>步骤3: 无需修复</h2>';
        echo '<div class="success">✓ 所有Tab字段的label都存在</div>';
        echo '<div class="info">如果页面仍然不显示Tab标签，可能是ACF渲染问题或浏览器缓存问题</div>';
    }
    
    // 4. 检查是否有ACF过滤器影响Tab字段
    echo '<h2>步骤4: 检查ACF过滤器</h2>';
    global $wp_filter;
    $acf_filters = array();
    if (isset($wp_filter['acf/load_field'])) {
        echo '<div class="info">发现 acf/load_field 过滤器，可能影响字段加载</div>';
    }
    if (isset($wp_filter['acf/load_field_group'])) {
        echo '<div class="info">发现 acf/load_field_group 过滤器，可能影响字段组加载</div>';
    }
    ?>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a>
        <br><br>
        <div class="info">
            <strong>如果Tab标签仍然不显示，请尝试：</strong><br>
            1. 清除浏览器缓存（Ctrl+Shift+Delete）<br>
            2. 硬刷新页面（Ctrl+F5）<br>
            3. 检查浏览器控制台是否有JavaScript错误<br>
            4. 检查是否有其他插件或主题代码影响了ACF的渲染
        </div>
    </div>
</body>
</html>

