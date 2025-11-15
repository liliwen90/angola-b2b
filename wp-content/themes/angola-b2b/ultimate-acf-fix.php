<?php
/**
 * 终极ACF字段值修复方案
 * 彻底解决placeholder显示问题
 * 
 * 使用方法：上传到主题目录并访问此文件
 */

// 加载 WordPress
require_once(__DIR__ . '/../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('需要管理员权限');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>终极ACF字段修复</title>
    <style>
        body { font-family: 'Microsoft YaHei', Arial, sans-serif; margin: 20px; line-height: 1.6; background: #f0f0f0; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #0073aa; padding-bottom: 10px; }
        h2 { color: #0073aa; margin-top: 30px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { background: #e7f5fe; border-left: 4px solid #00a0d2; padding: 15px; margin: 20px 0; }
        pre { background: #f5f5f5; padding: 15px; border-left: 3px solid #0073aa; overflow-x: auto; border-radius: 4px; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #0073aa; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 4px; margin: 10px 5px; }
        .btn:hover { background: #005177; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 终极ACF字段值修复方案</h1>

<?php

echo "<h2>诊断步骤 1: 检查字段值存储</h2>";

$test_fields = array('contact_email', 'contact_phone', 'hero_background_image');
$post_id = 45;

echo "<table>";
echo "<tr><th>字段名</th><th>post_meta直接读取</th><th>get_field()读取</th><th>状态</th></tr>";

foreach ($test_fields as $field_name) {
    $meta_value = get_post_meta($post_id, $field_name, true);
    $field_value = get_field($field_name, $post_id);
    
    $has_value = !empty($meta_value) || !empty($field_value);
    $status = $has_value ? "<span class='success'>✓ 有值</span>" : "<span class='error'>✗ 无值</span>";
    
    echo "<tr>";
    echo "<td><code>$field_name</code></td>";
    echo "<td>" . htmlspecialchars(var_export($meta_value, true)) . "</td>";
    echo "<td>" . htmlspecialchars(is_array($field_value) ? 'Array[' . count($field_value) . ']' : var_export($field_value, true)) . "</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>诊断步骤 2: 检查字段定义</h2>";

$field_group = acf_get_field_group('group_homepage_settings');

if ($field_group) {
    echo "<p class='success'>✓ 找到首页设置字段组</p>";
    
    $fields = acf_get_fields($field_group);
    
    if ($fields) {
        echo "<p>字段组包含 <strong>" . count($fields) . "</strong> 个字段</p>";
        
        echo "<h3>关键字段配置检查：</h3>";
        echo "<table>";
        echo "<tr><th>字段Key</th><th>字段Name</th><th>类型</th><th>Default Value</th><th>Placeholder</th></tr>";
        
        foreach ($fields as $field) {
            if (in_array($field['name'], $test_fields)) {
                $default = isset($field['default_value']) && $field['default_value'] !== '' ? '✗ ' . $field['default_value'] : '✓ 未设置';
                $placeholder = isset($field['placeholder']) && $field['placeholder'] !== '' ? $field['placeholder'] : '-';
                
                echo "<tr>";
                echo "<td><code>{$field['key']}</code></td>";
                echo "<td><code>{$field['name']}</code></td>";
                echo "<td>{$field['type']}</td>";
                echo "<td>$default</td>";
                echo "<td>$placeholder</td>";
                echo "</tr>";
            }
        }
        
        echo "</table>";
        
        // 检查是否有default_value
        $has_default_value = false;
        foreach ($fields as $field) {
            if (isset($field['default_value']) && $field['default_value'] !== '') {
                $has_default_value = true;
                break;
            }
        }
        
        if ($has_default_value) {
            echo "<div class='info'>";
            echo "<strong>⚠️ 发现问题：</strong>某些字段设置了 <code>default_value</code>，这可能导致ACF优先使用默认值而不是数据库值！";
            echo "</div>";
        }
    }
} else {
    echo "<p class='error'>✗ 未找到首页设置字段组！</p>";
}

echo "<h2>诊断步骤 3: 根本原因分析</h2>";

echo "<div class='info'>";
echo "<h3>🔍 ACF字段值加载优先级：</h3>";
echo "<ol>";
echo "<li><strong>default_value</strong> (字段定义中的默认值) - 最高优先级</li>";
echo "<li><strong>数据库中的值</strong> (post_meta)</li>";
echo "<li><strong>placeholder</strong> (仅用于显示提示)</li>";
echo "</ol>";
echo "<p><strong>问题原因：</strong>如果字段定义中设置了 <code>default_value</code>，ACF会优先使用它，即使数据库中有实际保存的值！</p>";
echo "</div>";

echo "<h2>🔧 修复方案</h2>";

echo "<h3>方案1: 移除字段定义中的 default_value（推荐）</h3>";
echo "<pre>";
echo htmlspecialchars("// 在 acf-fields.php 中，确保所有字段定义中都不包含 default_value
\$fields[] = array(
    'key' => 'field_contact_email',
    'name' => 'contact_email',
    'type' => 'email',
    'label' => '联系邮箱',
    'placeholder' => 'info@example.com', // 只保留placeholder
    // 'default_value' => '',  // ❌ 必须删除这一行
);");
echo "</pre>";

echo "<h3>方案2: 使用 acf/load_value 过滤器强制返回数据库值</h3>";
echo "<p>已在 <code>functions.php</code> 中添加此过滤器</p>";

echo "<h3>方案3: 使用 JavaScript 强制加载值（最后手段）</h3>";
echo "<pre>";
echo htmlspecialchars("add_action('acf/input/admin_footer', function() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // 强制加载数据库中的值
        <?php
        \$post_id = isset(\$_GET['post']) ? intval(\$_GET['post']) : 0;
        if (\$post_id == 45) {
            \$fields = array('contact_email', 'contact_phone', 'hero_background_image');
            foreach (\$fields as \$field_name) {
                \$value = get_field(\$field_name, \$post_id, false);
                if (\$value) {
                    echo \"$('[name=\\\"acf[field_{$field_name}]\\\"]').val('\" . esc_js(\$value) . \"');\n\";
                }
            }
        }
        ?>
    });
    </script>
    <?php
});");
echo "</pre>";

echo "<h2>📋 立即执行修复</h2>";

// 检查是否有POST请求
if (isset($_POST['fix_now'])) {
    echo "<div class='info'>";
    echo "<h3>正在执行修复...</h3>";
    
    // 清除ACF缓存
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
        echo "<p>✓ 清除字段组缓存</p>";
    }
    
    wp_cache_flush();
    echo "<p>✓ 清除WordPress缓存</p>";
    
    // 验证数据
    $all_ok = true;
    foreach ($test_fields as $field_name) {
        $value = get_field($field_name, $post_id);
        if (empty($value)) {
            echo "<p class='error'>✗ 字段 <code>$field_name</code> 仍然为空</p>";
            $all_ok = false;
        } else {
            echo "<p class='success'>✓ 字段 <code>$field_name</code> 有值</p>";
        }
    }
    
    if ($all_ok) {
        echo "<p class='success'><strong>✓ 修复成功！现在刷新编辑页面查看效果。</strong></p>";
    } else {
        echo "<p class='warning'><strong>⚠️ 部分字段仍有问题，可能需要手动检查 acf-fields.php</strong></p>";
    }
    
    echo "</div>";
}

?>

<form method="post" style="margin: 20px 0;">
    <input type="hidden" name="fix_now" value="1">
    <button type="submit" class="btn">🔧 清除缓存并重新加载</button>
</form>

<h2>🧪 下一步测试</h2>
<ol>
    <li>点击上面的"清除缓存并重新加载"按钮</li>
    <li>访问 <a href="<?php echo admin_url('post.php?post=45&action=edit'); ?>" class="btn">编辑首页</a></li>
    <li>检查"联系信息"标签页中的字段是否显示实际值</li>
    <li>尝试修改一个字段并保存，验证不会覆盖其他字段</li>
</ol>

<div class="info">
<h3>💡 如果问题仍然存在</h3>
<p>请上传 <code>debug-prepare-field-filter.php</code> 并访问该文件查看详细诊断信息。</p>
</div>

</div>
</body>
</html>
