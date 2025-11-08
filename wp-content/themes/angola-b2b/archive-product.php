<?php
/**
 * The template for displaying product archives
 * 产品归档页面模板
 * 
 * ==================== ⚠️ 重要说明 IMPORTANT NOTE ⚠️ ====================
 * 
 * 📍 文件用途：
 *    此文件仅用于"所有产品"归档页面
 *    This file is ONLY used for the main product archive page
 * 
 * 🔗 适用URL：
 *    - /product/  （显示所有产品，不分类）
 * 
 * ❌ 不适用于产品分类页面：
 *    - /product-category/物流清关/   ← 不使用此文件！
 *    - /product-category/建筑材料/   ← 不使用此文件！
 *    - 这些页面使用 taxonomy-product_category.php ✅
 * 
 * 📂 WordPress 模板层级：
 *    对于 /product/ 页面：
 *    1️⃣ archive-product.php    ← 👈 当前文件 THIS FILE
 *    2️⃣ archive.php
 *    3️⃣ index.php
 * 
 * ✅ 如需修改产品分类页面（如物流清关、建筑材料），请编辑：
 *    👉 taxonomy-product_category.php  ← 正确的文件！
 * 
 * ====================================================================
 *
 * @package Angola_B2B
 * @version 1.0
 */

get_header();
?>

<main id="primary" class="site-main product-archive">
    <div class="container">
        <?php angola_b2b_display_breadcrumbs(); ?>
        
        <?php if (is_tax('product_category')) : 
            $term = get_queried_object();
        ?>
            <!-- Products Section -->
            <section id="products" class="archive-content-section">
                <div class="section-header">
                    <h2 class="section-title"><?php echo esc_html($term->name); ?></h2>
                </div>
                
                <?php
                // Temporary debug info
                if (current_user_can('manage_options')) {
                    global $wp_query;
                    echo '<div style="background: #fff3cd; border: 2px solid #ffc107; padding: 20px; margin-bottom: 20px; border-radius: 8px;">';
                    echo '<h3 style="margin: 0 0 10px 0; color: #856404;">🔍 调试信息（仅管理员可见）</h3>';
                    echo '<p><strong>当前分类ID:</strong> ' . $term->term_id . '</p>';
                    echo '<p><strong>查询到的产品数量:</strong> ' . $wp_query->found_posts . '</p>';
                    echo '<p><strong>当前页产品数:</strong> ' . $wp_query->post_count . '</p>';
                    echo '<p><strong>查询参数:</strong></p>';
                    echo '<pre style="background: #f8f9fa; padding: 10px; border-radius: 4px; overflow: auto;">' . print_r($wp_query->query_vars, true) . '</pre>';
                    echo '</div>';
                }
                ?>
                
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
                        <p class="no-products"><?php esc_html_e('No products found in this category.', 'angola-b2b'); ?></p>
                        <?php
                    endif;
                    ?>
                </div>
                
                <!-- Pagination -->
                <?php if (paginate_links()) : ?>
                    <div class="pagination">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => esc_html__('Previous', 'angola-b2b'),
                            'next_text' => esc_html__('Next', 'angola-b2b'),
                        ));
                        ?>
                    </div>
                <?php endif; ?>
            </section>
            
        <?php else : ?>
            <!-- Product Archive (not category) -->
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Products', 'angola-b2b'); ?></h1>
            </header>

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
                    <p class="no-products"><?php esc_html_e('No products found.', 'angola-b2b'); ?></p>
                    <?php
                endif;
                ?>
            </div>

            <!-- Pagination -->
            <?php if (paginate_links()) : ?>
                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => esc_html__('Previous', 'angola-b2b'),
                        'next_text' => esc_html__('Next', 'angola-b2b'),
                    ));
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
