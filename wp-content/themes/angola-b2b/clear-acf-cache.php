<?php
/**
 * Clear ACF Field Group Cache
 * 清除ACF字段组缓存，强制重新加载字段定义
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// 检查权限
if (!current_user_can('manage_options')) {
    die('权限不足');
}

echo "<h1>清除ACF缓存</h1>";
echo "<hr>";

// 1. 删除ACF字段组缓存
if (function_exists('acf_get_field_groups')) {
    $field_groups = acf_get_field_groups();
    
    echo "<h2>步骤1: 删除字段组缓存</h2>";
    foreach ($field_groups as $group) {
        $cache_key = 'group_' . $group['key'];
        wp_cache_delete($cache_key, 'acf');
        echo "<p>✓ 已删除字段组缓存: {$group['title']} ({$group['key']})</p>";
    }
} else {
    echo "<p style='color: red;'>ACF未激活</p>";
}

// 2. 清除所有ACF相关的WordPress对象缓存
echo "<hr>";
echo "<h2>步骤2: 清除WordPress对象缓存</h2>";
wp_cache_flush();
echo "<p>✓ 已清除WordPress对象缓存</p>";

// 3. 删除页面45的post meta缓存
echo "<hr>";
echo "<h2>步骤3: 清除页面45的缓存</h2>";
clean_post_cache(45);
echo "<p>✓ 已清除页面45的缓存</p>";

// 4. 强制ACF重新同步（如果有JSON文件）
echo "<hr>";
echo "<h2>步骤4: ACF同步状态</h2>";
if (function_exists('acf_get_local_field_groups')) {
    $local_groups = acf_get_local_field_groups();
    echo "<p>找到 " . count($local_groups) . " 个本地字段组（从PHP代码注册）</p>";
    
    foreach ($local_groups as $group) {
        echo "<p>- {$group['title']} ({$group['key']})</p>";
    }
}

// 5. 显示当前字段的默认值设置
echo "<hr>";
echo "<h2>步骤5: 验证字段默认值已移除</h2>";
if (function_exists('acf_get_field')) {
    $test_fields = array('field_site_title', 'field_contact_email', 'field_contact_phone');
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>字段Key</th><th>字段名称</th><th>默认值</th><th>占位符</th></tr>";
    
    foreach ($test_fields as $field_key) {
        $field = acf_get_field($field_key);
        if ($field) {
            $default = isset($field['default_value']) ? $field['default_value'] : '<em>未设置</em>';
            $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '<em>未设置</em>';
            
            // 高亮显示问题
            $default_style = (!empty($field['default_value']) && $field['default_value'] !== '' && $field['default_value'] !== 0) ? 
                'background: #ffcccc;' : '';
            
            echo "<tr>";
            echo "<td><code>{$field_key}</code></td>";
            echo "<td>{$field['label']}</td>";
            echo "<td style='{$default_style}'>" . htmlspecialchars($default) . "</td>";
            echo "<td>" . htmlspecialchars($placeholder) . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "<p><small>注：default_value应该为空或未设置（不是0）</small></p>";
}

echo "<hr>";
echo "<h1 style='color: green;'>✓ 缓存清除完成！</h1>";
echo "<p><strong>现在请：</strong></p>";
echo "<ol>";
echo "<li>返回WordPress后台</li>";
echo "<li>编辑「首页设置」页面</li>";
echo "<li>检查字段是否显示正确的已保存值</li>";
echo "<li>如果还是不对，按Ctrl+F5强制刷新浏览器</li>";
echo "</ol>";
