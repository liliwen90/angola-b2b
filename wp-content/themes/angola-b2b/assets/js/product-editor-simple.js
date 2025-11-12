/**
 * 产品编辑器 - 简洁版
 * 4个语言Tab切换系统
 * Version: 2.0.0
 */

(function($) {
    'use strict';

    // 等待DOM加载完成
    $(document).ready(function() {
        console.log('=== Angola Product Editor Simple v2.0.0 ===');
        initProductEditor();
    });

    /**
     * 初始化产品编辑器
     */
    function initProductEditor() {
        console.log('Initializing simple product editor...');

        // 等待ACF加载完成
        var checkInterval = setInterval(function() {
            var $tabs = $('.angola-lang-tab');
            
            if ($tabs.length > 0) {
                clearInterval(checkInterval);
                console.log('✓ ACF tabs found:', $tabs.length);
                
                // 设置语言切换系统
                setupLanguageTabs();
                
                // 隐藏WordPress原生编辑器（除了在English tab）
                handleNativeEditor();
                
                console.log('✓ Product editor initialized successfully');
            }
        }, 100);

        // 20秒后停止检查
        setTimeout(function() {
            clearInterval(checkInterval);
        }, 20000);
    }

    /**
     * 设置语言Tab系统
     */
    function setupLanguageTabs() {
        console.log('Setting up language tabs...');

        // 获取所有语言tab
        var $tabLinks = $('.acf-tab-button');
        console.log('Found ACF tab buttons:', $tabLinks.length);

        // 标记每个tab
        $tabLinks.each(function() {
            var $this = $(this);
            var tabText = $this.text().trim();
            
            console.log('Tab found:', tabText);
            
            // 根据tab文本添加语言标识
            if (tabText === 'English') {
                $this.addClass('angola-tab-btn-en').attr('data-lang', 'en');
            } else if (tabText === 'Português') {
                $this.addClass('angola-tab-btn-pt').attr('data-lang', 'pt');
            } else if (tabText === '简体中文') {
                $this.addClass('angola-tab-btn-zh').attr('data-lang', 'zh');
            } else if (tabText === '繁體中文') {
                $this.addClass('angola-tab-btn-zh-tw').attr('data-lang', 'zh-tw');
            }
        });

        // 监听tab点击
        $tabLinks.on('click', function() {
            var $this = $(this);
            var lang = $this.attr('data-lang');
            
            if (lang) {
                console.log('Tab clicked:', lang);
                
                // 保存当前语言
                localStorage.setItem('angola_product_lang', lang);
                
                // 切换显示/隐藏WordPress原生编辑器
                setTimeout(function() {
                    handleNativeEditor(lang);
                }, 100);
            }
        });

        // 恢复上次选择的语言
        var savedLang = localStorage.getItem('angola_product_lang') || 'en';
        console.log('Restoring saved language:', savedLang);
        
        var $savedTab = $('.angola-tab-btn-' + savedLang.replace('_', '-'));
        if ($savedTab.length > 0) {
            setTimeout(function() {
                $savedTab.trigger('click');
            }, 200);
        }
    }

    /**
     * 处理WordPress原生编辑器的显示/隐藏
     * 新版本：所有语言都使用ACF编辑器，隐藏WordPress原生编辑器
     */
    function handleNativeEditor(currentLang) {
        console.log('Hiding native editor (using ACF editors only)');

        // WordPress原生标题和编辑器容器
        var $titleWrap = $('#titlewrap');
        var $postdivrich = $('#postdivrich');

        // 隐藏WordPress原生编辑器（所有语言都使用ACF编辑器）
        $titleWrap.hide();
        $postdivrich.hide();
        
        console.log('✓ Native editor hidden');
    }

    /**
     * 在WordPress编辑器前添加提示信息
     * 已禁用：不再使用WordPress原生编辑器
     */
    function addEditorHint() {
        // 不再添加提示，因为不使用WordPress原生编辑器
        return;
    }

})(jQuery);

