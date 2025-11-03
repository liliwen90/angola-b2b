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
                $thumbnail_url = get_the_post_thumbnail_url($product_id, 'homepage-banner');
                
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
                                 loading="eager">
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

