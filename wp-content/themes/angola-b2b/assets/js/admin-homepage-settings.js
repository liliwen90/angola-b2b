(function ($) {
  $(function () {
    // ==================== 强制修复页面布局 ====================
    // 确保内容不会被左侧栏遮挡
    console.log('Homepage Settings: 开始修复布局...');
    
    // 方法1: 直接设置样式
    $('#wpcontent').css({
      'margin-left': '160px',
      'background': 'linear-gradient(to right, lightblue 160px, transparent 160px)'
    });
    
    // 方法2: 检查是否folded状态
    if ($('body').hasClass('folded')) {
      $('#wpcontent').css({
        'margin-left': '36px',
        'background': 'linear-gradient(to right, lightblue 36px, transparent 36px)'
      });
    }
    
    console.log('Homepage Settings: 布局修复完成', $('#wpcontent').css('margin-left'));
    
    // ==================== Tab 标签映射 ====================
    var tabLabels = {
      field_tab_site_info: '站点信息',
      field_tab_hero: 'Hero区域',
      field_tab_contact: '联系信息',
      field_tab_social: '社交媒体',
      field_tab_about: '关于我们',
      field_tab_services: '我们的服务',
      field_tab_careers: '招聘'
    };

    // 修复 Tab 标签显示
    var $tabs = $('#acf-group_homepage_settings .acf-tab-button');
    if ($tabs.length) {
      $tabs.each(function () {
        var $tab = $(this);
        var key = $tab.data('key');
        if (key && tabLabels[key]) {
          $tab.text(tabLabels[key]);
          $tab.attr('title', tabLabels[key]);
        }
        $tab.css({
          opacity: 1,
          visibility: 'visible',
          color: '#1f2937'
        });
      });
    }

    // 彻底移除 div.inside.acf-fields.-top.-sidebar 的遮挡效果
    // 方法：移除所有样式类，让它变成普通容器
    var $sidebarContainer = $('#acf-group_homepage_settings .inside.acf-fields.-top.-sidebar');
    if ($sidebarContainer.length) {
      // 等待 ACF 完全加载
      setTimeout(function() {
        // 移除所有可能导致灰色背景的类
        $sidebarContainer.removeClass('-sidebar -top');
        
        // 强制移除所有内联样式和计算样式
        $sidebarContainer.css({
          'background': 'transparent',
          'background-color': 'transparent',
          'background-image': 'none',
          'border': 'none',
          'box-shadow': 'none',
          'padding': '0',
          'margin': '0',
          'width': '100%',
          'max-width': '100%',
          'min-width': '0',
          'min-height': '0'
        });

        // 如果还是遮挡，尝试将内容移到父元素
        var $parent = $sidebarContainer.parent();
        if ($parent.length && $parent.is('.inside')) {
          // 将 sidebar 容器的所有子元素移到父元素
          var $children = $sidebarContainer.children();
          if ($children.length > 0) {
            // 先尝试移除类，如果还不行再移动内容
            console.log('已移除 -sidebar 和 -top 类，并重置样式');
          }
        }
      }, 100);
    }
  });
})(window.jQuery);

