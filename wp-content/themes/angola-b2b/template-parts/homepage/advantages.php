<?php
/**
 * Homepage Core Advantages Section
 * 首页核心优势区域
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="core-advantages">
    <div class="container">
        <h2 class="section-title"><?php esc_html_e('Why Choose Us', 'angola-b2b'); ?></h2>
        <div class="advantages-grid">
            <?php
            if (have_rows('core_advantages', 'option')) :
                while (have_rows('core_advantages', 'option')) : the_row();
                    $icon = get_sub_field('advantage_icon');
                    $title = get_sub_field('advantage_title');
                    $description = get_sub_field('advantage_description');
                    ?>
                    <div class="advantage-card">
                        <?php if ($icon && is_array($icon)) : 
                            $icon_alt = !empty($icon['alt']) ? $icon['alt'] : $title;
                        ?>
                            <div class="advantage-icon">
                                <img src="<?php echo esc_url($icon['url']); ?>" 
                                     alt="<?php echo esc_attr($icon_alt); ?>">
                            </div>
                        <?php endif; ?>
                        <h3 class="advantage-title"><?php echo esc_html($title); ?></h3>
                        <p class="advantage-description"><?php echo esc_html($description); ?></p>
                    </div>
                    <?php
                endwhile;
            else :
                ?>
                <p class="no-content-message">
                    <?php esc_html_e('Please configure advantages in Theme Settings.', 'angola-b2b'); ?>
                </p>
                <?php
            endif;
            ?>
        </div>
    </div>
</section>

