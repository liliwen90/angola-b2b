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
        <!-- Header Background Layer -->
        <div class="header-background"></div>
        
        <!-- Header Content Layer -->
        <div class="header-content">
            <div class="header-container">
                <!-- Logo -->
                <div class="site-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            echo '<h1 class="site-title">' . esc_html(get_bloginfo('name')) . '</h1>';
                        }
                        ?>
                    </a>
                </div>
                
                <!-- Primary Navigation Menu (Desktop) -->
                <nav class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'angola-b2b'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'header-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>
                
                <!-- Header Action Icons - MSC Style (Icon上 Text下) -->
                <div class="header-actions">
                    <!-- Search -->
                    <button class="header-action-btn" id="search-toggle" aria-label="<?php esc_attr_e('Search', 'angola-b2b'); ?>">
                        <svg class="action-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <span class="action-label">Search</span>
                    </button>
                    
                    <!-- Contact Dropdown -->
                    <div class="header-contact-wrapper">
                        <button class="header-action-btn" aria-label="<?php esc_attr_e('Contact', 'angola-b2b'); ?>">
                            <svg class="action-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span class="action-label">Contact</span>
                        </button>
                        <div class="header-dropdown contact-dropdown">
                            <?php
                            $contact_email = get_field('contact_email', 45) ?: 'info@example.com';
                            $contact_phone = get_field('contact_phone', 45) ?: '+86 15319996326';
                            ?>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span><?php echo esc_html($contact_email); ?></span>
                            </a>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $contact_phone)); ?>" class="dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span><?php echo esc_html($contact_phone); ?></span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Language Switcher -->
                    <div class="header-language-wrapper">
                        <?php
                        $current_lang = angola_b2b_get_current_language();
                        $lang_labels = array(
                            'en' => 'EN',
                            'pt' => 'PT',
                            'es' => 'ES',
                            'fr' => 'FR',
                            'zh' => 'ZH',
                            'zh_tw' => '繁'
                        );
                        $current_label = isset($lang_labels[$current_lang]) ? $lang_labels[$current_lang] : 'EN';
                        ?>
                        <button class="header-action-btn header-lang-btn" aria-label="<?php esc_attr_e('Language', 'angola-b2b'); ?>">
                            <svg class="action-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <span class="action-label"><?php echo esc_html($current_label); ?></span>
                        </button>
                        <div class="header-dropdown language-dropdown">
                            <?php
                            angola_b2b_language_switcher(array(
                                'show_flag' => false,
                                'show_name' => true,
                                'class' => 'language-list',
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-controls="mobile-menu" aria-expanded="false" aria-label="<?php echo esc_attr(__t('toggle_menu')); ?>">
                    <span class="hamburger">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </span>
                </button>
            </div>
        </div>
    </header>

