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
        'background_image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=1920&h=1080&fit=crop',
        'overlay_opacity' => 0.5,
    ));
    
    // 2. 产品大分类展示（Our Products - 4个大卡片：建筑工程、建筑材料、农机农具、工业设备）
    get_template_part('template-parts/homepage/product-categories-showcase');
    
    // 3. 服务展示横向轮播（Our Services - 贸易服务）
    get_template_part('template-parts/homepage/services-showcase');
    
    // 4. 网络展示轮播（Network轮播）
    get_template_part('template-parts/homepage/category-showcase');
    
    // 4. 行业展示轮播（Your Shipping Needs Met - 10个行业卡片）
    get_template_part('template-parts/homepage/industries-carousel');
    
    // 5. 统计数字展示区域（Moving the World, Together - 公司实力数据）
    get_template_part('template-parts/homepage/statistics');
    
    // 6. 新闻轮播区（Discover the Latest News）
    get_template_part('template-parts/homepage/news-carousel');
    
    // 7. 客户公告列表（CUSTOMER ADVISORIES）
    get_template_part('template-parts/homepage/customer-advisories');
    
    // 8. 核心优势区域（建立信任）
    get_template_part('template-parts/homepage/advantages');
    
    // 9. CTA联系我们区域（行动号召）
    get_template_part('template-parts/homepage/cta-section');
    ?>

</main>

<?php
get_footer();

