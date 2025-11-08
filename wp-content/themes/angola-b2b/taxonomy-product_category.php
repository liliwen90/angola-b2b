<?php
/**
 * Template for displaying product category archives
 * 产品分类归档页面模板
 * 
 * ==================== ⚠️ 重要说明 IMPORTANT NOTE ⚠️ ====================
 * 
 * 📍 文件用途：
 *    此文件控制所有产品分类页面的显示（如：物流清关、建筑材料、工业设备等）
 *    This file controls ALL product category archive pages
 * 
 * 🔗 适用URL：
 *    - /product-category/物流清关/
 *    - /product-category/建筑材料/
 *    - /product-category/{任何分类}/
 * 
 * 📂 WordPress 模板层级优先级（Template Hierarchy）：
 *    对于产品分类页面，WordPress按以下顺序查找模板：
 *    1️⃣ taxonomy-product_category-{slug}.php    (特定分类)
 *    2️⃣ taxonomy-product_category.php           ← 👈 当前文件 THIS FILE ✅
 *    3️⃣ taxonomy.php                            (所有分类法)
 *    4️⃣ archive.php                             (所有归档页)
 *    5️⃣ index.php                               (最后备用)
 * 
 * ❌ 常见错误：
 *    archive-product.php 不会用于产品分类页面！
 *    archive-product.php is NOT used for product category pages!
 *    它只用于 /product/ 归档页（所有产品列表）
 * 
 * ✅ 修改产品分类页面时，请编辑此文件！
 *    To modify product category pages, edit THIS file!
 * 
 * 🎨 当前布局：
 *    - 面包屑导航
 *    - 分类标题
 *    - 产品网格（4列布局，响应式）
 *    - 分页功能
 * 
 * ====================================================================
 *
 * @package Angola_B2B
 * @version 1.0
 */

get_header();

$term = get_queried_object();
?>

<main id="primary" class="site-main product-archive">
    <div class="container">
        
        <?php angola_b2b_display_breadcrumbs(); ?>
        
        <!-- Products Section -->
        <section id="products" class="archive-content-section">
            <div class="section-header" style="text-align: center; margin-bottom: 40px;">
                <h1 class="section-title">
                    <?php 
                    // Get translated category name from ACF multilingual fields
                    $current_lang = angola_b2b_get_current_language();
                    $translated_name = $term->name; // Default to English name
                    
                    // Try to get translated name from ACF fields
                    if ($current_lang === 'pt' && function_exists('get_field')) {
                        $pt_name = get_field('name_pt', $term);
                        if (!empty($pt_name)) {
                            $translated_name = $pt_name;
                        }
                    } elseif ($current_lang === 'zh' && function_exists('get_field')) {
                        $zh_name = get_field('name_zh', $term);
                        if (!empty($zh_name)) {
                            $translated_name = $zh_name;
                        }
                    } elseif ($current_lang === 'zh_tw' && function_exists('get_field')) {
                        $zh_tw_name = get_field('name_zh_tw', $term);
                        if (!empty($zh_tw_name)) {
                            $translated_name = $zh_tw_name;
                        }
                    }
                    
                    echo esc_html($translated_name);
                    ?>
                </h1>
                <?php if ($term->description) : ?>
                    <p class="section-description"><?php echo esc_html($term->description); ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Products Grid -->
            <div class="products-grid">
                <?php
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        get_template_part('template-parts/product/product-card');
                    endwhile;
                else :
                    ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                            <p style="font-size: 18px; color: #666;"><?php _et('no_products_category'); ?></p>
                            <?php if (current_user_can('manage_options')) : ?>
                                <p style="margin-top: 20px;">
                                    <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" class="btn btn-primary"><?php _et('add_new_product'); ?></a>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php
                endif;
                ?>
            </div>
            
            <!-- Pagination -->
            <?php if (paginate_links()) : ?>
                <div class="pagination" style="margin-top: 60px;">
                    <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __t('previous'),
                            'next_text' => __t('next'),
                        ));
                    ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php
get_footer();

