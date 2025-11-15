<?php
/**
 * The front page template (Homepage)
 * 首页模板 - 模块化版本
 *
 * @package Angola_B2B
 */

// 启用错误显示（临时调试）
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// 开始输出缓冲，以便捕获错误
ob_start();

try {
    get_header();
} catch (Exception $e) {
    echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
    echo '<h2>❌ Header 加载错误</h2>';
    echo '<p>' . esc_html($e->getMessage()) . '</p>';
    echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '</div>';
} catch (Error $e) {
    echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
    echo '<h2>❌ Header 致命错误</h2>';
    echo '<p>' . esc_html($e->getMessage()) . '</p>';
    echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '</div>';
}

$header_output = ob_get_clean();
echo $header_output;
?>

<main id="primary" class="site-main homepage">
    
    <?php
    // 测试：先检查函数是否存在
    if (!function_exists('angola_b2b_display_hero_section')) {
        echo '<div style="padding: 20px; background: #ffcc00; margin: 20px; border: 2px solid #ff0000;">';
        echo '<h2>⚠️ 警告：angola_b2b_display_hero_section 函数未找到</h2>';
        echo '<p>请检查 helpers.php 是否正确加载</p>';
        echo '</div>';
    } else {
        try {
            // 1. Hero区域 - 从ACF字段读取数据
            $homepage_id = get_option('page_on_front');
            if (!$homepage_id) {
                $homepage_id = 45; // 后备首页ID
            }
            
            angola_b2b_display_hero_section(array(
                'height' => get_field('hero_height', $homepage_id) ?: 'full',
                'background_image' => get_field('hero_background_image', $homepage_id) ?: '',
                'title' => get_field('hero_title', $homepage_id) ?: '',
                'subtitle' => get_field('hero_subtitle', $homepage_id) ?: '',
                'cta_primary' => array(
                    'text' => get_field('hero_cta_primary_text', $homepage_id) ?: '',
                    'url' => get_field('hero_cta_primary_url', $homepage_id) ?: '',
                ),
                'cta_secondary' => array(
                    'text' => get_field('hero_cta_secondary_text', $homepage_id) ?: '',
                    'url' => get_field('hero_cta_secondary_url', $homepage_id) ?: '',
                ),
                'overlay_opacity' => get_field('hero_overlay_opacity', $homepage_id) ?: 0.2,
            ));
        } catch (Exception $e) {
            echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
            echo '<h2>❌ Hero Section 错误</h2>';
            echo '<p>' . esc_html($e->getMessage()) . '</p>';
            echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
            echo '</div>';
        } catch (Error $e) {
            echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
            echo '<h2>❌ Hero Section 致命错误</h2>';
            echo '<p>' . esc_html($e->getMessage()) . '</p>';
            echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
            echo '</div>';
        }
    }
    
    // 2. 产品大分类展示
    try {
        get_template_part('template-parts/homepage/product-categories-showcase');
    } catch (Exception $e) {
        echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
        echo '<h2>❌ 产品分类展示错误</h2>';
        echo '<p>' . esc_html($e->getMessage()) . '</p>';
        echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '</div>';
    } catch (Error $e) {
        echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
        echo '<h2>❌ 产品分类展示致命错误</h2>';
        echo '<p>' . esc_html($e->getMessage()) . '</p>';
        echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '</div>';
    }
    
    // 7. 新闻轮播区
    try {
        get_template_part('template-parts/homepage/news-carousel');
    } catch (Exception $e) {
        echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
        echo '<h2>❌ 新闻轮播错误</h2>';
        echo '<p>' . esc_html($e->getMessage()) . '</p>';
        echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '</div>';
    } catch (Error $e) {
        echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
        echo '<h2>❌ 新闻轮播致命错误</h2>';
        echo '<p>' . esc_html($e->getMessage()) . '</p>';
        echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '</div>';
    }
    ?>

</main>

<?php
try {
    get_footer();
} catch (Exception $e) {
    echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
    echo '<h2>❌ Footer 加载错误</h2>';
    echo '<p>' . esc_html($e->getMessage()) . '</p>';
    echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '</div>';
} catch (Error $e) {
    echo '<div style="padding: 20px; background: #ff0000; color: white; margin: 20px;">';
    echo '<h2>❌ Footer 致命错误</h2>';
    echo '<p>' . esc_html($e->getMessage()) . '</p>';
    echo '<p>文件：' . esc_html($e->getFile()) . ':' . $e->getLine() . '</p>';
    echo '</div>';
}
