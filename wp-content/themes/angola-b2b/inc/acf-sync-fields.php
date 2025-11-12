<?php
/**
 * ACF字段同步功能
 * 提供后台管理界面一键同步ACF字段
 *
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加ACF同步管理页面到后台菜单
 */
function angola_b2b_add_acf_sync_menu() {
    add_management_page(
        'ACF字段同步',           // 页面标题
        'ACF字段同步',           // 菜单标题
        'manage_options',        // 权限要求
        'angola-acf-sync',       // 菜单slug
        'angola_b2b_acf_sync_page' // 回调函数
    );
}
add_action('admin_menu', 'angola_b2b_add_acf_sync_menu');

/**
 * 渲染ACF同步管理页面
 */
function angola_b2b_acf_sync_page() {
    // 检查用户权限
    if (!current_user_can('manage_options')) {
        wp_die(__('您没有权限访问此页面。'));
    }

    // 处理同步请求
    $sync_result = '';
    if (isset($_POST['sync_acf_fields']) && check_admin_referer('angola_acf_sync_action', 'angola_acf_sync_nonce')) {
        $sync_result = angola_b2b_sync_acf_fields();
    }

    // 获取需要同步的字段组
    $sync_groups = array();
    if (function_exists('acf_get_field_groups')) {
        $groups = acf_get_field_groups();
        
        foreach ($groups as $group) {
            $local = acf_get_local_field_group($group['key']);
            $db = acf_get_field_group($group['key']);
            
            // 检查是否需要同步
            if ($local && $db) {
                $local_modified = isset($local['modified']) ? $local['modified'] : 0;
                $db_modified = isset($db['modified']) ? $db['modified'] : 0;
                
                if ($local_modified > $db_modified) {
                    $sync_groups[] = array(
                        'title' => $group['title'],
                        'key' => $group['key'],
                        'modified' => date('Y-m-d H:i:s', $local_modified),
                    );
                }
            }
        }
    }

    ?>
    <div class="wrap">
        <h1>🔄 ACF字段同步</h1>
        
        <?php if ($sync_result): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo $sync_result; ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>当前状态</h2>
            
            <?php if (empty($sync_groups)): ?>
                <p style="color: green; font-size: 16px;">
                    ✅ <strong>所有字段组都已同步！</strong>
                </p>
                <p>没有需要同步的字段组。</p>
            <?php else: ?>
                <p style="color: orange; font-size: 16px;">
                    ⚠️ <strong>有 <?php echo count($sync_groups); ?> 个字段组需要同步</strong>
                </p>
                
                <table class="wp-list-table widefat fixed striped" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>字段组名称</th>
                            <th>字段组Key</th>
                            <th>最后修改时间</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sync_groups as $index => $group): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><strong><?php echo esc_html($group['title']); ?></strong></td>
                                <td><code><?php echo esc_html($group['key']); ?></code></td>
                                <td><?php echo esc_html($group['modified']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('angola_acf_sync_action', 'angola_acf_sync_nonce'); ?>
                    <button type="submit" name="sync_acf_fields" class="button button-primary button-hero">
                        🔄 立即同步所有字段组
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>说明</h2>
            <ul style="line-height: 1.8;">
                <li><strong>什么是同步？</strong><br>
                    当您修改了主题文件中的ACF字段定义（PHP代码或JSON文件），需要同步到数据库中才能生效。
                </li>
                <li><strong>何时需要同步？</strong><br>
                    当上方显示有字段组需要同步时，点击"立即同步"按钮即可。
                </li>
                <li><strong>同步是否安全？</strong><br>
                    是的！同步只会更新字段组的定义，不会影响已保存的数据。
                </li>
                <li><strong>同步后需要做什么？</strong><br>
                    刷新产品编辑页面，修改后的字段就会生效了。
                </li>
            </ul>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>快速访问</h2>
            <p>
                <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="button">
                    📦 管理产品
                </a>
                <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" class="button">
                    ➕ 添加新产品
                </a>
                <?php if (function_exists('acf')): ?>
                    <a href="<?php echo admin_url('edit.php?post_type=acf-field-group'); ?>" class="button">
                        ⚙️ ACF字段组
                    </a>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <style>
        .card {
            background: white;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        .card h2 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .button-hero {
            font-size: 16px !important;
            padding: 10px 30px !important;
            height: auto !important;
        }
    </style>
    <?php
}

/**
 * 执行ACF字段同步
 */
function angola_b2b_sync_acf_fields() {
    if (!function_exists('acf_get_field_groups')) {
        return '❌ ACF插件未激活，无法同步字段。';
    }

    $synced_count = 0;
    $groups = acf_get_field_groups();

    foreach ($groups as $group) {
        $local = acf_get_local_field_group($group['key']);
        
        if ($local) {
            // 同步字段组
            acf_import_field_group($local);
            
            // 获取并同步字段
            $fields = acf_get_fields($group['key']);
            if ($fields) {
                foreach ($fields as $field) {
                    acf_update_field($field);
                }
            }
            
            $synced_count++;
        }
    }

    if ($synced_count > 0) {
        return "✅ 成功同步了 {$synced_count} 个字段组！请刷新产品编辑页面查看效果。";
    } else {
        return '✅ 没有需要同步的字段组。';
    }
}

/**
 * 添加管理栏快捷链接
 */
function angola_b2b_add_acf_sync_admin_bar($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $wp_admin_bar->add_node(array(
        'id'    => 'angola-acf-sync',
        'title' => '🔄 ACF同步',
        'href'  => admin_url('tools.php?page=angola-acf-sync'),
        'meta'  => array(
            'title' => '同步ACF字段',
        ),
    ));
}
add_action('admin_bar_menu', 'angola_b2b_add_acf_sync_admin_bar', 100);

