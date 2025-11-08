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
            
            <!-- MSC-Style Header Icons -->
            <div class="header-icons">
                <!-- Search Icon -->
                <button class="header-icon-btn" id="search-toggle" aria-label="<?php esc_attr_e('Search', 'angola-b2b'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <span class="header-icon-label"><?php _et('search'); ?></span>
                </button>
                
                <!-- Contact Icon (替代MSC的Tracking) -->
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="header-icon-btn" aria-label="<?php esc_attr_e('Contact', 'angola-b2b'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span class="header-icon-label"><?php _et('contact'); ?></span>
                </a>
                
                <!-- Language Switcher (simplified) -->
                <div class="header-language">
                    <?php
                    $current_lang = angola_b2b_get_current_language();
                    $lang_labels = array(
                        'en' => 'EN',
                        'pt' => 'PT',
                        'zh' => '中文',
                        'zh_tw' => '繁中'
                    );
                    $current_label = isset($lang_labels[$current_lang]) ? $lang_labels[$current_lang] : 'EN';
                    ?>
                    <button class="header-icon-btn language-toggle" aria-label="<?php esc_attr_e('Language', 'angola-b2b'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <span class="header-icon-label"><?php echo esc_html($current_label); ?></span>
                    </button>
                    <div class="language-dropdown">
                        <?php
                        // Custom language switcher
                        angola_b2b_language_switcher(array(
                            'show_flag' => false,
                            'show_name' => true,
                            'class' => 'language-dropdown-menu',
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php echo esc_attr(__t('toggle_menu')); ?>">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php _et('menu'); ?></span>
            </button>
        </div>
    </header>

