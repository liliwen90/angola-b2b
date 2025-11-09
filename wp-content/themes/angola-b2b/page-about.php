<?php
/**
 * Template Name: About Us Page
 * Template for displaying "About Us" content from ACF
 *
 * @package Angola_B2B
 */

get_header();

// 获取当前语言
$current_lang = angola_b2b_get_current_language();

// 根据当前语言获取对应的内容
$field_name = 'footer_about_us_content';
if ($current_lang === 'pt') {
    $field_name = 'footer_about_us_content_pt';
} elseif ($current_lang === 'zh') {
    $field_name = 'footer_about_us_content_zh';
} elseif ($current_lang === 'zh_tw') {
    $field_name = 'footer_about_us_content_zh_tw';
}

$about_content = get_field($field_name, 45);

// 如果当前语言没有内容，回退到英语
if (empty($about_content)) {
    $about_content = get_field('footer_about_us_content', 45);
}
?>

<main id="primary" class="site-main">
    <article class="content-page">
        <div class="container">
            <div class="content-wrapper">
                <header class="page-header">
                    <h1 class="page-title"><?php _et('about_us'); ?></h1>
                </header>

                <div class="page-content">
                    <?php
                    if ($about_content) {
                        echo wp_kses_post($about_content);
                    } else {
                        echo '<p>' . esc_html__('内容正在准备中...', 'angola-b2b') . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </article>
</main>

<?php
get_footer();

