<?php
/**
 * ACF 数据结构体检脚本
 * 访问路径：/wp-content/themes/angola-b2b/acf-diagnostic.php
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
    wp_die('ACF 插件未激活，无法执行诊断。');
}

function angola_b2b_describe_value($value)
{
    if (is_null($value)) {
        return 'null';
    }
    if ($value === '') {
        return '空字符串';
    }
    if ($value === false) {
        return 'false';
    }
    if ($value === true) {
        return 'true';
    }
    if (is_array($value)) {
        return 'array(' . count($value) . ')';
    }
    if (is_object($value)) {
        return 'object(' . get_class($value) . ')';
    }
    return (string) $value;
}

$group_reports = array();
$groups = acf_get_field_groups();
$groups_checked = 0;
$groups_with_issues = 0;

$required_group_keys = array('key', 'title', 'location', 'active', 'style', 'label_placement');
$required_field_keys = array('key', 'type', 'label');

foreach ($groups as $index => $group_raw) {
    $groups_checked++;
    $group = is_array($group_raw) ? $group_raw : array();
    $report = array(
        'title' => isset($group['title']) && $group['title'] !== '' ? $group['title'] : '(无标题)',
        'key' => isset($group['key']) && $group['key'] !== '' ? $group['key'] : '(无key)',
        'id' => isset($group['ID']) ? $group['ID'] : (isset($group['id']) ? $group['id'] : '(未知ID)'),
        'issues' => array(),
        'fields_checked' => 0,
        'raw' => $group_raw,
    );

    if (!is_array($group_raw)) {
        $report['issues'][] = '字段组数据类型异常：' . angola_b2b_describe_value($group_raw);
        $group_reports[] = $report;
        $groups_with_issues++;
        continue;
    }

    foreach ($required_group_keys as $key_name) {
        if (!isset($group[$key_name])) {
            $report['issues'][] = "字段组缺少属性 {$key_name}";
        } elseif ($group[$key_name] === null) {
            $report['issues'][] = "字段组属性 {$key_name} 的值为 null";
        }
    }

    if (isset($group['key'])) {
        $local_group = acf_get_local_field_group($group['key']);
        if ($local_group) {
            $validation = acf_validate_field_group($local_group);
            if (is_wp_error($validation)) {
                $report['issues'][] = '本地字段组验证失败：' . $validation->get_error_message();
            }
        }
    }

    $fields = isset($group['key']) ? acf_get_fields($group['key']) : null;
    if (!is_array($fields)) {
        $report['issues'][] = '无法获取字段列表（acf_get_fields 返回 ' . angola_b2b_describe_value($fields) . '）';
    } else {
        foreach ($fields as $field) {
            $report['fields_checked']++;
            $field_label = isset($field['label']) && $field['label'] !== '' ? $field['label'] : '(无label)';
            $field_key = isset($field['key']) ? $field['key'] : '(无key)';
            $prefix = "字段 {$field_label} [{$field_key}]：";

            foreach ($required_field_keys as $field_key_name) {
                if (!array_key_exists($field_key_name, $field)) {
                    $report['issues'][] = $prefix . "缺少属性 {$field_key_name}";
                } elseif ($field[$field_key_name] === null) {
                    $report['issues'][] = $prefix . "{$field_key_name} 为 null";
                }
            }

            if (isset($field['type']) && $field['type'] !== 'tab') {
                if (!isset($field['name']) || $field['name'] === '') {
                    $report['issues'][] = $prefix . '非 Tab 字段缺少 name';
                }
            }

            if (isset($field['wrapper']) && !is_array($field['wrapper'])) {
                $report['issues'][] = $prefix . 'wrapper 应为数组，当前为 ' . angola_b2b_describe_value($field['wrapper']);
            }

            if (!array_key_exists('conditional_logic', $field)) {
                $report['issues'][] = $prefix . '缺少 conditional_logic 属性';
            }
        }
    }

    if (!empty($report['issues'])) {
        $groups_with_issues++;
    }
    $group_reports[] = $report;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>ACF字段组体检报告</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 0 16px 40px;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
            padding: 32px 40px;
        }
        h1 {
            margin-top: 0;
            font-size: 28px;
            color: #111c44;
        }
        .summary {
            display: flex;
            gap: 20px;
            margin: 20px 0 30px;
        }
        .card {
            flex: 1;
            padding: 16px 20px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .card h3 {
            margin: 0;
            font-size: 15px;
            color: #475569;
        }
        .card p {
            margin: 6px 0 0;
            font-size: 24px;
            font-weight: 600;
            color: #0f172a;
        }
        .report {
            border-top: 1px solid #e2e8f0;
            padding-top: 24px;
        }
        .group {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #e2e8f0;
        }
        .group:last-child {
            border-bottom: none;
        }
        .group h2 {
            margin: 0 0 6px;
            font-size: 20px;
            color: #0f172a;
        }
        .group code {
            font-size: 13px;
            color: #0369a1;
            background: #ecfeff;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .group .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .status.ok {
            background: #d1fae5;
            color: #065f46;
        }
        .status.problem {
            background: #fee2e2;
            color: #991b1b;
        }
        .issues {
            margin: 12px 0 0;
            padding-left: 20px;
            color: #b91c1c;
        }
        .issues li {
            margin-bottom: 6px;
        }
        .no-issues {
            margin: 12px 0 0;
            color: #16a34a;
        }
        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ACF字段组体检报告</h1>
        <div class="summary">
            <div class="card">
                <h3>检测字段组数量</h3>
                <p><?php echo esc_html($groups_checked); ?></p>
            </div>
            <div class="card">
                <h3>存在问题的字段组</h3>
                <p><?php echo esc_html($groups_with_issues); ?></p>
            </div>
            <div class="card">
                <h3>检测时间</h3>
                <p><?php echo esc_html(current_time('Y-m-d H:i:s')); ?></p>
            </div>
        </div>

        <div class="report">
            <?php foreach ($group_reports as $group_report): ?>
                <div class="group">
                    <h2><?php echo esc_html($group_report['title']); ?>
                        <code><?php echo esc_html($group_report['key']); ?></code>
                        <small>ID: <?php echo esc_html($group_report['id']); ?></small>
                        <span class="status <?php echo empty($group_report['issues']) ? 'ok' : 'problem'; ?>">
                            <?php echo empty($group_report['issues']) ? '无异常' : '发现 ' . count($group_report['issues']) . ' 个问题'; ?>
                        </span>
                    </h2>
                    <p>字段数量：<?php echo esc_html($group_report['fields_checked']); ?></p>
                    <?php if (empty($group_report['issues'])): ?>
                        <p class="no-issues">✅ 未发现异常。</p>
                    <?php else: ?>
                        <ul class="issues">
                            <?php foreach ($group_report['issues'] as $issue): ?>
                                <li><?php echo esc_html($issue); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <details>
                            <summary>查看原始数据</summary>
                            <pre><?php echo esc_html(print_r($group_report['raw'], true)); ?></pre>
                        </details>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer">
            如果某个字段组持续出现问题，请记录其 title/key 并告知我们，我们会进一步修复。
        </div>
    </div>
</body>
</html>

