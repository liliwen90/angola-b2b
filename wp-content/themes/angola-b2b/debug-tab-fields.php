<?php
/**
 * 诊断Tab字段问题
 * 检查已注册字段组中Tab字段的实际数据
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
    <title>诊断Tab字段</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; max-height: 500px; overflow-y: auto; }
    </style>
</head>
<body>
    <h1>诊断Tab字段问题</h1>
    
    <?php
    if (!function_exists('acf_get_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 1. 检查首页设置字段组
    echo '<h2>1. 检查首页设置字段组</h2>';
    $group = acf_get_field_group('group_homepage_settings');
    if ($group) {
        echo '<div class="success">✓ 字段组已注册</div>';
        echo '<pre>' . esc_html(print_r($group, true)) . '</pre>';
        
        // 2. 获取所有字段
        echo '<h2>2. 获取所有字段</h2>';
        $fields = acf_get_fields($group);
        if (!empty($fields)) {
            echo '<div class="success">✓ 字段数量: ' . count($fields) . '</div>';
            
            // 3. 检查Tab字段
            echo '<h2>3. 检查Tab字段</h2>';
            $tab_fields = array();
            foreach ($fields as $index => $field) {
                if ($field['type'] === 'tab') {
                    $tab_fields[] = array('index' => $index, 'field' => $field);
                }
            }
            
            if (!empty($tab_fields)) {
                echo '<div class="success">✓ 找到 ' . count($tab_fields) . ' 个Tab字段</div>';
                foreach ($tab_fields as $tab_info) {
                    echo '<h3>Tab字段 #' . $tab_info['index'] . ':</h3>';
                    echo '<pre>' . esc_html(print_r($tab_info['field'], true)) . '</pre>';
                    
                    // 检查关键属性
                    echo '<div class="info">';
                    echo 'key: ' . (isset($tab_info['field']['key']) ? esc_html($tab_info['field']['key']) : '❌ 缺失') . '<br>';
                    echo 'label: ' . (isset($tab_info['field']['label']) ? '"' . esc_html($tab_info['field']['label']) . '"' : '❌ 缺失') . '<br>';
                    echo 'label是否为空: ' . (empty($tab_info['field']['label']) ? '❌ 是' : '✓ 否') . '<br>';
                    echo 'name: ' . (isset($tab_info['field']['name']) ? '"' . esc_html($tab_info['field']['name']) . '"' : '❌ 缺失') . '<br>';
                    echo 'type: ' . (isset($tab_info['field']['type']) ? esc_html($tab_info['field']['type']) : '❌ 缺失') . '<br>';
                    echo 'placement: ' . (isset($tab_info['field']['placement']) ? esc_html($tab_info['field']['placement']) : '❌ 缺失') . '<br>';
                    echo '</div>';
                }
            } else {
                echo '<div class="error">✗ 没有找到Tab字段</div>';
            }
        } else {
            echo '<div class="error">✗ 字段组没有字段</div>';
        }
    } else {
        echo '<div class="error">✗ 字段组未注册</div>';
    }
    
    // 4. 对比成功的字段组
    echo '<h2>4. 对比成功的字段组（产品字段）</h2>';
    $product_group = acf_get_field_group('group_product_simple_multilingual_v2');
    if ($product_group) {
        echo '<div class="success">✓ 找到产品字段组</div>';
        $product_fields = acf_get_fields($product_group);
        $product_tabs = array();
        foreach ($product_fields as $field) {
            if ($field['type'] === 'tab') {
                $product_tabs[] = $field;
            }
        }
        if (!empty($product_tabs)) {
            echo '<div class="success">✓ 找到 ' . count($product_tabs) . ' 个Tab字段（产品字段组）</div>';
            echo '<h3>第一个Tab字段（产品字段组）:</h3>';
            echo '<pre>' . esc_html(print_r($product_tabs[0], true)) . '</pre>';
            
            // 对比关键属性
            echo '<h3>属性对比:</h3>';
            echo '<div class="info">';
            echo 'key: ' . (isset($product_tabs[0]['key']) ? esc_html($product_tabs[0]['key']) : '❌ 缺失') . '<br>';
            echo 'label: ' . (isset($product_tabs[0]['label']) ? '"' . esc_html($product_tabs[0]['label']) . '"' : '❌ 缺失') . '<br>';
            echo 'name: ' . (isset($product_tabs[0]['name']) ? '"' . esc_html($product_tabs[0]['name']) . '"' : '❌ 缺失') . '<br>';
            echo 'type: ' . (isset($product_tabs[0]['type']) ? esc_html($product_tabs[0]['type']) : '❌ 缺失') . '<br>';
            echo 'placement: ' . (isset($product_tabs[0]['placement']) ? esc_html($product_tabs[0]['placement']) : '❌ 缺失') . '<br>';
            echo '</div>';
        }
    }
    ?>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a>
    </div>
</body>
</html>

