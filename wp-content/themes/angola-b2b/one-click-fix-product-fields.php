<?php
/**
 * 一键修复产品字段
 * 
 * 这个脚本会：
 * 1. 删除所有旧的产品相关ACF字段组（从数据库）
 * 2. 删除所有旧的ACF JSON文件（产品相关）
 * 3. 强制同步新的简洁字段组到数据库
 * 4. 刷新ACF缓存
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加管理菜单
 */
add_action('admin_menu', function() {
    add_submenu_page(
        'tools.php',
        '一键修复产品字段',
        '🔧 一键修复产品字段',
        'manage_options',
        'one-click-fix-product-fields',
        'angola_b2b_render_one_click_fix_page'
    );
});

/**
 * 渲染修复页面
 */
function angola_b2b_render_one_click_fix_page() {
    ?>
    <div class="wrap">
        <h1>🔧 一键修复产品字段</h1>
        <p>此工具会清理所有旧的产品字段组，并重新创建简洁版字段组。</p>

        <?php
        if (isset($_POST['fix_fields']) && check_admin_referer('one_click_fix_product_fields')) {
            echo '<div class="notice notice-info"><p><strong>🚀 开始修复...</strong></p></div>';
            
            // 步骤1: 删除数据库中的旧字段组
            echo '<h2>步骤 1: 清理数据库中的旧字段组</h2>';
            $deleted_db = angola_b2b_delete_old_field_groups_from_db();
            
            // 步骤2: 删除ACF JSON文件中的旧字段组
            echo '<h2>步骤 2: 清理ACF JSON文件</h2>';
            $deleted_json = angola_b2b_delete_old_acf_json_files();
            
            // 步骤3: 删除数据库中所有同key的字段组（避免重复）
            echo '<h2>步骤 3: 删除数据库中的重复字段组</h2>';
            angola_b2b_delete_duplicate_field_groups();
            
            // 步骤4: 强制同步新字段组到数据库
            echo '<h2>步骤 4: 同步新字段组到数据库</h2>';
            $synced = angola_b2b_force_sync_new_field_group();
            
            // 步骤5: 刷新ACF缓存
            echo '<h2>步骤 5: 刷新ACF缓存</h2>';
            angola_b2b_refresh_acf_cache();
            
            echo '<div class="notice notice-success" style="margin-top: 20px;"><p><strong>✅ 修复完成！</strong></p>';
            echo '<p>请前往 <a href="' . admin_url('post-new.php?post_type=product') . '">添加新产品</a> 查看效果。</p></div>';
        }
        ?>

        <form method="post" style="margin-top: 30px;">
            <?php wp_nonce_field('one_click_fix_product_fields'); ?>
            <input type="submit" name="fix_fields" class="button button-primary button-hero" value="🔧 开始一键修复" 
                   style="background: #d63638; border-color: #d63638; font-size: 18px; padding: 15px 30px;"
                   onclick="return confirm('确定要执行修复吗？这将删除所有旧的产品字段组。');">
        </form>

        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-left: 4px solid #ffc107;">
            <h3>⚠️ 注意事项</h3>
            <ul>
                <li>此操作会删除所有旧的产品字段组</li>
                <li>建议在操作前备份数据库</li>
                <li>如果产品中有旧字段的数据，这些数据不会被删除，但字段界面会被移除</li>
                <li>新字段组包含4个语言标签：English、Português、简体中文、繁體中文</li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * 步骤1: 删除数据库中的旧字段组
 */
function angola_b2b_delete_old_field_groups_from_db() {
    global $wpdb;
    
    // 要删除的字段组key列表
    $old_group_keys = array(
        'group_product_basic_info',
        'group_product_specs',
        'group_product_multilingual',
        'group_product_images',
        'group_6902552c37085',
        'group_6902da0bb487a',
    );
    
    $deleted = array();
    
    foreach ($old_group_keys as $key) {
        // 查找对应的post
        $post = $wpdb->get_row($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type = 'acf-field-group'",
            $key
        ));
        
        if ($post) {
            // 删除这个字段组及其所有子字段
            wp_delete_post($post->ID, true);
            $deleted[] = $key;
            echo '<p>✅ 已删除数据库字段组: <code>' . esc_html($key) . '</code></p>';
        }
    }
    
    // 额外：删除所有标题包含"产品"的ACF字段组
    $product_groups = $wpdb->get_results(
        "SELECT ID, post_title FROM {$wpdb->posts} 
         WHERE post_type = 'acf-field-group' 
         AND (post_title LIKE '%产品%' OR post_title LIKE '%Product%')
         AND post_title NOT LIKE '%简洁版%'"
    );
    
    foreach ($product_groups as $group) {
        wp_delete_post($group->ID, true);
        echo '<p>✅ 已删除数据库字段组: <code>' . esc_html($group->post_title) . '</code> (ID: ' . $group->ID . ')</p>';
    }
    
    if (empty($deleted) && empty($product_groups)) {
        echo '<p>ℹ️ 数据库中没有找到需要删除的旧字段组</p>';
    }
    
    return array_merge($deleted, wp_list_pluck($product_groups, 'ID'));
}

/**
 * 步骤2: 删除ACF JSON文件中的旧字段组
 */
function angola_b2b_delete_old_acf_json_files() {
    $acf_json_path = get_template_directory() . '/acf-json';
    
    if (!file_exists($acf_json_path)) {
        echo '<p>ℹ️ ACF JSON目录不存在</p>';
        return array();
    }
    
    $files = glob($acf_json_path . '/group_*.json');
    $deleted = array();
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // 保留新的简洁版字段组JSON
        if ($filename === 'group_product_simple_multilingual.json') {
            echo '<p>✅ 保留新字段组: <code>' . esc_html($filename) . '</code></p>';
            continue;
        }
        
        // 保留非产品相关的字段组（如分类、首页等）
        if (strpos($filename, 'category') !== false || strpos($filename, 'homepage') !== false) {
            echo '<p>ℹ️ 保留非产品字段组: <code>' . esc_html($filename) . '</code></p>';
            continue;
        }
        
        // 读取JSON文件，检查是否与产品相关
        $json_content = file_get_contents($file);
        $field_group = json_decode($json_content, true);
        
        if ($field_group && isset($field_group['location'])) {
            $is_product_related = false;
            foreach ($field_group['location'] as $location_group) {
                foreach ($location_group as $rule) {
                    if (isset($rule['param']) && $rule['param'] === 'post_type' && 
                        isset($rule['value']) && $rule['value'] === 'product') {
                        $is_product_related = true;
                        break 2;
                    }
                }
            }
            
            if ($is_product_related) {
                unlink($file);
                $deleted[] = $filename;
                echo '<p>✅ 已删除JSON文件: <code>' . esc_html($filename) . '</code></p>';
            }
        }
    }
    
    if (empty($deleted)) {
        echo '<p>ℹ️ 没有找到需要删除的产品相关JSON文件</p>';
    }
    
    return $deleted;
}

/**
 * 步骤3: 删除数据库中所有同名的字段组（避免重复）
 */
function angola_b2b_delete_duplicate_field_groups() {
    global $wpdb;
    
    $target_key = 'group_product_simple_multilingual';
    $target_title = '产品多语言信息（简洁版）';
    
    // 查找所有相同key或相同title的字段组
    $query = $wpdb->prepare(
        "SELECT ID, post_title, post_name, post_modified FROM {$wpdb->posts} 
        WHERE post_type = 'acf-field-group' 
        AND (post_name = %s OR post_title = %s)
        ORDER BY post_modified DESC",
        $target_key,
        $target_title
    );
    
    $results = $wpdb->get_results($query);
    
    if (empty($results)) {
        echo '<p>ℹ️ 数据库中没有找到相关字段组</p>';
        return true;
    }
    
    echo '<p>🔍 找到 <code>' . count($results) . '</code> 个相同key或标题的字段组记录：</p>';
    foreach ($results as $result) {
        echo '<p>   - ID: ' . $result->ID . ', 标题: ' . esc_html($result->post_title) . ', Key: ' . esc_html($result->post_name) . ', 修改时间: ' . $result->post_modified . '</p>';
    }
    
    // 删除所有找到的字段组
    $deleted_count = 0;
    foreach ($results as $result) {
        // 先删除该字段组的所有子字段
        $fields = $wpdb->get_results($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field' AND post_parent = %d",
            $result->ID
        ));
        
        foreach ($fields as $field) {
            wp_delete_post($field->ID, true);
        }
        
        // 使用wp_delete_post完全删除字段组（不进入回收站）
        if (wp_delete_post($result->ID, true)) {
            $deleted_count++;
            echo '<p>✅ 已删除字段组 ID: ' . $result->ID . '（含 ' . count($fields) . ' 个子字段）</p>';
        } else {
            echo '<p>❌ 删除失败 ID: ' . $result->ID . '</p>';
        }
    }
    
    echo '<p>✅ 共删除 <code>' . $deleted_count . '</code> 个重复的字段组记录</p>';
    return $deleted_count > 0;
}

/**
 * 步骤4: 强制同步新字段组到数据库
 */
function angola_b2b_force_sync_new_field_group() {
    if (!function_exists('acf_get_local_field_groups') || !function_exists('acf_update_field_group')) {
        echo '<p>❌ ACF函数不可用</p>';
        return false;
    }
    
    // 获取本地注册的字段组
    $local_groups = acf_get_local_field_groups();
    
    if (isset($local_groups['group_product_simple_multilingual'])) {
        $field_group = $local_groups['group_product_simple_multilingual'];
        
        // 同步到数据库
        $result = acf_update_field_group($field_group);
        
        if ($result) {
            echo '<p>✅ 已同步新字段组到数据库: <code>group_product_simple_multilingual</code></p>';
            echo '<p>   字段组标题: <code>' . esc_html($field_group['title']) . '</code></p>';
            $field_count = isset($field_group['fields']) && is_array($field_group['fields']) ? count($field_group['fields']) : 0;
            echo '<p>   字段数量: <code>' . $field_count . '</code></p>';
            return true;
        } else {
            echo '<p>❌ 同步失败</p>';
            return false;
        }
    } else {
        echo '<p>❌ 未找到新字段组: group_product_simple_multilingual</p>';
        echo '<p>可用的本地字段组: <code>' . implode(', ', array_keys($local_groups)) . '</code></p>';
        return false;
    }
}

/**
 * 步骤5: 刷新ACF缓存
 */
function angola_b2b_refresh_acf_cache() {
    // 删除ACF缓存
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->reset();
        acf_get_store('fields')->reset();
        echo '<p>✅ 已刷新ACF缓存</p>';
    }
    
    // 删除WordPress缓存
    wp_cache_flush();
    echo '<p>✅ 已刷新WordPress缓存</p>';
    
    echo '<p>✅ 缓存刷新完成</p>';
}

