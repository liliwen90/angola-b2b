<?php
/**
 * Homepage Hero Section with Dual CTA Buttons
 * 首页Hero区域（双按钮版本）
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="hero-banner">
    <div class="hero-background">
        <?php
        $hero_video = get_field('hero_video_url', 'option'); // 保持从主题选项读取（这些字段在其他字段组中）
        $hero_image = get_field('hero_image', 'option');
        
        if ($hero_video) :
            ?>
            <video class="hero-video" autoplay muted loop playsinline>
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
            </video>
            <?php
        elseif ($hero_image && is_array($hero_image)) :
            $hero_alt = !empty($hero_image['alt']) ? $hero_image['alt'] : get_bloginfo('name');
            ?>
            <img src="<?php echo esc_url($hero_image['url']); ?>" 
                 alt="<?php echo esc_attr($hero_alt); ?>" 
                 class="hero-image">
            <?php
        endif;
        ?>
    </div>
    <div class="hero-content">
        <h1 class="hero-title anim-fade-down">
            <?php 
            $hero_title = get_field('hero_title', 'option'); // 保持从主题选项读取（这些字段在其他字段组中）
            echo esc_html($hero_title ? $hero_title : get_bloginfo('name')); 
            ?>
        </h1>
        <p class="hero-subtitle anim-fade-up">
            <?php 
            $hero_subtitle = get_field('hero_subtitle', 'option'); // 保持从主题选项读取（这些字段在其他字段组中）
            echo esc_html($hero_subtitle ? $hero_subtitle : get_bloginfo('description')); 
            ?>
        </p>
        
        <!-- 双按钮CTA -->
        <div class="hero-cta-group anim-scale-in">
            <?php
            // 主按钮
            $primary_text = get_field('hero_cta_primary_text', 45);
            $primary_link = get_field('hero_cta_primary_link', 45);
            $primary_text = $primary_text ? $primary_text : __('查看库存产品', 'angola-b2b');
            $primary_link = $primary_link ? $primary_link : '#stock-products';
            ?>
            <a href="<?php echo esc_url($primary_link); ?>" class="btn btn-primary btn-lg">
                <?php echo esc_html($primary_text); ?>
            </a>
            
            <?php
            // 次按钮
            $secondary_text = get_field('hero_cta_secondary_text', 45);
            $secondary_link = get_field('hero_cta_secondary_link', 45);
            $secondary_text = $secondary_text ? $secondary_text : __('搜索中国商品', 'angola-b2b');
            $secondary_link = $secondary_link ? $secondary_link : '#search-section';
            ?>
            <a href="<?php echo esc_url($secondary_link); ?>" class="btn btn-outline-white btn-lg">
                <?php echo esc_html($secondary_text); ?>
            </a>
        </div>
    </div>
</section>

