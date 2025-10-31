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
    // 1. 热门库存产品区域（本地现货，首屏展示）
    get_template_part('template-parts/homepage/stock-products');
    
    // 2. 精选产品区域（公司成熟商品）
    get_template_part('template-parts/homepage/featured-products');
    
    // 3. 核心优势区域（建立信任）
    get_template_part('template-parts/homepage/advantages');
    
    // 4. CTA联系我们区域（行动号召）
    get_template_part('template-parts/homepage/cta-section');
    ?>

</main>

<?php
get_footer();

