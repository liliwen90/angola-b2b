<?php
/**
 * ACF Field Groups Registration
 * This file will be populated with ACF field groups after ACF Pro is installed
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set ACF JSON save and load points
 */
// Save JSON to theme folder
add_filter('acf/settings/save_json', 'angola_b2b_acf_json_save_point');
function angola_b2b_acf_json_save_point($path) {
    return ANGOLA_B2B_THEME_DIR . '/acf-json';
}

// Load JSON from theme folder
add_filter('acf/settings/load_json', 'angola_b2b_acf_json_load_point');
function angola_b2b_acf_json_load_point($paths) {
    // Remove original path
    unset($paths[0]);
    
    // Append new path
    $paths[] = ANGOLA_B2B_THEME_DIR . '/acf-json';
    
    return $paths;
}

/**
 * 防止空的JSON文件覆盖PHP代码中文的字段：
 * 如果JSON文件中文的字段为空，则使用PHP代码中文的字段
 */
add_filter('acf/load_field_group', 'angola_b2b_prevent_empty_json_override', 10, 1);
function angola_b2b_prevent_empty_json_override($field_group) {
    // 只处理首页设置字段组
    if (isset($field_group['key']) && $field_group['key'] === 'group_homepage_settings') {
        // 如果从JSON加载的字段组字段为空，返回null让PHP代码注册
        if (isset($field_group['fields']) && empty($field_group['fields'])) {
            return null;
        }
    }
    return $field_group;
}

/**
 * 确保Tab字段的label在加载时被正确设：
 * 这个过滤器在字段被ACF加载时运行，确保Tab字段的label不为：
 */
add_filter('acf/load_field', 'angola_b2b_fix_tab_field_label', 20, 1);
function angola_b2b_fix_tab_field_label($field) {
    // 只处理Tab字段
    if (isset($field['type']) && $field['type'] === 'tab') {
        // 如果label为空或不存在，尝试从key推断
        if (empty($field['label']) || !isset($field['label'])) {
            if (isset($field['key'])) {
                $key_parts = explode('_', $field['key']);
                $last_part = end($key_parts);
                $label_map = array(
                    'site_info' => '站点信息',
                    'hero' => 'Hero区域',
                    'contact' => '联系信息',
                    'social' => '社交媒体',
                    'about' => '关于我们',
                    'services' => '我们的服务务',
                    'careers' => '招聘',
                );
                if (isset($label_map[$last_part])) {
                    $field['label'] = $label_map[$last_part];
                } else {
                    $field['label'] = ucfirst($last_part);
                }
            } else {
                $field['label'] = 'Tab';
            }
        }
        // 确保placement存在
        if (!isset($field['placement'])) {
            $field['placement'] = 'left';
        }
        // 确保endpoint存在
        if (!isset($field['endpoint'])) {
            $field['endpoint'] = 0;
        }
    }
    return $field;
}

/**
 * Register ACF options pages
 * ACF免费版不支持Options Page功能，已改用专门的WordPress页面（ID: 45）存储首页设：
 * 其他设置（社交媒体、联系信息）将来可以用类似方式创建独立页：
 */
// function angola_b2b_register_acf_options_pages() {
//     // ACF PRO required for Options Pages
//     // Using dedicated WordPress page (ID: 45) as alternative
// }
// add_action('acf/init', 'angola_b2b_register_acf_options_pages', 5);

/**
 * Hide ACF menu in production
 * 暂时注释掉，开发阶段需要访问ACF菜单
 */
// if (!defined('WP_DEBUG') || WP_DEBUG === false) {
//     add_filter('acf/settings/show_admin', '__return_false');
// }

/**
 * ACF field groups will be added here after ACF Pro installation
 * Field groups will be exported from ACF UI and added programmatically
 * 
 * Expected field groups:
 * - Product Fields (gallery, specifications, 360 images, etc.)
 * - Homepage Hero Section
 * - Core Advantages
 * - Social Media Links
 * - Contact Information
 */

/**
 * Register Homepage Settings Fields
 * 注册首页设置字段
 */
function angola_b2b_register_homepage_settings_fields() {
    // 检查ACF是否可用
    if (!function_exists('acf_add_local_field_group')) {
        // 如果是管理员，显示错误信：
        if (current_user_can('manage_options') && is_admin()) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p><strong>ACF插件未安装或未激：/strong>：首页设置字段无法注册。请确保已安装并激活Advanced Custom Fields插件：/p></div>';
            });
        }
        return;
    }

    // 确保页面ID 45存在
    $page = get_post(45);
    if (!$page || $page->post_type !== 'page') {
        // 如果是管理员，显示警：
        if (current_user_can('manage_options') && is_admin()) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-warning"><p><strong>页面ID 45不存：/strong>：首页设置页面不存在。请运行主题初始化或手动创建该页面：/p></div>';
            });
        }
    }
    
    // 检查并删除可能存在的空JSON文件
    $json_file = ANGOLA_B2B_THEME_DIR . '/acf-json/group_homepage_settings.json';
    if (file_exists($json_file)) {
        $json_content = @file_get_contents($json_file);
        if ($json_content !== false) {
            $json_data = json_decode($json_content, true);
            // 如果JSON文件中文的fields为空数组，删除它
            if (isset($json_data['fields']) && empty($json_data['fields'])) {
                @unlink($json_file);
            }
        }
    }
    
    // 清除可能存在的字段组缓存
    if (function_exists('acf_get_store')) {
        acf_get_store('field-groups')->remove('group_homepage_settings');
    }
    wp_cache_flush();
    
    // 检查是否已经有完整字段组（快速检查，避免重复注册：
    $existing_group = acf_get_field_group('group_homepage_settings');
    if ($existing_group) {
        $existing_fields = acf_get_fields($existing_group);
        if (!empty($existing_fields) && count($existing_fields) > 10) {
            // 如果字段组已经有足够多的字段，就不需要重复注：
            return true;
        }
    }
    
    // 准备字段组数：
    // 使用与渐进式测试相同的方法：逐个添加到数组，避免null值问：
    $fields = array();
            
            // Tab: 站点信息
    $fields[] = array(
                'key' => 'field_tab_site_info',
                'label' => '站点信息',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_site_logo',
                'label' => '网站Logo',
                'name' => 'site_logo',
                'type' => 'image',
                'instructions' => '上传网站Logo图片（建议尺寸：200x60px，透明背景PNG格式）',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
    );
    $fields[] = array(
                'key' => 'field_site_title',
                'label' => '网站标题',
                'name' => 'site_title',
                'type' => 'text',
                'instructions' => '网站名称，显示在Header和浏览器标签页',
                'placeholder' => 'Unibro',
    );
    $fields[] = array(
                'key' => 'field_site_tagline',
                'label' => '网站副标题',
                'name' => 'site_tagline',
                'type' => 'text',
                'instructions' => '网站描述或口号',
                'placeholder' => 'Your trusted partner for quality products',
    );
            
            // Tab: Hero区域
    $fields[] = array(
                'key' => 'field_tab_hero',
                'label' => 'Hero区域',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_hero_background_image',
                'label' => 'Hero背景图片/视频',
                'name' => 'hero_background_image',
                'type' => 'file',
                'instructions' => '首页Hero区域的背景图片或视频（支持jpg/png/gif/webp/mp4/webm等格式，建议尺寸：1920x800px）',
                'return_format' => 'array',
                'library' => 'all',
                'mime_types' => 'jpg,jpeg,png,gif,webp,svg,mp4,webm,ogg,mov',
    );
    $fields[] = array(
                'key' => 'field_hero_title',
                'label' => 'Hero标题',
                'name' => 'hero_title',
                'type' => 'text',
                'instructions' => '首页Hero区域的主标题',
                'placeholder' => '输入Hero主标题',
    );
    $fields[] = array(
                'key' => 'field_hero_subtitle',
                'label' => 'Hero副标题/描述',
                'name' => 'hero_subtitle',
                'type' => 'textarea',
                'instructions' => '首页Hero区域的副标题或描述文字（显示在标题下方，较小字体）',
                'rows' => 3,
                'new_lines' => 'wpautop',
    );
            
            // Tab: 联系信息
    $fields[] = array(
                'key' => 'field_tab_contact',
                'label' => '联系信息',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_contact_email',
                'label' => '联系邮箱',
                'name' => 'contact_email',
                'type' => 'email',
                'instructions' => '网站联系邮箱（显示在Header和Footer）',
                'placeholder' => 'info@example.com',
    );
    $fields[] = array(
                'key' => 'field_contact_phone',
                'label' => '联系电话',
                'name' => 'contact_phone',
                'type' => 'text',
                'instructions' => '网站联系电话（显示在Header和Footer）',
                'placeholder' => '+1 234 567 8900',
    );
            
            // Tab: 社交媒体
    $fields[] = array(
                'key' => 'field_tab_social',
                'label' => '社交媒体',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_social_facebook',
                'label' => 'Facebook链接',
                'name' => 'social_facebook',
                'type' => 'url',
                'instructions' => 'Facebook主页链接',
                'placeholder' => 'https://facebook.com/your-page',
    );
    $fields[] = array(
                'key' => 'field_social_facebook_show',
                'label' => '显示Facebook',
                'name' => 'social_facebook_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示Facebook图标',
                'ui' => 1,
                'default_value' => 0,
    );
    $fields[] = array(
                'key' => 'field_social_twitter',
                'label' => 'Twitter链接',
                'name' => 'social_twitter',
                'type' => 'url',
                'instructions' => 'Twitter主页链接',
                'default_value' => '',
                'placeholder' => 'https://twitter.com/your-account',
    );
    $fields[] = array(
                'key' => 'field_social_twitter_show',
                'label' => '显示Twitter',
                'name' => 'social_twitter_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示Twitter图标',
                'ui' => 1,
                'default_value' => 0,
    );
    $fields[] = array(
                'key' => 'field_social_linkedin',
                'label' => 'LinkedIn链接',
                'name' => 'social_linkedin',
                'type' => 'url',
                'instructions' => 'LinkedIn公司主页链接',
                'default_value' => '',
                'placeholder' => 'https://linkedin.com/company/your-company',
    );
    $fields[] = array(
                'key' => 'field_social_linkedin_show',
                'label' => '显示LinkedIn',
                'name' => 'social_linkedin_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示LinkedIn图标',
                'ui' => 1,
                'default_value' => 0,
    );
    $fields[] = array(
                'key' => 'field_social_whatsapp',
                'label' => 'WhatsApp号码',
                'name' => 'social_whatsapp',
                'type' => 'text',
                'instructions' => 'WhatsApp联系号码（国际格式，如：+8615319996326）',
                'default_value' => '',
                'placeholder' => '+8615319996326',
    );
    $fields[] = array(
                'key' => 'field_social_whatsapp_show',
                'label' => '显示WhatsApp',
                'name' => 'social_whatsapp_show',
                'type' => 'true_false',
                'instructions' => '勾选后在页脚和Contact下拉菜单中显示WhatsApp图标',
                'ui' => 1,
                'default_value' => 0,
    );
            
            // Tab: 关于我们
    $fields[] = array(
                'key' => 'field_tab_about',
                'label' => '关于我们',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_about_us_content',
                'label' => 'English',
                'name' => 'footer_about_us_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_about_us_content_pt',
                'label' => 'Português',
                'name' => 'footer_about_us_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_about_us_content_zh',
                'label' => '简体中文',
                'name' => 'footer_about_us_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_about_us_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_about_us_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"关于我们"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
            
            // Tab: 我们的服务
    $fields[] = array(
                'key' => 'field_tab_services',
                'label' => '我们的服务',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_services_content',
                'label' => 'English',
                'name' => 'footer_services_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务?页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_services_content_pt',
                'label' => 'Português',
                'name' => 'footer_services_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务?页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_services_content_zh',
                'label' => '简体中文',
                'name' => 'footer_services_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_services_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_services_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"我们的服务"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
            
            // Tab: 招聘
    $fields[] = array(
                'key' => 'field_tab_careers',
                'label' => '招聘',
                'name' => '',
                'type' => 'tab',
                'placement' => 'top',
        'endpoint' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_careers_content',
                'label' => 'English',
                'name' => 'footer_careers_content',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的英文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_careers_content_pt',
                'label' => 'Português',
                'name' => 'footer_careers_content_pt',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的葡萄牙语内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_careers_content_zh',
                'label' => '简体中文',
                'name' => 'footer_careers_content_zh',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的简体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    $fields[] = array(
                'key' => 'field_footer_careers_content_zh_tw',
                'label' => '繁體中文',
                'name' => 'footer_careers_content_zh_tw',
                'type' => 'wysiwyg',
                'instructions' => '编辑"招聘"页面的繁体中文内容',
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
                'delay' => 0,
    );
    
    // 清理字段数组，移除所有null值，并添加ACF必需的属性
    $cleaned_fields = array();
    $field_order = 0;
    foreach ($fields as $field) {
        if (!is_array($field)) {
            continue;
        }
        // 清理字段中文的null：
        $cleaned_field = array();
        // 先保存原始label（特别是Tab字段需要）
        $original_label = null;
        $is_tab_field = false;
        foreach ($field as $key => $value) {
            if ($value !== null) {
                $cleaned_field[$key] = $value;
                if ($key === 'label') {
                    $original_label = $value;
                }
                if ($key === 'type' && $value === 'tab') {
                    $is_tab_field = true;
                }
            }
        }
        // 确保必需属性存：
        if (!isset($cleaned_field['key']) || empty($cleaned_field['key'])) {
            continue; // 跳过没有key的字：
        }
        if (!isset($cleaned_field['type'])) {
            $cleaned_field['type'] = 'text'; // 默认类型
        }
        // 对于Tab字段，label是必需的，必须保留原始：
        if ($cleaned_field['type'] === 'tab' || $is_tab_field) {
            // Tab字段的label绝对不能为空，优先使用原始：
            // 如果label不存在或为空，使用原始值或从key推断
            if (!isset($cleaned_field['label']) || $cleaned_field['label'] === '' || empty($cleaned_field['label'])) {
                if (!empty($original_label)) {
                    $cleaned_field['label'] = $original_label;
                } else {
                    // 如果原始值也为空，尝试从key推断
                    if (isset($cleaned_field['key'])) {
                        $key_parts = explode('_', $cleaned_field['key']);
                        $last_part = end($key_parts);
                        // 根据key推断中文标签
                        $label_map = array(
                            'site_info' => '站点信息',
                            'hero' => 'Hero区域',
                            'contact' => '联系信息',
                            'social' => '社交媒体',
                            'about' => '关于我们',
                            'services' => '我们的服务',
                            'careers' => '招聘',
                        );
                        if (isset($label_map[$last_part])) {
                            $cleaned_field['label'] = $label_map[$last_part];
                        } else {
                            $cleaned_field['label'] = ucfirst($last_part);
                        }
                    } else {
                        $cleaned_field['label'] = 'Tab';
                    }
                }
            }
            // 确保Tab字段的label不是空字符串
            if (empty($cleaned_field['label']) || $cleaned_field['label'] === '') {
                $cleaned_field['label'] = 'Tab'; // 最后的保底
            }
        } else {
            // 非Tab字段，label可以为空
            if (!isset($cleaned_field['label'])) {
                $cleaned_field['label'] = ''; // 空标：
            }
        }
        if (!isset($cleaned_field['name'])) {
            $cleaned_field['name'] = ''; // 空名称（tab字段允许：
        }
        
        // 添加ACF 6.x必需的属性（完全按照成功字段组的格式：
        $cleaned_field['ID'] = 0; // ACF内容部ID
        $cleaned_field['parent'] = 'group_homepage_settings'; // 字段组key
        $cleaned_field['menu_order'] = $field_order++; // 字段顺序
        $cleaned_field['prefix'] = 'acf'; // ACF前缀
        $cleaned_field['aria-label'] = ''; // 无障碍标：
        $cleaned_field['value'] = false; // 字段：
        $cleaned_field['_name'] = ''; // 内容部名称
        $cleaned_field['_valid'] = 1; // 验证标志
        $cleaned_field['selected'] = 0; // 选中文状：
        
        // 添加其他可能需要的属性（使用默认值）
        if (!isset($cleaned_field['instructions'])) {
            $cleaned_field['instructions'] = '';
        }
        if (!isset($cleaned_field['required'])) {
            $cleaned_field['required'] = 0;
        }
        if (!isset($cleaned_field['conditional_logic'])) {
            $cleaned_field['conditional_logic'] = false; // 注意：应该是false，不：
        }
        if (!isset($cleaned_field['id'])) {
            $cleaned_field['id'] = '';
        }
        if (!isset($cleaned_field['class'])) {
            $cleaned_field['class'] = '';
        }
        if (!isset($cleaned_field['wrapper'])) {
            $cleaned_field['wrapper'] = array(
                'width' => '',
                'class' => '',
                'id' => '',
            );
        }
        
        // 根据字段类型添加特定属性
        if ($cleaned_field['type'] === 'text' || $cleaned_field['type'] === 'email' || $cleaned_field['type'] === 'url') {
            if (!isset($cleaned_field['prepend'])) {
                $cleaned_field['prepend'] = '';
            }
            if (!isset($cleaned_field['append'])) {
                $cleaned_field['append'] = '';
            }
            if (!isset($cleaned_field['maxlength'])) {
                $cleaned_field['maxlength'] = '';
            }
        }
        
        if ($cleaned_field['type'] === 'image') {
            if (!isset($cleaned_field['mime_types'])) {
                $cleaned_field['mime_types'] = '';
            }
            if (!isset($cleaned_field['min_width'])) {
                $cleaned_field['min_width'] = '';
            }
            if (!isset($cleaned_field['min_height'])) {
                $cleaned_field['min_height'] = '';
            }
            if (!isset($cleaned_field['min_size'])) {
                $cleaned_field['min_size'] = '';
            }
            if (!isset($cleaned_field['max_width'])) {
                $cleaned_field['max_width'] = '';
            }
            if (!isset($cleaned_field['max_height'])) {
                $cleaned_field['max_height'] = '';
            }
            if (!isset($cleaned_field['max_size'])) {
                $cleaned_field['max_size'] = '';
            }
        }
        
        if ($cleaned_field['type'] === 'textarea') {
            if (!isset($cleaned_field['maxlength'])) {
                $cleaned_field['maxlength'] = '';
            }
            if (!isset($cleaned_field['rows'])) {
                $cleaned_field['rows'] = 4;
            }
        }
        
        if ($cleaned_field['type'] === 'wysiwyg') {
            if (!isset($cleaned_field['tabs'])) {
                $cleaned_field['tabs'] = 'all';
            }
            if (!isset($cleaned_field['toolbar'])) {
                $cleaned_field['toolbar'] = 'full';
            }
            if (!isset($cleaned_field['media_upload'])) {
                $cleaned_field['media_upload'] = 1;
            }
            if (!isset($cleaned_field['delay'])) {
                $cleaned_field['delay'] = 0;
            }
        }
        
        if ($cleaned_field['type'] === 'true_false') {
            if (!isset($cleaned_field['ui'])) {
                $cleaned_field['ui'] = 0;
            }
            if (!isset($cleaned_field['ui_on_text'])) {
                $cleaned_field['ui_on_text'] = '';
            }
            if (!isset($cleaned_field['ui_off_text'])) {
                $cleaned_field['ui_off_text'] = '';
            }
            if (!isset($cleaned_field['message'])) {
                $cleaned_field['message'] = '';
            }
        }
        
        if ($cleaned_field['type'] === 'tab') {
            // Tab字段必须保留label，这是显示在Tab按钮上的文字
            // 再次确保label不为空（防止在后续处理中文被覆盖）
            if (empty($cleaned_field['label']) || $cleaned_field['label'] === '') {
                if (!empty($original_label)) {
                    $cleaned_field['label'] = $original_label;
                } else if (isset($cleaned_field['key'])) {
                    $key_parts = explode('_', $cleaned_field['key']);
                    $last_part = end($key_parts);
                    // 根据key推断中文标签
                    $label_map = array(
                        'site_info' => '站点信息',
                        'hero' => 'Hero区域',
                        'contact' => '联系信息',
                        'social' => '社交媒体',
                        'about' => '关于我们',
                        'services' => '我们的服务',
                        'careers' => '招聘',
                    );
                    if (isset($label_map[$last_part])) {
                        $cleaned_field['label'] = $label_map[$last_part];
                    } else {
                        $cleaned_field['label'] = ucfirst($last_part);
                    }
                } else {
                    $cleaned_field['label'] = 'Tab';
                }
            }
            // 确保Tab字段的必需属性存：
            if (!isset($cleaned_field['placement'])) {
                $cleaned_field['placement'] = 'left'; // 默认左侧
            }
            if (!isset($cleaned_field['endpoint'])) {
                $cleaned_field['endpoint'] = 0;
            }
            if (!isset($cleaned_field['instructions'])) {
                $cleaned_field['instructions'] = '';
            }
            if (!isset($cleaned_field['required'])) {
                $cleaned_field['required'] = 0;
            }
            if (!isset($cleaned_field['conditional_logic'])) {
                $cleaned_field['conditional_logic'] = false;
            }
        }
        
        // 对于Tab字段，最后再次确保label不为：
        if ($cleaned_field['type'] === 'tab') {
            if (empty($cleaned_field['label']) || $cleaned_field['label'] === '') {
                // 从key推断
                if (isset($cleaned_field['key'])) {
                    $key_parts = explode('_', $cleaned_field['key']);
                    $last_part = end($key_parts);
                    $label_map = array(
                        'site_info' => '站点信息',
                        'hero' => 'Hero区域',
                        'contact' => '联系信息',
                        'social' => '社交媒体',
                        'about' => '关于我们',
                        'services' => '我们的服务',
                        'careers' => '招聘',
                    );
                    if (isset($label_map[$last_part])) {
                        $cleaned_field['label'] = $label_map[$last_part];
                    } else {
                        $cleaned_field['label'] = ucfirst($last_part);
                    }
                } else {
                    $cleaned_field['label'] = 'Tab';
                }
            }
        }
        
        $cleaned_fields[] = $cleaned_field;
    }
    
    // 构建字段组数据（完全按照成功字段组的格式：
    $field_group_data = array(
        'ID' => 0, // ACF内容部ID
        'key' => 'group_homepage_settings',
        'title' => '首页设置',
        'fields' => $cleaned_fields,
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45', // 首页设置页面ID
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => array(),
        'active' => true,
        'description' => '',
        'show_in_rest' => false,
        'display_title' => '', // 显示标题
        'local' => 'php', // 标识这是PHP注册的字段组
        '_valid' => true, // 验证标志
    );
    
    // 验证字段组数：
    if (empty($field_group_data['fields']) || !is_array($field_group_data['fields'])) {
        if (current_user_can('manage_options')) {
            error_log('ACF字段：首页设置"：字段数组为空或不是数组');
}
        return false;
    }
    
    // 确保location规则格式正确
    if (!isset($field_group_data['location']) || !is_array($field_group_data['location']) || empty($field_group_data['location'])) {
        $field_group_data['location'] = array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '45',
                ),
            ),
        );
    }
    
    // 确保location规则中文的每个规则都有必需的属性
    foreach ($field_group_data['location'] as &$location_group) {
        if (!is_array($location_group)) {
            continue;
        }
        foreach ($location_group as &$rule) {
            if (!is_array($rule)) {
                continue;
            }
            if (!isset($rule['param'])) {
                $rule['param'] = 'page';
            }
            if (!isset($rule['operator'])) {
                $rule['operator'] = '==';
            }
            if (!isset($rule['value'])) {
                $rule['value'] = '45';
            }
        }
    }
    unset($location_group, $rule);
    
    // 最后清理：确保字段组数据中文没有null：
    if (!function_exists('angola_b2b_clean_array_recursive')) {
        function angola_b2b_clean_array_recursive($array) {
            if (!is_array($array)) {
                return $array;
            }
            $cleaned = array();
            foreach ($array as $key => $value) {
                if ($value !== null) {
                    if (is_array($value)) {
                        $cleaned[$key] = angola_b2b_clean_array_recursive($value);
                    } else {
                        $cleaned[$key] = $value;
                    }
                }
            }
            return $cleaned;
        }
    }
    
    $field_group_data = angola_b2b_clean_array_recursive($field_group_data);
    
    // 尝试注册字段组（直接注册，不进行复杂验证：
    try {
        // 方法1：先尝试不使用过滤器直接注册
        // 如果失败，再尝试使用过滤：
        
        // 移除可能引起问题的属性
        unset($field_group_data['modified']);
        unset($field_group_data['local']);
        
        // 直接注册，让ACF自己处理验证
        $result = acf_add_local_field_group($field_group_data);
        
        // 如果失败，尝试使用过滤器
        if (!$result) {
            // 重新添加属性
            $field_group_data['modified'] = time();
            $field_group_data['local'] = 'php';
            
            // 使用ACF的过滤器来验证和修复数据
            $field_group_data = apply_filters('acf/validate_field_group', $field_group_data);
            
            // 再次尝试注册
            $result = acf_add_local_field_group($field_group_data);
        }
        
        // 调试信息
        if (current_user_can('manage_options')) {
            if ($result) {
                error_log('ACF字段：首页设置"注册成功');
            } else {
                error_log('ACF字段：首页设置"注册失败 - acf_add_local_field_group返回false');
                // 尝试获取错误信息
                $group = acf_get_field_group('group_homepage_settings');
                if (!$group) {
                    error_log('ACF字段：首页设置"：注册后无法获取字段');
                } else {
                    error_log('ACF字段：首页设置"：字段组存在但可能有问题 - ' . print_r($group, true));
                }
            }
        }
        
        return $result;
    } catch (Exception $e) {
        if (current_user_can('manage_options')) {
            error_log('ACF字段：首页设置"注册异常：' . $e->getMessage());
        }
        return false;
    } catch (Error $e) {
        if (current_user_can('manage_options')) {
            error_log('ACF字段：首页设置"注册错误：' . $e->getMessage());
        }
        return false;
    }
}

// 使用多种钩子确保字段组能够注：
// 方法1：在plugins_loaded时检查ACF是否已加：
add_action('plugins_loaded', function() {
    if (function_exists('acf_add_local_field_group')) {
        // ACF已加载，直接注册
        angola_b2b_register_homepage_settings_fields();
    }
}, 5);

// 方法2：在acf/init时注册（备用方案：
add_action('acf/init', 'angola_b2b_register_homepage_settings_fields', 20);

/**
 * Register Product Multilingual Fields
 * 已禁：- 使用 product-fields-simple.php 中文的新版：
 */
// function angola_b2b_register_product_multilingual_fields() {
//     // 已迁移到 inc/product-fields-simple.php
// }
// add_action('acf/init', 'angola_b2b_register_product_multilingual_fields');

/**
 * Register Product Stock and SKU Fields
 * 注册产品库存和SKU字段
 * 已禁：- 改为在富文本编辑器中文编写所有产品信：
 */
// function angola_b2b_register_product_stock_fields() {
//     if (!function_exists('acf_add_local_field_group')) {
//         return;
//     }
//
//     acf_add_local_field_group(array(
//         'key' => 'group_product_stock_info',
//         'title' => '产品库存和SKU信息',
//         'fields' => array(
//             array(
//                 'key' => 'field_product_sku',
//                 'label' => 'SKU编号',
//                 'name' => 'product_sku',
//                 'type' => 'text',
//                 'instructions' => '产品SKU编号（库存单位）',
//                 'required' => 0,
//                 'placeholder' => 'SKU-0001',
//             ),
//             array(
//                 'key' => 'field_product_in_stock',
//                 'label' => '是否为库存商：,
//                 'name' => 'product_in_stock',
//                 'type' => 'true_false',
//                 'instructions' => '勾选后将在首页"热门库存"区域显示',
//                 'ui' => 1,
//                 'default_value' => 0,
//             ),
//             array(
//                 'key' => 'field_product_stock_quantity',
//                 'label' => '库存数量',
//                 'name' => 'product_stock_quantity',
//                 'type' => 'number',
//                 'instructions' => '留空则不显示存',
//                 'conditional_logic' => array(
//                     array(
//                         array(
//                             'field' => 'field_product_in_stock',
//                             'operator' => '==',
//                             'value' => '1',
//                         ),
//                     ),
//                 ),
//                 'min' => 0,
//                 'step' => 1,
//             ),
//             array(
//                 'key' => 'field_product_stock_badge_text',
//                 'label' => '库存徽章文字',
//                 'name' => 'product_stock_badge_text',
//                 'type' => 'text',
//                 'instructions' => '显示在产品卡片上的徽章字',
//                 'default_value' => '现货',
//                 'conditional_logic' => array(
//                     array(
//                         array(
//                             'field' => 'field_product_in_stock',
//                             'operator' => '==',
//                             'value' => '1',
//                         ),
//                     ),
//                 ),
//             ),
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'product',
//                 ),
//             ),
//         ),
//         'menu_order' => 5,
//         'position' => 'side',
//         'style' => 'default',
//     ));
// }
// add_action('acf/init', 'angola_b2b_register_product_stock_fields');

/**
 * Register Product Category Hero Fields
 * 为产品分类添加Hero区域字段
 */
function angola_b2b_register_category_hero_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_category_hero',
        'title' => '分类Hero设置',
        'fields' => array(
            array(
                'key' => 'field_category_hero_image',
                'label' => 'Hero背景图片',
                'name' => 'category_hero_image',
                'type' => 'image',
                'instructions' => '分类归档页Hero区域的背景图片（建议寸：920x800px）',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_category_nav_image',
                'label' => '导航菜单图片',
                'name' => 'category_nav_image',
                'type' => 'image',
                'instructions' => '导航菜单下拉面板中显示的图片（建议尺寸：400x300px）',
                'return_format' => 'url',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'product_category',
                ),
            ),
        ),
        'menu_order' => 10,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_category_hero_fields');

/**
 * Register Product Hero Fields
 * 为产品添加Hero区域字段
 * 已禁：- 改为在富文本编辑器中文编写所有产品信：
 */
// function angola_b2b_register_product_hero_fields() {
//     if (!function_exists('acf_add_local_field_group')) {
//         return;
//     }
//
//     acf_add_local_field_group(array(
//         'key' => 'group_product_hero',
//         'title' => '产品Hero设置',
//         'fields' => array(
//             array(
//                 'key' => 'field_product_hero_image',
//                 'label' => 'Hero背景图片',
//                 'name' => 'product_hero_image',
//                 'type' => 'image',
//                 'instructions' => '产品详情页Hero区域的背景图片（建议寸：920x800px）。如果未设置，将使用产品特色片',
//                 'return_format' => 'array',
//                 'preview_size' => 'medium',
//                 'library' => 'all',
//             ),
//         ),
//         'location' => array(
//             array(
//                 array(
//                     'param' => 'post_type',
//                     'operator' => '==',
//                     'value' => 'product',
//                 ),
//             ),
//         ),
//         'menu_order' => 15,
//         'position' => 'normal',
//         'style' => 'default',
//     ));
// }
// add_action('acf/init', 'angola_b2b_register_product_hero_fields');

/**
 * Register Service (解决方案) ACF Fields
 */
function angola_b2b_register_service_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_service_details',
        'title' => '解决方案详细信息',
        'fields' => array(
            array(
                'key' => 'field_service_icon',
                'label' => '图标SVG代码',
                'name' => 'service_icon',
                'type' => 'textarea',
                'instructions' => '粘贴SVG图标代码（可选）。留空则不显示图标',
                'rows' => 4,
            ),
            array(
                'key' => 'field_service_link',
                'label' => '链接地址',
                'name' => 'service_link',
                'type' => 'url',
                'instructions' => '点击该解决方案后跳转的链接（可选）。留空则不可点击',
            ),
            array(
                'key' => 'field_service_features',
                'label' => '特性列表',
                'name' => 'service_features',
                'type' => 'repeater',
                'instructions' => '添加该解决方案的关键特性（显示为列表）',
                'button_label' => '添加特性',
                'sub_fields' => array(
                    array(
                        'key' => 'field_service_feature_text',
                        'label' => '特性文字',
                        'name' => 'feature_text',
                        'type' => 'text',
                    ),
                ),
                'min' => 0,
                'max' => 5,
                'layout' => 'table',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'service',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_service_fields');

/**
 * Register Industry (行业) ACF Fields
 */
function angola_b2b_register_industry_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_industry_details',
        'title' => '行业详细信息',
        'fields' => array(
            array(
                'key' => 'field_industry_link',
                'label' => '链接地址',
                'name' => 'industry_link',
                'type' => 'url',
                'instructions' => '点击该行业后跳转的链接（可选）。留空则不可点击',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'industry',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
    ));
}
add_action('acf/init', 'angola_b2b_register_industry_fields');

