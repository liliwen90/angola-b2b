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
    
    add_management_page(
        '重新生成缩略图',
        '重新生成缩略图',
        'manage_options',
        'angola-b2b-regenerate-thumbnails',
        'angola_b2b_regenerate_thumbnails_page'
    );
    
    add_management_page(
        '导入解决方案和行业数据',
        '导入解决方案和行业数据',
        'manage_options',
        'angola-b2b-import-content',
        'angola_b2b_import_content_page'
    );
    
    add_management_page(
        '删除所有产品和分类',
        '删除所有产品和分类',
        'manage_options',
        'angola-b2b-delete-all',
        'angola_b2b_delete_all_page'
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
            <p>点击下面的按钮，系统会自动创建 <strong>18个测试产品</strong>：</p>
            <ul>
                <li>✅ <strong>9个库存产品</strong> - 会显示在首页"库存产品"区域（可滑动查看）</li>
                <li>✅ <strong>18个精选产品</strong> - 会显示在首页"Featured Products"区域（可滑动查看）</li>
                <li>✅ 所有产品都带精美图片</li>
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
        // === 建筑工程类产品 ===
        array(
            'title' => '混凝土搅拌机',
            'description' => '大型混凝土搅拌设备，适用于各类建筑工地。高效率，低能耗，操作简便。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 15,
            'parent_category' => '建筑工程',
            'category' => '混凝土设备',
            'image' => '1.jpeg',
        ),
        array(
            'title' => '塔吊起重机',
            'description' => '建筑工地专用塔式起重机，承载能力强，安全性能高。适合高层建筑施工。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 8,
            'parent_category' => '建筑工程',
            'category' => '起重设备',
            'image' => '2.jpeg',
        ),
        array(
            'title' => '钢管脚手架',
            'description' => '建筑施工脚手架系统，坚固耐用，安装便捷。符合国际安全标准。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 500,
            'parent_category' => '建筑工程',
            'category' => '脚手架系统',
            'image' => '3.jpg',
        ),
        
        // === 建筑材料类产品 ===
        array(
            'title' => '优质水泥',
            'description' => '425#硅酸盐水泥，强度高，凝固快。适用于各类建筑工程，符合国际建筑标准。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 2000,
            'parent_category' => '建筑材料',
            'category' => '水泥',
            'image' => '4.jpeg',
        ),
        array(
            'title' => '钢筋钢材',
            'description' => '螺纹钢筋，规格齐全，强度符合国标。广泛应用于建筑结构工程。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 800,
            'parent_category' => '建筑材料',
            'category' => '钢材',
            'image' => '5.jpg',
        ),
        array(
            'title' => '外墙瓷砖',
            'description' => '高品质外墙装饰瓷砖，防水防污，色彩多样。适用于建筑外立面装修。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 5000,
            'parent_category' => '建筑材料',
            'category' => '装饰材料',
            'image' => '6.jpg',
        ),
        
        // === 农机农具类产品 ===
        array(
            'title' => '拖拉机',
            'description' => '多功能农用拖拉机，动力强劲，适合各种农田作业。省油耐用，维护简便。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 20,
            'parent_category' => '农机农具',
            'category' => '动力机械',
            'image' => '7.jpg',
        ),
        array(
            'title' => '播种机',
            'description' => '精密播种机械，播种均匀，效率高。适用于小麦、玉米等农作物种植。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 35,
            'parent_category' => '农机农具',
            'category' => '播种设备',
            'image' => '8.jpg',
        ),
        array(
            'title' => '喷灌设备',
            'description' => '农业灌溉喷灌系统，节水高效，覆盖面积广。适合大型农场使用。',
            'is_stock' => true,
            'is_featured' => true,
            'stock_quantity' => 50,
            'parent_category' => '农机农具',
            'category' => '灌溉设备',
            'image' => '9.jpeg',
        ),
        
        // === 工业设备类产品 ===
        array(
            'title' => '发电机组',
            'description' => '柴油发电机组，功率范围广，运行稳定。适用于工业生产和应急备用电源。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '工业设备',
            'category' => '动力设备',
            'image' => '11.jpg',
        ),
        array(
            'title' => '空气压缩机',
            'description' => '工业用螺杆式空压机，高效节能，噪音低。广泛应用于制造业生产线。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '工业设备',
            'category' => '压缩设备',
            'image' => '12.jpeg',
        ),
        array(
            'title' => '数控机床',
            'description' => '精密数控加工设备，加工精度高，自动化程度高。适合金属零部件加工。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '工业设备',
            'category' => '机床设备',
            'image' => '8.jpeg',
        ),
        
        // === 更多精选产品 ===
        array(
            'title' => '挖掘机',
            'description' => '中型液压挖掘机，作业效率高，操作灵活。适用于土方工程和基础建设。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '建筑工程',
            'category' => '土方设备',
            'image' => '13.jpeg',
        ),
        array(
            'title' => '砂石骨料',
            'description' => '建筑用砂石骨料，粒度均匀，质量稳定。适用于混凝土配比和道路铺设。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '建筑材料',
            'category' => '骨料砂石',
            'image' => '14.jpeg',
        ),
        array(
            'title' => '收割机',
            'description' => '联合收割机，收割、脱粒一体化作业。提高收获效率，减少粮食损耗。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '农机农具',
            'category' => '收获设备',
            'image' => '15.jpeg',
        ),
        array(
            'title' => '焊接设备',
            'description' => '工业焊接机械，焊接质量稳定，适用于钢结构制造和管道安装。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '工业设备',
            'category' => '焊接设备',
            'image' => '16.jpeg',
        ),
        array(
            'title' => '推土机',
            'description' => '履带式推土机，推土能力强，适合土地平整和道路修建工程。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '建筑工程',
            'category' => '土方设备',
            'image' => '17.jpg',
        ),
        array(
            'title' => '保温材料',
            'description' => '建筑外墙保温板材，保温隔热性能优异，防火阻燃。节能环保。',
            'is_stock' => false,
            'is_featured' => true,
            'stock_quantity' => 0,
            'parent_category' => '建筑材料',
            'category' => '保温材料',
            'image' => '18.jpeg',
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
        
        // Add category with parent-child hierarchy
        // 1. 先创建或获取父分类
        $parent_term = null;
        if (!empty($product_data['parent_category'])) {
            $parent_term = get_term_by('name', $product_data['parent_category'], 'product_category');
            if (!$parent_term) {
                $parent_result = wp_insert_term($product_data['parent_category'], 'product_category');
                if (!is_wp_error($parent_result)) {
                    $parent_term = get_term($parent_result['term_id'], 'product_category');
                }
            }
        }
        
        // 2. 创建或获取子分类（属于父分类）
        $category_term = null;
        if (!empty($product_data['category'])) {
            $category_term = get_term_by('name', $product_data['category'], 'product_category');
            if (!$category_term) {
                $category_args = array();
                if ($parent_term) {
                    $category_args['parent'] = $parent_term->term_id;
                }
                $category_result = wp_insert_term($product_data['category'], 'product_category', $category_args);
                if (!is_wp_error($category_result)) {
                    $category_term = get_term($category_result['term_id'], 'product_category');
                }
            } elseif ($parent_term && $category_term->parent != $parent_term->term_id) {
                // 如果子分类存在但父分类不对，更新父分类
                wp_update_term($category_term->term_id, 'product_category', array('parent' => $parent_term->term_id));
            }
        }
        
        // 3. 将产品分配给子分类
        if ($category_term) {
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

/**
 * Regenerate thumbnails page
 */
function angola_b2b_regenerate_thumbnails_page() {
    ?>
    <div class="wrap">
        <h1>🔄 重新生成缩略图</h1>
        
        <?php
        // Handle form submission
        if (isset($_POST['regenerate_thumbnails']) && check_admin_referer('angola_b2b_regenerate_thumbnails')) {
            angola_b2b_regenerate_all_thumbnails();
        }
        ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>重新生成所有产品图片尺寸</h2>
            <p>点击下面的按钮，系统会为所有产品图片重新生成以下尺寸：</p>
            <ul>
                <li>✅ <strong>product-card</strong> (300×300) - 首页产品卡片固定尺寸</li>
                <li>✅ <strong>homepage-banner</strong> (1100×400) - Banner轮播固定尺寸</li>
                <li>✅ <strong>product-thumbnail</strong> (400×400) - 产品缩略图</li>
                <li>✅ <strong>product-medium</strong> (600×600) - 产品中等尺寸</li>
                <li>✅ <strong>product-large</strong> (1200×1200) - 产品大图</li>
            </ul>
            
            <form method="post">
                <?php wp_nonce_field('angola_b2b_regenerate_thumbnails'); ?>
                <p>
                    <button type="submit" name="regenerate_thumbnails" class="button button-primary button-large">
                        🔄 开始重新生成
                    </button>
                </p>
            </form>
            
            <hr>
            <p><strong>提示：</strong>这个过程可能需要几分钟时间，具体取决于产品数量和图片大小。</p>
        </div>
    </div>
    <?php
}

/**
 * Regenerate all thumbnails for products
 */
function angola_b2b_regenerate_all_thumbnails() {
    // 获取所有产品
    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    
    $processed_count = 0;
    $skipped_count = 0;
    
    echo '<div class="notice notice-info"><p>开始重新生成缩略图...</p></div>';
    
    foreach ($products as $product) {
        $thumbnail_id = get_post_thumbnail_id($product->ID);
        
        if (!$thumbnail_id) {
            echo '<div class="notice notice-warning inline"><p>⏩ 跳过（无特色图片）：<strong>' . esc_html($product->post_title) . '</strong></p></div>';
            $skipped_count++;
            continue;
        }
        
        // 获取图片文件路径
        $file_path = get_attached_file($thumbnail_id);
        
        if (!file_exists($file_path)) {
            echo '<div class="notice notice-error inline"><p>❌ 图片文件不存在：<strong>' . esc_html($product->post_title) . '</strong></p></div>';
            $skipped_count++;
            continue;
        }
        
        // 重新生成缩略图
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $metadata = wp_generate_attachment_metadata($thumbnail_id, $file_path);
        wp_update_attachment_metadata($thumbnail_id, $metadata);
        
        echo '<div class="notice notice-success inline"><p>✅ 已处理：<strong>' . esc_html($product->post_title) . '</strong></p></div>';
        $processed_count++;
    }
    
    // Summary
    echo '<div class="notice notice-success is-dismissible">';
    echo '<h3>🎉 完成！</h3>';
    echo '<ul>';
    echo '<li><strong>已处理：</strong>' . $processed_count . ' 个产品图片</li>';
    echo '<li><strong>跳过：</strong>' . $skipped_count . ' 个产品</li>';
    echo '</ul>';
    echo '<p><a href="' . home_url() . '" class="button button-primary" target="_blank">🏠 查看首页</a></p>';
    echo '</div>';
}

/**
 * Delete all products and categories page
 */
function angola_b2b_delete_all_page() {
    ?>
    <div class="wrap">
        <h1>🗑️ 删除所有产品和分类</h1>
        
        <?php
        // Handle form submission
        if (isset($_POST['delete_all']) && check_admin_referer('angola_b2b_delete_all')) {
            angola_b2b_delete_all_products_and_categories();
        }
        ?>
        
        <div class="card" style="max-width: 800px;">
            <h2>⚠️ 危险操作</h2>
            <p><strong>此操作将永久删除以下内容：</strong></p>
            <ul>
                <li>❌ 所有产品（包括已发布、草稿、已删除的所有状态）</li>
                <li>❌ 所有产品分类（包括父分类和子分类）</li>
                <li>❌ 所有产品标签</li>
            </ul>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; margin: 20px 0;">
                <p><strong>⚠️ 警告：</strong>此操作<strong>不可恢复</strong>！请确保您已经备份了重要数据。</p>
            </div>
            
            <?php
            // Get counts before deletion
            $products_count = wp_count_posts('product');
            $total_products = array_sum((array)$products_count);
            
            $categories_count = wp_count_terms(array(
                'taxonomy' => 'product_category',
                'hide_empty' => false,
            ));
            if (is_wp_error($categories_count)) {
                $categories_count = 0;
            }
            
            $tags_count = wp_count_terms(array(
                'taxonomy' => 'product_tag',
                'hide_empty' => false,
            ));
            if (is_wp_error($tags_count)) {
                $tags_count = 0;
            }
            ?>
            
            <p><strong>当前统计：</strong></p>
            <ul>
                <li>产品总数：<strong><?php echo esc_html($total_products); ?></strong> 个</li>
                <li>产品分类总数：<strong><?php echo esc_html($categories_count); ?></strong> 个</li>
                <li>产品标签总数：<strong><?php echo esc_html($tags_count); ?></strong> 个</li>
            </ul>
            
            <form method="post" onsubmit="return confirm('⚠️ 您确定要删除所有产品和分类吗？此操作不可恢复！');">
                <?php wp_nonce_field('angola_b2b_delete_all'); ?>
                <p>
                    <button type="submit" name="delete_all" class="button button-primary button-large" style="background: #dc3232; border-color: #dc3232;">
                        🗑️ 确认删除所有产品和分类
                    </button>
                </p>
            </form>
            
            <hr>
            <p><strong>提示：</strong>删除完成后，您可以重新运行"生成测试产品"工具来创建新的测试数据。</p>
        </div>
    </div>
    <?php
}

/**
 * Delete all products and categories
 */
function angola_b2b_delete_all_products_and_categories() {
    echo '<div class="notice notice-info"><p>开始删除...</p></div>';
    
    $deleted_products = 0;
    $deleted_categories = 0;
    $deleted_tags = 0;
    $errors = array();
    
    // Step 1: Delete all products (including all post statuses)
    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'any', // 包括所有状态
    ));
    
    foreach ($products as $product) {
        $result = wp_delete_post($product->ID, true); // true = force delete (bypass trash)
        if ($result) {
            $deleted_products++;
            echo '<div class="notice notice-success inline"><p>✅ 已删除产品：<strong>' . esc_html($product->post_title) . '</strong></p></div>';
        } else {
            $errors[] = '删除产品失败：' . $product->post_title;
        }
    }
    
    // Step 2: Delete all product categories
    $categories = get_terms(array(
        'taxonomy' => 'product_category',
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($categories) && !empty($categories)) {
        foreach ($categories as $category) {
            $result = wp_delete_term($category->term_id, 'product_category');
            if (!is_wp_error($result) && $result) {
                $deleted_categories++;
                echo '<div class="notice notice-success inline"><p>✅ 已删除分类：<strong>' . esc_html($category->name) . '</strong></p></div>';
            } else {
                $errors[] = '删除分类失败：' . $category->name;
                if (is_wp_error($result)) {
                    $errors[] = '错误信息：' . $result->get_error_message();
                }
            }
        }
    }
    
    // Step 3: Delete all product tags
    $tags = get_terms(array(
        'taxonomy' => 'product_tag',
        'hide_empty' => false,
    ));
    
    if (!is_wp_error($tags) && !empty($tags)) {
        foreach ($tags as $tag) {
            $result = wp_delete_term($tag->term_id, 'product_tag');
            if (!is_wp_error($result) && $result) {
                $deleted_tags++;
                echo '<div class="notice notice-success inline"><p>✅ 已删除标签：<strong>' . esc_html($tag->name) . '</strong></p></div>';
            } else {
                $errors[] = '删除标签失败：' . $tag->name;
                if (is_wp_error($result)) {
                    $errors[] = '错误信息：' . $result->get_error_message();
                }
            }
        }
    }
    
    // Summary
    echo '<div class="notice notice-success is-dismissible">';
    echo '<h3>🎉 完成！</h3>';
    echo '<ul>';
    echo '<li><strong>已删除产品：</strong>' . $deleted_products . ' 个</li>';
    echo '<li><strong>已删除分类：</strong>' . $deleted_categories . ' 个</li>';
    echo '<li><strong>已删除标签：</strong>' . $deleted_tags . ' 个</li>';
    echo '</ul>';
    
    if (!empty($errors)) {
        echo '<h4>⚠️ 错误：</h4>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul>';
    }
    
    echo '<p><a href="' . admin_url('tools.php?page=angola-b2b-generate-products') . '" class="button button-primary">🚀 生成新的测试产品</a> ';
    echo '<a href="' . admin_url('edit.php?post_type=product') . '" class="button">📦 查看产品列表</a> ';
    echo '<a href="' . admin_url('edit-tags.php?taxonomy=product_category&post_type=product') . '" class="button">📁 查看分类列表</a></p>';
    echo '</div>';
}

/**
 * Import Services and Industries Content Page
 * 导入解决方案和行业数据
 */
function angola_b2b_import_content_page() {
    ?>
    <div class="wrap">
        <h1>📥 导入解决方案和行业数据</h1>
        <p>将MSC风格的解决方案和行业数据导入到后台，导入后你可以在后台自由编辑这些内容。</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('angola_b2b_import_content_action', 'angola_b2b_import_content_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label>导入解决方案数据</label></th>
                    <td>
                        <input type="checkbox" name="import_services" value="1" checked> 
                        导入5个解决方案（Shipping Solutions, Inland Transportation, Air Cargo, Digital Solutions, Cargo Protection）
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>导入行业数据</label></th>
                    <td>
                        <input type="checkbox" name="import_industries" value="1" checked> 
                        导入10个行业（Agriculture, Fruit, Pharmaceuticals, Car Parts, Mining, Plastics, Chemicals, Food & Beverages, Forestry, Retail）
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>覆盖已存在的数据</label></th>
                    <td>
                        <input type="checkbox" name="overwrite_existing" value="1"> 
                        <strong>警告：</strong>如果勾选，将删除所有现有的解决方案和行业数据后重新导入
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" name="import_content" class="button button-primary">🚀 开始导入</button>
            </p>
        </form>
    </div>
    <?php
    
    // Handle form submission
    if (isset($_POST['import_content']) && check_admin_referer('angola_b2b_import_content_action', 'angola_b2b_import_content_nonce')) {
        angola_b2b_import_content_data();
    }
}

/**
 * Import Services and Industries Data
 * 执行导入操作
 */
function angola_b2b_import_content_data() {
    $import_services = isset($_POST['import_services']);
    $import_industries = isset($_POST['import_industries']);
    $overwrite = isset($_POST['overwrite_existing']);
    
    $services_count = 0;
    $industries_count = 0;
    $errors = array();
    
    // Delete existing data if overwrite is enabled
    if ($overwrite) {
        // Delete services
        $existing_services = get_posts(array(
            'post_type' => 'service',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ));
        foreach ($existing_services as $service) {
            wp_delete_post($service->ID, true);
        }
        
        // Delete industries
        $existing_industries = get_posts(array(
            'post_type' => 'industry',
            'posts_per_page' => -1,
            'post_status' => 'any',
        ));
        foreach ($existing_industries as $industry) {
            wp_delete_post($industry->ID, true);
        }
    }
    
    // Import Services
    if ($import_services) {
        $services_data = array(
            array(
                'title' => 'Shipping Solutions',
                'description' => 'Comprehensive shipping solutions for all your cargo needs. From dry containers to specialized transport, we ensure your goods reach their destination safely.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/dry-cargo/msc-dry-cargo-shipping-solutions-hero.jpg?w=800',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18h18M3 6h18M5 6v12M19 6v12M9 6v12M15 6v12"/></svg>',
                'features' => array('Dry Containers', 'Specialized Transport', 'Global Coverage'),
            ),
            array(
                'title' => 'Inland Transportation & Logistics',
                'description' => 'Seamless inland transportation and logistics services. Door-to-door delivery solutions that keep your supply chain moving efficiently.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/inland-services/msc-inland-services-solutions-hero.jpg?w=800',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 6h15v9H1V6zM16 8h5l3 3v4h-3M5.5 18a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM18.5 18a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/></svg>',
                'features' => array('Door-to-Door Delivery', 'Warehouse Services', 'Supply Chain Management'),
            ),
            array(
                'title' => 'Air Cargo Solutions',
                'description' => 'Fast and reliable air cargo services for time-sensitive shipments. Global reach with express delivery options for urgent needs.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/air-cargo/msc-air-cargo-solutions-hero.jpg?w=800',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg>',
                'features' => array('Express Delivery', 'Time-Critical Shipments', 'Global Network'),
            ),
            array(
                'title' => 'Digital Business Solutions',
                'description' => 'Advanced digital tools and platforms to streamline your operations. Real-time tracking, automated documentation, and seamless integration.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/digital/msc-digital-solutions-hero.jpg?w=800',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
                'features' => array('Real-time Tracking', 'Automated Documentation', 'API Integration'),
            ),
            array(
                'title' => 'Cargo Cover Solutions',
                'description' => 'Comprehensive insurance and protection plans for your valuable cargo. Peace of mind with every shipment, backed by trusted coverage.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/solutions/reefer-cargo/msc-reefer-cargo-shipping-solutions-hero.jpg?w=800',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                'features' => array('Comprehensive Coverage', 'Risk Management', 'Claims Support'),
            ),
        );
        
        foreach ($services_data as $index => $service) {
            $post_id = wp_insert_post(array(
                'post_type' => 'service',
                'post_title' => $service['title'],
                'post_content' => $service['description'],
                'post_status' => 'publish',
                'menu_order' => $index + 1,
            ));
            
            if ($post_id && !is_wp_error($post_id)) {
                // Set featured image from URL (note: this will just store the URL, not download the image)
                // For a production site, you'd want to download and attach the image properly
                // update_post_meta($post_id, '_thumbnail_url', $service['image_url']);
                
                // Set ACF fields
                update_field('service_icon', $service['icon'], $post_id);
                
                // Set features
                $features_array = array();
                foreach ($service['features'] as $feature_text) {
                    $features_array[] = array('feature_text' => $feature_text);
                }
                update_field('service_features', $features_array, $post_id);
                
                $services_count++;
            } else {
                $errors[] = '导入解决方案 "' . $service['title'] . '" 失败';
            }
        }
    }
    
    // Import Industries
    if ($import_industries) {
        $industries_data = array(
            array(
                'title' => 'Agriculture',
                'description' => 'With global sourcing an everyday reality, MSC connects the growers, farmers and producers of agricultural products around the world with their key markets.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/agriculture/msc-agriculture-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Fruit',
                'description' => 'Whether you\'re shipping apples or avocados, our world-leading reefer fleet is equipped with the technology you need to keep your fruit in perfect condition.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/fruit/msc-fruit-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Pharmaceuticals',
                'description' => 'More and more pharmaceutical companies are turning to sea transport to deliver medicines and other essential goods quickly and safely to the places where they are needed.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/pharma/msc-pharma-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Car Parts',
                'description' => 'Whether you are shipping production or service parts, a reliable and experienced shipping partner is a vital link in your uninterruptible supply chain.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/automotive/msc-automotive-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Mining & Minerals',
                'description' => 'For decades MSC has been successfully connecting the minerals extraction industries with customer markets around the world – offering fast transit times across all key trade lanes.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/mining/msc-mining-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Plastics & Rubber Products',
                'description' => 'Transported to and from every major trade lane, plastic and rubber goods are at the very centre of most modern global supply chains.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/plastics/msc-plastics-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Chemicals & Petrochemicals',
                'description' => 'MSC provides careful, precise and robust processes to safely transport hazardous and dangerous goods, such as chemicals and petrochemicals.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/chemicals/msc-chemicals-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Food & Beverages',
                'description' => 'Thanks to its decades of experience servicing the food and beverage industries, MSC understands the unique needs of the sector.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/food-beverage/msc-food-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Pulp, Paper & Forestry Products',
                'description' => 'Using our knowledge in transportation and logistics we can provide versatile solutions for your pulp, paper and forest products.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/forestry/msc-forestry-shipping-solutions-hero.jpg?w=800',
            ),
            array(
                'title' => 'Retail',
                'description' => 'Retailers rely on efficient global product sourcing and a flexible and robust "just-in-time" supply chain.',
                'image_url' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/details/industries/retail/msc-retail-shipping-solutions-hero.jpg?w=800',
            ),
        );
        
        foreach ($industries_data as $index => $industry) {
            $post_id = wp_insert_post(array(
                'post_type' => 'industry',
                'post_title' => $industry['title'],
                'post_content' => $industry['description'],
                'post_status' => 'publish',
                'menu_order' => $index + 1,
            ));
            
            if ($post_id && !is_wp_error($post_id)) {
                $industries_count++;
            } else {
                $errors[] = '导入行业 "' . $industry['title'] . '" 失败';
            }
        }
    }
    
    // Display results
    echo '<div class="notice notice-success is-dismissible">';
    echo '<h3>🎉 导入完成！</h3>';
    echo '<ul>';
    echo '<li><strong>已导入解决方案：</strong>' . $services_count . ' 个</li>';
    echo '<li><strong>已导入行业：</strong>' . $industries_count . ' 个</li>';
    echo '</ul>';
    
    if (!empty($errors)) {
        echo '<h4>⚠️ 错误：</h4>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul>';
    }
    
    echo '<p><strong>📝 下一步：</strong></p>';
    echo '<ol>';
    echo '<li>前往 <a href="' . admin_url('edit.php?post_type=service') . '"><strong>解决方案</strong></a> 或 <a href="' . admin_url('edit.php?post_type=industry') . '"><strong>行业</strong></a> 编辑内容</li>';
    echo '<li>点击每个条目，上传自己的图片（设置特色图片）</li>';
    echo '<li>根据需要修改标题、描述等信息</li>';
    echo '<li>支持多语言：配合Polylang插件翻译内容</li>';
    echo '</ol>';
    echo '</div>';
}


