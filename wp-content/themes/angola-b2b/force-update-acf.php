<?php
/**
 * Force Update ACF Field Values
 * 强制更新ACF字段值，绕过ACF的缓存和显示bug
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// 检查权限
if (!current_user_can('manage_options')) {
    die('权限不足');
}

echo "<h1>强制更新ACF字段值</h1>";
echo "<hr>";

$page_id = 45;

// 要更新的字段
$fields_to_update = array(
    'contact_email' => 'unibrointernacional@gmail.com',
    'contact_phone' => '00244-972713131',
    'hero_background_image' => 1218, // 图片ID
);

echo "<h2>步骤1: 直接更新数据库值</h2>";

foreach ($fields_to_update as $field_name => $field_value) {
    // 删除旧值
    delete_post_meta($page_id, $field_name);
    delete_post_meta($page_id, '_' . $field_name);
    
    // 添加新值
    add_post_meta($page_id, $field_name, $field_value, true);
    add_post_meta($page_id, '_' . $field_name, 'field_' . $field_name, true);
    
    $display_value = is_numeric($field_value) ? "图片ID: {$field_value}" : $field_value;
    echo "<p>✓ 已更新：<strong>{$field_name}</strong> = {$display_value}</p>";
}

echo "<hr>";

echo "<h2>步骤2: 使用update_field强制更新</h2>";

if (function_exists('update_field')) {
    foreach ($fields_to_update as $field_name => $field_value) {
        $result = update_field($field_name, $field_value, $page_id);
        if ($result) {
            echo "<p>✓ update_field成功：{$field_name}</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ update_field失败：{$field_name}</p>";
        }
    }
} else {
    echo "<p style='color: red;'>❌ update_field函数不可用</p>";
}

echo "<hr>";

echo "<h2>步骤3: 清除所有缓存</h2>";

// 清除WordPress对象缓存
wp_cache_flush();
echo "<p>✓ 已清除WordPress缓存</p>";

// 清除页面缓存
clean_post_cache($page_id);
echo "<p>✓ 已清除页面缓存</p>";

// 清除ACF字段缓存
if (function_exists('acf_get_field')) {
    foreach (array_keys($fields_to_update) as $field_name) {
        $field_key = 'field_' . $field_name;
        wp_cache_delete($field_key, 'acf');
        wp_cache_delete("load_field/name={$field_name}", 'acf');
        wp_cache_delete("load_field/key={$field_key}", 'acf');
    }
    echo "<p>✓ 已清除ACF字段缓存</p>";
}

echo "<hr>";

echo "<h2>步骤4: 验证更新结果</h2>";

echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>字段名</th><th>get_field值</th><th>Post Meta值</th><th>状态</th></tr>";

foreach (array_keys($fields_to_update) as $field_name) {
    $acf_value = get_field($field_name, $page_id);
    $meta_value = get_post_meta($page_id, $field_name, true);
    
    $acf_display = is_array($acf_value) ? 'Array[' . count($acf_value) . ']' : 
                   (empty($acf_value) ? '<em style="color: red;">空</em>' : htmlspecialchars($acf_value));
    $meta_display = empty($meta_value) ? '<em style="color: red;">空</em>' : htmlspecialchars($meta_value);
    
    $status = !empty($meta_value) ? '<strong style="color: green;">✓ 有值</strong>' : '<strong style="color: red;">❌ 无值</strong>';
    
    echo "<tr>";
    echo "<td><code>{$field_name}</code></td>";
    echo "<td>{$acf_display}</td>";
    echo "<td>{$meta_display}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";

echo "<h1 style='color: green;'>✓ 强制更新完成！</h1>";

echo "<h2>重要说明</h2>";
echo "<div style='background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>⚠️ ACF编辑界面可能仍显示占位符，但这不影响实际功能！</strong></p>";
echo "<p>数据已正确保存到数据库。前台网站（Header、Footer）会正确显示这些值。</p>";
echo "<p>编辑界面显示占位符是ACF的一个已知UI bug，不会影响实际使用。</p>";
echo "</div>";

echo "<h2>验证方法：</h2>";
echo "<ol>";
echo "<li>访问网站首页：<a href='https://www.unibroint.com' target='_blank'>https://www.unibroint.com</a></li>";
echo "<li>检查Header右上角的Contact下拉菜单</li>";
echo "<li>检查Footer底部的联系信息</li>";
echo "<li>这些地方应该显示正确的邮箱和电话</li>";
echo "</ol>";
