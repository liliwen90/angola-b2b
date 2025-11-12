/**
 * 产品多语言Tab切换编辑器
 * Product Multilingual Tab Editor
 */

(function($) {
    'use strict';

    // 等待DOM完全加载
    $(document).ready(function() {
        // 检查是否在产品编辑页面
        if (!$('body').hasClass('post-type-product')) {
            return;
        }

        console.log('Product Multilingual Tabs: Initializing...');
        console.log('Default Language:', angolaProductEditor.defaultLang);
        console.log('Recommended Language:', angolaProductEditor.recommendedLang);
        console.log('User Roles:', angolaProductEditor.userRoles);

        // 语言配置
        var languages = {
            'zh': { label: '简体中文', code: 'zh-CN' },
            'zh_tw': { label: '繁體中文', code: 'zh-TW' },
            'pt': { label: 'Português', code: 'pt-PT' },
            'en': { label: 'English', code: 'en-US' }
        };

        var defaultLang = angolaProductEditor.defaultLang || 'zh';
        var recommendedLang = angolaProductEditor.recommendedLang || '';
        var currentLang = defaultLang;

        // 从localStorage读取用户上次选择的语言
        var savedLang = localStorage.getItem('angola_product_editor_lang');
        if (savedLang && languages[savedLang]) {
            currentLang = savedLang;
        }

        /**
         * 创建语言切换Tab
         */
        function createLanguageTabs() {
            console.log('Creating language tabs...');

            // 检查是否已存在Tab
            if ($('#angola-language-tabs').length > 0) {
                console.log('Tabs already exist, skipping creation');
                return;
            }

            // 创建Tab容器
            var $tabContainer = $('<div id="angola-language-tabs" class="angola-language-tabs"></div>');

            // 创建Tab按钮
            $.each(['zh', 'zh_tw', 'pt', 'en'], function(index, lang) {
                var langConfig = languages[lang];
                var $tab = $('<button type="button" class="angola-lang-tab" data-lang="' + lang + '">' + 
                    langConfig.label + '</button>');

                // 添加"推荐"徽章
                if (lang === recommendedLang && recommendedLang !== '') {
                    $tab.append('<span class="angola-recommended-badge">推荐</span>');
                }

                // 设置当前激活的Tab
                if (lang === currentLang) {
                    $tab.addClass('active');
                }

                $tab.on('click', function(e) {
                    e.preventDefault();
                    switchLanguageTab(lang);
                });

                $tabContainer.append($tab);
            });

            // 将Tab插入到页面标题下方
            var $titleWrap = $('#post-body-content');
            if ($titleWrap.length > 0) {
                $titleWrap.prepend($tabContainer);
                console.log('Tabs inserted successfully');
            } else {
                console.error('Could not find #post-body-content to insert tabs');
            }
        }

        /**
         * 标记ACF字段的语言属性
         */
        function markACFFields() {
            console.log('Marking ACF fields...');

            var markedCount = 0;

            // 遍历所有ACF字段，通过data-key属性识别
            $('.acf-field').each(function() {
                var $field = $(this);
                var dataKey = $field.attr('data-key');
                
                if (!dataKey) {
                    return;
                }
                
                // 跳过已经标记过的字段
                if ($field.hasClass('angola-lang-field')) {
                    return;
                }
                
                console.log('Checking field:', dataKey);
                
                var lang = '';
                
                // 精确匹配字段key来识别语言
                if (dataKey === 'field_product_title_pt' || dataKey === 'field_product_content_pt') {
                    lang = 'pt';
                } else if (dataKey === 'field_product_title_zh_tw' || dataKey === 'field_product_content_zh_tw') {
                    lang = 'zh_tw';
                } else if (dataKey === 'field_product_title_zh' || dataKey === 'field_product_content_zh') {
                    lang = 'zh';
                }
                
                if (lang) {
                    $field.addClass('angola-lang-field').attr('data-lang', lang);
                    console.log('✓ Marked field as', lang, ':', dataKey);
                    markedCount++;
                }
            });

            if (markedCount > 0) {
                console.log('Newly marked', markedCount, 'fields');
                
                // 将相同语言的字段包装到一个容器中
                ['zh', 'zh_tw', 'pt'].forEach(function(lang) {
                    var $fields = $('.angola-lang-field[data-lang="' + lang + '"]');
                    if ($fields.length > 0) {
                        console.log('Found', $fields.length, 'fields for', lang);
                        
                        // 确保字段还没有被包装
                        if (!$fields.first().parent().hasClass('angola-lang-fields')) {
                            $fields.wrapAll('<div class="angola-lang-fields" data-lang="' + lang + '"></div>');
                            console.log('✓ Wrapped fields for language:', lang);
                        }
                    }
                });
            }

            console.log('ACF fields marked successfully (total marked:', markedCount, ')');
            return markedCount;
        }

        /**
         * 处理WordPress原生字段（英文）
         */
        function handleNativeFields() {
            console.log('Handling native WordPress fields for English...');
            
            // 处理标题字段
            var $titleDiv = $('#titlediv');
            if ($titleDiv.length > 0 && !$titleDiv.hasClass('angola-lang-field')) {
                // 隐藏所有可能的中文标签
                $titleDiv.find('label').hide(); // 隐藏所有label
                $titleDiv.find('.screen-reader-text').hide(); // 隐藏屏幕阅读器文本
                
                // 添加英文标签
                var $titleWrap = $titleDiv.find('#titlewrap');
                if ($titleWrap.length > 0) {
                    if ($titleWrap.find('.angola-english-label').length === 0) {
                        $titleWrap.prepend('<div class="angola-english-label" style="margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #1d2327;">Product Title</div>');
                    }
                }
                
                // 完全移除placeholder属性，然后重新添加英文的
                var $titleInput = $('#title');
                
                // 如果输入框的值是中文提示文字，清空它
                if ($titleInput.val() === '添加标题') {
                    $titleInput.val('');
                }
                
                // 移除并重新设置placeholder
                $titleInput.removeAttr('placeholder');
                $titleInput.prop('placeholder', '');
                
                setTimeout(function() {
                    $titleInput.attr('placeholder', 'Enter product title in English');
                }, 200);
                
                $titleDiv.addClass('angola-lang-field').attr('data-lang', 'en');
                console.log('✓ Marked native title field as English');
            }
            
            // 处理内容编辑器
            var $editorDiv = $('#postdivrich');
            if ($editorDiv.length > 0 && !$editorDiv.hasClass('angola-lang-field')) {
                // 隐藏原有的编辑器标题（如果有）
                var $editorLabel = $editorDiv.prev('label');
                if ($editorLabel.length > 0) {
                    $editorLabel.hide();
                }
                
                // 添加英文标签
                if ($editorDiv.find('.angola-english-label').length === 0) {
                    $editorDiv.prepend('<div class="angola-english-label" style="margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #1d2327;">Product Details</div>');
                }
                
                // 修改"添加媒体"按钮文本为英文
                var $addMediaBtn = $('#insert-media-button');
                if ($addMediaBtn.length > 0) {
                    $addMediaBtn.text('Add Media');
                }
                
                $editorDiv.addClass('angola-lang-field').attr('data-lang', 'en');
                console.log('✓ Marked native content editor as English');
            }
            
            // 将英文字段包装到一个容器中
            var $enFields = $('.angola-lang-field[data-lang="en"]');
            if ($enFields.length > 0 && !$enFields.first().parent().hasClass('angola-lang-fields')) {
                $enFields.wrapAll('<div class="angola-lang-fields" data-lang="en"></div>');
                console.log('✓ Wrapped English fields (count:', $enFields.length, ')');
            }
        }

        /**
         * 切换语言Tab
         */
        function switchLanguageTab(lang) {
            console.log('==================');
            console.log('Switching to language:', lang);

            currentLang = lang;

            // 更新Tab激活状态
            $('.angola-lang-tab').removeClass('active');
            $('.angola-lang-tab[data-lang="' + lang + '"]').addClass('active');

            // 移除所有active类
            $('.angola-lang-fields').removeClass('active');
            
            // 为当前语言添加active类
            var $activeLangFields = $('.angola-lang-fields[data-lang="' + lang + '"]');
            $activeLangFields.addClass('active');
            
            console.log('Active language fields count:', $activeLangFields.length);
            console.log('Active language fields HTML:', $activeLangFields.html());

            // 如果切换到英文，保护标题输入框
            if (lang === 'en') {
                preventChineseTitleValue();
            }

            // 保存用户选择到localStorage
            localStorage.setItem('angola_product_editor_lang', lang);

            console.log('Language switched to:', lang);
            console.log('==================');
        }

        /**
         * 防止并清理标题输入框的中文内容
         */
        function preventChineseTitleValue() {
            var $titleInput = $('#title');
            if ($titleInput.length === 0) return;
            
            // 清空当前值（如果是中文）
            var currentVal = $titleInput.val();
            if (currentVal === '添加标题') {
                $titleInput.val('');
                console.log('✓ Cleared Chinese title value');
            }
            
            // 监听input事件，防止任何尝试设置"添加标题"的操作
            $titleInput.off('input.angolaTitleProtect').on('input.angolaTitleProtect', function() {
                if ($(this).val() === '添加标题') {
                    $(this).val('');
                    console.log('✓ Blocked attempt to set Chinese title');
                }
            });
            
            // 使用MutationObserver监听value属性的变化
            if ($titleInput[0] && !$titleInput.data('angola-observer')) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                            var val = $titleInput.val();
                            if (val === '添加标题') {
                                $titleInput.val('');
                                console.log('✓ Blocked attribute change to Chinese title');
                            }
                        }
                    });
                });
                
                observer.observe($titleInput[0], {
                    attributes: true,
                    attributeFilter: ['value']
                });
                
                $titleInput.data('angola-observer', true);
                console.log('✓ Observer set up to prevent Chinese title');
            }
            
            // 设置英文placeholder
            $titleInput.removeAttr('placeholder');
            $titleInput.attr('placeholder', 'Enter product title in English');
        }

        /**
         * 初始化语言Tab系统
         */
        function initLanguageTabs() {
            console.log('Initializing language tabs system...');

            // 立即设置标题输入框保护
            preventChineseTitleValue();

            var tabsCreated = false;
            var checkCount = 0;

            // 定期检查新加载的ACF字段（特别是WYSIWYG编辑器）
            var checkACFInterval = setInterval(function() {
                checkCount++;
                
                // 持续检查并保护标题输入框
                preventChineseTitleValue();
                
                var newFieldsMarked = markACFFields();
                
                // 如果发现新字段，重新显示当前语言
                if (newFieldsMarked > 0 && tabsCreated) {
                    console.log('Found new fields, refreshing display...');
                    switchLanguageTab(currentLang);
                }
                
                // 初次发现字段时创建Tab
                if (!tabsCreated && $('.acf-field').length > 0) {
                    handleNativeFields();
                    createLanguageTabs();
                    switchLanguageTab(currentLang);
                    tabsCreated = true;
                    console.log('Language tabs system initialized successfully');
                }
                
                // 检查20秒后停止（给WYSIWYG足够的加载时间）
                if (checkCount > 200) {
                    clearInterval(checkACFInterval);
                    console.log('Stopped checking for new fields after 20 seconds');
                }
            }, 100);

            // 添加MutationObserver监听DOM变化（用于捕获延迟加载的WYSIWYG编辑器）
            var observer = new MutationObserver(function(mutations) {
                var hasNewFields = false;
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // 元素节点
                            if ($(node).hasClass('acf-field') || $(node).find('.acf-field').length > 0) {
                                hasNewFields = true;
                            }
                        }
                    });
                });
                
                if (hasNewFields) {
                    console.log('MutationObserver detected new ACF fields');
                    var newFieldsMarked = markACFFields();
                    if (newFieldsMarked > 0 && tabsCreated) {
                        switchLanguageTab(currentLang);
                    }
                }
            });

            // 监听整个表单区域
            var $postBody = $('#post-body');
            if ($postBody.length > 0) {
                observer.observe($postBody[0], {
                    childList: true,
                    subtree: true
                });
                console.log('MutationObserver started');
            }
        }

        // 启动初始化
        initLanguageTabs();
    });

})(jQuery);

