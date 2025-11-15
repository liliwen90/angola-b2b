<?php
/**
 * WordPress 404错误修复工具
 * 修复产品分类和新闻链接的404问题
 * 
 * 使用方法：将此文件放在WordPress根目录，然后访问：
 * https://your-domain.com/fix-permalinks-404.php
 * 
 * 修复完成后请删除此文件！
 */

// 加载WordPress
require_once(__DIR__ . '/wp-load.php');

// 检查用户权限
if (!current_user_can('manage_options')) {
    wp_die('您没有权限访问此页面。');
}

// 设置页面标题
$page_title = 'WordPress 404错误修复工具';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($page_title); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
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
        h1 {
            color: #1d2327;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #646970;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f6f7f7;
            border-radius: 6px;
            border-left: 4px solid #2271b1;
        }
        .section h2 {
            color: #1d2327;
            margin-bottom: 15px;
            font-size: 20px;
        }
        .status {
            padding: 12px 16px;
            border-radius: 4px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status.success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }
        .status.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffecb5;
        }
        .status.error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }
        .status.info {
            background: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
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
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #135e96;
        }
        .btn-success {
            background: #00a32a;
        }
        .btn-success:hover {
            background: #007a20;
        }
        .btn-danger {
            background: #d63638;
        }
        .btn-danger:hover {
            background: #b32d2e;
        }
        .code {
            background: #1d2327;
            color: #f0f0f1;
            padding: 15px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .info-box {
            background: #fff;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
        }
        .info-box strong {
            color: #1d2327;
        }
        ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        li {
            margin: 5px 0;
        }
        .actions {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #c3c4c7;
        }
        .actions .btn {
            margin-right: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 WordPress 404错误修复工具</h1>
        <p class="subtitle">修复产品分类和新闻链接的404问题</p>

        <?php
        // 处理修复操作
        if (isset($_POST['action'])) {
            $action = sanitize_text_field($_POST['action']);
            $messages = array();
            $errors = array();

            if ($action === 'flush_rewrite') {
                // 刷新rewrite规则
                flush_rewrite_rules(true);
                $messages[] = '✓ 已刷新WordPress rewrite规则';
            }

            if ($action === 'update_permalink') {
                // 更新永久链接设置（设置为"文章名"格式）
                global $wp_rewrite;
                $wp_rewrite->set_permalink_structure('/%postname%/');
                flush_rewrite_rules(true);
                $messages[] = '✓ 已更新永久链接设置为"文章名"格式';
            }

            if ($action === 'check_htaccess') {
                // 检查并创建.htaccess文件
                $htaccess_file = ABSPATH . '.htaccess';
                $htaccess_content = "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
";

                if (file_exists($htaccess_file)) {
                    $current_content = file_get_contents($htaccess_file);
                    if (strpos($current_content, '# BEGIN WordPress') === false) {
                        // 添加WordPress规则
                        file_put_contents($htaccess_file, $htaccess_content . "\n" . $current_content);
                        $messages[] = '✓ 已在.htaccess文件中添加WordPress rewrite规则';
                    } else {
                        $messages[] = '✓ .htaccess文件已包含WordPress规则';
                    }
                } else {
                    // 创建.htaccess文件
                    if (file_put_contents($htaccess_file, $htaccess_content)) {
                        chmod($htaccess_file, 0644);
                        $messages[] = '✓ 已创建.htaccess文件并添加WordPress规则';
                    } else {
                        $errors[] = '✗ 无法创建.htaccess文件，请检查文件权限';
                    }
                }
            }

            if ($action === 'fix_all') {
                // 执行所有修复
                global $wp_rewrite;
                
                // 1. 更新永久链接设置
                $wp_rewrite->set_permalink_structure('/%postname%/');
                $messages[] = '✓ 已更新永久链接设置';
                
                // 2. 刷新rewrite规则
                flush_rewrite_rules(true);
                $messages[] = '✓ 已刷新rewrite规则';
                
                // 3. 检查.htaccess（如果是Apache）
                if (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false || function_exists('apache_get_modules')) {
                    $htaccess_file = ABSPATH . '.htaccess';
                    $htaccess_content = "# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
";
                    if (file_exists($htaccess_file)) {
                        $current_content = file_get_contents($htaccess_file);
                        if (strpos($current_content, '# BEGIN WordPress') === false) {
                            file_put_contents($htaccess_file, $htaccess_content . "\n" . $current_content);
                            $messages[] = '✓ 已更新.htaccess文件';
                        }
                    } else {
                        if (file_put_contents($htaccess_file, $htaccess_content)) {
                            chmod($htaccess_file, 0644);
                            $messages[] = '✓ 已创建.htaccess文件';
                        }
                    }
                } else {
                    $messages[] = 'ℹ 检测到非Apache服务器，跳过.htaccess检查';
                }
            }

            // 显示消息
            if (!empty($messages)) {
                echo '<div class="section">';
                echo '<h2>修复结果</h2>';
                foreach ($messages as $msg) {
                    echo '<div class="status success">' . esc_html($msg) . '</div>';
                }
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        echo '<div class="status error">' . esc_html($error) . '</div>';
                    }
                }
                echo '</div>';
            }
        }

        // 检查当前状态
        ?>

        <div class="section">
            <h2>📊 当前状态检查</h2>

            <?php
            // 1. 检查永久链接设置
            $permalink_structure = get_option('permalink_structure');
            if (empty($permalink_structure)) {
                echo '<div class="status error">✗ 永久链接设置为"朴素"格式（会导致404错误）</div>';
            } else {
                echo '<div class="status success">✓ 永久链接设置：' . esc_html($permalink_structure) . '</div>';
            }

            // 2. 检查.htaccess文件
            $htaccess_file = ABSPATH . '.htaccess';
            if (file_exists($htaccess_file)) {
                $htaccess_content = file_get_contents($htaccess_file);
                if (strpos($htaccess_content, '# BEGIN WordPress') !== false) {
                    echo '<div class="status success">✓ .htaccess文件存在且包含WordPress规则</div>';
                } else {
                    echo '<div class="status warning">⚠ .htaccess文件存在但缺少WordPress rewrite规则</div>';
                }
            } else {
                $server_software = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown';
                if (strpos($server_software, 'Apache') !== false) {
                    echo '<div class="status error">✗ .htaccess文件不存在（Apache服务器需要此文件）</div>';
                } else {
                    echo '<div class="status info">ℹ 检测到非Apache服务器，.htaccess文件不是必需的</div>';
                }
            }

            // 3. 检查自定义分类法
            $taxonomies = get_taxonomies(array('public' => true), 'objects');
            $product_category_exists = isset($taxonomies['product_category']);
            if ($product_category_exists) {
                echo '<div class="status success">✓ 产品分类（product_category）已注册</div>';
                
                // 检查分类是否存在
                $categories = get_terms(array(
                    'taxonomy' => 'product_category',
                    'hide_empty' => false,
                ));
                if (!empty($categories) && !is_wp_error($categories)) {
                    echo '<div class="status success">✓ 找到 ' . count($categories) . ' 个产品分类</div>';
                } else {
                    echo '<div class="status warning">⚠ 未找到产品分类，请先在后台创建分类</div>';
                }
            } else {
                echo '<div class="status error">✗ 产品分类（product_category）未注册</div>';
            }

            // 4. 检查自定义文章类型
            $post_types = get_post_types(array('public' => true), 'objects');
            $product_exists = isset($post_types['product']);
            if ($product_exists) {
                echo '<div class="status success">✓ 产品文章类型（product）已注册</div>';
            } else {
                echo '<div class="status error">✗ 产品文章类型（product）未注册</div>';
            }

            // 5. 检查服务器类型
            $server_software = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown';
            echo '<div class="status info">ℹ 服务器软件：' . esc_html($server_software) . '</div>';

            // 6. 检查mod_rewrite（如果是Apache）
            if (function_exists('apache_get_modules')) {
                $modules = apache_get_modules();
                if (in_array('mod_rewrite', $modules)) {
                    echo '<div class="status success">✓ Apache mod_rewrite模块已启用</div>';
                } else {
                    echo '<div class="status error">✗ Apache mod_rewrite模块未启用</div>';
                }
            }
            ?>

        </div>

        <div class="section">
            <h2>🔍 链接测试</h2>
            <div class="info-box">
                <p><strong>产品分类链接格式：</strong></p>
                <ul>
                    <li>标准格式：<code>/product-category/分类slug/</code></li>
                    <li>示例：<code>/product-category/logistics/</code></li>
                </ul>
                <p style="margin-top: 15px;"><strong>新闻文章链接格式：</strong></p>
                <ul>
                    <li>标准格式：<code>/文章slug/</code></li>
                    <li>示例：<code>/news-title/</code></li>
                </ul>
            </div>

            <?php
            // 测试分类链接
            $test_categories = array('logistics', 'building-materials', 'agricultural-machinery', 'industrial-equipment', 'construction-engineering');
            echo '<p><strong>测试分类链接：</strong></p>';
            foreach ($test_categories as $slug) {
                $term = get_term_by('slug', $slug, 'product_category');
                if ($term && !is_wp_error($term)) {
                    $link = get_term_link($term);
                    echo '<div class="status info">';
                    echo '分类：' . esc_html($term->name) . ' → ';
                    echo '<a href="' . esc_url($link) . '" target="_blank">' . esc_html($link) . '</a>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <div class="section">
            <h2>📝 修复说明</h2>
            <div class="info-box">
                <p><strong>404错误的常见原因：</strong></p>
                <ul>
                    <li>永久链接设置为"朴素"格式（?p=123）</li>
                    <li>WordPress rewrite规则未刷新</li>
                    <li>Apache服务器缺少.htaccess文件或rewrite规则</li>
                    <li>Nginx服务器未配置rewrite规则</li>
                </ul>
                <p style="margin-top: 15px;"><strong>修复步骤：</strong></p>
                <ol style="margin-left: 20px;">
                    <li>点击"一键修复所有问题"按钮</li>
                    <li>如果仍有问题，请检查服务器配置（Nginx需要手动配置）</li>
                    <li>修复完成后，请删除此文件（fix-permalinks-404.php）</li>
                </ol>
            </div>
        </div>

        <div class="actions">
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="fix_all">
                <button type="submit" class="btn btn-success">🚀 一键修复所有问题</button>
            </form>

            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="flush_rewrite">
                <button type="submit" class="btn">🔄 仅刷新Rewrite规则</button>
            </form>

            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="update_permalink">
                <button type="submit" class="btn">⚙️ 更新永久链接设置</button>
            </form>

            <?php if (strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false || function_exists('apache_get_modules')) : ?>
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="check_htaccess">
                <button type="submit" class="btn">📄 检查/创建.htaccess</button>
            </form>
            <?php endif; ?>

            <a href="<?php echo esc_url(admin_url('options-permalink.php')); ?>" class="btn" target="_blank">⚙️ 打开WordPress永久链接设置</a>
        </div>

        <div class="section" style="margin-top: 30px; background: #fff3cd; border-left-color: #ffc107;">
            <h2>⚠️ 安全提醒</h2>
            <p><strong>修复完成后，请立即删除此文件！</strong></p>
            <p>此文件包含管理功能，不应保留在生产服务器上。</p>
            <p>文件位置：<code><?php echo esc_html(__FILE__); ?></code></p>
        </div>
    </div>
</body>
</html>

