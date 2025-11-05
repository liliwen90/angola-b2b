<?php
/**
 * The front page template (Homepage)
 * 首页模板 - 模块化版本
 *
 * @package Angola_B2B
 */

get_header();
?>

<main id="primary" class="site-main homepage">
    
    <?php
            // 1. Hero区域（MSC风格全宽Hero，背景图片+标题+Quick Actions）
            angola_b2b_display_hero_section(array(
                'height' => 'full',
                'background_image' => 'https://assets.msc.com/msc-p-001/msc-p-001/media/msc-home/hero-ship-at-sunset.jpg?w=1920',
                'overlay_opacity' => 0.5,
            ));
    
    // 2. 统计数字展示区域（公司实力）
    get_template_part('template-parts/homepage/statistics');
    
    // 3. 热门库存产品区域（本地现货，首屏展示）
    get_template_part('template-parts/homepage/stock-products');
    
    // 4. 精选产品区域（公司成熟商品）
    get_template_part('template-parts/homepage/featured-products');
    
    // 5. 服务/解决方案展示区（MSC风格Tabs）
    get_template_part('template-parts/homepage/services-showcase');
    
    // 6. 核心优势区域（建立信任）
    get_template_part('template-parts/homepage/advantages');
    
    // 7. CTA联系我们区域（行动号召）
    get_template_part('template-parts/homepage/cta-section');
    ?>

</main>

<?php
get_footer();

