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
 * 在产品编辑页面添加语言Tab切换器
 */
function angola_b2b_add_language_tabs() {
    global $post_type;
    
    // 只在产品编辑页面显示
    if ($post_type !== 'product') {
        return;
    }
    
    $default_lang = angola_b2b_get_user_default_language();
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    
    // 确定推荐的语言Tab（安哥拉员工推荐葡语）
    $recommended_lang = in_array('ao_product_editor', $user_roles) ? 'pt' : '';
    
    ?>
    <style>
        /* Tab切换器容器 */
        #angola-lang-tabs-wrapper {
            background: white;
            padding: 20px 20px 0 20px;
            margin: 20px 0 0 0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        /* Tab切换器 */
        .angola-lang-tabs {
            display: flex;
            gap: 8px;
            border-bottom: 2px solid #ddd;
            margin: 0;
            padding: 0;
        }
        
        /* 单个Tab按钮 */
        .angola-lang-tab {
            padding: 12px 24px;
            background: #f0f0f1;
            border: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #2c3338;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid transparent;
            border-bottom: none;
        }
        
        .angola-lang-tab:hover:not(.active) {
            background: #e5e5e5;
            color: #1d2327;
        }
        
        /* 激活状态的Tab */
        .angola-lang-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(2px);
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        
        /* 推荐标记 */
        .angola-lang-tab .recommended-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #f0b849;
            color: #1d2327;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .angola-lang-tab.active .recommended-badge {
            background: #ffd700;
        }
        
        /* Tab标签 */
        .angola-lang-tab .lang-label {
            display: block;
            font-weight: 600;
        }
        
        .angola-lang-tab .lang-code {
            display: block;
            font-size: 11px;
            opacity: 0.8;
            margin-top: 2px;
        }
        
        /* 提示信息 */
        .angola-lang-tabs-info {
            background: #f0f6fc;
            border-left: 4px solid #2271b1;
            padding: 12px 16px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #1d2327;
        }
        
        .angola-lang-tabs-info strong {
            color: #2271b1;
        }
        
        /* 语言字段组 */
        .angola-lang-fields {
            display: none;
        }
        
        .angola-lang-fields.active {
            display: block;
        }
        
        /* WordPress原生标题字段的语言标识 */
        #titlediv {
            position: relative;
        }
        
        #titlediv .lang-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
        }
        
        /* 隐藏非当前语言的WordPress编辑器 */
        .postarea-lang {
            display: none;
        }
        
        .postarea-lang.active {
            display: block;
        }
        
        /* 隐藏ACF多语言字段组的标题 */
        .acf-field-group[data-key="group_product_multilingual"] > .acf-label {
            display: none !important;
        }
        
        /* 响应式 */
        @media (max-width: 782px) {
            .angola-lang-tabs {
                flex-wrap: wrap;
            }
            
            .angola-lang-tab {
                padding: 10px 16px;
                font-size: 13px;
            }
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // 语言配置
        var languages = {
            'zh': { label: '简体中文', code: 'zh-CN' },
            'zh_tw': { label: '繁體中文', code: 'zh-TW' },
            'pt': { label: 'Português', code: 'pt-PT' },
            'en': { label: 'English', code: 'en-US' }
        };
        
        var defaultLang = '<?php echo $default_lang; ?>';
        var recommendedLang = '<?php echo $recommended_lang; ?>';
        var currentLang = defaultLang;
        
        // 创建Tab切换器
        function createLanguageTabs() {
            var tabsHtml = '<div id="angola-lang-tabs-wrapper">';
            tabsHtml += '<div class="angola-lang-tabs">';
            
            // 按顺序显示Tab
            var langOrder = ['zh', 'zh_tw', 'pt', 'en'];
            
            langOrder.forEach(function(lang) {
                var langInfo = languages[lang];
                var activeClass = (lang === currentLang) ? 'active' : '';
                
                tabsHtml += '<button type="button" class="angola-lang-tab ' + activeClass + '" data-lang="' + lang + '">';
                
                // 添加推荐标记（如果是推荐语言）
                if (recommendedLang && lang === recommendedLang) {
                    tabsHtml += '<span class="recommended-badge">推荐</span>';
                }
                
                tabsHtml += '<span class="lang-label">' + langInfo.label + '</span>';
                tabsHtml += '<span class="lang-code">' + langInfo.code + '</span>';
                tabsHtml += '</button>';
            });
            
            tabsHtml += '</div>';
            
            // 添加提示信息
            tabsHtml += '<div class="angola-lang-tabs-info">';
            tabsHtml += '<strong>💡 提示：</strong> 点击上方的语言Tab切换编辑不同语言版本的产品信息。';
            if (recommendedLang) {
                var recommendedLangLabel = languages[recommendedLang].label;
                tabsHtml += ' 建议优先填写<strong>' + recommendedLangLabel + '</strong>版本的内容。';
            }
            tabsHtml += ' 系统会自动保存您上次选择的语言。';
            tabsHtml += '</div>';
            
            tabsHtml += '</div>';
            
            // 插入到标题字段之前
            $('#titlediv').before(tabsHtml);
        }
        
        // 为ACF字段添加语言标识
        function markACFFields() {
            // 标记多语言标题字段
            $('input[name*="acf[field_product_title_"]').each(function() {
                var $field = $(this);
                var fieldName = $field.attr('name');
                var lang = '';
                
                if (fieldName.indexOf('title_pt') > -1) {
                    lang = 'pt';
                } else if (fieldName.indexOf('title_zh_tw') > -1) {
                    lang = 'zh_tw';
                } else if (fieldName.indexOf('title_zh') > -1 && fieldName.indexOf('title_zh_tw') === -1) {
                    lang = 'zh';
                }
                
                if (lang) {
                    $field.closest('.acf-field').addClass('angola-lang-field').attr('data-lang', lang);
                }
            });
            
            // 标记多语言描述字段
            $('textarea[name*="acf[field_product_short_description_"]').each(function() {
                var $field = $(this);
                var fieldName = $field.attr('name');
                var lang = '';
                
                if (fieldName.indexOf('short_description_pt') > -1) {
                    lang = 'pt';
                } else if (fieldName.indexOf('short_description_zh_tw') > -1) {
                    lang = 'zh_tw';
                } else if (fieldName.indexOf('short_description_zh') > -1 && fieldName.indexOf('short_description_zh_tw') === -1) {
                    lang = 'zh';
                }
                
                if (lang) {
                    $field.closest('.acf-field').addClass('angola-lang-field').attr('data-lang', lang);
                }
            });
            
            // 将标记的字段包装到语言组中
            ['zh', 'zh_tw', 'pt', 'en'].forEach(function(lang) {
                var $fields = $('.angola-lang-field[data-lang="' + lang + '"]');
                if ($fields.length > 0) {
                    $fields.wrapAll('<div class="angola-lang-fields" data-lang="' + lang + '"></div>');
                }
            });
        }
        
        // 处理WordPress原生标题字段
        function handleNativeTitle() {
            // 为英文标题添加语言指示器
            var $titleDiv = $('#titlediv');
            if ($titleDiv.length) {
                $titleDiv.addClass('angola-lang-field').attr('data-lang', 'en');
                
                // 添加语言指示器
                var indicator = '<span class="lang-indicator">English Title</span>';
                $titleDiv.prepend(indicator);
                
                // 更新标题字段的说明文字
                var $titleWrap = $('#titlewrap');
                if ($titleWrap.length) {
                    var currentPrompt = $titleWrap.find('#title-prompt-text').text();
                    if (currentPrompt === '添加标题' || currentPrompt === 'Add title') {
                        $titleWrap.find('#title-prompt-text').text('Enter product title in English');
                    }
                }
            }
        }
        
        // 切换语言显示
        function switchLanguage(lang) {
            currentLang = lang;
            
            // 更新Tab状态
            $('.angola-lang-tab').removeClass('active');
            $('.angola-lang-tab[data-lang="' + lang + '"]').addClass('active');
            
            // 显示/隐藏对应的字段组
            $('.angola-lang-fields').removeClass('active');
            $('.angola-lang-fields[data-lang="' + lang + '"]').addClass('active');
            
            // 显示/隐藏WordPress原生标题
            if (lang === 'en') {
                $('#titlediv').show();
            } else {
                $('#titlediv').hide();
            }
            
            // 保存用户选择
            localStorage.setItem('angola_product_editor_lang', lang);
            
            // 触发自定义事件
            $(document).trigger('angola_lang_switched', [lang]);
        }
        
        // 初始化
        function init() {
            // 检查localStorage中是否有用户上次的选择
            var savedLang = localStorage.getItem('angola_product_editor_lang');
            if (savedLang && languages[savedLang]) {
                currentLang = savedLang;
            }
            
            // 创建Tab切换器
            createLanguageTabs();
            
            // 标记ACF字段
            markACFFields();
            
            // 处理原生标题
            handleNativeTitle();
            
            // 显示当前语言的字段
            switchLanguage(currentLang);
            
            // 绑定Tab点击事件
            $(document).on('click', '.angola-lang-tab', function() {
                var lang = $(this).data('lang');
                switchLanguage(lang);
            });
        }
        
        // 等待ACF加载完成
        if (typeof acf !== 'undefined') {
            acf.addAction('ready', function() {
                setTimeout(init, 100);
            });
        } else {
            // 如果没有ACF，直接初始化
            setTimeout(init, 500);
        }
    });
    </script>
    <?php
}
add_action('admin_head-post.php', 'angola_b2b_add_language_tabs');
add_action('admin_head-post-new.php', 'angola_b2b_add_language_tabs');

/**
 * 传递用户默认语言到前端
 */
function angola_b2b_enqueue_product_editor_script() {
    global $post_type;
    
    if ($post_type !== 'product') {
        return;
    }
    
    $default_lang = angola_b2b_get_user_default_language();
    $current_user = wp_get_current_user();
    
    wp_localize_script('jquery', 'angolaB2BEditor', array(
        'userDefaultLang' => $default_lang,
        'userRoles' => $current_user->roles,
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('angola_b2b_editor'),
    ));
}
add_action('admin_enqueue_scripts', 'angola_b2b_enqueue_product_editor_script');

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
            
            // 检查是否有该语言的内容
            if ($lang === 'en') {
                // 英文使用WordPress原生标题
                $title = get_the_title($post_id);
                $has_content = !empty($title);
            } else {
                // 其他语言检查ACF字段
                $title = get_field('title_' . $lang, $post_id);
                $has_content = !empty($title);
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

