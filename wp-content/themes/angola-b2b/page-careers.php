<?php
/**
 * Template Name: Careers Page
 * Template for displaying "Careers" content from ACF
 *
 * @package Angola_B2B
 */

get_header();

// 获取当前语言
$current_lang = angola_b2b_get_current_language();

// 根据当前语言获取对应的内容
$field_name = 'footer_careers_content';
if ($current_lang === 'pt') {
    $field_name = 'footer_careers_content_pt';
} elseif ($current_lang === 'zh') {
    $field_name = 'footer_careers_content_zh';
} elseif ($current_lang === 'zh_tw') {
    $field_name = 'footer_careers_content_zh_tw';
}

$careers_content = get_field($field_name, 45);

// 如果当前语言没有内容，回退到英语
if (empty($careers_content)) {
    $careers_content = get_field('footer_careers_content', 45);
}
?>

<main id="primary" class="site-main">
    <article class="content-page">
        <div class="container">
            <div class="content-wrapper">
                <header class="page-header">
                    <h1 class="page-title"><?php _et('careers'); ?></h1>
                </header>

                <div class="page-content">
                    <?php
                    if ($careers_content) {
                        echo wp_kses_post($careers_content);
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

