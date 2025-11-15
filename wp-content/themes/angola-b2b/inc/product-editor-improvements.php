<?php
/**
 * Product Editor Improvements
 * 优化产品编辑界面，突出显示产品分类选择
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 在产品编辑页面添加醒目的分类提示框
 */
function angola_b2b_product_category_reminder() {
    global $post_type;
    
    // 只在产品编辑页面显示
    if ($post_type !== 'product') {
        return;
    }
    
    ?>
    <style>
        /* 产品分类提示框样式 */
        #angola-category-reminder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        #angola-category-reminder h3 {
            margin: 0 0 12px 0;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        #angola-category-reminder h3:before {
            content: "📁";
            font-size: 20px;
        }
        
        #angola-category-reminder p {
            margin: 0 0 15px 0;
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.95;
        }
        
        #angola-category-reminder .category-list {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        
        #angola-category-reminder .category-list ul {
            margin: 0;
            padding-left: 20px;
        }
        
        #angola-category-reminder .category-list li {
            color: white;
            margin: 6px 0;
            font-size: 13px;
        }
        
        #angola-category-reminder .category-list li:before {
            content: "▸ ";
            margin-right: 5px;
            opacity: 0.7;
        }
        
        #angola-category-reminder .reminder-note {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        
        #angola-category-reminder .reminder-note:before {
            content: "💡";
            font-size: 16px;
            flex-shrink: 0;
        }
        
        /* 隐藏摘要字段 */
        #postexcerpt {
            display: none !important;
        }
        
        /* 让产品分类框更醒目 */
        #product_categorydiv {
            border: 3px solid #667eea !important;
            border-radius: 8px !important;
            background: #f8f9ff !important;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.15) !important;
        }
        
        #product_categorydiv .inside {
            padding: 15px !important;
        }
        
        #product_categorydiv h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            margin: -1px -1px 15px -1px !important;
            padding: 12px 15px !important;
            border-radius: 5px 5px 0 0 !important;
            font-size: 15px !important;
        }
        
        #product_categorydiv h2:before {
            content: "📁 ";
            font-size: 18px;
            margin-right: 5px;
        }
        
        /* 分类复选框样式优化 */
        #product_categorychecklist li {
            margin: 8px 0 !important;
        }
        
        #product_categorychecklist input[type="checkbox"] {
            width: 18px !important;
            height: 18px !important;
            margin-right: 8px !important;
            cursor: pointer !important;
        }
        
        #product_categorychecklist label {
            font-size: 14px !important;
            cursor: pointer !important;
        }
        
        /* 高亮已选中的分类 */
        #product_categorychecklist input[type="checkbox"]:checked + label {
            color: #667eea !important;
            font-weight: 600 !important;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // 在产品分类框之前插入提示框
        var reminderBox = $('<div id="angola-category-reminder">' +
            '<h3>请选择产品分类</h3>' +
            '<p>每个产品必须归属于一个分类，这样才能在首页和产品列表页正确显示。</p>' +
            '<div class="category-list">' +
                '<strong style="display: block; margin-bottom: 8px;">我们的5个主要产品分类：</strong>' +
                '<ul>' +
                    '<li>建筑工程 (Construction Engineering)</li>' +
                    '<li>建筑材料 (Building Materials)</li>' +
                    '<li>农机农具 (Agricultural Machinery)</li>' +
                    '<li>工业设备 (Industrial Equipment)</li>' +
                    '<li>物流与海关 (Logistics & Customs)</li>' +
                '</ul>' +
            '</div>' +
            '<div class="reminder-note">' +
                '<span>请在下方的"产品分类"框中勾选对应的分类。如果没有合适的分类，请联系管理员添加。</span>' +
            '</div>' +
        '</div>');
        
        // 插入到产品分类框之前
        $('#product_categorydiv').before(reminderBox);
        
        // 监听分类选择变化
        $('#product_categorychecklist input[type="checkbox"]').on('change', function() {
            var checkedCount = $('#product_categorychecklist input[type="checkbox"]:checked').length;
            
            if (checkedCount > 0) {
                $('#angola-category-reminder').css({
                    'background': 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                    'border-left': '5px solid #059669'
                });
                $('#angola-category-reminder h3').html('✅ 已选择产品分类');
                $('#angola-category-reminder p').html('很好！您已经为这个产品选择了分类。产品将显示在对应的分类页面中。');
            } else {
                $('#angola-category-reminder').css({
                    'background': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'border-left': 'none'
                });
                $('#angola-category-reminder h3').html('📁 请选择产品分类');
                $('#angola-category-reminder p').html('每个产品必须归属于一个分类，这样才能在首页和产品列表页正确显示。');
            }
        });
        
        // 页面加载时检查
        $('#product_categorychecklist input[type="checkbox"]').trigger('change');
    });
    </script>
    <?php
}
add_action('admin_head-post.php', 'angola_b2b_product_category_reminder');
add_action('admin_head-post-new.php', 'angola_b2b_product_category_reminder');

/**
 * 在产品发布前检查是否选择了分类
 */
function angola_b2b_check_product_category($post_id, $post, $update) {
    // 跳过自动保存和修订版本
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 只检查产品类型
    if ($post->post_type !== 'product') {
        return;
    }
    
    // 只在发布时检查
    if ($post->post_status !== 'publish') {
        return;
    }
    
    // 检查是否有产品分类
    $terms = wp_get_object_terms($post_id, 'product_category');
    
    if (empty($terms) || is_wp_error($terms)) {
        // 如果没有分类，设置为草稿并显示错误
        wp_update_post(array(
            'ID' => $post_id,
            'post_status' => 'draft'
        ));
        
        // 设置管理员通知
        set_transient('angola_product_category_error_' . $post_id, true, 45);
    }
}
add_action('save_post', 'angola_b2b_check_product_category', 10, 3);

/**
 * 显示分类错误提示
 */
function angola_b2b_show_category_error() {
    global $post;
    
    if (!$post || $post->post_type !== 'product') {
        return;
    }
    
    if (get_transient('angola_product_category_error_' . $post->ID)) {
        delete_transient('angola_product_category_error_' . $post->ID);
        ?>
        <div class="notice notice-error is-dismissible">
            <p><strong>⚠️ 发布失败：</strong>产品必须选择至少一个分类才能发布！</p>
            <p>请在右侧的"产品分类"框中选择一个分类，然后再次点击"发布"按钮。</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'angola_b2b_show_category_error');

/**
 * 添加产品编辑帮助文档
 */
function angola_b2b_product_editor_help() {
    $screen = get_current_screen();
    
    if ($screen->post_type !== 'product') {
        return;
    }
    
    $screen->add_help_tab(array(
        'id'      => 'angola_product_category_help',
        'title'   => '产品分类说明',
        'content' => '
            <h3>关于产品分类</h3>
            <p>每个产品都必须归属于一个产品分类。产品分类用于：</p>
            <ul>
                <li>在首页展示对应分类的产品</li>
                <li>在产品列表页进行筛选</li>
                <li>帮助客户快速找到相关产品</li>
            </ul>
            
            <h3>我们的5个主要产品分类</h3>
            <ol>
                <li><strong>建筑工程</strong> - 工程机械、施工设备等</li>
                <li><strong>建筑材料</strong> - 水泥、钢材、装饰材料等</li>
                <li><strong>农机农具</strong> - 拖拉机、收割机、灌溉设备等</li>
                <li><strong>工业设备</strong> - 生产设备、加工机械等</li>
                <li><strong>物流与海关</strong> - 物流服务、清关服务等</li>
            </ol>
            
            <h3>如何选择分类</h3>
            <p>在右侧的"产品分类"框中，勾选最符合该产品的分类即可。</p>
        ',
    ));
}
add_action('load-post.php', 'angola_b2b_product_editor_help');
add_action('load-post-new.php', 'angola_b2b_product_editor_help');

/**
 * 移除产品编辑页面的 Excerpt（摘要）功能
 */
function angola_b2b_remove_product_excerpt() {
    remove_post_type_support('product', 'excerpt');
}
add_action('init', 'angola_b2b_remove_product_excerpt');

