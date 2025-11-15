<?php
/**
 * Sync ACF Data - 强制同步ACF字段数据
 * 解决字段值显示为placeholder而不是实际值的问题
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// 检查权限
if (!current_user_can('manage_options')) {
    die('权限不足');
}

echo "<h1>ACF数据同步工具</h1>";
echo "<hr>";

$page_id = 45;

echo "<h2>步骤1: 检查现有数据</h2>";

// 检查当前字段值
$fields_to_check = array(
    'hero_background_image' => 'Hero背景图片',
    'contact_email' => '联系邮箱',
    'contact_phone' => '联系电话',
    'site_title' => '网站标题',
    'hero_title' => 'Hero标题',
);

echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>字段名</th><th>当前值(get_field)</th><th>Post Meta值</th><th>状态</th></tr>";

foreach ($fields_to_check as $field_name => $field_label) {
    $acf_value = get_field($field_name, $page_id);
    $meta_value = get_post_meta($page_id, $field_name, true);
    
    $acf_display = is_array($acf_value) ? 'Array[' . count($acf_value) . ']' : 
                   (empty($acf_value) ? '<em style="color: red;">空</em>' : htmlspecialchars($acf_value));
    $meta_display = is_array($meta_value) ? 'Array' : 
                    (empty($meta_value) ? '<em style="color: red;">空</em>' : htmlspecialchars($meta_value));
    
    $status = ($acf_value === false && !empty($meta_value)) ? '<strong style="color: orange;">⚠️ 不匹配</strong>' : '✓';
    
    echo "<tr>";
    echo "<td><strong>{$field_label}</strong><br><code>{$field_name}</code></td>";
    echo "<td>{$acf_display}</td>";
    echo "<td>{$meta_display}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

echo "<h2>步骤2: 强制刷新ACF字段值</h2>";

// 强制重新加载字段值
if (function_exists('acf_get_field')) {
    foreach ($fields_to_check as $field_name => $field_label) {
        // 获取字段定义
        $field_key = 'field_' . $field_name;
        $field = acf_get_field($field_key);
        
        if ($field) {
            // 删除ACF缓存
            wp_cache_delete($field_key, 'acf');
            wp_cache_delete("load_field/name={$field_name}", 'acf');
            wp_cache_delete("load_field/key={$field_key}", 'acf');
            
            echo "<p>✓ 已刷新字段：{$field_label} ({$field_name})</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ 未找到字段定义：{$field_label} ({$field_key})</p>";
        }
    }
}

echo "<hr>";

echo "<h2>步骤3: 检查字段引用（Reference）</h2>";

// 检查 _字段名 的引用是否正确
echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
echo "<tr><th>字段名</th><th>引用值 (_meta)</th><th>状态</th></tr>";

foreach ($fields_to_check as $field_name => $field_label) {
    $reference = get_post_meta($page_id, '_' . $field_name, true);
    $expected_reference = 'field_' . $field_name;
    
    $status = ($reference === $expected_reference) ? '✓ 正确' : '<strong style="color: red;">❌ 错误</strong>';
    $reference_display = empty($reference) ? '<em>不存在</em>' : htmlspecialchars($reference);
    
    echo "<tr>";
    echo "<td><strong>{$field_label}</strong><br><code>_{$field_name}</code></td>";
    echo "<td>{$reference_display}<br><small>应该是: {$expected_reference}</small></td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";
echo "<hr>";

echo "<h2>步骤4: 修复引用</h2>";

$fixed_count = 0;
foreach ($fields_to_check as $field_name => $field_label) {
    $reference = get_post_meta($page_id, '_' . $field_name, true);
    $expected_reference = 'field_' . $field_name;
    
    if ($reference !== $expected_reference) {
        update_post_meta($page_id, '_' . $field_name, $expected_reference);
        echo "<p style='color: green;'>✓ 已修复引用：{$field_label} (_{$field_name})</p>";
        $fixed_count++;
    }
}

if ($fixed_count === 0) {
    echo "<p>所有引用都是正确的，无需修复。</p>";
} else {
    echo "<p><strong>已修复 {$fixed_count} 个字段引用</strong></p>";
}

echo "<hr>";

// 清除所有缓存
wp_cache_flush();
clean_post_cache($page_id);

echo "<h1 style='color: green;'>✓ 同步完成！</h1>";
echo "<p><strong>现在请：</strong></p>";
echo "<ol>";
echo "<li>返回WordPress后台</li>";
echo "<li>刷新「首页设置」页面（按F5）</li>";
echo "<li>检查字段是否显示正确的值</li>";
echo "</ol>";
