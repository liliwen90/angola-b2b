<?php
/**
 * Multilingual Support Functions
 * Support for WPML/Polylang integration
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get current language code
 */
function angola_b2b_get_current_language() {
    // WPML
    if (function_exists('icl_get_current_language')) {
        return icl_get_current_language();
    }
    
    // Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default to site language
    $locale = get_locale();
    $locale_parts = explode('_', $locale);
    return $locale_parts[0];
}

/**
 * Get all available languages
 */
function angola_b2b_get_languages() {
    $languages = array();
    
    // WPML
    if (function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($wpml_languages)) {
            foreach ($wpml_languages as $lang) {
                $languages[] = array(
                    'code'         => $lang['language_code'],
                    'native_name'  => $lang['native_name'],
                    'url'          => $lang['url'],
                    'active'       => $lang['active'],
                );
            }
        }
        return $languages;
    }
    
    // Polylang
    if (function_exists('pll_the_languages')) {
        $pll_languages = pll_the_languages(array('raw' => 1));
        if (!empty($pll_languages)) {
            foreach ($pll_languages as $lang) {
                $languages[] = array(
                    'code'        => $lang['slug'],
                    'native_name' => $lang['name'],
                    'url'         => $lang['url'],
                    'active'      => $lang['current_lang'],
                );
            }
        }
        return $languages;
    }
    
    return $languages;
}

/**
 * Get translated post ID
 */
function angola_b2b_get_translated_post_id($post_id, $lang_code = null) {
    if (!$lang_code) {
        $lang_code = angola_b2b_get_current_language();
    }
    
    // WPML
    if (function_exists('icl_object_id')) {
        $translated_id = icl_object_id($post_id, 'product', false, $lang_code);
        return $translated_id ? $translated_id : $post_id;
    }
    
    // Polylang
    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($post_id, $lang_code);
        return $translated_id ? $translated_id : $post_id;
    }
    
    return $post_id;
}

/**
 * Get language name display
 */
function angola_b2b_get_language_name($lang_code) {
    $language_names = array(
        'zh'      => '简体中文',
        'zh-hans' => '简体中文',
        'zh_CN'   => '简体中文',
        'zh-hant' => '繁體中文',
        'zh_TW'   => '繁體中文',
        'pt'      => 'Português',
        'pt_PT'   => 'Português',
        'en'      => 'English',
        'en_US'   => 'English',
    );
    
    return isset($language_names[$lang_code]) ? $language_names[$lang_code] : $lang_code;
}

/**
 * Output language switcher HTML
 */
function angola_b2b_language_switcher($args = array()) {
    $defaults = array(
        'dropdown'       => true,
        'show_flags'     => false,
        'show_names'     => true,
        'echo'           => true,
        'hide_if_empty'  => true,
        'class'          => 'language-switcher',
    );
    
    $args = wp_parse_args($args, $defaults);
    $languages = angola_b2b_get_languages();
    
    if (empty($languages) && $args['hide_if_empty']) {
        return '';
    }
    
    $output = '';
    
    if ($args['dropdown']) {
        $output .= '<div class="' . esc_attr($args['class']) . '">';
        $output .= '<select class="language-select-dropdown" aria-label="' . esc_attr__('Select Language', 'angola-b2b') . '">';
        
        foreach ($languages as $lang) {
            $selected = $lang['active'] ? ' selected="selected"' : '';
            $output .= sprintf(
                '<option value="%s"%s>%s</option>',
                esc_url($lang['url']),
                $selected,
                esc_html($lang['native_name'])
            );
        }
        
        $output .= '</select>';
        $output .= '</div>';
    } else {
        $output .= '<ul class="' . esc_attr($args['class']) . '">';
        
        foreach ($languages as $lang) {
            $active_class = $lang['active'] ? ' class="active"' : '';
            $output .= sprintf(
                '<li%s><a href="%s">%s</a></li>',
                $active_class,
                esc_url($lang['url']),
                esc_html($lang['native_name'])
            );
        }
        
        $output .= '</ul>';
    }
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}

/**
 * Add language body class
 */
function angola_b2b_language_body_class($classes) {
    $current_lang = angola_b2b_get_current_language();
    if ($current_lang) {
        $classes[] = 'lang-' . sanitize_html_class($current_lang);
    }
    return $classes;
}
add_filter('body_class', 'angola_b2b_language_body_class');

/**
 * Disable WPML admin language filtering for backend
 */
function angola_b2b_wpml_admin_language() {
    // Force admin to Chinese
    if (is_admin() && function_exists('icl_get_current_language')) {
        global $sitepress;
        if ($sitepress) {
            $sitepress->switch_lang('zh-hans', true);
        }
    }
}
add_action('init', 'angola_b2b_wpml_admin_language', 1);

