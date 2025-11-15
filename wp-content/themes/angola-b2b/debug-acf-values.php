<?php
/**
 * Debug ACF Values
 * 调试ACF字段值，检查是否真的保存了数据
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// 设置页面ID
$page_id = 45;

echo "<h1>ACF字段值调试 - 页面ID: {$page_id}</h1>";
echo "<hr>";

// 检查页面是否存在
$page = get_post($page_id);
if (!$page) {
    echo "<p style='color: red;'>错误：页面ID {$page_id} 不存在！</p>";
    exit;
}

echo "<h2>页面信息</h2>";
echo "<p><strong>标题：</strong>" . $page->post_title . "</p>";
echo "<p><strong>状态：</strong>" . $page->post_status . "</p>";
echo "<hr>";

// 要检查的字段列表
$fields_to_check = array(
    'site_logo' => 'Logo',
    'site_title' => '网站标题',
    'hero_title' => 'Hero标题',
    'hero_subtitle' => 'Hero副标题',
    'hero_image' => 'Hero图片',
    'hero_background_image' => 'Hero背景图片',
    'contact_email' => '联系邮箱',
    'contact_phone' => '联系电话',
    'social_facebook' => 'Facebook链接',
    'social_facebook_show' => 'Facebook显示开关',
);

echo "<h2>ACF字段值（使用get_field）</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>字段名</th><th>字段Key</th><th>值</th><th>类型</th></tr>";

foreach ($fields_to_check as $field_name => $field_label) {
    $value = get_field($field_name, $page_id);
    $type = gettype($value);
    
    if (is_array($value)) {
        $display_value = '<pre>' . print_r($value, true) . '</pre>';
    } elseif (is_bool($value)) {
        $display_value = $value ? 'true' : 'false';
    } elseif (is_null($value)) {
        $display_value = '<em style="color: #999;">NULL（未保存或为空）</em>';
    } else {
        $display_value = htmlspecialchars($value);
    }
    
    echo "<tr>";
    echo "<td><strong>{$field_label}</strong><br><code>{$field_name}</code></td>";
    echo "<td><code>field_{$field_name}</code></td>";
    echo "<td>{$display_value}</td>";
    echo "<td>{$type}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

// 检查原始post meta
echo "<h2>原始Post Meta（直接从数据库）</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>Meta Key</th><th>Meta Value</th></tr>";

$all_meta = get_post_meta($page_id);
foreach ($all_meta as $key => $values) {
    // 只显示ACF相关的meta
    if (strpos($key, '_') === 0 || in_array($key, array_keys($fields_to_check))) {
        foreach ($values as $value) {
            $display = is_serialized($value) ? '<pre>' . print_r(maybe_unserialize($value), true) . '</pre>' : htmlspecialchars($value);
            echo "<tr>";
            echo "<td><code>{$key}</code></td>";
            echo "<td>{$display}</td>";
            echo "</tr>";
        }
    }
}

echo "</table>";
echo "<hr>";

echo "<h2>ACF字段组信息</h2>";
if (function_exists('acf_get_field_groups')) {
    $field_groups = acf_get_field_groups(array('post_id' => $page_id));
    if ($field_groups) {
        echo "<p>找到 " . count($field_groups) . " 个字段组：</p>";
        foreach ($field_groups as $group) {
            echo "<h3>{$group['title']} (Key: {$group['key']})</h3>";
            
            // 获取该字段组的所有字段
            $fields = acf_get_fields($group['key']);
            if ($fields) {
                echo "<ul>";
                foreach ($fields as $field) {
                    echo "<li><strong>{$field['label']}</strong> - ";
                    echo "Name: <code>{$field['name']}</code>, ";
                    echo "Type: <code>{$field['type']}</code>";
                    if (isset($field['default_value']) && !empty($field['default_value'])) {
                        echo ", Default: <code>" . htmlspecialchars(print_r($field['default_value'], true)) . "</code>";
                    }
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
    } else {
        echo "<p style='color: orange;'>没有找到应用于此页面的字段组</p>";
    }
} else {
    echo "<p style='color: red;'>ACF函数不可用</p>";
}
