<?php
/**
 * ACF Field Loading Diagnostic
 * 诊断ACF字段值加载问题
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

if (!current_user_can('manage_options')) {
    die('权限不足');
}

echo "<h1>ACF字段加载诊断</h1>";
echo "<hr>";

$page_id = 45;

echo "<h2>问题诊断：为什么编辑界面显示placeholder而不是实际值？</h2>";

echo "<h3>1. 检查字段定义</h3>";

$test_fields = array('contact_email', 'contact_phone', 'hero_background_image');

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>字段名</th><th>字段Key</th><th>字段类型</th><th>Default Value</th><th>Placeholder</th></tr>";

foreach ($test_fields as $field_name) {
    $field_key = 'field_' . $field_name;
    $field = acf_get_field($field_key);
    
    if ($field) {
        $default = isset($field['default_value']) && $field['default_value'] !== '' ? 
                   htmlspecialchars(print_r($field['default_value'], true)) : 
                   '<em style="color: green;">未设置</em>';
        $placeholder = isset($field['placeholder']) && $field['placeholder'] !== '' ? 
                      htmlspecialchars($field['placeholder']) : 
                      '<em>无</em>';
        
        echo "<tr>";
        echo "<td><code>{$field_name}</code></td>";
        echo "<td><code>{$field_key}</code></td>";
        echo "<td>{$field['type']}</td>";
        echo "<td>{$default}</td>";
        echo "<td>{$placeholder}</td>";
        echo "</tr>";
    } else {
        echo "<tr>";
        echo "<td><code>{$field_name}</code></td>";
        echo "<td colspan='4' style='color: red;'><strong>❌ 字段未注册</strong></td>";
        echo "</tr>";
    }
}

echo "</table>";

echo "<hr>";

echo "<h3>2. 检查字段值和引用</h3>";

echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>字段名</th><th>值 (post_meta)</th><th>引用 (_meta)</th><th>get_field()结果</th><th>状态</th></tr>";

foreach ($test_fields as $field_name) {
    $value = get_post_meta($page_id, $field_name, true);
    $reference = get_post_meta($page_id, '_' . $field_name, true);
    $acf_value = get_field($field_name, $page_id);
    
    $value_display = empty($value) ? '<em style="color: red;">空</em>' : htmlspecialchars($value);
    $ref_display = empty($reference) ? '<em style="color: red;">空</em>' : htmlspecialchars($reference);
    $acf_display = $acf_value === false ? '<em style="color: red;">false</em>' : 
                   (is_array($acf_value) ? 'Array[' . count($acf_value) . ']' : htmlspecialchars($acf_value));
    
    // 判断状态
    if (empty($value)) {
        $status = '<span style="color: red;">❌ 无值</span>';
    } elseif ($reference !== 'field_' . $field_name) {
        $status = '<span style="color: orange;">⚠️ 引用错误</span>';
    } elseif ($acf_value === false || empty($acf_value)) {
        $status = '<span style="color: orange;">⚠️ get_field失败</span>';
    } else {
        $status = '<span style="color: green;">✓ 正常</span>';
    }
    
    echo "<tr>";
    echo "<td><code>{$field_name}</code></td>";
    echo "<td>{$value_display}</td>";
    echo "<td>{$ref_display}</td>";
    echo "<td>{$acf_display}</td>";
    echo "<td>{$status}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";

echo "<h3>3. 模拟ACF加载字段到编辑器的过程</h3>";

echo "<p>ACF在编辑界面加载字段值的流程：</p>";
echo "<ol>";
echo "<li>WordPress加载页面编辑器</li>";
echo "<li>ACF通过 <code>acf/prepare_field</code> 过滤器准备字段</li>";
echo "<li>ACF通过 <code>acf/load_value</code> 过滤器加载值</li>";
echo "<li>如果没有值，使用 <code>default_value</code></li>";
echo "<li>前端JavaScript渲染字段</li>";
echo "</ol>";

echo "<h4>测试 acf/load_value 过滤器</h4>";

foreach ($test_fields as $field_name) {
    $field_key = 'field_' . $field_name;
    $field = acf_get_field($field_key);
    
    if ($field) {
        // 模拟ACF加载值
        $value = acf_get_value($page_id, $field);
        
        $value_display = $value === false ? '<em style="color: red;">false</em>' :
                        (is_null($value) ? '<em style="color: orange;">null</em>' :
                        (is_array($value) ? 'Array[' . count($value) . ']' : htmlspecialchars($value)));
        
        echo "<p><strong>{$field_name}</strong>: acf_get_value() = {$value_display}</p>";
    }
}

echo "<hr>";

echo "<h3>4. 根本原因分析</h3>";

echo "<div style='background: #fff3cd; border: 2px solid #ffc107; padding: 15px; border-radius: 5px;'>";
echo "<h4>🔍 诊断结果</h4>";

$has_issue = false;

foreach ($test_fields as $field_name) {
    $value = get_post_meta($page_id, $field_name, true);
    $reference = get_post_meta($page_id, '_' . $field_name, true);
    $acf_value = get_field($field_name, $page_id);
    
    if (!empty($value) && ($acf_value === false || $acf_value === null || $acf_value === '')) {
        echo "<p style='color: red;'><strong>❌ {$field_name}:</strong> 数据库有值但get_field返回空</p>";
        $has_issue = true;
    }
}

if (!$has_issue) {
    echo "<p style='color: green;'><strong>✓ 数据库和get_field都正常</strong></p>";
    echo "<p><strong>问题可能在于：</strong></p>";
    echo "<ul>";
    echo "<li>ACF编辑器JavaScript未能正确渲染字段值</li>";
    echo "<li>字段定义中可能有导致值被忽略的配置</li>";
    echo "<li>浏览器缓存或ACF缓存问题</li>";
    echo "</ul>";
}

echo "</div>";

echo "<hr>";

echo "<h3>5. 解决方案建议</h3>";

echo "<div style='background: #d1ecf1; border: 2px solid #0c5460; padding: 15px; border-radius: 5px;'>";
echo "<p><strong>需要添加 acf/prepare_field 过滤器来强制加载值：</strong></p>";
echo "<pre style='background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto;'>";
echo htmlspecialchars("add_filter('acf/prepare_field', 'force_load_field_value', 10, 1);
function force_load_field_value(\$field) {
    // 只处理首页设置页面的字段
    if (\$_GET['post'] == 45 || \$_POST['post_ID'] == 45) {
        // 如果字段有值，强制设置到value属性
        if (isset(\$field['name']) && !empty(\$field['name'])) {
            \$value = get_field(\$field['name'], 45);
            if (\$value !== false && \$value !== null && \$value !== '') {
                \$field['value'] = \$value;
            }
        }
    }
    return \$field;
}");
echo "</pre>";
echo "</div>";
