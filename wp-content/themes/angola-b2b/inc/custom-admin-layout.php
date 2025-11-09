<?php
/**
 * Custom Admin Layout - Expanded Sidebar Navigation
 * 自定义管理后台布局 - 展开式侧边栏导航
 *
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 自定义WordPress管理菜单样式 - 让所有子菜单默认展开显示
 */
function angola_b2b_expand_admin_menu() {
    ?>
    <style id="angola-b2b-expanded-menu-style">
        /* 强制显示左侧管理菜单 - 所有情况下 */
        #adminmenuwrap {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: fixed !important;
            left: 0 !important;
            top: 32px !important;
            bottom: 0 !important;
            z-index: 9989 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }
        
        #adminmenu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            position: relative !important;
            overflow: visible !important;
            height: auto !important;
            min-height: 100% !important;
        }
        
        /* 禁用Gutenberg全屏模式对左侧菜单的隐藏 */
        body.block-editor-page #adminmenuwrap,
        body.block-editor-page #adminmenu,
        .is-fullscreen-mode #adminmenuwrap,
        .is-fullscreen-mode #adminmenu,
        .edit-post-layout.is-fullscreen-mode #adminmenuwrap,
        .edit-post-layout.is-fullscreen-mode #adminmenu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateX(0) !important;
        }
        
        /* 确保内容区域不被菜单遮挡 */
        body.block-editor-page #wpcontent,
        .is-fullscreen-mode #wpcontent {
            margin-left: 160px !important;
        }
        
        /* Gutenberg编辑器界面调整 */
        .edit-post-layout,
        .edit-post-layout__content {
            margin-left: 0 !important;
        }
        
        .interface-interface-skeleton {
            left: 0 !important;
        }
        
        /* 让所有子菜单默认显示在父级菜单下方，而不是悬浮 */
        #adminmenu .wp-submenu {
            display: block !important;
            position: static !important;
            top: auto !important;
            left: auto !important;
            margin: 0 !important;
            padding: 5px 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: transparent !important;
            width: auto !important;
            min-width: 0 !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* 子菜单项样式调整 */
        #adminmenu .wp-submenu li {
            padding: 0 !important;
            display: block !important;
        }
        
        #adminmenu .wp-submenu a {
            padding: 8px 12px 8px 36px !important;
            font-size: 13px !important;
            color: #c3c4c7 !important;
            display: block !important;
        }
        
        #adminmenu .wp-submenu a:hover,
        #adminmenu .wp-submenu a:focus {
            color: #72aee6 !important;
            background: rgba(255, 255, 255, 0.04) !important;
        }
        
        #adminmenu .wp-submenu li.current a,
        #adminmenu .wp-submenu li.current a:hover,
        #adminmenu .wp-submenu li.current a:focus {
            color: #fff !important;
            font-weight: 600 !important;
        }
        
        /* 父级菜单项调整 */
        #adminmenu li.menu-top {
            margin-bottom: 0 !important;
        }
        
        /* 移除悬浮时的箭头指示器 */
        #adminmenu .wp-menu-arrow {
            display: none !important;
        }
        
        /* 确保折叠模式下也不显示悬浮菜单 */
        .folded #adminmenu .wp-submenu {
            display: none !important;
        }
        
        .folded #adminmenu li.menu-top:hover .wp-submenu {
            display: none !important;
        }
        
        /* 调整左侧菜单宽度以适应子菜单 */
        #adminmenu {
            width: 160px !important;
        }
        
        #wpcontent,
        #wpfooter {
            margin-left: 160px !important;
        }
        
        /* 确保内容区域正确显示 */
        #wpbody-content {
            padding-left: 20px !important;
        }
        
        /* 响应式调整 */
        @media screen and (max-width: 960px) {
            #adminmenu {
                width: 160px !important;
            }
            
            #wpcontent,
            #wpfooter {
                margin-left: 160px !important;
            }
        }
        
        @media screen and (max-width: 782px) {
            #adminmenu {
                width: 190px !important;
            }
            
            #wpcontent,
            #wpfooter {
                margin-left: 0 !important;
            }
        }
    </style>
    
    <script id="angola-b2b-expanded-menu-script">
        (function() {
            var isInitialized = false;
            var SCROLL_STORAGE_KEY = 'angola_b2b_admin_menu_scroll';
            
            function expandAllMenus(preserveScroll) {
                var $ = jQuery;
                var $adminMenuWrap = $('#adminmenuwrap');
                
                // 确保所有菜单项的子菜单都展开
                $('#adminmenu li.menu-top').addClass('wp-has-current-submenu wp-menu-open');
                
                // 移除悬浮事件
                $('#adminmenu li.menu-top').off('mouseenter mouseleave');
                
                // 防止点击父级菜单时收起子菜单
                if (!isInitialized) {
                    $('#adminmenu li.menu-top > a').off('click.expandmenu').on('click.expandmenu', function(e) {
                        var $parent = $(this).parent();
                        if ($parent.find('.wp-submenu').length > 0) {
                            // 如果有子菜单，阻止默认跳转行为
                            e.preventDefault();
                        }
                    });
                }
                
                // 强制显示所有子菜单
                $('#adminmenu .wp-submenu').css({
                    'display': 'block',
                    'position': 'static',
                    'visibility': 'visible',
                    'opacity': '1'
                });
            }
            
            // 保存滚动位置到sessionStorage
            function saveScrollPosition() {
                var $ = jQuery;
                var $adminMenuWrap = $('#adminmenuwrap');
                if ($adminMenuWrap.length) {
                    var scrollTop = $adminMenuWrap.scrollTop();
                    try {
                        sessionStorage.setItem(SCROLL_STORAGE_KEY, scrollTop);
                    } catch(e) {
                        // sessionStorage可能不可用
                    }
                }
            }
            
            // 从sessionStorage恢复滚动位置
            function restoreScrollPosition() {
                var $ = jQuery;
                var $adminMenuWrap = $('#adminmenuwrap');
                if ($adminMenuWrap.length) {
                    try {
                        var savedScroll = sessionStorage.getItem(SCROLL_STORAGE_KEY);
                        if (savedScroll !== null) {
                            $adminMenuWrap.scrollTop(parseInt(savedScroll, 10));
                        }
                    } catch(e) {
                        // sessionStorage可能不可用
                    }
                }
            }
            
            // 页面加载时执行
            jQuery(document).ready(function() {
                var $ = jQuery;
                
                expandAllMenus(false);
                isInitialized = true;
                
                // 恢复之前保存的滚动位置
                setTimeout(function() {
                    restoreScrollPosition();
                }, 100);
                
                // 监听滚动事件，实时保存滚动位置
                var scrollTimer;
                $('#adminmenuwrap').on('scroll', function() {
                    clearTimeout(scrollTimer);
                    scrollTimer = setTimeout(function() {
                        saveScrollPosition();
                    }, 100);
                });
                
                // 点击菜单链接时保存滚动位置
                $('#adminmenu a').on('click', function(e) {
                    saveScrollPosition();
                    // 不阻止链接跳转
                });
                
                // 监听DOM变化，但保持滚动位置
                var observer = new MutationObserver(function(mutations) {
                    var needsUpdate = false;
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && 
                            (mutation.attributeName === 'class' || mutation.attributeName === 'style')) {
                            needsUpdate = true;
                        }
                    });
                    
                    if (needsUpdate) {
                        var currentScroll = $('#adminmenuwrap').scrollTop();
                        expandAllMenus(true);
                        // 立即恢复滚动位置
                        setTimeout(function() {
                            $('#adminmenuwrap').scrollTop(currentScroll);
                        }, 10);
                    }
                });
                
                var adminMenu = document.getElementById('adminmenu');
                if (adminMenu) {
                    observer.observe(adminMenu, {
                        childList: false,
                        subtree: true,
                        attributes: true,
                        attributeFilter: ['class', 'style']
                    });
                }
            });
            
            // 页面完全加载后再次执行
            jQuery(window).on('load', function() {
                setTimeout(function() {
                    expandAllMenus(false);
                    restoreScrollPosition();
                }, 100);
            });
            
            // 页面卸载前保存滚动位置
            jQuery(window).on('beforeunload', function() {
                saveScrollPosition();
            });
            
            // 禁用Gutenberg全屏模式
            if (typeof wp !== 'undefined' && wp.data && wp.data.select && wp.data.dispatch) {
                jQuery(window).on('load', function() {
                    setTimeout(function() {
                        var editPostStore = wp.data.select('core/edit-post');
                        var editSiteStore = wp.data.select('core/edit-site');
                        
                        if (editPostStore && editPostStore.isFeatureActive('fullscreenMode')) {
                            wp.data.dispatch('core/edit-post').toggleFeature('fullscreenMode');
                        }
                        
                        if (editSiteStore && editSiteStore.isFeatureActive('fullscreenMode')) {
                            wp.data.dispatch('core/edit-site').toggleFeature('fullscreenMode');
                        }
                    }, 500);
                });
            }
        })();
    </script>
    <?php
}
add_action('admin_head', 'angola_b2b_expand_admin_menu');

/**
 * 在admin_footer也注入，确保所有页面都生效
 */
function angola_b2b_expand_admin_menu_footer() {
    ?>
    <script>
        // 确保页面底部也执行一次
        jQuery(function($) {
            $('#adminmenu li.menu-top').addClass('wp-has-current-submenu wp-menu-open');
            $('#adminmenu .wp-submenu').css({
                'display': 'block',
                'position': 'static',
                'visibility': 'visible',
                'opacity': '1'
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'angola_b2b_expand_admin_menu_footer');

// 所有自定义管理页面相关代码已移除
// 现在只保留展开式菜单功能
