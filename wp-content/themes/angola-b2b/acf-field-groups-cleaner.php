<?php
/**
 * ACF字段组清理工具
 * 用于查找并删除损坏/空字段组，解决 ACF 报错
 */

$wp_load_candidates = array(
    dirname(__FILE__, 3) . '/wp-load.php',
    dirname(__FILE__, 4) . '/wp-load.php',
    dirname(__FILE__) . '/../../../../wp-load.php',
    '/www/wwwroot/www.unibroint.com/wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_candidates as $candidate) {
    if (file_exists($candidate)) {
        require_once $candidate;
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('无法加载 wp-load.php，请检查路径设置。');
}

if (!current_user_can('manage_options')) {
    wp_die('您没有权限访问此页面。');
}

if (!function_exists('acf_get_field_groups')) {
    wp_die('ACF 插件未激活，无法执行清理。');
}

$messages = array();

if (isset($_POST['angola_acf_cleanup_submit']) && check_admin_referer('angola_acf_cleanup_action', 'angola_acf_cleanup_nonce')) {
    $ids = isset($_POST['group_ids']) ? (array) $_POST['group_ids'] : array();
    $deleted = 0;
    foreach ($ids as $id) {
        $id = intval($id);
        if ($id > 0) {
            $result = wp_delete_post($id, true);
            if ($result) {
                $deleted++;
            }
        }
    }
    if ($deleted > 0) {
        $messages[] = array('type' => 'success', 'text' => "已永久删除 {$deleted} 个字段组。");
    } else {
        $messages[] = array('type' => 'warning', 'text' => '未删除任何字段组，请勾选需要删除的条目。');
    }
}

$db_groups = get_posts(array(
    'post_type'      => 'acf-field-group',
    'post_status'    => array('publish', 'draft', 'trash', 'auto-draft'),
    'posts_per_page' => -1,
    'orderby'        => 'ID',
    'order'          => 'ASC',
));

$invalid_groups = array();
$valid_groups = array();

foreach ($db_groups as $group_post) {
    $data = array(
        'ID'          => $group_post->ID,
        'title'       => $group_post->post_title,
        'status'      => $group_post->post_status,
        'excerpt'     => $group_post->post_excerpt,
        'name'        => $group_post->post_name,
        'modified'    => $group_post->post_modified,
        'author'      => $group_post->post_author,
    );

    $issues = array();

    if ($group_post->post_status === 'trash') {
        $issues[] = '在回收站';
    }
    if ($group_post->post_status === 'auto-draft') {
        $issues[] = '自动草稿';
    }
    if (empty($group_post->post_title)) {
        $issues[] = '缺少标题';
    }
    if (empty($group_post->post_excerpt)) {
        $issues[] = '缺少 Key（post_excerpt）';
    }
    if (strpos($group_post->post_excerpt, 'group_') !== 0) {
        $issues[] = 'Key 格式异常';
    }

    $field_data = get_post_meta($group_post->ID, 'acf_fields', true);
    if (empty($field_data) || !is_array($field_data)) {
        $issues[] = '字段列表为空或损坏';
    }

    if (!empty($issues)) {
        $invalid_groups[] = array(
            'data'   => $data,
            'issues' => $issues,
            'fields' => $field_data,
        );
    } else {
        $valid_groups[] = $data;
    }
}

$raw_groups = acf_get_field_groups();
$raw_warnings = array();
foreach ($raw_groups as $index => $group) {
    if (!is_array($group)) {
        $raw_warnings[] = "索引 {$index} 的字段组数据异常：" . (is_null($group) ? 'null' : gettype($group));
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>ACF字段组清理工具</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 16px 60px;
            color: #0f172a;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .notice {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .notice.success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .notice.warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .notice.error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(15,23,42,0.08);
        }
        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .issues {
            color: #b91c1c;
            margin: 6px 0 0;
        }
        details summary {
            cursor: pointer;
            color: #2563eb;
            margin-top: 8px;
        }
        .actions {
            margin-top: 16px;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
        }
        .btn-danger {
            background: #dc2626;
            color: #fff;
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #0f172a;
        }
        .stats {
            display: flex;
            gap: 16px;
            margin: 20px 0;
        }
        .stat-card {
            flex: 1;
            padding: 16px 20px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(15,23,42,0.08);
        }
        .stat-card h3 {
            margin: 0;
            font-size: 14px;
            color: #64748b;
        }
        .stat-card p {
            margin: 8px 0 0;
            font-size: 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ACF字段组清理工具</h1>

        <?php foreach ($messages as $msg): ?>
            <div class="notice <?php echo esc_attr($msg['type']); ?>">
                <?php echo esc_html($msg['text']); ?>
            </div>
        <?php endforeach; ?>

        <?php if (!empty($raw_warnings)): ?>
            <div class="notice warning">
                <strong>检测到 <?php echo count($raw_warnings); ?> 个 ACF 返回的异常条目：</strong>
                <ul>
                    <?php foreach ($raw_warnings as $warning): ?>
                        <li><?php echo esc_html($warning); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>数据库字段组数量</h3>
                <p><?php echo esc_html(count($db_groups)); ?></p>
            </div>
            <div class="stat-card">
                <h3>疑似异常字段组</h3>
                <p><?php echo esc_html(count($invalid_groups)); ?></p>
            </div>
        </div>

        <?php if (!empty($invalid_groups)): ?>
            <form method="post">
                <?php wp_nonce_field('angola_acf_cleanup_action', 'angola_acf_cleanup_nonce'); ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width:60px;">删除</th>
                            <th>ID</th>
                            <th>标题</th>
                            <th>Key (excerpt)</th>
                            <th>状态</th>
                            <th>问题</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invalid_groups as $item): ?>
                            <tr>
                                <td><input type="checkbox" name="group_ids[]" value="<?php echo esc_attr($item['data']['ID']); ?>"></td>
                                <td><?php echo esc_html($item['data']['ID']); ?></td>
                                <td><?php echo esc_html($item['data']['title']); ?></td>
                                <td><?php echo esc_html($item['data']['excerpt']); ?></td>
                                <td><?php echo esc_html($item['data']['status']); ?></td>
                                <td>
                                    <ul class="issues">
                                        <?php foreach ($item['issues'] as $issue): ?>
                                            <li><?php echo esc_html($issue); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <details>
                                        <summary>查看原始meta</summary>
                                        <pre><?php echo esc_html(print_r($item['fields'], true)); ?></pre>
                                    </details>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="actions">
                    <button type="submit" name="angola_acf_cleanup_submit" class="btn btn-danger" onclick="return confirm('确定要永久删除选中的字段组吗？此操作不可恢复。');">
                        🗑 永久删除选中字段组
                    </button>
                    <a href="<?php echo esc_url(admin_url('edit.php?post_type=acf-field-group')); ?>" class="btn btn-secondary">打开ACF字段组后台</a>
                </div>
            </form>
        <?php else: ?>
            <div class="notice success">未检测到异常字段组。</div>
        <?php endif; ?>
    </div>
</body>
</html>

