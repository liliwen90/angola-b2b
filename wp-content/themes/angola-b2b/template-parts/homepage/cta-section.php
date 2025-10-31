<?php
/**
 * Homepage Call to Action Section
 * 首页CTA区域
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">
                <?php 
                $cta_title = get_field('cta_title', 'option');
                echo esc_html($cta_title ? $cta_title : __('Ready to Start?', 'angola-b2b')); 
                ?>
            </h2>
            <p class="cta-text">
                <?php 
                $cta_text = get_field('cta_text', 'option');
                echo esc_html($cta_text ? $cta_text : __('Contact us for more information.', 'angola-b2b')); 
                ?>
            </p>
            <?php
            // Get contact page URL from ACF option or find by slug (WordPress 5.9+ compatible)
            $contact_url = get_field('contact_page_url', 'option');
            if (empty($contact_url)) {
                $contact_page = get_posts(array(
                    'post_type'   => 'page',
                    'name'        => 'contact',
                    'numberposts' => 1,
                ));
                $contact_url = !empty($contact_page) ? get_permalink($contact_page[0]->ID) : home_url('/contact/');
            }
            ?>
            <a href="<?php echo esc_url($contact_url); ?>" class="btn btn-light">
                <?php esc_html_e('Contact Us Now', 'angola-b2b'); ?>
            </a>
        </div>
    </div>
</section>

