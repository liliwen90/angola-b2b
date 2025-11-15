<?php
/**
 * 首页设置页面诊断和修复工具
 * 
 * 使用方法：将此文件放在WordPress根目录，然后访问：
 * https://your-domain.com/wp-content/themes/angola-b2b/fix-homepage-settings.php
 * 
 * 修复完成后请删除此文件！
 */

// 加载WordPress - 修复路径问题（兼容open_basedir限制）
// 方法1：尝试从主题目录向上查找WordPress根目录
$wp_root = dirname(dirname(dirname(dirname(__FILE__))));
$wp_load_path = $wp_root . '/wp-load.php';

// 方法2：如果方法1失败，尝试使用realpath
if (!file_exists($wp_load_path)) {
    $wp_root = realpath(dirname(dirname(dirname(dirname(__FILE__)))));
    if ($wp_root) {
        $wp_load_path = $wp_root . '/wp-load.php';
    }
}

// 方法3：如果还是失败，尝试直接使用绝对路径（需要根据实际服务器路径调整）
if (!file_exists($wp_load_path)) {
    // 宝塔面板默认路径
    $possible_paths = array(
        '/www/wwwroot/www.unibroint.com/wp-load.php',
        dirname(__FILE__) . '/../../../../wp-load.php',
    );
    
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $wp_load_path = $path;
            break;
        }
    }
}

if (file_exists($wp_load_path)) {
    require_once($wp_load_path);
} else {
    die('错误：无法找到 wp-load.php 文件。<br>当前文件位置：' . __FILE__ . '<br>请检查WordPress安装路径。');
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
    <title>首页设置诊断工具</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f0f1;
            padding: 40px 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
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
        .btn-success { background: #00a32a; }
        .btn-success:hover { background: #007a20; }
        .code {
            background: #1d2327;
            color: #f0f0f1;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 首页设置页面诊断工具</h1>
        
        <?php
        // 处理修复操作
        if (isset($_POST['action'])) {
            $action = sanitize_text_field($_POST['action']);
            $messages = array();
            $errors = array();

            if ($action === 'sync_acf') {
                // 同步ACF字段组
                if (function_exists('acf_get_field_groups')) {
                    $field_groups = acf_get_field_groups();
                    $found = false;
                    foreach ($field_groups as $group) {
                        if ($group['key'] === 'group_homepage_settings') {
                            $found = true;
                            // 强制重新加载字段组
                            acf_get_field_group($group['key']);
                            $messages[] = '✓ 已重新加载ACF字段组';
                            break;
                        }
                    }
                    if (!$found) {
                        $errors[] = '✗ 未找到"首页设置"字段组，请检查acf-fields.php是否正确加载';
                    }
                } else {
                    $errors[] = '✗ ACF插件未安装或未激活';
                }
            }

            if ($action === 'check_page') {
                // 检查页面ID 45是否存在
                $page = get_post(45);
                if ($page && $page->post_type === 'page') {
                    $messages[] = '✓ 页面ID 45存在：' . esc_html($page->post_title);
                    $messages[] = '✓ 页面状态：' . esc_html($page->post_status);
                    $messages[] = '✓ 页面链接：<a href="' . esc_url(get_edit_post_link(45)) . '" target="_blank">编辑页面</a>';
                } else {
                    $errors[] = '✗ 页面ID 45不存在或已被删除';
                    // 尝试创建页面
                    if (isset($_POST['create_page'])) {
                        $page_data = array(
                            'post_title'    => '首页设置',
                            'post_content'  => '<!-- wp:paragraph --><p>此页面用于存储首页的ACF设置字段。请勿删除此页面。</p><!-- /wp:paragraph -->',
                            'post_status'   => 'publish',
                            'post_type'     => 'page',
                            'post_author'   => 1,
                            'post_name'     => 'homepage-settings',
                            'comment_status' => 'closed',
                            'ping_status'   => 'closed',
                        );
                        
                        // 尝试直接插入到ID 45
                        global $wpdb;
                        $page_data['ID'] = 45;
                        $result = wp_insert_post($page_data, true);
                        
                        if (!is_wp_error($result)) {
                            $messages[] = '✓ 已创建页面ID 45';
                        } else {
                            $errors[] = '✗ 创建页面失败：' . $result->get_error_message();
                        }
                    }
                }
            }

            if ($action === 'flush_acf') {
                // 清除ACF缓存
                if (function_exists('acf_get_store')) {
                    acf_get_store('field-groups')->reset();
                    acf_get_store('fields')->reset();
                    $messages[] = '✓ 已清除ACF缓存';
                }
                // 清除WordPress对象缓存
                wp_cache_flush();
                $messages[] = '✓ 已清除WordPress缓存';
            }

            // 显示消息
            if (!empty($messages)) {
                echo '<div class="section">';
                echo '<h2>修复结果</h2>';
                foreach ($messages as $msg) {
                    echo '<div class="status success">' . $msg . '</div>';
                }
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<div class="status error">' . esc_html($error) . '</div>';
                    }
                }
                echo '</div>';
            }
        }

        // 诊断信息
        ?>

        <div class="section">
            <h2>📊 诊断信息</h2>

            <?php
            // 1. 检查ACF插件
            if (function_exists('acf_get_field_groups')) {
                echo '<div class="status success">✓ ACF插件已安装并激活</div>';
            } else {
                echo '<div class="status error">✗ ACF插件未安装或未激活</div>';
            }

            // 2. 检查页面ID 45
            $page = get_post(45);
            if ($page && $page->post_type === 'page') {
                echo '<div class="status success">✓ 页面ID 45存在：' . esc_html($page->post_title) . '</div>';
                echo '<div class="status info">ℹ 页面状态：' . esc_html($page->post_status) . '</div>';
                echo '<div class="status info">ℹ 页面链接：<a href="' . esc_url(get_edit_post_link(45)) . '" target="_blank">' . esc_url(get_edit_post_link(45)) . '</a></div>';
            } else {
                echo '<div class="status error">✗ 页面ID 45不存在</div>';
            }

            // 3. 检查ACF字段组
            if (function_exists('acf_get_field_groups')) {
                $field_groups = acf_get_field_groups();
                $found = false;
                foreach ($field_groups as $group) {
                    if ($group['key'] === 'group_homepage_settings') {
                        $found = true;
                        echo '<div class="status success">✓ 找到"首页设置"字段组</div>';
                        echo '<div class="status info">ℹ 字段组标题：' . esc_html($group['title']) . '</div>';
                        
                        // 检查字段数量
                        $fields = acf_get_fields($group['key']);
                        if ($fields) {
                            echo '<div class="status info">ℹ 字段数量：' . count($fields) . '</div>';
                        } else {
                            echo '<div class="status warning">⚠ 字段组存在但没有字段</div>';
                        }
                        break;
                    }
                }
                if (!$found) {
                    echo '<div class="status error">✗ 未找到"首页设置"字段组</div>';
                    echo '<div class="status info">ℹ 已注册的字段组：';
                    foreach ($field_groups as $group) {
                        echo '<br>- ' . esc_html($group['title']) . ' (' . esc_html($group['key']) . ')';
                    }
                    echo '</div>';
                }
            }

            // 4. 检查acf-fields.php是否加载
            $functions_file = get_template_directory() . '/inc/acf-fields.php';
            if (file_exists($functions_file)) {
                echo '<div class="status success">✓ acf-fields.php文件存在</div>';
                
                // 检查函数是否已注册
                if (function_exists('angola_b2b_register_homepage_settings_fields')) {
                    echo '<div class="status success">✓ angola_b2b_register_homepage_settings_fields函数已定义</div>';
                } else {
                    echo '<div class="status error">✗ angola_b2b_register_homepage_settings_fields函数未定义</div>';
                }
            } else {
                echo '<div class="status error">✗ acf-fields.php文件不存在</div>';
            }

            // 5. 检查functions.php是否加载acf-fields.php
            $functions_php = get_template_directory() . '/functions.php';
            if (file_exists($functions_php)) {
                $content = file_get_contents($functions_php);
                if (strpos($content, 'acf-fields.php') !== false) {
                    echo '<div class="status success">✓ functions.php已包含acf-fields.php</div>';
                } else {
                    echo '<div class="status error">✗ functions.php未包含acf-fields.php</div>';
                }
            }

            // 6. 检查PHP错误
            $error_log = WP_CONTENT_DIR . '/debug.log';
            if (file_exists($error_log)) {
                $errors = file_get_contents($error_log);
                if (strpos($errors, 'acf') !== false || strpos($errors, 'homepage') !== false) {
                    echo '<div class="status warning">⚠ 发现相关错误日志，请检查debug.log文件</div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>🔧 修复操作</h2>
            
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="check_page">
                <button type="submit" class="btn">检查页面ID 45</button>
            </form>

            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="sync_acf">
                <button type="submit" class="btn">同步ACF字段组</button>
            </form>

            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="flush_acf">
                <button type="submit" class="btn btn-success">清除ACF缓存</button>
            </form>

            <a href="<?php echo esc_url(admin_url('post.php?post=45&action=edit')); ?>" class="btn" target="_blank">打开首页设置页面</a>
        </div>

        <div class="section">
            <h2>📝 手动修复步骤</h2>
            <ol style="margin-left: 20px; margin-top: 10px;">
                <li>确保ACF插件已安装并激活</li>
                <li>检查页面ID 45是否存在（如果不存在，点击"检查页面ID 45"按钮创建）</li>
                <li>点击"清除ACF缓存"按钮</li>
                <li>访问WordPress后台 → 首页设置页面</li>
                <li>如果仍然空白，检查浏览器控制台是否有JavaScript错误</li>
                <li>检查服务器PHP错误日志</li>
            </ol>
        </div>

        <div class="section" style="background: #fff3cd; border-left-color: #ffc107;">
            <h2>⚠️ 安全提醒</h2>
            <p><strong>修复完成后，请立即删除此文件！</strong></p>
            <p>文件位置：<code><?php echo esc_html(__FILE__); ?></code></p>
        </div>
    </div>
</body>
</html>

