<?php
/**
 * Product Card - Stock Variant
 * 产品卡片 - 库存商品版本
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$product_id = get_the_ID();
?>

<article id="product-<?php echo esc_attr($product_id); ?>" class="product-card product-card--stock">
    
    <!-- 库存徽章 -->
    <div class="product-badges">
        <?php
        $badge_text = get_field('product_stock_badge_text', $product_id);
        $badge_text = $badge_text ? $badge_text : __('现货', 'angola-b2b');
        ?>
        <span class="badge badge-stock">
            <?php echo esc_html($badge_text); ?>
        </span>
    </div>
    
    <!-- 产品图片 -->
    <div class="product-thumbnail">
        <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('View %s', 'angola-b2b'), get_the_title())); ?>">
            <?php
            $image = get_field('product_image_1', $product_id);
            if ($image && is_array($image)) {
                $image_alt = !empty($image['alt']) ? $image['alt'] : get_the_title();
                ?>
                <img src="<?php echo esc_url($image['url']); ?>" 
                     alt="<?php echo esc_attr($image_alt); ?>"
                     loading="lazy">
                <?php
            } else {
                // 占位图
                ?>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/placeholder.jpg'); ?>" 
                     alt="<?php echo esc_attr__('Product placeholder', 'angola-b2b'); ?>"
                     loading="lazy">
                <?php
            }
            ?>
        </a>
    </div>
    
    <!-- 产品信息 -->
    <div class="product-info">
        <h3 class="product-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php
        $short_desc = get_field('product_short_description', $product_id);
        if ($short_desc) :
        ?>
            <p class="product-excerpt">
                <?php echo esc_html(wp_trim_words($short_desc, 15, '...')); ?>
            </p>
        <?php endif; ?>
        
        <!-- 库存信息 -->
        <?php
        $stock_quantity = get_field('product_stock_quantity', $product_id);
        if ($stock_quantity && intval($stock_quantity) > 0) :
        ?>
            <div class="product-stock-info">
                <span class="stock-icon" aria-hidden="true">📦</span>
                <span class="stock-quantity">
                    <?php 
                    printf(
                        esc_html__('库存：%d 件', 'angola-b2b'), 
                        intval($stock_quantity)
                    ); 
                    ?>
                </span>
            </div>
        <?php endif; ?>
        
        <!-- 操作按钮 -->
        <div class="product-actions">
            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                <?php esc_html_e('立即询价', 'angola-b2b'); ?>
            </a>
        </div>
    </div>
</article>

