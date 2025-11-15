<?php
/**
 * Angola B2B Theme Functions
 * 
 * @package Angola_B2B
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('ANGOLA_B2B_VERSION', '1.0.7');
define('ANGOLA_B2B_THEME_DIR', get_template_directory());
define('ANGOLA_B2B_THEME_URI', get_template_directory_uri());

// Include required files
$includes = array(
	'/inc/custom-multilingual.php',      // 自定义多语言系统（必须最先加载）
	'/inc/simple-translations.php',      // UI字符串翻译（保留用于按钮/标签）
	'/inc/theme-setup.php',
	'/inc/enqueue-scripts.php',
	'/inc/custom-post-types.php',
	'/inc/custom-taxonomies.php',
	'/inc/acf-fields.php',  // 非产品字段定义（首页、分类等）
	'/inc/product-fields-simple-v2.php', // 产品字段定义（4语言标题+富文本）⭐
	'/inc/acf-filters.php',
	'/inc/acf-field-translations.php',   // ACF字段标签动态翻译
	'/inc/admin-customization.php',
	'/inc/admin-menu-simplification.php', // 管理菜单简化
	'/inc/user-role-manager.php',         // 用户角色管理
	'/inc/admin-tools.php',
	'/inc/ajax-handlers.php',
	'/inc/inquiry-system.php',
	'/inc/helpers.php',
	'/inc/query-modifications.php',
	'/inc/create-default-pages.php',     // 创建默认页面
	'/inc/custom-admin-layout.php',      // 自定义管理后台布局
	'/inc/product-editor-simple.php',    // 新版简洁产品编辑器
	'/inc/acf-sync-fields.php',          // ACF字段同步管理页面
	'/inc/news-integration.php',         // 新闻系统接入与种子数据
	'/inc/news-editor.php',              // 新闻编辑器（经典编辑器+插入媒体）
	'/inc/news-language.php',            // 新闻语言分离（post_lang/lang_group）
	'/one-click-fix-product-fields.php'  // 一键修复产品字段 ⭐
);

foreach ($includes as $file) {
    $filepath = ANGOLA_B2B_THEME_DIR . $file;
    if (file_exists($filepath)) {
        // 使用 @ 抑制错误，避免单个文件错误阻止主题加载
        @require_once $filepath;
    }
}

/**
 * 修复ACF字段编辑界面显示placeholder问题 - 方案1: acf/prepare_field
 * 在字段准备阶段强制加载已保存的值
 */
add_filter('acf/prepare_field', 'angola_b2b_force_load_homepage_field_values', 10, 1);
function angola_b2b_force_load_homepage_field_values($field) {
    // 只处理首页设置页面（Post ID 45）
    $post_id = null;
    
    // 获取当前编辑的Post ID
    if (isset($_GET['post'])) {
        $post_id = intval($_GET['post']);
    } elseif (isset($_POST['post_ID'])) {
        $post_id = intval($_POST['post_ID']);
    }
    
    // 只处理首页设置页面
    if ($post_id !== 45) {
        return $field;
    }
    
    // 确保字段有name属性
    if (!isset($field['name']) || empty($field['name'])) {
        return $field;
    }
    
    // 获取字段的实际值
    $value = get_field($field['name'], $post_id, false); // false = 返回原始值，不格式化
    
    // 如果有值且不为空，强制设置到字段的value属性
    // 注意：必须检查 !== false，因为空字符串、0等也是有效值
    if ($value !== false && $value !== null && $value !== '') {
        $field['value'] = $value;
    }
    
    return $field;
}

/**
 * 修复ACF字段编辑界面显示placeholder问题 - 方案2: acf/load_value
 * 在值加载阶段拦截，确保返回数据库中的实际值
 * 这是更底层的方案，优先于prepare_field执行
 */
add_filter('acf/load_value', 'angola_b2b_force_load_homepage_values', 10, 3);
function angola_b2b_force_load_homepage_values($value, $post_id, $field) {
    // 只处理首页设置页面（Post ID 45）
    if ($post_id != 45) {
        return $value;
    }
    
    // 如果ACF返回的值为空或null，尝试直接从数据库获取
    if ($value === null || $value === false || $value === '') {
        $db_value = get_post_meta($post_id, $field['name'], true);
        
        // 如果数据库中有值，返回数据库值
        if ($db_value !== '' && $db_value !== false) {
            return $db_value;
        }
    }
    
    return $value;
}

/**
 * 修复ACF字段编辑界面显示placeholder问题 - 方案3: JavaScript强制注入
 * 在ACF JavaScript渲染完成后，强制将数据库值注入到输入框
 * 这是最底层的解决方案，直接操作DOM元素
 */
add_action('acf/input/admin_footer', 'angola_b2b_force_inject_field_values');
function angola_b2b_force_inject_field_values() {
    // 只在编辑首页时执行
    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
    if ($post_id !== 45) {
        return;
    }
    
    // 获取所有首页设置字段的值
    $field_group = acf_get_field_group('group_homepage_settings');
    if (!$field_group) {
        return;
    }
    
    $fields = acf_get_fields($field_group);
    if (!$fields) {
        return;
    }
    
    ?>
    <script type="text/javascript">
    (function($) {
        'use strict';
        
        // 等待ACF完全加载
        if (typeof acf !== 'undefined') {
            acf.addAction('ready', function() {
                console.log('ACF Ready - 开始注入字段值');
                
                // 字段值映射（从PHP传递到JavaScript）
                var fieldValues = <?php 
                    $field_data = array();
                    foreach ($fields as $field) {
                        if (isset($field['name']) && $field['type'] !== 'tab' && $field['type'] !== 'message') {
                            $value = get_field($field['name'], $post_id, false);
                            
                            // 对于某些字段类型，需要特殊处理空值检查
                            $should_include = false;
                            
                            if ($field['type'] === 'true_false') {
                                // 复选框字段，即使是0也要包含
                                $should_include = ($value !== false && $value !== null);
                            } elseif ($field['type'] === 'wysiwyg' || $field['type'] === 'textarea') {
                                // 富文本和文本域，检查是否为空字符串
                                $should_include = ($value !== false && $value !== null && trim($value) !== '');
                            } else {
                                // 其他字段类型
                                $should_include = ($value !== false && $value !== null && $value !== '');
                            }
                            
                            if ($should_include) {
                                $field_data[$field['key']] = array(
                                    'name' => $field['name'],
                                    'type' => $field['type'],
                                    'value' => $value
                                );
                                
                                // 对于图片字段，获取完整的图片数据
                                if ($field['type'] === 'image' && is_numeric($value)) {
                                    $image_data = wp_get_attachment_image_src($value, 'thumbnail');
                                    $field_data[$field['key']]['image_url'] = $image_data ? $image_data[0] : '';
                                    $field_data[$field['key']]['image_full'] = wp_get_attachment_url($value);
                                    $field_data[$field['key']]['image_title'] = get_the_title($value);
                                }
                            }
                        }
                    }
                    echo json_encode($field_data, JSON_UNESCAPED_UNICODE);
                ?>;
                
                console.log('准备注入的字段数据:', fieldValues);
                
                // 延迟执行，确保ACF的JavaScript已完成渲染
                setTimeout(function() {
                    var successCount = 0;
                    var failCount = 0;
                    
                    $.each(fieldValues, function(fieldKey, fieldData) {
                        var fieldName = fieldData.name;
                        var fieldType = fieldData.type;
                        var fieldValue = fieldData.value;
                        
                        console.log('处理字段:', fieldName, '类型:', fieldType, '值:', fieldValue);
                        
                        // 使用ACF的API查找字段对象
                        var $field = acf.getField(fieldKey);
                        
                        if ($field) {
                            console.log('✓ 找到ACF字段对象:', fieldName);
                            
                            // 使用ACF的val()方法设置值
                            try {
                                var currentVal = $field.val();
                                console.log('  当前值:', currentVal);
                                
                                // 只在当前值为空时设置
                                if (!currentVal || currentVal === '' || currentVal === null) {
                                    // 图片字段需要特殊处理
                                    if (fieldType === 'image' && fieldData.image_url) {
                                        console.log('  图片字段 - 使用特殊渲染方法');
                                        console.log('  图片ID:', fieldValue);
                                        console.log('  缩略图URL:', fieldData.image_url);
                                        
                                        // 设置图片ID到隐藏字段
                                        $field.val(fieldValue);
                                        
                                        // 手动渲染图片预览 - 获取字段的jQuery元素
                                        var $fieldElement = $field.$el ? $field.$el : $field.data.$el;
                                        
                                        if ($fieldElement && $fieldElement.length > 0) {
                                            var $imageWrap = $fieldElement.find('.acf-image-uploader');
                                            
                                            if ($imageWrap.length > 0) {
                                                // 添加has-value类
                                                $imageWrap.addClass('has-value');
                                                
                                                // 设置图片预览
                                                var $img = $imageWrap.find('img');
                                                if ($img.length > 0) {
                                                    $img.attr('src', fieldData.image_url).attr('alt', fieldData.image_title || '');
                                                } else {
                                                    $imageWrap.find('.image-wrap').html('<img src="' + fieldData.image_url + '" alt="' + (fieldData.image_title || '') + '">');
                                                }
                                                
                                                // 隐藏上传按钮，显示移除按钮
                                                $imageWrap.find('.acf-button').hide();
                                                $imageWrap.find('[data-name="remove"]').show();
                                                
                                                console.log('  ✓ 图片预览已渲染');
                                            } else {
                                                console.warn('  ⚠️ 未找到图片上传容器');
                                            }
                                        } else {
                                            console.warn('  ⚠️ 无法获取字段DOM元素，尝试备选方案');
                                            
                                            // 备选方案：直接通过data-key查找
                                            var $directField = $('[data-key="' + fieldKey + '"]');
                                            if ($directField.length > 0) {
                                                var $imageWrap = $directField.find('.acf-image-uploader');
                                                if ($imageWrap.length > 0) {
                                                    $imageWrap.addClass('has-value');
                                                    var $img = $imageWrap.find('img');
                                                    if ($img.length > 0) {
                                                        $img.attr('src', fieldData.image_url);
                                                    } else {
                                                        $imageWrap.find('.image-wrap').html('<img src="' + fieldData.image_url + '">');
                                                    }
                                                    $imageWrap.find('.acf-button').hide();
                                                    $imageWrap.find('[data-name="remove"]').show();
                                                    console.log('  ✓ 通过备选方案渲染成功');
                                                }
                                            }
                                        }
                                        
                                        successCount++;
                                    } else {
                                        // 其他字段类型 - 根据类型使用不同策略
                                        if (fieldType === 'wysiwyg') {
                                            // 富文本编辑器 - 需要特殊处理
                                            console.log('  富文本编辑器字段');
                                            $field.val(fieldValue);
                                            
                                            // 如果TinyMCE已加载，同步到编辑器
                                            var $fieldElement = $field.$el ? $field.$el : $field.data.$el;
                                            if ($fieldElement) {
                                                var $textarea = $fieldElement.find('textarea');
                                                if ($textarea.length > 0 && typeof tinymce !== 'undefined') {
                                                    var editorId = $textarea.attr('id');
                                                    setTimeout(function() {
                                                        var editor = tinymce.get(editorId);
                                                        if (editor && editor.getContent() === '') {
                                                            editor.setContent(fieldValue);
                                                            console.log('  ✓ TinyMCE内容已设置');
                                                        }
                                                    }, 200);
                                                }
                                            }
                                            console.log('  ✓ 成功设置新值');
                                            successCount++;
                                        } else if (fieldType === 'select' || fieldType === 'radio' || fieldType === 'checkbox') {
                                            // 选择类字段
                                            console.log('  选择类字段:', fieldType);
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        } else if (fieldType === 'url') {
                                            // URL字段
                                            console.log('  URL字段');
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        } else if (fieldType === 'number' || fieldType === 'range') {
                                            // 数字类字段
                                            console.log('  数字字段:', fieldType);
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        } else if (fieldType === 'date_picker' || fieldType === 'time_picker' || fieldType === 'date_time_picker') {
                                            // 日期时间选择器
                                            console.log('  日期时间字段:', fieldType);
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        } else if (fieldType === 'color_picker') {
                                            // 颜色选择器
                                            console.log('  颜色选择器');
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        } else {
                                            // 默认处理：text, email, textarea, true_false等
                                            $field.val(fieldValue);
                                            console.log('  ✓ 成功设置新值:', fieldValue);
                                            successCount++;
                                        }
                                    }
                                } else {
                                    console.log('  - 字段已有值，跳过');
                                }
                            } catch (e) {
                                console.error('  ✗ 设置值失败:', e);
                                failCount++;
                            }
                        } else {
                            console.warn('✗ 未找到ACF字段对象:', fieldName, '(key:', fieldKey + ')');
                            console.log('  尝试DOM备选方案...');
                            failCount++;
                            
                            // 备选方案：直接操作DOM
                            var $fieldWrapper = $('[data-key="' + fieldKey + '"]');
                            if ($fieldWrapper.length > 0) {
                                var handled = false;
                                
                                // 处理文本类字段
                                if (fieldType === 'text' || fieldType === 'email' || fieldType === 'url' || fieldType === 'number') {
                                    var $input = $fieldWrapper.find('input[type="text"], input[type="email"], input[type="url"], input[type="number"]').first();
                                    if ($input.length > 0 && !$input.val()) {
                                        $input.val(fieldValue).trigger('change').trigger('input');
                                        console.log('  ✓ 通过DOM设置文本字段成功');
                                        successCount++;
                                        failCount--;
                                        handled = true;
                                    }
                                }
                                
                                // 处理文本域
                                if (!handled && fieldType === 'textarea') {
                                    var $textarea = $fieldWrapper.find('textarea').first();
                                    if ($textarea.length > 0 && !$textarea.val()) {
                                        $textarea.val(fieldValue).trigger('change');
                                        console.log('  ✓ 通过DOM设置文本域成功');
                                        successCount++;
                                        failCount--;
                                        handled = true;
                                    }
                                }
                                
                                // 处理复选框
                                if (!handled && fieldType === 'true_false') {
                                    var $checkbox = $fieldWrapper.find('input[type="checkbox"]').first();
                                    if ($checkbox.length > 0) {
                                        $checkbox.prop('checked', fieldValue == '1' || fieldValue === true).trigger('change');
                                        console.log('  ✓ 通过DOM设置复选框成功');
                                        successCount++;
                                        failCount--;
                                        handled = true;
                                    }
                                }
                                
                                // 处理选择框
                                if (!handled && (fieldType === 'select' || fieldType === 'radio')) {
                                    var $select = $fieldWrapper.find('select').first();
                                    if ($select.length > 0) {
                                        $select.val(fieldValue).trigger('change');
                                        console.log('  ✓ 通过DOM设置选择框成功');
                                        successCount++;
                                        failCount--;
                                        handled = true;
                                    }
                                }
                                
                                // 处理富文本编辑器
                                if (!handled && fieldType === 'wysiwyg') {
                                    var $textarea = $fieldWrapper.find('textarea').first();
                                    if ($textarea.length > 0) {
                                        var editorId = $textarea.attr('id');
                                        $textarea.val(fieldValue);
                                        if (editorId && typeof tinymce !== 'undefined') {
                                            setTimeout(function() {
                                                var editor = tinymce.get(editorId);
                                                if (editor) {
                                                    editor.setContent(fieldValue);
                                                }
                                            }, 300);
                                        }
                                        console.log('  ✓ 通过DOM设置富文本编辑器成功');
                                        successCount++;
                                        failCount--;
                                        handled = true;
                                    }
                                }
                                
                                if (!handled) {
                                    console.log('  ✗ DOM备选方案也无法处理该字段类型:', fieldType);
                                }
                            } else {
                                console.log('  ✗ 连DOM元素也未找到');
                            }
                        }
                    });
                    
                    console.log('=================================');
                    console.log('字段值注入完成');
                    console.log('成功:', successCount, '失败:', failCount);
                    console.log('=================================');
                }, 800); // 增加延迟到800ms
            });
        } else {
            console.error('ACF JavaScript未加载');
        }
    })(jQuery);
    </script>
    <?php
}


