<?php
/**
 * Product Multilingual Tabs Editor
 * 产品多语言Tab切换编辑系统
 * 
 * 根据用户角色自动设置默认语言Tab：
 * - 管理员 (administrator) → 简体中文
 * - 中国产品管理员 (cn_product_manager) → 简体中文
 * - 安哥拉产品编辑 (ao_product_editor) → 葡萄牙语
 * 
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 获取当前用户的默认语言
 */
function angola_b2b_get_user_default_language() {
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    
    // 根据用户角色确定默认语言
    if (in_array('administrator', $user_roles)) {
        return 'zh'; // 管理员 → 简体中文
    } elseif (in_array('cn_product_manager', $user_roles)) {
        return 'zh'; // 中国产品管理员 → 简体中文
    } elseif (in_array('ao_product_editor', $user_roles)) {
        return 'pt'; // 安哥拉产品编辑 → 葡萄牙语
    }
    
    return 'zh'; // 默认简体中文
}

/**
 * 获取当前用户的推荐语言（用于显示"推荐"徽章）
 */
function angola_b2b_get_user_recommended_language() {
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    
    // 安哥拉产品编辑推荐使用葡萄牙语
    if (in_array('ao_product_editor', $user_roles)) {
        return 'pt';
    }
    
    return ''; // 其他用户无推荐语言
}

/**
 * 加载产品编辑器的JavaScript和CSS
 */
function angola_b2b_enqueue_product_editor_assets($hook) {
    // 只在产品编辑页面加载
    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
        return;
    }
    
    // 获取当前post_type
    global $post_type;
    $current_post_type = $post_type;
    
    // 如果global变量未设置，尝试从GET参数获取
    if (empty($current_post_type) && isset($_GET['post_type'])) {
        $current_post_type = sanitize_text_field($_GET['post_type']);
    }
    
    // 如果是编辑页面，从post ID获取
    if (empty($current_post_type) && isset($_GET['post'])) {
        $post_id = intval($_GET['post']);
        $current_post_type = get_post_type($post_id);
    }
    
    if ($current_post_type !== 'product') {
        return;
    }
    
    // 加载JavaScript
    wp_enqueue_script(
        'angola-product-multilingual-tabs',
        get_template_directory_uri() . '/assets/js/product-multilingual-tabs.js',
        array('jquery'),
        '1.1.3', // 版本号更新 - 使用Observer主动拦截中文value设置
        true
    );
    
    // 传递数据到JavaScript
    $current_user = wp_get_current_user();
    wp_localize_script('angola-product-multilingual-tabs', 'angolaProductEditor', array(
        'defaultLang' => angola_b2b_get_user_default_language(),
        'recommendedLang' => angola_b2b_get_user_recommended_language(),
        'userRoles' => $current_user->roles,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('angola_b2b_editor'),
    ));
}
add_action('admin_enqueue_scripts', 'angola_b2b_enqueue_product_editor_assets');

/**
 * 在产品编辑页面添加CSS样式
 */
function angola_b2b_add_product_editor_styles() {
    global $post_type;
    $screen = get_current_screen();
    
    // 只在产品编辑页面显示
    if ($post_type !== 'product' || $screen->id !== 'product') {
        return;
    }
    
    ?>
    <style>
        /* 语言切换Tab样式 */
        #angola-language-tabs {
            display: flex;
            gap: 8px;
            margin: 20px 0;
            padding: 0;
            border-bottom: 2px solid #ddd;
            background: #fff;
        }

        .angola-lang-tab {
            position: relative;
            padding: 12px 24px;
            background: #f0f0f1;
            border: 1px solid #c3c4c7;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #2c3338;
            transition: all 0.2s ease;
            outline: none;
        }

        .angola-lang-tab:hover {
            background: #e8e8e8;
            color: #135e96;
        }

        .angola-lang-tab.active {
            background: #fff;
            color: #135e96;
            border-color: #135e96;
            border-bottom: 2px solid #fff;
            margin-bottom: -2px;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.05);
        }

        .angola-recommended-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 3px 8px;
            background: #FDB913;
            color: #000;
            font-size: 10px;
            font-weight: bold;
            border-radius: 10px;
            vertical-align: middle;
            text-transform: uppercase;
        }

        /* 隐藏非当前语言的字段组 - 使用更强的选择器 */
        .angola-lang-fields {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        .angola-lang-fields.active {
            display: block !important;
            visibility: visible !important;
            height: auto !important;
            overflow: visible !important;
        }

        /* 确保字段组内的元素正确显示 */
        .angola-lang-fields.active .acf-field {
            display: block !important;
            visibility: visible !important;
        }
        
        /* 隐藏非激活组内的字段 */
        .angola-lang-fields:not(.active) .acf-field {
            display: none !important;
            visibility: hidden !important;
        }

        /* 字段间距 */
        .angola-lang-field {
            margin-bottom: 20px;
        }
        
        /* 强制清除所有标题输入框的占位符 */
        #titlewrap #title::-webkit-input-placeholder {
            color: transparent !important;
            opacity: 0 !important;
        }
        #titlewrap #title::-moz-placeholder {
            color: transparent !important;
            opacity: 0 !important;
        }
        #titlewrap #title:-ms-input-placeholder {
            color: transparent !important;
            opacity: 0 !important;
        }
        #titlewrap #title::placeholder {
            color: transparent !important;
            opacity: 0 !important;
        }

        /* 语言标识 */
        .angola-lang-fields::before {
            content: attr(data-lang-label);
            display: block;
            padding: 10px 15px;
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #1d2327;
        }

        .angola-lang-fields[data-lang="zh"]::before {
            content: "📝 简体中文编辑模式";
        }

        .angola-lang-fields[data-lang="zh_tw"]::before {
            content: "📝 繁體中文編輯模式";
        }

        .angola-lang-fields[data-lang="pt"]::before {
            content: "📝 Modo de Edição em Português";
        }

        .angola-lang-fields[data-lang="en"]::before {
            content: "📝 English Editing Mode";
        }

        /* 产品标题提示 */
        .angola-lang-fields[data-lang="en"] #titlediv::before {
            content: "English Product Title";
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #135e96;
            margin-bottom: 8px;
        }

        /* 隐藏ACF字段组标题和容器 */
        .acf-field-group[data-key="group_product_multilingual"] > .acf-label,
        .postbox[id*="acf-group_product_multilingual"] > h2,
        .postbox[id*="acf-group_product_multilingual"] > .postbox-header,
        #acf-group_product_multilingual .postbox-header,
        #acf-group_product_multilingual .hndle {
            display: none !important;
        }
        
        /* 确保多语言信息容器无边框无标题 */
        #acf-group_product_multilingual {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        
        #acf-group_product_multilingual .inside {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* 使用wrapper class来标记字段语言 */
        .angola-field-title-pt,
        .angola-field-content-pt {
            /* 葡萄牙语字段标记 */
        }
        
        .angola-field-title-zh,
        .angola-field-content-zh {
            /* 简体中文字段标记 */
        }
        
        .angola-field-title-zh-tw,
        .angola-field-content-zh-tw {
            /* 繁体中文字段标记 */
        }
    </style>
    <?php
}
add_action('admin_head-post.php', 'angola_b2b_add_product_editor_styles');
add_action('admin_head-post-new.php', 'angola_b2b_add_product_editor_styles');

/**
 * 在产品列表页显示多语言状态
 */
function angola_b2b_product_multilang_status_column($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // 在标题后添加多语言状态列
        if ($key === 'title') {
            $new_columns['multilang_status'] = __('多语言状态', 'angola-b2b');
        }
    }
    
    return $new_columns;
}
add_filter('manage_product_posts_columns', 'angola_b2b_product_multilang_status_column');

/**
 * 显示多语言状态
 */
function angola_b2b_product_multilang_status_content($column, $post_id) {
    if ($column === 'multilang_status') {
        $languages = array(
            'en' => array('label' => 'EN', 'color' => '#2271b1'),
            'pt' => array('label' => 'PT', 'color' => '#00a32a'),
            'zh' => array('label' => '简', 'color' => '#d63638'),
            'zh_tw' => array('label' => '繁', 'color' => '#f0b849'),
        );
        
        $status_html = '<div style="display: flex; gap: 4px;">';
        
        foreach ($languages as $lang => $info) {
            $has_content = false;
            
            // 检查是否有该语言的内容（标题和详情）
            if ($lang === 'en') {
                // 英文使用WordPress原生标题和内容
                $title = get_the_title($post_id);
                $content = get_post_field('post_content', $post_id);
                $has_content = !empty($title) || !empty($content);
            } else {
                // 其他语言检查ACF字段（标题和内容）
                $title = get_field('title_' . $lang, $post_id);
                $content = get_field('content_' . $lang, $post_id);
                $has_content = !empty($title) || !empty($content);
            }
            
            $opacity = $has_content ? '1' : '0.3';
            $icon = $has_content ? '✓' : '○';
            
            $status_html .= sprintf(
                '<span style="display: inline-block; padding: 2px 6px; background: %s; color: white; border-radius: 3px; font-size: 11px; opacity: %s;" title="%s">%s %s</span>',
                $info['color'],
                $opacity,
                $has_content ? $info['label'] . ' 已填写' : $info['label'] . ' 未填写',
                $icon,
                $info['label']
            );
        }
        
        $status_html .= '</div>';
        
        echo $status_html;
    }
}
add_action('manage_product_posts_custom_column', 'angola_b2b_product_multilang_status_content', 10, 2);
