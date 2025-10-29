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
<?php wp_body_open(); ?>

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
                // WPML language switcher will be added here
                if (function_exists('icl_get_languages')) {
                    $languages = icl_get_languages('skip_missing=0&orderby=code');
                    if (!empty($languages)) {
                        echo '<select id="language-select" class="language-select-dropdown" aria-label="' . esc_attr__('Select Language', 'angola-b2b') . '">';
                        foreach ($languages as $lang) {
                            $selected = $lang['active'] ? 'selected="selected"' : '';
                            echo '<option value="' . esc_url($lang['url']) . '" ' . $selected . '>' . esc_html($lang['native_name']) . '</option>';
                        }
                        echo '</select>';
                    }
                }
                ?>
            </div>
            
            <!-- CTA Button -->
            <div class="header-cta">
                <?php
                $contact_page = get_page_by_path('contact');
                $contact_url = $contact_page ? get_permalink($contact_page) : home_url('/contact/');
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

