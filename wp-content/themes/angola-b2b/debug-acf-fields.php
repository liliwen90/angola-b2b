<?php
/**
 * ACF字段组诊断工具
 * 直接在WordPress后台运行，检查字段组是否正确注册
 */

// 加载WordPress - 修复路径问题
$wp_load_paths = array(
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',
    '/www/wwwroot/www.unibroint.com/wp-load.php',
    dirname(__FILE__) . '/../../../../wp-load.php',
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
    die('无法找到 wp-load.php 文件。请检查WordPress安装路径。');
}

// 检查用户权限
if (!current_user_can('manage_options')) {
    wp_die('您没有权限访问此页面。');
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACF字段组诊断</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f0f1;
            padding: 40px 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        h1 { color: #1d2327; margin-bottom: 10px; }
        .section {
            margin: 30px 0;
            padding: 20px;
            background: #f6f7f7;
            border-radius: 6px;
            border-left: 4px solid #2271b1;
        }
        .status {
            padding: 12px 16px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .error { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffecb5; }
        .info { background: #cfe2ff; color: #084298; border: 1px solid #b6d4fe; }
        .code {
            background: #1d2327;
            color: #f0f0f1;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
            white-space: pre-wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #2271b1;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 5px 10px 0;
        }
        .btn:hover { background: #135e96; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 ACF字段组诊断工具</h1>
        
        <div class="section">
            <h2>1. 基本信息</h2>
            <?php
            echo '<div class="status info">WordPress版本：' . get_bloginfo('version') . '</div>';
            echo '<div class="status info">主题：' . wp_get_theme()->get('Name') . ' ' . wp_get_theme()->get('Version') . '</div>';
            
            // 检查ACF插件
            if (function_exists('acf_get_setting')) {
                echo '<div class="status success">✓ ACF插件已安装</div>';
                echo '<div class="status info">ACF版本：' . acf_get_setting('version') . '</div>';
            } else {
                echo '<div class="status error">✗ ACF插件未安装或未激活</div>';
            }
            ?>
        </div>

        <div class="section">
            <h2>2. 页面ID 45检查</h2>
            <?php
            $page = get_post(45);
            if ($page && $page->post_type === 'page') {
                echo '<div class="status success">✓ 页面ID 45存在</div>';
                echo '<div class="status info">页面标题：' . esc_html($page->post_title) . '</div>';
                echo '<div class="status info">页面状态：' . esc_html($page->post_status) . '</div>';
                echo '<div class="status info">编辑链接：<a href="' . esc_url(get_edit_post_link(45)) . '" target="_blank">' . esc_url(get_edit_post_link(45)) . '</a></div>';
            } else {
                echo '<div class="status error">✗ 页面ID 45不存在</div>';
            }
            ?>
        </div>

        <div class="section">
            <h2>3. 字段组注册检查</h2>
            <?php
            if (function_exists('acf_get_field_groups')) {
                $field_groups = acf_get_field_groups();
                echo '<div class="status info">已注册的字段组数量：' . count($field_groups) . '</div>';
                
                $found = false;
                foreach ($field_groups as $group) {
                    if ($group['key'] === 'group_homepage_settings') {
                        $found = true;
                        echo '<div class="status success">✓ 找到"首页设置"字段组</div>';
                        echo '<div class="code">';
                        echo 'Key: ' . esc_html($group['key']) . "\n";
                        echo 'Title: ' . esc_html($group['title']) . "\n";
                        echo 'Location: ' . print_r($group['location'], true) . "\n";
                        
                        // 检查字段
                        $fields = acf_get_fields($group['key']);
                        if ($fields) {
                            echo '字段数量：' . count($fields) . "\n";
                            echo "\n字段列表：\n";
                            foreach ($fields as $field) {
                                echo '- ' . esc_html($field['label']) . ' (' . esc_html($field['name']) . ') [' . esc_html($field['type']) . "]\n";
                            }
                        } else {
                            echo '⚠ 字段数量：0（字段为空）' . "\n";
                        }
                        echo '</div>';
                        break;
                    }
                }
                
                if (!$found) {
                    echo '<div class="status error">✗ 未找到"首页设置"字段组</div>';
                    echo '<div class="status info">已注册的字段组：';
                    foreach ($field_groups as $group) {
                        echo '<br>- ' . esc_html($group['title']) . ' (' . esc_html($group['key']) . ')';
                    }
                    echo '</div>';
                }
            } else {
                echo '<div class="status error">✗ ACF函数不可用</div>';
            }
            ?>
        </div>

        <div class="section">
            <h2>4. 字段组位置规则检查</h2>
            <?php
            if (function_exists('acf_get_field_group')) {
                $group = acf_get_field_group('group_homepage_settings');
                if ($group) {
                    echo '<div class="status success">✓ 字段组存在</div>';
                    echo '<div class="code">位置规则：' . print_r($group['location'], true) . '</div>';
                    
                    // 检查位置规则是否匹配当前页面
                    $screen = get_current_screen();
                    if ($screen) {
                        echo '<div class="status info">当前页面类型：' . esc_html($screen->id) . '</div>';
                    }
                    
                    // 手动检查位置规则
                    $page_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
                    if ($page_id === 45) {
                        echo '<div class="status success">✓ 当前正在编辑页面ID 45</div>';
                    } else {
                        echo '<div class="status warning">⚠ 当前页面ID：' . $page_id . '（不是45）</div>';
                    }
                } else {
                    echo '<div class="status error">✗ 无法获取字段组</div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>5. JSON文件检查</h2>
            <?php
            $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
            if (file_exists($json_file)) {
                echo '<div class="status warning">⚠ JSON文件存在</div>';
                $json_content = file_get_contents($json_file);
                $json_data = json_decode($json_content, true);
                if (isset($json_data['fields']) && empty($json_data['fields'])) {
                    echo '<div class="status error">✗ JSON文件中的fields为空数组</div>';
                    echo '<div class="code">' . esc_html($json_content) . '</div>';
                } else {
                    echo '<div class="status info">JSON文件内容：</div>';
                    echo '<div class="code">' . esc_html($json_content) . '</div>';
                }
            } else {
                echo '<div class="status success">✓ JSON文件不存在（将使用PHP代码中的字段）</div>';
            }
            ?>
        </div>

        <div class="section">
            <h2>6. 函数检查</h2>
            <?php
            echo '<div class="status info">angola_b2b_register_homepage_settings_fields函数：' . (function_exists('angola_b2b_register_homepage_settings_fields') ? '✓ 已定义' : '✗ 未定义') . '</div>';
            echo '<div class="status info">acf_add_local_field_group函数：' . (function_exists('acf_add_local_field_group') ? '✓ 可用' : '✗ 不可用') . '</div>';
            ?>
        </div>

        <div class="section">
            <h2>7. 浏览器Console命令</h2>
            <p>在浏览器Console中执行以下命令来检查ACF字段组：</p>
            <div class="code">
// 检查ACF字段组（通过REST API）
fetch('/wp-json/wp/v2/acf/field-group/group_homepage_settings')
    .then(r => r.json())
    .then(data => console.log('字段组数据：', data))
    .catch(e => console.error('错误：', e));

// 检查当前页面的字段组
wp.data.select('core').getEntityRecords('postType', 'acf-field-group', {per_page: -1})
    .then(groups => {
        const homepage = groups.find(g => g.acf_key === 'group_homepage_settings');
        console.log('首页设置字段组：', homepage);
    });

// 检查ACF字段（如果ACF注册了REST端点）
wp.apiFetch({ path: '/wp/v2/acf/field-group/group_homepage_settings' })
    .then(data => console.log('字段组：', data))
    .catch(e => console.error('错误：', e));
            </div>
        </div>

        <div class="section">
            <h2>8. 修复操作</h2>
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="force_register">
                <button type="submit" class="btn">强制重新注册字段组</button>
            </form>
            
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="delete_json">
                <button type="submit" class="btn">删除JSON文件</button>
            </form>
            
            <a href="<?php echo esc_url(admin_url('post.php?post=45&action=edit')); ?>" class="btn" target="_blank">打开首页设置页面</a>
        </div>

        <?php
        // 处理修复操作
        if (isset($_POST['action'])) {
            $action = sanitize_text_field($_POST['action']);
            
            if ($action === 'force_register') {
                // 清除字段组缓存
                if (function_exists('acf_get_store')) {
                    acf_get_store('field-groups')->remove('group_homepage_settings');
                }
                wp_cache_flush();
                echo '<div class="status success">✓ 已清除缓存，请刷新页面</div>';
            }
            
            if ($action === 'delete_json') {
                $json_file = get_template_directory() . '/acf-json/group_homepage_settings.json';
                if (file_exists($json_file)) {
                    @unlink($json_file);
                    echo '<div class="status success">✓ 已删除JSON文件</div>';
                } else {
                    echo '<div class="status info">ℹ JSON文件不存在</div>';
                }
            }
        }
        ?>
    </div>
</body>
</html>

