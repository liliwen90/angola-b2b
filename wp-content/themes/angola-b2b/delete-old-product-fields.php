<?php
/**
 * 临时脚本：删除数据库中的旧产品ACF字段组
 * 
 * 使用方法：
 * 1. 在WordPress后台创建一个临时管理页面
 * 2. 点击按钮执行删除
 * 3. 完成后删除此文件
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 删除旧的产品ACF字段组
 */
function angola_b2b_delete_old_product_field_groups() {
    // 要删除的字段组key列表
    $old_groups_to_delete = array(
        'group_6902552c37085',  // 产品基本信息
        'group_6902da0bb487a',  // 产品规格参数
        'group_product_multilingual',  // 旧的多语言字段组
        'group_product_stock_info',  // 库存信息
        'group_product_hero',  // Hero字段
    );
    
    $deleted_groups = array();
    $failed_groups = array();
    
    foreach ($old_groups_to_delete as $group_key) {
        // 尝试获取字段组
        $group = acf_get_field_group($group_key);
        
        if ($group && isset($group['ID'])) {
            // 删除字段组（这会删除数据库中的post和所有相关字段）
            $result = acf_delete_field_group($group['ID']);
            
            if ($result) {
                $deleted_groups[] = $group['title'] . ' (' . $group_key . ')';
            } else {
                $failed_groups[] = $group['title'] . ' (' . $group_key . ')';
            }
        }
    }
    
    return array(
        'deleted' => $deleted_groups,
        'failed' => $failed_groups
    );
}

/**
 * 添加管理菜单
 */
add_action('admin_menu', 'angola_b2b_add_delete_fields_menu');
function angola_b2b_add_delete_fields_menu() {
    add_management_page(
        '删除旧产品字段',
        '删除旧产品字段',
        'manage_options',
        'angola-b2b-delete-old-fields',
        'angola_b2b_delete_fields_page'
    );
}

/**
 * 管理页面内容
 */
function angola_b2b_delete_fields_page() {
    if (!current_user_can('manage_options')) {
        wp_die('您没有足够的权限访问此页面。');
    }
    
    $message = '';
    
    // 处理删除请求
    if (isset($_POST['delete_old_fields']) && wp_verify_nonce($_POST['delete_fields_nonce'], 'delete_old_fields_action')) {
        $result = angola_b2b_delete_old_product_field_groups();
        
        if (!empty($result['deleted'])) {
            $message .= '<div class="notice notice-success"><p><strong>✅ 成功删除以下字段组：</strong><br>';
            $message .= implode('<br>', $result['deleted']);
            $message .= '</p></div>';
        }
        
        if (!empty($result['failed'])) {
            $message .= '<div class="notice notice-error"><p><strong>❌ 删除失败：</strong><br>';
            $message .= implode('<br>', $result['failed']);
            $message .= '</p></div>';
        }
        
        if (empty($result['deleted']) && empty($result['failed'])) {
            $message .= '<div class="notice notice-info"><p>✅ 没有找到需要删除的旧字段组。</p></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1>删除旧产品字段组</h1>
        <?php echo $message; ?>
        
        <div class="notice notice-warning">
            <p><strong>⚠️ 警告：</strong>此操作将永久删除数据库中的旧产品ACF字段组！</p>
            <p>以下字段组将被删除：</p>
            <ul>
                <li>产品基本信息（产品图片1-5、视频链接、推荐产品等）</li>
                <li>产品规格参数（规格参数1-8）</li>
                <li>旧的多语言字段组</li>
                <li>库存信息</li>
                <li>Hero字段</li>
            </ul>
            <p><strong>删除后将只保留新的简洁版多语言字段组（4个Tab，每个Tab只有标题和富文本编辑器）。</strong></p>
        </div>
        
        <form method="post" style="margin-top: 20px;">
            <?php wp_nonce_field('delete_old_fields_action', 'delete_fields_nonce'); ?>
            <p>
                <input type="submit" name="delete_old_fields" class="button button-primary button-large" value="🗑️ 确认删除旧字段组" 
                    onclick="return confirm('您确定要删除这些旧字段组吗？此操作不可撤销！');">
            </p>
        </form>
        
        <h2>删除后的下一步：</h2>
        <ol>
            <li>删除完成后，刷新<a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" target="_blank">产品添加页面</a></li>
            <li>确认只显示4个语言Tab</li>
            <li>删除此临时文件：<code>wp-content/themes/angola-b2b/delete-old-product-fields.php</code></li>
            <li>从 <code>functions.php</code> 中移除此文件的引用</li>
        </ol>
    </div>
    <?php
}

