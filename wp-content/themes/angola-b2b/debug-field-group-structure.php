<?php
/**
 * 调试字段组数据结构
 * 输出完整的字段组数据，找出ACF期望但缺失的属性
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
    <title>调试字段组数据结构</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 500px; overflow-y: auto; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
    </style>
</head>
<body>
    <h1>调试字段组数据结构</h1>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 获取字段组数据（不注册）
    require_once(get_template_directory() . '/inc/acf-fields.php');
    
    // 创建一个临时函数来获取字段组数据
    // 我们需要修改注册函数，让它返回数据而不是注册
    
    echo '<div class="test-section">';
    echo '<h2>1. 检查其他成功注册的字段组</h2>';
    
    // 获取所有已注册的字段组
    $all_groups = acf_get_field_groups();
    if (!empty($all_groups)) {
        echo '<div class="info">找到 ' . count($all_groups) . ' 个已注册的字段组</div>';
        
        // 检查第一个字段组的结构
        $sample_group = $all_groups[0];
        echo '<h3>示例字段组结构（' . $sample_group['title'] . '）:</h3>';
        echo '<pre>' . esc_html(print_r($sample_group, true)) . '</pre>';
        
        // 获取该字段组的字段
        $sample_fields = acf_get_fields($sample_group);
        if (!empty($sample_fields)) {
            echo '<h3>示例字段结构（第一个字段）:</h3>';
            echo '<pre>' . esc_html(print_r($sample_fields[0], true)) . '</pre>';
        }
    } else {
        echo '<div class="error">没有找到已注册的字段组</div>';
    }
    
    echo '<h2>2. 构建首页设置字段组数据</h2>';
    
    // 直接复制注册函数中的字段构建逻辑
    $fields = array();
    
    // Tab: 站点信息
    $fields[] = array(
        'key' => 'field_tab_site_info',
        'label' => '站点信息',
        'name' => '',
        'type' => 'tab',
        'placement' => 'left',
        'endpoint' => 0,
    );
    $fields[] = array(
        'key' => 'field_site_logo',
        'label' => '网站Logo',
        'name' => 'site_logo',
        'type' => 'image',
        'return_format' => 'array',
        'preview_size' => 'medium',
        'library' => 'all',
    );
    $fields[] = array(
        'key' => 'field_site_title',
        'label' => '网站标题',
        'name' => 'site_title',
        'type' => 'text',
    );
    
    // 清理字段
    $cleaned_fields = array();
    foreach ($fields as $field) {
        if (!is_array($field)) {
            continue;
        }
        $cleaned_field = array();
        foreach ($field as $key => $value) {
            if ($value !== null) {
                $cleaned_field[$key] = $value;
            }
        }
        if (!isset($cleaned_field['key']) || empty($cleaned_field['key'])) {
            continue;
        }
        if (!isset($cleaned_field['type'])) {
            $cleaned_field['type'] = 'text';
        }
        if (!isset($cleaned_field['label'])) {
            $cleaned_field['label'] = '';
        }
        if (!isset($cleaned_field['name'])) {
            $cleaned_field['name'] = '';
        }
        $cleaned_fields[] = $cleaned_field;
    }
    
    $field_group_data = array(
        'key' => 'group_homepage_settings',
        'title' => '首页设置',
        'fields' => $cleaned_fields,
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
    
    // 清理null值
    if (!function_exists('angola_b2b_clean_array_recursive')) {
        function angola_b2b_clean_array_recursive($array) {
            if (!is_array($array)) {
                return $array;
            }
            $cleaned = array();
            foreach ($array as $key => $value) {
                if ($value !== null) {
                    if (is_array($value)) {
                        $cleaned[$key] = angola_b2b_clean_array_recursive($value);
                    } else {
                        $cleaned[$key] = $value;
                    }
                }
            }
            return $cleaned;
        }
    }
    
    $field_group_data = angola_b2b_clean_array_recursive($field_group_data);
    
    echo '<h3>字段组数据结构:</h3>';
    echo '<pre>' . esc_html(print_r($field_group_data, true)) . '</pre>';
    
    echo '<h3>字段数量: ' . count($field_group_data['fields']) . '</h3>';
    
    echo '<h2>3. 对比差异</h2>';
    
    if (!empty($all_groups) && !empty($sample_fields)) {
        $sample_field = $sample_fields[0];
        $our_field = $cleaned_fields[0];
        
        echo '<h3>示例字段的键:</h3>';
        echo '<pre>' . esc_html(implode(', ', array_keys($sample_field))) . '</pre>';
        
        echo '<h3>我们的字段的键:</h3>';
        echo '<pre>' . esc_html(implode(', ', array_keys($our_field))) . '</pre>';
        
        // 找出缺失的键
        $missing_keys = array_diff(array_keys($sample_field), array_keys($our_field));
        if (!empty($missing_keys)) {
            echo '<div class="error">我们的字段缺少以下键: ' . implode(', ', $missing_keys) . '</div>';
        } else {
            echo '<div class="success">✓ 字段键完整</div>';
        }
        
        // 找出多余的键
        $extra_keys = array_diff(array_keys($our_field), array_keys($sample_field));
        if (!empty($extra_keys)) {
            echo '<div class="info">我们的字段有额外的键: ' . implode(', ', $extra_keys) . '</div>';
        }
    }
    
    echo '</div>';
    ?>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a>
    </div>
</body>
</html>

