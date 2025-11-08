<?php
/**
 * Admin Customization
 * Customize WordPress admin interface for better Chinese UX
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set admin language to Simplified Chinese
 */
function angola_b2b_set_admin_locale($locale) {
    if (is_admin()) {
        return 'zh_CN';
    }
    return $locale;
}
add_filter('locale', 'angola_b2b_set_admin_locale');

/**
 * Add custom admin columns for Product post type
 */
function angola_b2b_product_admin_columns($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // Add thumbnail column after checkbox
        if ($key === 'cb') {
            $new_columns['product_thumbnail'] = __('图片', 'angola-b2b');
        }
        
        // Add category column after title
        if ($key === 'title') {
            $new_columns['product_category'] = __('分类', 'angola-b2b');
            $new_columns['product_featured'] = __('推荐', 'angola-b2b');
        }
    }
    
    return $new_columns;
}
add_filter('manage_product_posts_columns', 'angola_b2b_product_admin_columns');

/**
 * Populate custom admin columns for Product post type
 */
function angola_b2b_product_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'product_thumbnail':
            $thumbnail = get_the_post_thumbnail($post_id, 'thumbnail');
            echo $thumbnail ? $thumbnail : '—';
            break;
            
        case 'product_category':
            $terms = get_the_terms($post_id, 'product_category');
            if ($terms && !is_wp_error($terms)) {
                $category_names = array();
                foreach ($terms as $term) {
                    $category_names[] = esc_html($term->name);
                }
                echo implode(', ', $category_names);
            } else {
                echo '—';
            }
            break;
            
        case 'product_featured':
            $is_featured = get_post_meta($post_id, 'product_featured', true);
            if ($is_featured === '1' || $is_featured === 1) {
                echo '<span class="dashicons dashicons-star-filled featured-icon" style="color:#f0b429" aria-label="' . esc_attr__('推荐产品', 'angola-b2b') . '"></span>';
            } else {
                echo '<span aria-hidden="true">—</span>';
            }
            break;
    }
}
add_action('manage_product_posts_custom_column', 'angola_b2b_product_admin_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function angola_b2b_product_sortable_columns($columns) {
    $columns['product_category'] = 'product_category';
    $columns['product_featured'] = 'product_featured';
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'angola_b2b_product_sortable_columns');

/**
 * Add admin notice for theme setup
 */
function angola_b2b_admin_notices() {
    $screen = get_current_screen();
    
    // Only show on product pages for new installations
    if ($screen && $screen->post_type === 'product') {
        $products_count = wp_count_posts('product');
        
        if ($products_count->publish < 1) {
            ?>
            <div class="notice notice-info">
                <p><strong><?php esc_html_e('欢迎使用Angola B2B主题！', 'angola-b2b'); ?></strong></p>
                <p><?php esc_html_e('开始添加您的第一个产品吧。确保已安装并激活ACF Pro插件以使用完整功能。', 'angola-b2b'); ?></p>
            </div>
            <?php
        }
    }
}
add_action('admin_notices', 'angola_b2b_admin_notices');

/**
 * Add custom dashboard widget (多语言支持)
 */
function angola_b2b_dashboard_widget() {
    // 使用自定义翻译函数（在admin-menu-simplification.php中定义）
    if (function_exists('angola_b2b_admin_translate')) {
        $widget_title = angola_b2b_admin_translate('theme_info_widget');
    } else {
        $widget_title = 'Angola B2B 主题信息';
    }
    
    wp_add_dashboard_widget(
        'angola_b2b_dashboard_widget',
        $widget_title,
        'angola_b2b_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'angola_b2b_dashboard_widget');

/**
 * Dashboard widget content (多语言支持)
 */
function angola_b2b_dashboard_widget_content() {
    $products_count = wp_count_posts('product');
    $categories_count = wp_count_terms(array('taxonomy' => 'product_category', 'hide_empty' => false));
    
    if (is_wp_error($categories_count)) {
        $categories_count = 0;
    }
    
    // 使用自定义翻译函数
    $t = function($key) {
        if (function_exists('angola_b2b_admin_translate')) {
            return angola_b2b_admin_translate($key);
        }
        // 默认中文
        $defaults = array(
            'site_stats' => '网站统计',
            'published_products' => '已发布产品',
            'draft_products' => '草稿产品',
            'product_categories_count' => '产品分类',
            'quick_links' => '快速链接',
            'add_product_link' => '添加新产品',
            'manage_products_link' => '管理产品',
            'theme_settings_link' => '主题设置',
        );
        return isset($defaults[$key]) ? $defaults[$key] : $key;
    };
    
    ?>
    <div class="angola-b2b-dashboard-widget">
        <h3><?php echo esc_html($t('site_stats')); ?></h3>
        <ul>
            <li><strong><?php echo esc_html($t('published_products')); ?>:</strong> <?php echo esc_html($products_count->publish); ?></li>
            <li><strong><?php echo esc_html($t('draft_products')); ?>:</strong> <?php echo esc_html($products_count->draft); ?></li>
            <li><strong><?php echo esc_html($t('product_categories_count')); ?>:</strong> <?php echo esc_html($categories_count); ?></li>
        </ul>
        
        <h3><?php echo esc_html($t('quick_links')); ?></h3>
        <ul>
            <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><?php echo esc_html($t('add_product_link')); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>"><?php echo esc_html($t('manage_products_link')); ?></a></li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=theme-general-settings')); ?>"><?php echo esc_html($t('theme_settings_link')); ?></a></li>
        </ul>
    </div>
    <style>
        .angola-b2b-dashboard-widget ul {
            margin-left: 1.5em;
        }
        .angola-b2b-dashboard-widget h3 {
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
    </style>
    <?php
}

/**
 * Remove unnecessary dashboard widgets
 */
function angola_b2b_remove_dashboard_widgets() {
    // Remove WordPress Events and News
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    // Remove Quick Draft
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'angola_b2b_remove_dashboard_widgets');

/**
 * Add helpful admin notice for product editing (多语言支持)
 */
function angola_b2b_product_edit_help_notice() {
    $screen = get_current_screen();
    
    // Only show on product edit screen
    if ($screen && $screen->post_type === 'product' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        // 获取当前用户的语言设置
        $user_locale = get_user_locale();
        
        // 翻译函数（使用admin-menu-simplification.php中的翻译系统）
        $t = function($key) use ($user_locale) {
            $translations = array(
                'zh_CN' => array(
                    'product_edit_tips' => '💡 产品编辑提示：',
                    'product_name' => '产品名称',
                    'product_name_tip' => '请在页面内容区域最顶部的"添加标题"输入框中输入（不是页面顶部的搜索框）',
                    'product_description' => '产品描述',
                    'product_description_tip' => '在"添加标题"下方的内容编辑器中输入详细描述',
                    'product_main_image' => '产品主图',
                    'product_main_image_tip' => '在右侧"产品主图"面板中上传（建议尺寸：1200×900px，宽高比4:3）',
                    'product_other_info' => '其他信息',
                    'product_other_info_tip' => '在右侧"产品基本信息"面板中填写型号、SKU等字段',
                ),
                'pt_PT' => array(
                    'product_edit_tips' => '💡 Dicas de Edição de Produtos:',
                    'product_name' => 'Nome do Produto',
                    'product_name_tip' => 'Digite na caixa "Adicionar título" no topo da área de conteúdo (não na caixa de pesquisa do topo da página)',
                    'product_description' => 'Descrição do Produto',
                    'product_description_tip' => 'Digite a descrição detalhada no editor de conteúdo abaixo de "Adicionar título"',
                    'product_main_image' => 'Imagem Principal',
                    'product_main_image_tip' => 'Carregue no painel "Imagem Principal do Produto" à direita (tamanho recomendado: 1200×900px, proporção 4:3)',
                    'product_other_info' => 'Outras Informações',
                    'product_other_info_tip' => 'Preencha modelo, SKU e outros campos no painel "Informações Básicas do Produto" à direita',
                ),
            );
            
            if ($user_locale === 'pt_PT' && isset($translations['pt_PT'][$key])) {
                return $translations['pt_PT'][$key];
            }
            
            // 默认返回中文
            if (isset($translations['zh_CN'][$key])) {
                return $translations['zh_CN'][$key];
            }
            
            return $key;
        };
        
        ?>
        <div class="notice notice-info" style="border-left-color: #0073aa;">
            <p><strong style="color: #0073aa;"><?php echo esc_html($t('product_edit_tips')); ?></strong></p>
            <ul style="margin-left: 20px; line-height: 1.8;">
                <li>📝 <strong><?php echo esc_html($t('product_name')); ?></strong>：<?php echo esc_html($t('product_name_tip')); ?></li>
                <li>📄 <strong><?php echo esc_html($t('product_description')); ?></strong>：<?php echo esc_html($t('product_description_tip')); ?></li>
                <li>🖼️ <strong><?php echo esc_html($t('product_main_image')); ?></strong>：<?php echo esc_html($t('product_main_image_tip')); ?></li>
                <li>📋 <strong><?php echo esc_html($t('product_other_info')); ?></strong>：<?php echo esc_html($t('product_other_info_tip')); ?></li>
            </ul>
        </div>
        <?php
    }
}
add_action('admin_notices', 'angola_b2b_product_edit_help_notice');

/**
 * Add image size hint for product featured image (多语言支持)
 */
function angola_b2b_product_featured_image_hint($content, $post_id, $thumbnail_id) {
    // Only add hint for product post type
    if (get_post_type($post_id) === 'product') {
        // 获取当前用户的语言设置
        $user_locale = get_user_locale();
        
        // 定义翻译
        $translations = array(
            'zh_CN' => array(
                'title' => '📐 产品主图尺寸建议：',
                'aspect_ratio' => '• <strong>最佳宽高比：4:3</strong>（例如：1200×900px 或 1600×1200px）',
                'recommended_size' => '• 推荐尺寸：<strong>1200×900 像素</strong> 或 <strong>1600×1200 像素</strong>',
                'file_format' => '• 文件格式：JPG 或 WebP',
                'file_size' => '• 文件大小：建议控制在 200KB 以内',
                'warning' => '⚠️ 请务必使用 4:3 的宽高比，其他比例会导致图片变形或被裁剪！',
            ),
            'pt_PT' => array(
                'title' => '📐 Recomendações de Tamanho da Imagem:',
                'aspect_ratio' => '• <strong>Melhor proporção: 4:3</strong> (por exemplo: 1200×900px ou 1600×1200px)',
                'recommended_size' => '• Tamanho recomendado: <strong>1200×900 pixels</strong> ou <strong>1600×1200 pixels</strong>',
                'file_format' => '• Formato do arquivo: JPG ou WebP',
                'file_size' => '• Tamanho do arquivo: recomendado manter abaixo de 200KB',
                'warning' => '⚠️ Certifique-se de usar a proporção 4:3, outras proporções podem distorcer ou cortar a imagem!',
            ),
        );
        
        // 选择翻译
        $t = $user_locale === 'pt_PT' && isset($translations['pt_PT']) ? $translations['pt_PT'] : $translations['zh_CN'];
        
        $hint = '<p class="product-image-hint" style="margin-top: 10px; padding: 10px; background: #e7f5fe; border-left: 4px solid #0073aa; font-size: 13px; line-height: 1.6;">';
        $hint .= '<strong style="color: #0073aa;">' . $t['title'] . '</strong><br>';
        $hint .= $t['aspect_ratio'] . '<br>';
        $hint .= $t['recommended_size'] . '<br>';
        $hint .= $t['file_format'] . '<br>';
        $hint .= $t['file_size'] . '<br>';
        $hint .= '<span style="color: #d63638;">' . $t['warning'] . '</span>';
        $hint .= '</p>';
        
        $content .= $hint;
    }
    
    return $content;
}
add_filter('admin_post_thumbnail_html', 'angola_b2b_product_featured_image_hint', 10, 3);

/**
 * Add custom admin styles to ensure title field is visible
 * 支持经典编辑器和Gutenberg编辑器
 */
function angola_b2b_product_admin_styles() {
    $screen = get_current_screen();
    
    // Only load on product edit screen
    if ($screen && $screen->post_type === 'product' && ($screen->base === 'post' || $screen->base === 'post-new')) {
        ?>
        <style>
            /* ===== 经典编辑器（Classic Editor）样式 ===== */
            #titlewrap,
            #titlediv {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* 高亮标题输入框 - 经典编辑器 */
            #titlediv {
                border: 3px solid #0073aa !important;
                border-radius: 6px;
                padding: 15px;
                margin-bottom: 20px;
                background: #e7f5fe;
                box-shadow: 0 2px 8px rgba(0, 115, 170, 0.2);
            }
            
            #titlediv label {
                display: block !important;
                font-size: 16px !important;
                font-weight: 700 !important;
                color: #0073aa !important;
                margin-bottom: 10px !important;
            }
            
            #titlediv #title {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                font-size: 18px !important;
                padding: 12px !important;
                border: 2px solid #0073aa !important;
                border-radius: 4px;
                background: white !important;
            }
            
            #titlediv #title::placeholder {
                color: #0073aa !important;
                opacity: 0.7;
            }
            
            /* 添加醒目的提示文字 - 经典编辑器 */
            #titlediv::before {
                content: "👇 请在下方输入产品名称";
                display: block;
                background: #0073aa;
                color: white;
                padding: 8px 12px;
                margin: -15px -15px 15px -15px;
                border-radius: 3px 3px 0 0;
                font-weight: 700;
                font-size: 14px;
            }
            
            /* ===== Gutenberg编辑器样式 ===== */
            .editor-post-title,
            .editor-post-title__input,
            .wp-block-post-title {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            /* 高亮标题区域 - Gutenberg */
            .editor-post-title {
                border: 3px solid #0073aa;
                border-radius: 6px;
                padding: 15px;
                margin-bottom: 20px;
                background: #e7f5fe;
                box-shadow: 0 2px 8px rgba(0, 115, 170, 0.2);
            }
            
            .editor-post-title__input {
                font-size: 18px !important;
                color: #111 !important;
            }
            
            .editor-post-title__input::placeholder {
                color: #0073aa !important;
                font-weight: 500;
            }
            
            /* 添加醒目的提示文字 - Gutenberg */
            .editor-post-title::before {
                content: "👇 请在下方输入产品名称";
                display: block;
                background: #0073aa;
                color: white;
                padding: 8px 12px;
                margin: -15px -15px 15px -15px;
                border-radius: 3px 3px 0 0;
                font-weight: 700;
                font-size: 14px;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'angola_b2b_product_admin_styles');

/**
 * Customize admin footer text (多语言支持)
 */
function angola_b2b_admin_footer_text() {
    // 使用自定义翻译函数
    if (function_exists('angola_b2b_admin_translate')) {
        $footer_text = angola_b2b_admin_translate('theme_footer');
    } else {
        $footer_text = 'Angola B2B 主题 | 版本';
    }
    
    echo '<span id="footer-thankyou">' . 
         esc_html($footer_text) . 
         ' ' . 
         esc_html(ANGOLA_B2B_VERSION) . 
         '</span>';
}
add_filter('admin_footer_text', 'angola_b2b_admin_footer_text');

