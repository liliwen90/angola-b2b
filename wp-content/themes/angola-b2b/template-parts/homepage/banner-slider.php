<?php
/**
 * Template part for displaying the homepage banner slider
 * 首页Banner轮播区域
 *
 * @package Angola_B2B
 */

// 检查是否启用Banner轮播
$enable_banner = get_field('enable_banner_slider', 45);
if (!$enable_banner) {
    return;
}

// 获取选择的产品
$banner_products = get_field('banner_products', 45);

// 如果没有选择产品，不显示
if (empty($banner_products) || !is_array($banner_products)) {
    return;
}
?>

<section class="homepage-banner-slider" aria-label="<?php esc_attr_e('Featured Products Banner', 'angola-b2b'); ?>">
    <div class="swiper banner-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($banner_products as $product_id) : ?>
                <?php
                $product_title = get_the_title($product_id);
                $product_url = get_permalink($product_id);
                
                // 调试：检查是否有特色图片
                $thumbnail_id = get_post_thumbnail_id($product_id);
                
                // 尝试获取Banner图片，按优先级回退
                $thumbnail_url = get_the_post_thumbnail_url($product_id, 'homepage-banner');
                if (!$thumbnail_url) {
                    $thumbnail_url = get_the_post_thumbnail_url($product_id, 'product-large');
                }
                if (!$thumbnail_url) {
                    $thumbnail_url = get_the_post_thumbnail_url($product_id, 'full');
                }
                if (!$thumbnail_url) {
                    $thumbnail_url = get_the_post_thumbnail_url($product_id, 'large');
                }
                if (!$thumbnail_url && $thumbnail_id) {
                    // 如果所有尺寸都不存在，但attachment ID存在，直接获取原始文件URL
                    $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'full');
                }
                
                // 如果没有特色图片，跳过这个产品
                if (!$thumbnail_url) {
                    continue;
                }
                ?>
                <div class="swiper-slide banner-slide">
                    <a href="<?php echo esc_url($product_url); ?>" 
                       class="banner-slide-link"
                       aria-label="<?php echo esc_attr(sprintf(__('View %s', 'angola-b2b'), $product_title)); ?>">
                        <div class="banner-image-wrapper">
                            <img src="<?php echo esc_url($thumbnail_url); ?>" 
                                 alt="<?php echo esc_attr($product_title); ?>"
                                 class="banner-image"
                                 loading="eager"
                                 width="1100"
                                 height="400">
                            <div class="banner-overlay"></div>
                        </div>
                        <div class="banner-content">
                            <h2 class="banner-product-title"><?php echo esc_html($product_title); ?></h2>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- 分页器 -->
        <div class="swiper-pagination banner-pagination"></div>
        
        <!-- 导航按钮 -->
        <div class="swiper-button-prev banner-prev"></div>
        <div class="swiper-button-next banner-next"></div>
    </div>
</section>

