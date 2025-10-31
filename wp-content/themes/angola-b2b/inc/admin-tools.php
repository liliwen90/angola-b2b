<?php
/**
 * Admin Tools
 * 后台管理工具
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin tools menu
 */
function angola_b2b_add_tools_menu() {
    add_management_page(
        '生成测试产品',
        '生成测试产品',
        'manage_options',
        'angola-b2b-generate-products',
        'angola_b2b_generate_products_page'
    );
}
add_action('admin_menu', 'angola_b2b_add_tools_menu');

/**
 * Generate products page
 */
function angola_b2b_generate_products_page() {
    ?>
    <div class="wrap">
        <h1>🚀 生成测试产品</h1>
        
        <?php
        // Handle form submission
        if (isset($_POST['generate_products']) && check_admin_referer('angola_b2b_generate_products')) {
            angola_b2b_create_test_products();
        }
        ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>测试产品生成器</h2>
            <p>点击下面的按钮，系统会自动创建 <strong>8个测试产品</strong>：</p>
            <ul>
                <li>✅ <strong>5个库存产品</strong> - 会显示在首页"库存产品"区域</li>
                <li>✅ <strong>8个精选产品</strong> - 会显示在首页"Featured Products"区域</li>
            </ul>
            
            <form method="post">
                <?php wp_nonce_field('angola_b2b_generate_products'); ?>
                <p>
                    <button type="submit" name="generate_products" class="button button-primary button-large">
                        🎁 立即生成测试产品
                    </button>
                </p>
            </form>
            
            <hr>
            <p><strong>注意：</strong>如果产品已存在，系统会自动跳过，不会重复创建。</p>
        </div>
    </div>
    <?php
}

/**
 * Create test products
 */
function angola_b2b_create_test_products() {
    // 图片目录路径
    $images_dir = 'F:/011 Projects/UnibroWeb/Unirbro/PICS for TEST/';
    
    $test_products = array(
        array(
            'title' => 'LED灯泡套装',
            'description' => '高效节能LED灯泡，适用于家庭和商业场所。亮度可调，使用寿命长达25000小时。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 200,
            'category' => '照明设备',
            'image' => '1.jpeg',
        ),
        array(
            'title' => '建筑用水泥',
            'description' => '优质建筑水泥，强度高，适用于各类建筑工程。符合国际建筑标准。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 500,
            'category' => '建筑材料',
            'image' => '2.jpeg',
        ),
        array(
            'title' => '办公文具套装',
            'description' => '包含笔、笔记本、订书机等常用办公用品。适合企业批量采购。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 150,
            'category' => '办公用品',
            'image' => '3.jpg',
        ),
        array(
            'title' => '五金工具箱',
            'description' => '专业五金工具套装，包含螺丝刀、扳手、钳子等。适合家庭和工业使用。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 80,
            'category' => '五金工具',
            'image' => '4.jpeg',
        ),
        array(
            'title' => '手机保护壳套装',
            'description' => '适配多款手机型号，TPU材质，防摔耐用。颜色多样可选。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'category' => '电子配件',
            'image' => '5.jpg',
        ),
        array(
            'title' => '儿童玩具套装',
            'description' => '安全环保儿童玩具，适合3-12岁儿童。通过国际安全认证。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'category' => '玩具',
            'image' => '6.jpg',
        ),
        array(
            'title' => '电动螺丝刀',
            'description' => '充电式电动螺丝刀，家庭维修必备工具。多档调速，操作简便。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'category' => '电动工具',
            'image' => '7.jpg',
        ),
        array(
            'title' => '家用塑料收纳箱',
            'description' => '耐用塑料收纳箱，多种尺寸可选。防潮防尘，适合家庭和仓库使用。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'category' => '家居用品',
            'image' => '8.jpg',
        ),
    );
    
    $created_count = 0;
    $skipped_count = 0;
    $stock_count = 0;
    $featured_count = 0;
    
    echo '<div class="notice notice-info"><p>开始创建产品...</p></div>';
    
    foreach ($test_products as $product_data) {
        // Check if product already exists
        $existing = get_page_by_title($product_data['title'], OBJECT, 'product');
        
        if ($existing) {
            echo '<div class="notice notice-warning inline"><p>⏩ 产品已存在，跳过：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
            $skipped_count++;
            continue;
        }
        
        // Create product
        $product_id = wp_insert_post(array(
            'post_title'   => $product_data['title'],
            'post_content' => $product_data['description'],
            'post_status'  => 'publish',
            'post_type'    => 'product',
        ));
        
        if (is_wp_error($product_id)) {
            echo '<div class="notice notice-error inline"><p>❌ 创建失败：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
            continue;
        }
        
        // Add category
        $category_term = get_term_by('name', $product_data['category'], 'product_category');
        if (!$category_term) {
            $category_result = wp_insert_term($product_data['category'], 'product_category');
            if (!is_wp_error($category_result)) {
                wp_set_post_terms($product_id, array($category_result['term_id']), 'product_category');
            }
        } else {
            wp_set_post_terms($product_id, array($category_term->term_id), 'product_category');
        }
        
        // Add ACF fields
        update_field('product_short_description', $product_data['description'], $product_id);
        update_field('product_featured', $product_data['is_featured'] ? '1' : '0', $product_id);
        
        if ($product_data['is_stock']) {
            update_field('product_in_stock', '1', $product_id);
            update_field('product_stock_quantity', $product_data['stock_quantity'], $product_id);
            update_field('product_stock_badge_text', '现货', $product_id);
            $stock_count++;
        } else {
            update_field('product_in_stock', '0', $product_id);
        }
        
        if ($product_data['is_featured']) {
            $featured_count++;
        }
        
        // Add some specifications
        update_field('spec_name_1', '产品材质', $product_id);
        update_field('spec_value_1', '优质材料', $product_id);
        update_field('spec_name_2', '产地', $product_id);
        update_field('spec_value_2', '中国', $product_id);
        update_field('spec_name_3', '质保期', $product_id);
        update_field('spec_value_3', '1年', $product_id);
        
        // Upload and set featured image
        if (!empty($product_data['image'])) {
            $image_path = $images_dir . $product_data['image'];
            if (file_exists($image_path)) {
                $attachment_id = angola_b2b_upload_image_from_path($image_path, $product_id);
                if ($attachment_id) {
                    set_post_thumbnail($product_id, $attachment_id);
                    echo '<div class="notice notice-success inline"><p>✅ 创建成功（含图片）：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
                } else {
                    echo '<div class="notice notice-success inline"><p>✅ 创建成功（图片上传失败）：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
                }
            } else {
                echo '<div class="notice notice-success inline"><p>✅ 创建成功（图片文件不存在）：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
            }
        } else {
            echo '<div class="notice notice-success inline"><p>✅ 创建成功：<strong>' . esc_html($product_data['title']) . '</strong></p></div>';
        }
        
        $created_count++;
    }
    
    // Summary
    echo '<div class="notice notice-success is-dismissible">';
    echo '<h3>🎉 完成！</h3>';
    echo '<ul>';
    echo '<li><strong>新创建产品：</strong>' . $created_count . ' 个</li>';
    echo '<li><strong>跳过产品：</strong>' . $skipped_count . ' 个</li>';
    echo '<li><strong>库存产品：</strong>' . $stock_count . ' 个</li>';
    echo '<li><strong>精选产品：</strong>' . $featured_count . ' 个</li>';
    echo '</ul>';
    echo '<p><a href="' . home_url() . '" class="button button-primary" target="_blank">🏠 查看首页</a> ';
    echo '<a href="' . admin_url('edit.php?post_type=product') . '" class="button">📦 查看所有产品</a></p>';
    echo '</div>';
}

/**
 * Upload image from file path and attach to post
 */
function angola_b2b_upload_image_from_path($file_path, $post_id = 0) {
    if (!file_exists($file_path)) {
        return false;
    }
    
    // Include required WordPress files
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    // Get file info
    $filename = basename($file_path);
    $filetype = wp_check_filetype($filename);
    
    // Read file content
    $file_content = file_get_contents($file_path);
    
    // Upload to WordPress media library
    $upload = wp_upload_bits($filename, null, $file_content);
    
    if ($upload['error']) {
        return false;
    }
    
    // Prepare attachment data
    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    
    // Insert attachment
    $attachment_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
    
    if (is_wp_error($attachment_id)) {
        return false;
    }
    
    // Generate metadata
    $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    wp_update_attachment_metadata($attachment_id, $attachment_data);
    
    return $attachment_id;
}

