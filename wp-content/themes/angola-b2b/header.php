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
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/favicon.ico">
    
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
                        // 优先使用ACF字段中的Logo
                        $site_logo = get_field('site_logo', 45);
                        $site_title = get_field('site_title', 45) ?: get_bloginfo('name');
                        
                        if ($site_logo) {
                            // 使用ACF上传的Logo
                            if (is_array($site_logo) && isset($site_logo['url'])) {
                                echo '<img src="' . esc_url($site_logo['url']) . '" alt="' . esc_attr($site_title) . '" class="custom-logo">';
                            }
                        } elseif (has_custom_logo()) {
                            // 使用WordPress自定义Logo
                            the_custom_logo();
                        } else {
                            // 显示文字Logo
                            echo '<h1 class="site-title">' . esc_html($site_title) . '</h1>';
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
                            // 获取联系信息
                            $contact_email = get_field('contact_email', 45) ?: 'info@example.com';
                            $contact_phone = get_field('contact_phone', 45) ?: '+86 15319996326';
                            
                            // 获取社交媒体信息
                            $social_facebook = get_field('social_facebook', 45);
                            $social_facebook_show = get_field('social_facebook_show', 45);
                            $social_twitter = get_field('social_twitter', 45);
                            $social_twitter_show = get_field('social_twitter_show', 45);
                            $social_linkedin = get_field('social_linkedin', 45);
                            $social_linkedin_show = get_field('social_linkedin_show', 45);
                            $social_whatsapp = get_field('social_whatsapp', 45);
                            $social_whatsapp_show = get_field('social_whatsapp_show', 45);
                            ?>
                            
                            <!-- 邮箱 -->
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span><?php echo esc_html($contact_email); ?></span>
                            </a>
                            
                            <!-- 电话 -->
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $contact_phone)); ?>" class="dropdown-item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                <span><?php echo esc_html($contact_phone); ?></span>
                            </a>
                            
                            <?php if ($social_facebook && $social_facebook_show || $social_twitter && $social_twitter_show || $social_linkedin && $social_linkedin_show || $social_whatsapp && $social_whatsapp_show) : ?>
                                <!-- 分隔线 -->
                                <div class="dropdown-divider"></div>
                                
                                <!-- 社交媒体图标 -->
                                <div class="dropdown-social-icons">
                                    <?php if ($social_facebook && $social_facebook_show) : ?>
                                        <a href="<?php echo esc_url($social_facebook); ?>" class="social-icon-link" target="_blank" rel="noopener" aria-label="Facebook">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($social_twitter && $social_twitter_show) : ?>
                                        <a href="<?php echo esc_url($social_twitter); ?>" class="social-icon-link" target="_blank" rel="noopener" aria-label="Twitter">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($social_linkedin && $social_linkedin_show) : ?>
                                        <a href="<?php echo esc_url($social_linkedin); ?>" class="social-icon-link" target="_blank" rel="noopener" aria-label="LinkedIn">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($social_whatsapp && $social_whatsapp_show) : 
                                        $whatsapp_number = preg_replace('/[^0-9+]/', '', $social_whatsapp);
                                        $whatsapp_url = 'https://wa.me/' . ltrim($whatsapp_number, '+');
                                    ?>
                                        <a href="<?php echo esc_url($whatsapp_url); ?>" class="social-icon-link" target="_blank" rel="noopener" aria-label="WhatsApp">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
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

