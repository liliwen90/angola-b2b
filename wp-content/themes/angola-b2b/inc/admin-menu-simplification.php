<?php
/**
 * 管理后台菜单简化 - Admin Menu Simplification
 * 
 * 为管理员和员工提供简洁、易用的管理界面
 * 隐藏技术性菜单，只保留核心业务功能
 * 
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 简化管理菜单 - 移除不必要的菜单项
 */
function angola_b2b_simplify_admin_menu() {
    // === 隐藏不需要的核心WordPress菜单 ===
    remove_menu_page('edit-comments.php');           // 评论（B2B站点不需要）
    remove_menu_page('themes.php');                   // 外观（员工不需要修改主题）
    remove_menu_page('plugins.php');                  // 插件（员工不需要管理插件）
    remove_menu_page('tools.php');                    // 工具（技术性功能）
    remove_menu_page('options-general.php');          // 设置（员工不需要修改网站设置）
    
    // === 隐藏自定义文章类型（如果不使用） ===
    // 如果您不使用"解决方案"和"行业"，可以隐藏它们
    // 如果需要使用，请注释掉下面两行
    remove_menu_page('edit.php?post_type=solution'); // 解决方案
    remove_menu_page('edit.php?post_type=industry'); // 行业
    
    // === 隐藏插件菜单 ===
    remove_menu_page('acf-options');                 // ACF（技术人员使用）
    remove_menu_page('updraftplus');                 // UpdraftPlus备份
    remove_menu_page('Wordfence');                   // Wordfence安全
    remove_menu_page('smush');                       // Smush图片优化
    remove_menu_page('loco-translate');              // Loco Translate翻译
    remove_menu_page('users_user_role_editor_settings'); // User Role Editor
    
    // === 隐藏联系表单菜单（保留核心功能即可） ===
    remove_menu_page('wpcf7');                       // Contact Form 7
    
    // === 移除页面菜单（如果不需要频繁编辑静态页面） ===
    // 如果需要编辑页面，请注释掉下面这行
    remove_menu_page('edit.php?post_type=page');    // 页面
}
add_action('admin_menu', 'angola_b2b_simplify_admin_menu', 9999); // 更高优先级确保在所有插件之后执行

/**
 * 获取管理后台翻译文本
 * 根据当前用户的语言设置返回相应文本
 * 
 * @param string $key 翻译键
 * @return string 翻译后的文本
 */
function angola_b2b_admin_translate($key) {
    $user_locale = get_user_locale();
    
    $translations = array(
        'zh_CN' => array(
            // 菜单翻译
            'news_management' => '📰 新闻管理',
            'product_management' => '📦 产品管理',
            'all_products' => '所有产品',
            'add_new_product' => '添加新产品',
            'product_categories' => '📂 产品分类',
            'product_tags' => '🏷️ 产品标签',
            'media_library' => '🖼️ 媒体库',
            'homepage_settings' => '🏠 首页设置',
            'staff_management' => '👥 员工管理',
            'all_news' => '所有新闻',
            'add_news' => '添加新闻',
            'news_categories' => '新闻分类',
            // Dashboard小部件翻译
            'theme_info_widget' => 'Angola B2B 主题信息',
            'welcome_widget' => '👋 欢迎使用Unibro管理系统',
            'hello' => '您好',
            'welcome_back' => '欢迎回到Unibro B2B管理系统。以下是您的网站概况：',
            'product_count' => '产品总数',
            'news_count' => '新闻总数',
            'quick_actions' => '🚀 快速操作',
            'add_product_btn' => '➕ 添加新产品',
            'add_news_btn' => '📝 添加新闻',
            'homepage_images_btn' => '🖼️ 首页图片设置',
            'media_library_btn' => '📁 媒体库',
            'tip' => '温馨提示：',
            'tip_content' => '添加产品时，请记得填写所有4种语言的内容（英语、葡萄牙语、简体中文、繁体中文），以确保所有访客都能看到完整信息。',
            'site_stats' => '网站统计',
            'published_products' => '已发布产品',
            'draft_products' => '草稿产品',
            'product_categories_count' => '产品分类',
            'quick_links' => '快速链接',
            'add_product_link' => '添加新产品',
            'manage_products_link' => '管理产品',
            'theme_settings_link' => '主题设置',
            'theme_footer' => 'Angola B2B 主题 | 版本',
        ),
        'pt_PT' => array(
            // 菜单翻译
            'news_management' => '📰 Gestão de Notícias',
            'product_management' => '📦 Gestão de Produtos',
            'all_products' => 'Todos os Produtos',
            'add_new_product' => 'Adicionar Novo Produto',
            'product_categories' => '📂 Categorias',
            'product_tags' => '🏷️ Tags',
            'media_library' => '🖼️ Biblioteca',
            'homepage_settings' => '🏠 Página Inicial',
            'staff_management' => '👥 Gestão de Pessoal',
            'all_news' => 'Todas as Notícias',
            'add_news' => 'Adicionar Notícia',
            'news_categories' => 'Categorias de Notícias',
            // Dashboard小部件翻译
            'theme_info_widget' => 'Informações do Tema Angola B2B',
            'welcome_widget' => '👋 Bem-vindo ao Sistema Unibro',
            'hello' => 'Olá',
            'welcome_back' => 'Bem-vindo de volta. Aqui está a visão geral do seu site:',
            'product_count' => 'Total de Produtos',
            'news_count' => 'Total de Notícias',
            'quick_actions' => '🚀 Ações Rápidas',
            'add_product_btn' => '➕ Adicionar Novo Produto',
            'add_news_btn' => '📝 Adicionar Notícia',
            'homepage_images_btn' => '🖼️ Configurações de Imagens',
            'media_library_btn' => '📁 Biblioteca de Multimédia',
            'tip' => 'Dica:',
            'tip_content' => 'Ao adicionar produtos, lembre-se de preencher o conteúdo em todos os 4 idiomas (Inglês, Português, Chinês Simplificado, Chinês Tradicional) para garantir que todos os visitantes possam ver as informações completas.',
            'site_stats' => 'Estatísticas do Site',
            'published_products' => 'Produtos Publicados',
            'draft_products' => 'Rascunhos',
            'product_categories_count' => 'Categorias',
            'quick_links' => 'Links Rápidos',
            'add_product_link' => 'Adicionar Novo Produto',
            'manage_products_link' => 'Gerir Produtos',
            'theme_settings_link' => 'Configurações do Tema',
            'theme_footer' => 'Tema Angola B2B | Versão',
        ),
    );
    
    // 如果是葡语，返回葡语翻译
    if ($user_locale === 'pt_PT' && isset($translations['pt_PT'][$key])) {
        return $translations['pt_PT'][$key];
    }
    
    // 默认返回中文
    if (isset($translations['zh_CN'][$key])) {
        return $translations['zh_CN'][$key];
    }
    
    return $key;
}

/**
 * 重命名菜单项 - 使其更符合业务语言（多语言支持）
 */
function angola_b2b_rename_admin_menu_items() {
    global $menu, $submenu;
    
    // === 重命名主菜单 ===
    foreach ($menu as $key => $item) {
        // 将"文章"重命名为"新闻管理"
        if ($item[0] === 'Artigos') {
            $menu[$key][0] = angola_b2b_admin_translate('news_management');
        }
        
        // 将"产品管理"添加图标和翻译
        if (strpos($item[2], 'post_type=product') !== false) {
            $menu[$key][0] = angola_b2b_admin_translate('product_management');
        }
        
        // 将"媒体"添加图标和翻译
        if ($item[2] === 'upload.php') {
            $menu[$key][0] = angola_b2b_admin_translate('media_library');
        }
        
        // 将"用户"重命名为"员工管理"
        if ($item[2] === 'users.php') {
            $menu[$key][0] = angola_b2b_admin_translate('staff_management');
        }
    }
    
    // === 重命名产品子菜单 ===
    if (isset($submenu['edit.php?post_type=product'])) {
        foreach ($submenu['edit.php?post_type=product'] as $key => $item) {
            // "所有产品" 或 "Produtos" (WordPress核心翻译)
            if ($item[0] === 'Produtos' || $item[0] === '所有产品') {
                $submenu['edit.php?post_type=product'][$key][0] = angola_b2b_admin_translate('all_products');
            }
            // "添加新产品" 或 "Adicionar novo" (WordPress核心翻译)
            if ($item[0] === 'Adicionar novo' || $item[0] === '添加新产品') {
                $submenu['edit.php?post_type=product'][$key][0] = angola_b2b_admin_translate('add_new_product');
            }
            // "产品分类" 或 "Categorias" (自定义分类)
            if (strpos($item[0], 'Categorias') !== false || strpos($item[0], '产品分类') !== false) {
                $submenu['edit.php?post_type=product'][$key][0] = angola_b2b_admin_translate('product_categories');
            }
            // "产品标签" 或 "Tags"
            if (strpos($item[0], 'Tags') !== false || strpos($item[0], '产品标签') !== false) {
                $submenu['edit.php?post_type=product'][$key][0] = angola_b2b_admin_translate('product_tags');
            }
        }
    }
    
    // === 重命名新闻子菜单 ===
    if (isset($submenu['edit.php'])) {
        foreach ($submenu['edit.php'] as $key => $item) {
            // "所有文章" 或 "Todos os artigos"
            if ($item[0] === 'Todos os artigos' || $item[0] === '所有文章') {
                $submenu['edit.php'][$key][0] = angola_b2b_admin_translate('all_news');
            }
            // "添加文章" 或 "Adicionar artigo"
            if ($item[0] === 'Adicionar artigo' || $item[0] === '添加文章') {
                $submenu['edit.php'][$key][0] = angola_b2b_admin_translate('add_news');
            }
            // "分类目录" 或 "Categorias"
            if ($item[0] === 'Categorias' || $item[0] === '分类目录') {
                $submenu['edit.php'][$key][0] = angola_b2b_admin_translate('news_categories');
            }
        }
    }
}
add_action('admin_menu', 'angola_b2b_rename_admin_menu_items', 9999);

/**
 * 为安哥拉员工隐藏没有权限的菜单项
 * 避免他们点击后看到"您没有权限"的提示，提升用户体验
 */
function angola_b2b_hide_unauthorized_menus_for_angola_staff() {
    $current_user = wp_get_current_user();
    
    // 只针对ao_product_editor角色（安哥拉产品编辑）
    if (!in_array('ao_product_editor', $current_user->roles)) {
        return;
    }
    
    global $submenu;
    
    // 移除产品子菜单中安哥拉员工无权访问的项
    if (isset($submenu['edit.php?post_type=product'])) {
        foreach ($submenu['edit.php?post_type=product'] as $key => $item) {
            // 移除"产品分类"（安哥拉员工不能管理分类）
            if (strpos($item[2], 'taxonomy=product_category') !== false) {
                unset($submenu['edit.php?post_type=product'][$key]);
            }
            // 移除"产品标签"（安哥拉员工不能管理标签）
            if (strpos($item[2], 'taxonomy=product_tag') !== false) {
                unset($submenu['edit.php?post_type=product'][$key]);
            }
        }
    }
    
    // 移除新闻子菜单中安哥拉员工无权访问的项
    if (isset($submenu['edit.php'])) {
        foreach ($submenu['edit.php'] as $key => $item) {
            // 移除"分类目录"（安哥拉员工不能管理新闻分类）
            if (strpos($item[2], 'taxonomy=category') !== false) {
                unset($submenu['edit.php'][$key]);
            }
            // 移除"标签"（安哥拉员工不能管理新闻标签）
            if (strpos($item[2], 'taxonomy=post_tag') !== false) {
                unset($submenu['edit.php'][$key]);
            }
        }
    }
}
add_action('admin_menu', 'angola_b2b_hide_unauthorized_menus_for_angola_staff', 10000);

/**
 * 优化"工具"菜单 - 只保留业务相关的工具
 * 将首页设置提升到顶级菜单（多语言支持）
 */
function angola_b2b_reorganize_tools_menu() {
    // === 创建"首页设置"顶级菜单 ===
    add_menu_page(
        angola_b2b_admin_translate('homepage_settings'),  // 页面标题
        angola_b2b_admin_translate('homepage_settings'),  // 菜单标题
        'edit_pages',                        // 权限（管理员和产品经理可以访问）
        'post.php?post=45&action=edit',      // 直接编辑ID为45的页面
        '',                                  // 回调函数（使用WordPress内置编辑器）
        'dashicons-admin-home',              // 图标
        25                                   // 位置（在产品管理后面）
    );
    
    // === 添加"首页图片"子菜单 ===
    add_submenu_page(
        'post.php?post=45&action=edit',      // 父菜单slug
        '首页图片管理',                       // 页面标题
        '🖼️ 首页图片',                       // 菜单标题
        'edit_posts',                        // 权限
        'angola-homepage-images',            // 菜单slug
        'angola_b2b_homepage_images_page'    // 回调函数
    );
}
add_action('admin_menu', 'angola_b2b_reorganize_tools_menu', 9998);

/**
 * 隐藏管理栏中不必要的选项（多语言支持）
 */
function angola_b2b_simplify_admin_bar($wp_admin_bar) {
    // 移除WordPress logo和相关菜单
    $wp_admin_bar->remove_node('wp-logo');
    
    // 移除评论
    $wp_admin_bar->remove_node('comments');
    
    // 移除"新建" → "页面"选项（如果页面菜单已隐藏）
    $wp_admin_bar->remove_node('new-page');
    
    // 移除自定义文章类型（解决方案、行业）
    $wp_admin_bar->remove_node('new-solution');
    $wp_admin_bar->remove_node('new-industry');
    
    // ⚠️ 不要移除new-product，因为这是安哥拉员工需要的快捷方式
    // 但是如果显示为中文"产品"，我们需要用翻译来修复
    // WordPress会自动使用用户的语言设置翻译"新建产品"
    
    // 移除自定义选项
    $wp_admin_bar->remove_node('customize');
    
    // 移除主题选项
    $wp_admin_bar->remove_node('themes');
}
add_action('admin_bar_menu', 'angola_b2b_simplify_admin_bar', 999);

/**
 * 简化仪表盘小部件
 */
function angola_b2b_simplify_dashboard_widgets() {
    global $wp_meta_boxes;
    
    // 移除不需要的仪表盘小部件
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // 活动
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);       // 概况
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 近期评论
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);       // 快速草稿
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           // WordPress新闻
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         // 其他WordPress新闻
    
    // 移除插件添加的小部件 - 尝试所有可能的位置和ID
    // Wordfence
    unset($wp_meta_boxes['dashboard']['normal']['core']['wordfence_activity_report']);
    unset($wp_meta_boxes['dashboard']['normal']['high']['wordfence_activity_report_widget']);
    unset($wp_meta_boxes['dashboard']['normal']['default']['wordfence_activity_report_widget']);
    
    // Yoast SEO（如果有）
    unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
    
    // Smush
    unset($wp_meta_boxes['dashboard']['side']['core']['smush_dashboard_widget']);
    
    // 移除默认欢迎面板（我们有自定义的）
    remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('wp_dashboard_setup', 'angola_b2b_simplify_dashboard_widgets', 999);

/**
 * 添加自定义的欢迎小部件（简洁实用，多语言支持）
 */
function angola_b2b_add_custom_dashboard_widget() {
    wp_add_dashboard_widget(
        'angola_b2b_welcome_widget',
        angola_b2b_admin_translate('welcome_widget'),
        'angola_b2b_welcome_widget_content'
    );
}
add_action('wp_dashboard_setup', 'angola_b2b_add_custom_dashboard_widget');

/**
 * 欢迎小部件内容
 */
function angola_b2b_welcome_widget_content() {
    $current_user = wp_get_current_user();
    $user_display_name = $current_user->display_name;
    
    // 获取统计数据
    $product_count = wp_count_posts('product')->publish;
    $post_count = wp_count_posts('post')->publish;
    
    ?>
    <div style="padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
        <h2 style="margin-top: 0; color: #1d4ed8;">
            <?php echo angola_b2b_admin_translate('hello'); ?>，<?php echo esc_html($user_display_name); ?>！
        </h2>
        
        <p style="font-size: 16px; line-height: 1.6; color: #4b5563;">
            <?php echo angola_b2b_admin_translate('welcome_back'); ?>
        </p>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin: 20px 0;">
            <div style="background: #eff6ff; padding: 15px; border-radius: 8px; border-left: 4px solid #3b82f6;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">
                    <?php echo angola_b2b_admin_translate('product_count'); ?>
                </div>
                <div style="font-size: 32px; font-weight: bold; color: #1d4ed8;"><?php echo $product_count; ?></div>
            </div>
            <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border-left: 4px solid #10b981;">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">
                    <?php echo angola_b2b_admin_translate('news_count'); ?>
                </div>
                <div style="font-size: 32px; font-weight: bold; color: #059669;"><?php echo $post_count; ?></div>
            </div>
        </div>
        
        <h3 style="margin-top: 30px; margin-bottom: 15px; color: #1f2937;">
            <?php echo angola_b2b_admin_translate('quick_actions'); ?>
        </h3>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" 
               class="button button-primary" 
               style="text-decoration: none;">
                <?php echo angola_b2b_admin_translate('add_product_btn'); ?>
            </a>
            <a href="<?php echo admin_url('post-new.php'); ?>" 
               class="button button-primary" 
               style="text-decoration: none;">
                <?php echo angola_b2b_admin_translate('add_news_btn'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=angola-homepage-images'); ?>" 
               class="button" 
               style="text-decoration: none;">
                <?php echo angola_b2b_admin_translate('homepage_images_btn'); ?>
            </a>
            <a href="<?php echo admin_url('upload.php'); ?>" 
               class="button" 
               style="text-decoration: none;">
                <?php echo angola_b2b_admin_translate('media_library_btn'); ?>
            </a>
        </div>
        
        <div style="margin-top: 30px; padding: 15px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
            <strong style="color: #92400e;">💡 <?php echo angola_b2b_admin_translate('tip'); ?></strong>
            <p style="margin: 10px 0 0 0; color: #78350f; line-height: 1.6;">
                <?php echo angola_b2b_admin_translate('tip_content'); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * 简化屏幕选项（移除不必要的复选框）
 */
function angola_b2b_simplify_screen_options() {
    // 移除帮助选项卡（简化界面）
    $screen = get_current_screen();
    if ($screen) {
        $screen->remove_help_tabs();
    }
}
add_action('current_screen', 'angola_b2b_simplify_screen_options');

/**
 * 翻译自定义文章类型的标签（多语言支持）
 */
function angola_b2b_translate_cpt_labels($translation, $text, $domain) {
    // 只为葡语用户翻译
    if (get_user_locale() !== 'pt_PT' || $domain !== 'angola-b2b') {
        return $translation;
    }
    
    // 产品相关翻译
    $translations = array(
        '产品' => 'Produto',
        '产品管理' => 'Gestão de Produtos',
        '产品列表' => 'Lista de Produtos',
        '产品属性' => 'Atributos do Produto',
        '父级产品:' => 'Produto Pai:',
        '所有产品' => 'Todos os Produtos',
        '添加新产品' => 'Adicionar Novo Produto',
        '添加产品' => 'Adicionar Produto',
        '新产品' => 'Novo Produto',
        '编辑产品' => 'Editar Produto',
        '更新产品' => 'Atualizar Produto',
        '查看产品' => 'Ver Produto',
        '搜索产品' => 'Pesquisar Produtos',
        '未找到产品' => 'Nenhum produto encontrado',
        '回收站中未找到产品' => 'Nenhum produto encontrado na lixeira',
        '产品主图' => 'Imagem Principal do Produto',
        '设置产品主图' => 'Definir Imagem Principal',
        '移除产品主图' => 'Remover Imagem Principal',
        '使用产品主图' => 'Usar Imagem Principal',
        '插入到产品' => 'Inserir no Produto',
        '上传到此产品' => 'Carregado neste Produto',
        '筛选产品列表' => 'Filtrar Lista de Produtos',
        'B2B产品展示' => 'Exibição de Produtos B2B',
        
        // 解决方案（虽然已隐藏，但为了完整性）
        '解决方案' => 'Soluções',
        
        // 行业（虽然已隐藏，但为了完整性）
        '行业' => 'Indústrias',
    );
    
    if (isset($translations[$text])) {
        return $translations[$text];
    }
    
    return $translation;
}
add_filter('gettext', 'angola_b2b_translate_cpt_labels', 20, 3);

/**
 * 为管理员添加开关：是否启用简化菜单
 * （可选功能，暂时注释掉，如需要可启用）
 */
/*
function angola_b2b_add_simplification_toggle() {
    if (current_user_can('manage_options')) {
        $simplified = get_option('angola_b2b_simplified_menu', true);
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <strong>管理菜单简化：</strong>
                <?php if ($simplified): ?>
                    当前为<strong>简化模式</strong>。
                    <a href="<?php echo admin_url('?angola_toggle_menu=0'); ?>">切换到完整模式</a>
                <?php else: ?>
                    当前为<strong>完整模式</strong>。
                    <a href="<?php echo admin_url('?angola_toggle_menu=1'); ?>">切换到简化模式</a>
                <?php endif; ?>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'angola_b2b_add_simplification_toggle');
*/

/**
 * 使用CSS和JavaScript隐藏顽固的菜单项
 * 使用admin_footer钩子确保在所有插件加载后执行
 */
function angola_b2b_hide_menu_items_css_js() {
    ?>
    <style>
        /* 隐藏不需要的菜单项 - 使用多种选择器确保覆盖所有情况 */
        #toplevel_page_edit-post_type-solution,    /* 解决方案 */
        #menu-posts-solution,                      /* 解决方案（备用）*/
        #toplevel_page_edit-post_type-industry,    /* 行业 */
        #menu-posts-industry,                      /* 行业（备用）*/
        li.menu-top[id*="solution"],               /* 任何包含solution的菜单 */
        li.menu-top[id*="industry"],               /* 任何包含industry的菜单 */
        #toplevel_page_acf-options,                /* ACF */
        li.toplevel_page_acf-options,              /* ACF（备用）*/
        #toplevel_page_updraftplus,                /* UpdraftPlus */
        li.toplevel_page_updraftplus,              /* UpdraftPlus（备用）*/
        li.toplevel_page_loco-translate,           /* Loco Translate */
        #toplevel_page_loco-translate,             /* Loco Translate（备用）*/
        #toplevel_page_Wordfence,                  /* Wordfence（注意大写）*/
        li.toplevel_page_Wordfence,                /* Wordfence（备用）*/
        #toplevel_page_wordfence,                  /* Wordfence */
        #toplevel_page_smush,                      /* Smush */
        #toplevel_page_wpcf7,                      /* Contact Form 7 */
        li.wp-menu-separator {                     /* 分隔线 */
            display: none !important;
        }
        
        /* 隐藏顶部管理栏中的相关项 */
        #wp-admin-bar-new-solution,
        #wp-admin-bar-new-industry,
        #wp-admin-bar-new-page,
        #wp-admin-bar-updraftplus,
        #wp-admin-bar-wordfence {
            display: none !important;
        }
        
        /* 隐藏仪表盘中顽固的小部件 */
        #wordfence_activity_report_widget,
        #smush_dashboard_widget,
        .welcome-panel-content {
            display: none !important;
        }
    </style>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // 删除不需要的菜单项
        function hideStubbornMenuItems() {
            // 遍历所有菜单项，删除不需要的
            $('li.menu-top').each(function() {
                var $this = $(this);
                var id = $this.attr('id') || '';
                var text = $this.text().toLowerCase();
                
                // 检查ID或文本内容，删除匹配的菜单项
                if (id.indexOf('solution') > -1 || 
                    id.indexOf('industry') > -1 ||
                    id.indexOf('acf') > -1 ||
                    id.indexOf('updraft') > -1 ||
                    id.indexOf('loco') > -1 ||
                    id.indexOf('wordfence') > -1 ||
                    id.indexOf('smush') > -1 ||
                    id.indexOf('wpcf7') > -1 ||
                    text.indexOf('解决方案') > -1 ||
                    text.indexOf('acf') > -1 ||
                    text.indexOf('updraftplus') > -1 ||
                    text.indexOf('loco translate') > -1) {
                    $this.remove();
                }
            });
            
            // 删除顶部管理栏中的相关项
            $('#wp-admin-bar-new-solution, #wp-admin-bar-new-industry, #wp-admin-bar-new-page').remove();
            $('#wp-admin-bar-updraftplus, #wp-admin-bar-wordfence').remove();
            
            // 删除仪表盘小部件
            $('#wordfence_activity_report_widget, #smush_dashboard_widget').closest('.postbox').remove();
            $('.welcome-panel-content').closest('.welcome-panel').remove();
        }
        
        // 立即执行
        hideStubbornMenuItems();
        
        // 延迟执行确保所有插件都加载完成
        setTimeout(hideStubbornMenuItems, 500);
        setTimeout(hideStubbornMenuItems, 1000);
    });
    </script>
    <?php
}
add_action('admin_footer', 'angola_b2b_hide_menu_items_css_js');

