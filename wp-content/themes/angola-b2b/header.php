<?php
/**
 * The header template
 *
 * @package Angola_B2B
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php 
// Debug output (only visible when WP_DEBUG is true)
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo '<!-- ========== HEADER.PHP LOADED ========== -->';
    echo '<!-- Current Post Type: ' . esc_html(get_post_type()) . ' -->';
    echo '<!-- Current Template: ' . esc_html(basename(get_page_template())) . ' -->';
    echo '<!-- Is Singular: ' . (is_singular() ? 'YES' : 'NO') . ' -->';
    echo '<!-- Is Single Product: ' . (is_singular('product') ? 'YES' : 'NO') . ' -->';
    echo '<!-- ======================================== -->';
}

wp_body_open(); 
?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _et('skip_to_content'); ?></a>

    <header class="site-header" id="masthead">
        <div class="header-container">
            <!-- Logo -->
            <div class="site-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        echo '<h1 class="site-title">' . esc_html(get_bloginfo('name')) . '</h1>';
                    }
                    ?>
                </a>
            </div>
            
            <!-- Language Switcher -->
            <?php
            // Custom language switcher - Site-wide language switch (always go to homepage)
            // Using Cookie-based system, no plugin dependencies
            angola_b2b_language_switcher(array(
                'show_flag' => true,
                'show_name' => true,
                'class' => 'language-switcher',
            ));
            ?>
            
            <!-- CTA Button -->
            <div class="header-cta">
                <?php
                // Get contact page URL from ACF option or fallback to /contact/
                $contact_url = get_field('contact_page_url', 'option');
                if (empty($contact_url)) {
                    // Try to find contact page by slug (WordPress 5.9+ compatible)
                    $contact_page = get_posts(array(
                        'post_type'   => 'page',
                        'name'        => 'contact',
                        'numberposts' => 1,
                    ));
                    $contact_url = !empty($contact_page) ? get_permalink($contact_page[0]->ID) : home_url('/contact/');
                }
                ?>
                <a href="<?php echo esc_url($contact_url); ?>" class="cta-button">
                    <?php _et('request_quote'); ?>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php echo esc_attr(__t('toggle_menu')); ?>">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php _et('menu'); ?></span>
            </button>
        </div>
    </header>

