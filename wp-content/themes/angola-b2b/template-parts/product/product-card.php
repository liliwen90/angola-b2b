<?php
/**
 * Template part for displaying product cards
 *
 * @package Angola_B2B
 */

$product_id = get_the_ID();
// 调试：检查是否有特色图片
$thumbnail_id = get_post_thumbnail_id($product_id);
// 使用固定的产品卡片尺寸，但如果不存在则回退
$thumbnail = get_the_post_thumbnail_url($product_id, 'product-card');
// 如果固定尺寸不存在，按优先级回退
if (!$thumbnail) {
    $thumbnail = get_the_post_thumbnail_url($product_id, 'product-thumbnail');
}
if (!$thumbnail) {
    $thumbnail = get_the_post_thumbnail_url($product_id, 'product-medium');
}
if (!$thumbnail) {
    $thumbnail = get_the_post_thumbnail_url($product_id, 'thumbnail');
}
if (!$thumbnail) {
    $thumbnail = get_the_post_thumbnail_url($product_id, 'medium');
}
if (!$thumbnail) {
    $thumbnail = get_the_post_thumbnail_url($product_id, 'large');
}
if (!$thumbnail && $thumbnail_id) {
    // 如果所有尺寸都不存在，但attachment ID存在，直接获取原始文件URL
    $thumbnail = wp_get_attachment_image_url($thumbnail_id, 'full');
}
$is_featured = angola_b2b_is_featured_product($product_id);
$categories = get_the_terms($product_id, 'product_category');
$short_description = get_the_excerpt();
?>

<article id="product-<?php the_ID(); ?>" <?php post_class('product-card'); ?>>
    <div class="product-card-inner">
        
        <!-- Product Image -->
        <div class="product-card-image">
            <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('View %s', 'angola-b2b'), get_the_title())); ?>">
                <?php if ($thumbnail) : ?>
                    <img src="<?php echo esc_url($thumbnail); ?>" 
                         alt="<?php echo esc_attr(get_the_title()); ?>"
                         loading="lazy"
                         width="300"
                         height="300"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div class="product-placeholder" style="display:none;">
                        <span><?php esc_html_e('No Image', 'angola-b2b'); ?></span>
                    </div>
                <?php else : ?>
                    <div class="product-placeholder">
                        <span><?php esc_html_e('No Image', 'angola-b2b'); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($is_featured) : ?>
                    <span class="featured-badge"><?php esc_html_e('推荐', 'angola-b2b'); ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <!-- Product Info -->
        <div class="product-card-content">
            
            <!-- Categories -->
            <?php if ($categories && !is_wp_error($categories)) : ?>
                <div class="product-categories">
                    <?php
                    $category_links = array();
                    foreach ($categories as $category) {
                        $term_link = get_term_link($category);
                        if (!is_wp_error($term_link)) {
                            $category_links[] = sprintf(
                                '<a href="%s" class="product-category-link">%s</a>',
                                esc_url($term_link),
                                esc_html($category->name)
                            );
                        }
                    }
                    if (!empty($category_links)) {
                        echo implode(' ', $category_links);
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Title -->
            <h3 class="product-card-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            
            <!-- Excerpt -->
            <?php if ($short_description) : ?>
                <div class="product-card-excerpt">
                    <?php echo esc_html(wp_trim_words($short_description, 20, '...')); ?>
                </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="product-card-actions">
                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                    <?php esc_html_e('查看详情', 'angola-b2b'); ?>
                </a>
                
                <?php
                // Quick inquiry button
                $inquiry_btn = angola_b2b_quick_inquiry_button($product_id);
                if ($inquiry_btn) {
                    echo '<div class="quick-inquiry-wrapper">' . $inquiry_btn . '</div>';
                }
                ?>
            </div>
            
        </div>
        
    </div>
</article>
