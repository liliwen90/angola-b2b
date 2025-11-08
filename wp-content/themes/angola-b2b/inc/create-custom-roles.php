<?php
/**
 * 临时脚本：创建自定义用户角色
 * 
 * 用途：为中国员工和安哥拉员工创建两个自定义角色
 * 使用后请从functions.php中移除此文件的引用
 * 
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 创建自定义角色
 * 只执行一次，使用选项来标记
 */
function angola_b2b_create_custom_roles() {
    // 检查是否已经创建过
    if (get_option('angola_b2b_custom_roles_created')) {
        return;
    }

    // 1. 创建中国产品管理员角色（基于Editor，完整权限）
    $editor_role = get_role('editor');
    if ($editor_role) {
        $cn_manager_caps = $editor_role->capabilities;
        
        // 添加一些额外的权限
        $cn_manager_caps['manage_product_terms'] = true; // 管理产品分类
        $cn_manager_caps['edit_product_tags'] = true;    // 编辑产品标签
        $cn_manager_caps['delete_product_tags'] = true;  // 删除产品标签
        
        add_role(
            'cn_product_manager',
            '中国产品管理员',
            $cn_manager_caps
        );
        
        echo '<div class="notice notice-success"><p>✅ 成功创建角色：中国产品管理员 (cn_product_manager)</p></div>';
    }

    // 2. 创建安哥拉产品编辑角色（基于Author，有限权限）
    $author_role = get_role('author');
    if ($author_role) {
        $ao_editor_caps = $author_role->capabilities;
        
        // 移除一些权限（更谨慎）
        $ao_editor_caps['delete_published_posts'] = false; // 不能删除已发布的内容
        
        // 添加产品相关权限
        $ao_editor_caps['edit_products'] = true;
        $ao_editor_caps['edit_published_products'] = true;
        $ao_editor_caps['publish_products'] = true;
        
        add_role(
            'ao_product_editor',
            '安哥拉产品编辑',
            $ao_editor_caps
        );
        
        echo '<div class="notice notice-success"><p>✅ 成功创建角色：安哥拉产品编辑 (ao_product_editor)</p></div>';
    }

    // 标记为已创建
    update_option('angola_b2b_custom_roles_created', true);
    
    echo '<div class="notice notice-info"><p>📝 自定义角色创建完成！现在可以从functions.php中移除此脚本的引用了。</p></div>';
}

// 在管理员后台加载时执行
add_action('admin_init', 'angola_b2b_create_custom_roles');

