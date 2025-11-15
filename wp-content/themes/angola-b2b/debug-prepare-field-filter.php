<?php
/**
 * 调试 acf/prepare_field 过滤器
 * 检查过滤器是否被正确执行
 * 
 * 使用方法：将此文件上传到主题目录，在浏览器中访问
 */

// 加载 WordPress
require_once(__DIR__ . '/../../../wp-load.php');

// 必须是管理员
if (!current_user_can('manage_options')) {
    die('需要管理员权限');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>调试 acf/prepare_field 过滤器</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h1 { color: #333; }
        h2 { color: #0073aa; border-bottom: 2px solid #0073aa; padding-bottom: 5px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        pre { background: #f5f5f5; padding: 10px; border-left: 3px solid #0073aa; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #0073aa; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <h1>🔍 调试 acf/prepare_field 过滤器</h1>

<?php

echo "<h2>1. 检查过滤器是否已注册</h2>";

global $wp_filter;

if (isset($wp_filter['acf/prepare_field'])) {
    echo "<p class='success'>✓ acf/prepare_field 过滤器已注册</p>";
    
    echo "<h3>已注册的回调函数：</h3>";
    echo "<table>";
    echo "<tr><th>优先级</th><th>函数名</th></tr>";
    
    foreach ($wp_filter['acf/prepare_field']->callbacks as $priority => $callbacks) {
        foreach ($callbacks as $callback) {
            $function_name = is_array($callback['function']) 
                ? get_class($callback['function'][0]) . '::' . $callback['function'][1]
                : $callback['function'];
            echo "<tr><td>$priority</td><td>$function_name</td></tr>";
        }
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ acf/prepare_field 过滤器未注册</p>";
}

echo "<h2>2. 检查函数是否存在</h2>";

if (function_exists('angola_b2b_force_load_homepage_field_values')) {
    echo "<p class='success'>✓ angola_b2b_force_load_homepage_field_values 函数存在</p>";
} else {
    echo "<p class='error'>✗ angola_b2b_force_load_homepage_field_values 函数不存在</p>";
}

echo "<h2>3. 测试过滤器执行</h2>";

// 模拟首页编辑环境
$_GET['post'] = 45;

// 测试字段数据
$test_fields = array(
    array(
        'key' => 'field_contact_email',
        'name' => 'contact_email',
        'type' => 'email',
        'label' => '联系邮箱',
        'placeholder' => 'info@example.com',
        'value' => '' // 模拟ACF初始化时的空值
    ),
    array(
        'key' => 'field_contact_phone',
        'name' => 'contact_phone',
        'type' => 'text',
        'label' => '联系电话',
        'placeholder' => '+1 234 567 8900',
        'value' => '' // 模拟ACF初始化时的空值
    ),
    array(
        'key' => 'field_hero_background_image',
        'name' => 'hero_background_image',
        'type' => 'image',
        'label' => 'Hero背景图片',
        'value' => '' // 模拟ACF初始化时的空值
    )
);

echo "<table>";
echo "<tr><th>字段名</th><th>过滤前值</th><th>数据库实际值</th><th>过滤后值</th><th>状态</th></tr>";

foreach ($test_fields as $field) {
    $before_value = $field['value'];
    $db_value = get_field($field['name'], 45, false);
    
    // 应用过滤器
    $filtered_field = apply_filters('acf/prepare_field', $field);
    $after_value = $filtered_field['value'];
    
    $status = ($after_value == $db_value) 
        ? "<span class='success'>✓ 正确</span>" 
        : "<span class='error'>✗ 未修正</span>";
    
    echo "<tr>";
    echo "<td>{$field['name']}</td>";
    echo "<td>" . (empty($before_value) ? '<em>(空)</em>' : htmlspecialchars($before_value)) . "</td>";
    echo "<td>" . htmlspecialchars(var_export($db_value, true)) . "</td>";
    echo "<td>" . htmlspecialchars(var_export($after_value, true)) . "</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>4. 检查ACF字段组配置</h2>";

// 获取首页设置字段组
$field_groups = acf_get_field_groups(array('post_id' => 45));

if (!empty($field_groups)) {
    echo "<p class='success'>✓ 找到 " . count($field_groups) . " 个字段组</p>";
    
    foreach ($field_groups as $group) {
        echo "<h3>字段组: {$group['title']}</h3>";
        echo "<pre>";
        echo "Key: {$group['key']}\n";
        echo "Location规则:\n";
        print_r($group['location']);
        echo "</pre>";
        
        // 获取该字段组的字段
        $fields = acf_get_fields($group['key']);
        if ($fields) {
            echo "<p>包含 " . count($fields) . " 个字段</p>";
            echo "<table>";
            echo "<tr><th>字段名</th><th>类型</th><th>当前值</th><th>Placeholder</th></tr>";
            
            foreach (array_slice($fields, 0, 5) as $field) { // 只显示前5个
                $value = get_field($field['name'], 45);
                echo "<tr>";
                echo "<td>{$field['name']}</td>";
                echo "<td>{$field['type']}</td>";
                echo "<td>" . htmlspecialchars(var_export($value, true)) . "</td>";
                echo "<td>" . (isset($field['placeholder']) ? htmlspecialchars($field['placeholder']) : '-') . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
            echo "<p><em>（仅显示前5个字段）</em></p>";
        }
    }
} else {
    echo "<p class='error'>✗ 未找到应用于Post ID 45的字段组</p>";
}

echo "<h2>5. 检查字段值获取方式</h2>";

$test_get_methods = array(
    'get_field' => get_field('contact_email', 45),
    'get_field (raw)' => get_field('contact_email', 45, false),
    'get_post_meta' => get_post_meta(45, 'contact_email', true),
    'acf_get_value' => function_exists('acf_get_value') ? acf_get_value(45, array('name' => 'contact_email')) : 'N/A'
);

echo "<table>";
echo "<tr><th>获取方法</th><th>结果</th></tr>";
foreach ($test_get_methods as $method => $result) {
    echo "<tr>";
    echo "<td>$method</td>";
    echo "<td>" . htmlspecialchars(var_export($result, true)) . "</td>";
    echo "</tr>";
}
echo "</table>";

?>

<h2>6. 建议的解决方案</h2>

<?php
if (isset($wp_filter['acf/prepare_field']) && function_exists('angola_b2b_force_load_homepage_field_values')) {
    echo "<p class='success'>✓ 过滤器配置正确</p>";
    echo "<p>但字段仍显示placeholder，可能的原因：</p>";
    echo "<ol>";
    echo "<li><strong>JavaScript缓存问题：</strong>清空浏览器缓存并强制刷新（Ctrl+Shift+R）</li>";
    echo "<li><strong>ACF字段加载时机：</strong>过滤器执行时机可能在ACF JavaScript渲染之前</li>";
    echo "<li><strong>字段value属性未传递到前端：</strong>需要同时处理 acf/load_value 过滤器</li>";
    echo "</ol>";
    
    echo "<h3>建议使用更底层的解决方案：</h3>";
    echo "<pre>";
    echo htmlspecialchars("// 方案1: 使用 acf/load_value 而不是 acf/prepare_field
add_filter('acf/load_value', 'force_load_homepage_values', 10, 3);
function force_load_homepage_values(\$value, \$post_id, \$field) {
    if (\$post_id == 45 && empty(\$value)) {
        \$db_value = get_post_meta(\$post_id, \$field['name'], true);
        return \$db_value !== '' ? \$db_value : \$value;
    }
    return \$value;
}

// 方案2: 移除所有placeholder定义
// 在字段注册时不设置 'placeholder' 属性");
    echo "</pre>";
} else {
    echo "<p class='error'>✗ 配置有误，请检查 functions.php</p>";
}
?>

<p><a href="<?php echo admin_url('post.php?post=45&action=edit'); ?>">→ 返回编辑首页</a></p>

</body>
</html>
