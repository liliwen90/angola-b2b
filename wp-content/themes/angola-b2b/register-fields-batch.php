<?php
/**
 * 分批注册首页设置字段组
 * 使用渐进式方法，分多次注册，避免一次性注册失败
 */

// 加载WordPress
$wp_load_paths = array(
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',
    '/www/wwwroot/www.unibroint.com/wp-load.php',
    dirname(__FILE__) . '/../../../../wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('无法找到 wp-load.php 文件。');
}

// 检查权限
if (!current_user_can('manage_options')) {
    wp_die('您没有权限访问此页面。');
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>分批注册首页设置字段组</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1d2327; color: #f0f0f1; }
        .success { color: #00a32a; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .error { color: #f0b849; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        .info { color: #72aee6; padding: 10px; background: #0a0a0a; margin: 10px 0; }
        pre { background: #0a0a0a; padding: 15px; overflow-x: auto; font-size: 11px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #3c434a; }
        button { padding: 10px 20px; background: #2271b1; color: white; border: none; cursor: pointer; font-size: 16px; margin: 5px; }
        button:hover { background: #135e96; }
        .batch { margin: 10px 0; padding: 10px; background: #2c3338; border-left: 3px solid #72aee6; }
    </style>
</head>
<body>
    <h1>分批注册首页设置字段组</h1>
    <p>使用渐进式方法，分多次注册，每次添加一部分字段</p>
    
    <?php
    if (!function_exists('acf_add_local_field_group')) {
        echo '<div class="error">ACF插件未安装或未激活</div>';
        exit;
    }
    
    // 定义所有字段（分批）
    $all_fields = array(
        // 批次1: 站点信息
        array(
            'key' => 'field_tab_site_info',
            'label' => '站点信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_site_logo',
            'label' => '网站Logo',
            'name' => 'site_logo',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_site_title',
            'label' => '网站标题',
            'name' => 'site_title',
            'type' => 'text',
        ),
        array(
            'key' => 'field_site_tagline',
            'label' => '网站副标题',
            'name' => 'site_tagline',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => 'Your trusted partner for quality products',
        ),
        
        // 批次2: Hero区域
        array(
            'key' => 'field_tab_hero',
            'label' => 'Hero区域',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_hero_background_image',
            'label' => 'Hero背景图片',
            'name' => 'hero_background_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ),
        array(
            'key' => 'field_hero_title',
            'label' => 'Hero标题',
            'name' => 'hero_title',
            'type' => 'text',
        ),
        array(
            'key' => 'field_hero_subtitle',
            'label' => 'Hero副标题/描述',
            'name' => 'hero_subtitle',
            'type' => 'textarea',
            'rows' => 3,
            'new_lines' => 'wpautop',
        ),
        
        // 批次3: 联系信息
        array(
            'key' => 'field_tab_contact',
            'label' => '联系信息',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_contact_email',
            'label' => '联系邮箱',
            'name' => 'contact_email',
            'type' => 'email',
            'default_value' => 'info@example.com',
            'placeholder' => 'info@example.com',
        ),
        array(
            'key' => 'field_contact_phone',
            'label' => '联系电话',
            'name' => 'contact_phone',
            'type' => 'text',
            'default_value' => '+1 234 567 8900',
            'placeholder' => '+1 234 567 8900',
        ),
        
        // 批次4: 社交媒体
        array(
            'key' => 'field_tab_social',
            'label' => '社交媒体',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_social_facebook',
            'label' => 'Facebook链接',
            'name' => 'social_facebook',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://facebook.com/your-page',
        ),
        array(
            'key' => 'field_social_facebook_show',
            'label' => '显示Facebook',
            'name' => 'social_facebook_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ),
        array(
            'key' => 'field_social_twitter',
            'label' => 'Twitter链接',
            'name' => 'social_twitter',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://twitter.com/your-account',
        ),
        array(
            'key' => 'field_social_twitter_show',
            'label' => '显示Twitter',
            'name' => 'social_twitter_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ),
        array(
            'key' => 'field_social_linkedin',
            'label' => 'LinkedIn链接',
            'name' => 'social_linkedin',
            'type' => 'url',
            'default_value' => '',
            'placeholder' => 'https://linkedin.com/company/your-company',
        ),
        array(
            'key' => 'field_social_linkedin_show',
            'label' => '显示LinkedIn',
            'name' => 'social_linkedin_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ),
        array(
            'key' => 'field_social_whatsapp',
            'label' => 'WhatsApp号码',
            'name' => 'social_whatsapp',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '+8615319996326',
        ),
        array(
            'key' => 'field_social_whatsapp_show',
            'label' => '显示WhatsApp',
            'name' => 'social_whatsapp_show',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ),
        
        // 批次5: 关于我们
        array(
            'key' => 'field_tab_about',
            'label' => '关于我们',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_footer_about_us_content',
            'label' => 'English',
            'name' => 'footer_about_us_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_about_us_content_pt',
            'label' => 'Português',
            'name' => 'footer_about_us_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_about_us_content_zh',
            'label' => '简体中文',
            'name' => 'footer_about_us_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_about_us_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_about_us_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        
        // 批次6: 我们的服务
        array(
            'key' => 'field_tab_services',
            'label' => '我们的服务',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_footer_services_content',
            'label' => 'English',
            'name' => 'footer_services_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_services_content_pt',
            'label' => 'Português',
            'name' => 'footer_services_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_services_content_zh',
            'label' => '简体中文',
            'name' => 'footer_services_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_services_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_services_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        
        // 批次7: 招聘
        array(
            'key' => 'field_tab_careers',
            'label' => '招聘',
            'name' => '',
            'type' => 'tab',
            'placement' => 'left',
            'endpoint' => 0,
        ),
        array(
            'key' => 'field_footer_careers_content',
            'label' => 'English',
            'name' => 'footer_careers_content',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_careers_content_pt',
            'label' => 'Português',
            'name' => 'footer_careers_content_pt',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_careers_content_zh',
            'label' => '简体中文',
            'name' => 'footer_careers_content_zh',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
        array(
            'key' => 'field_footer_careers_content_zh_tw',
            'label' => '繁體中文',
            'name' => 'footer_careers_content_zh_tw',
            'type' => 'wysiwyg',
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
        ),
    );
    
    // 定义批次（每批字段的索引范围）
    $batches = array(
        array(0, 3),    // 批次1: 站点信息 (0-3)
        array(4, 7),    // 批次2: Hero区域 (4-7)
        array(8, 10),   // 批次3: 联系信息 (8-10)
        array(11, 19),  // 批次4: 社交媒体 (11-19)
        array(20, 24),  // 批次5: 关于我们 (20-24)
        array(25, 29),  // 批次6: 我们的服务 (25-29)
        array(30, 34),  // 批次7: 招聘 (30-34)
    );
    
    if (isset($_POST['register_all'])) {
        echo '<div class="test-section">';
        echo '<h2>开始分批注册...</h2>';
        
        // 清除现有字段组
        if (function_exists('acf_get_store')) {
            acf_get_store('field-groups')->remove('group_homepage_settings');
            acf_get_store('fields')->reset();
        }
        wp_cache_flush();
        
        $all_registered_fields = array();
        $success_count = 0;
        $fail_count = 0;
        
        // 逐个批次注册
        foreach ($batches as $batch_num => $range) {
            $batch_fields = array_slice($all_fields, $range[0], $range[1] - $range[0] + 1);
            $current_fields = array_merge($all_registered_fields, $batch_fields);
            
            echo '<div class="batch">';
            echo '<h3>批次 ' . ($batch_num + 1) . ' (字段 ' . ($range[0] + 1) . '-' . ($range[1] + 1) . ')</h3>';
            echo '<div class="info">当前字段总数: ' . count($current_fields) . '</div>';
            
            $field_group_data = array(
                'key' => 'group_homepage_settings',
                'title' => '首页设置',
                'fields' => $current_fields,
                'location' => array(
                    array(
                        array(
                            'param' => 'page',
                            'operator' => '==',
                            'value' => '45',
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
            );
            
            ob_start();
            $result = acf_add_local_field_group($field_group_data);
            $output = ob_get_clean();
            
            if ($result) {
                echo '<div class="success">✓ 批次 ' . ($batch_num + 1) . ' 注册成功！</div>';
                $all_registered_fields = $current_fields;
                $success_count++;
                
                // 验证
                $registered_group = acf_get_field_group('group_homepage_settings');
                if ($registered_group) {
                    $registered_fields = acf_get_fields($registered_group);
                    echo '<div class="info">已注册字段数: ' . count($registered_fields) . '</div>';
                }
            } else {
                echo '<div class="error">✗ 批次 ' . ($batch_num + 1) . ' 注册失败</div>';
                if ($output) {
                    echo '<pre>' . esc_html($output) . '</pre>';
                }
                $fail_count++;
                break; // 如果失败，停止后续批次
            }
            
            echo '</div>';
        }
        
        echo '<div class="test-section">';
        echo '<h2>注册结果</h2>';
        echo '<div class="info">成功批次: ' . $success_count . ' / ' . count($batches) . '</div>';
        echo '<div class="info">失败批次: ' . $fail_count . '</div>';
        
        if ($success_count == count($batches)) {
            echo '<div class="success">✓ 所有批次注册成功！</div>';
            echo '<div class="info"><a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">现在可以打开首页设置页面查看</a></div>';
        } else {
            echo '<div class="error">✗ 部分批次注册失败，请检查错误信息</div>';
        }
        echo '</div>';
        
        echo '</div>';
    }
    ?>
    
    <form method="post">
        <button type="submit" name="register_all" value="1">一键分批注册所有字段</button>
    </form>
    
    <div style="margin-top: 30px;">
        <a href="/wp-admin/post.php?post=45&action=edit" style="color: #72aee6;">打开首页设置页面</a> | 
        <a href="/wp-content/themes/angola-b2b/force-register-acf.php" style="color: #72aee6;">查看诊断工具</a>
    </div>
</body>
</html>

