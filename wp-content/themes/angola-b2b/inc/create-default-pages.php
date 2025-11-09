<?php
/**
 * Create default pages (About, Services, Careers)
 * This function will be called on theme activation
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create default content pages
 */
function angola_b2b_create_default_pages() {
    $pages = array(
        array(
            'post_title'    => 'About Us',
            'post_name'     => 'about',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'page_template' => 'page-about.php'
        ),
        array(
            'post_title'    => 'Services',
            'post_name'     => 'services',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'page_template' => 'page-services.php'
        ),
        array(
            'post_title'    => 'Careers',
            'post_name'     => 'careers',
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'page_template' => 'page-careers.php'
        ),
    );

    foreach ($pages as $page) {
        // Check if page already exists
        $existing_page = get_page_by_path($page['post_name']);
        
        if (!$existing_page) {
            // Create the page
            $page_id = wp_insert_post($page);
            
            if ($page_id && !is_wp_error($page_id)) {
                // Set the page template
                update_post_meta($page_id, '_wp_page_template', $page['page_template']);
            }
        } else {
            // Update the template if page exists
            update_post_meta($existing_page->ID, '_wp_page_template', $page['page_template']);
        }
    }
}

/**
 * Run on theme activation
 */
add_action('after_switch_theme', 'angola_b2b_create_default_pages');

/**
 * Admin notice to create pages manually if needed
 */
function angola_b2b_pages_admin_notice() {
    // Check if pages exist
    $about_page = get_page_by_path('about');
    $services_page = get_page_by_path('services');
    $careers_page = get_page_by_path('careers');
    
    if (!$about_page || !$services_page || !$careers_page) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>Angola B2B Theme:</strong> 
                需要创建默认页面（关于我们、我们的服务、招聘）。
                <a href="<?php echo admin_url('admin.php?page=angola-b2b-create-pages'); ?>" class="button button-primary">
                    立即创建
                </a>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'angola_b2b_pages_admin_notice');

/**
 * Add admin menu for creating pages
 */
function angola_b2b_add_create_pages_menu() {
    add_submenu_page(
        'themes.php',
        '创建默认页面',
        '创建默认页面',
        'manage_options',
        'angola-b2b-create-pages',
        'angola_b2b_create_pages_page'
    );
}
add_action('admin_menu', 'angola_b2b_add_create_pages_menu');

/**
 * Create pages admin page
 */
function angola_b2b_create_pages_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle form submission
    if (isset($_POST['create_pages']) && check_admin_referer('angola_b2b_create_pages')) {
        angola_b2b_create_default_pages();
        echo '<div class="notice notice-success"><p>页面创建成功！</p></div>';
    }
    
    // Check which pages exist
    $about_page = get_page_by_path('about');
    $services_page = get_page_by_path('services');
    $careers_page = get_page_by_path('careers');
    ?>
    <div class="wrap">
        <h1>创建默认页面</h1>
        
        <div class="card">
            <h2>页面状态</h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>页面</th>
                        <th>状态</th>
                        <th>链接</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>关于我们 (About Us)</td>
                        <td>
                            <?php if ($about_page): ?>
                                <span style="color: green;">✓ 已创建</span>
                            <?php else: ?>
                                <span style="color: red;">✗ 未创建</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($about_page): ?>
                                <a href="<?php echo get_permalink($about_page->ID); ?>" target="_blank">查看页面</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>我们的服务 (Services)</td>
                        <td>
                            <?php if ($services_page): ?>
                                <span style="color: green;">✓ 已创建</span>
                            <?php else: ?>
                                <span style="color: red;">✗ 未创建</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($services_page): ?>
                                <a href="<?php echo get_permalink($services_page->ID); ?>" target="_blank">查看页面</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>招聘 (Careers)</td>
                        <td>
                            <?php if ($careers_page): ?>
                                <span style="color: green;">✓ 已创建</span>
                            <?php else: ?>
                                <span style="color: red;">✗ 未创建</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($careers_page): ?>
                                <a href="<?php echo get_permalink($careers_page->ID); ?>" target="_blank">查看页面</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <form method="post" style="margin-top: 20px;">
            <?php wp_nonce_field('angola_b2b_create_pages'); ?>
            <button type="submit" name="create_pages" class="button button-primary button-hero">
                创建/更新页面
            </button>
            <p class="description">
                点击此按钮将创建缺失的页面，或更新已存在页面的模板设置。
            </p>
        </form>
    </div>
    <?php
}

