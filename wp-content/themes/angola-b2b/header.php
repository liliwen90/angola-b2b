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
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'angola-b2b'); ?></a>

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
            
            <!-- Main Navigation -->
            <nav class="main-navigation" id="site-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                ));
                ?>
            </nav>
            
            <!-- Language Switcher -->
            <div class="language-switcher">
                <?php
                // Polylang language switcher - Show all languages even if no translation exists
                if (function_exists('pll_the_languages')) {
                    $languages = pll_the_languages(array(
                        'raw' => 1,
                        'hide_if_empty' => 0,           // Show all languages even if no content
                        'hide_if_no_translation' => 0,  // Don't hide untranslated languages
                        'force_home' => 1,              // Link to home if no translation exists
                    ));
                    if (!empty($languages) && is_array($languages)) {
                        echo '<select id="language-select" class="language-select-dropdown" aria-label="' . esc_attr__('Select Language', 'angola-b2b') . '">';
                        foreach ($languages as $lang) {
                            $selected = ($lang['current_lang']) ? 'selected="selected"' : '';
                            echo '<option value="' . esc_url($lang['url']) . '" ' . $selected . '>' . esc_html($lang['name']) . '</option>';
                        }
                        echo '</select>';
                    }
                }
                ?>
            </div>
            
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
                    <?php esc_html_e('Request Quote', 'angola-b2b'); ?>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation menu', 'angola-b2b'); ?>">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'angola-b2b'); ?></span>
            </button>
        </div>
    </header>

