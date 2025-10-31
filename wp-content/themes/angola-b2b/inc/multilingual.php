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
 * Force admin backend to Chinese (Simplified)
 * Works with both WPML and Polylang
 */
function angola_b2b_force_admin_language($locale) {
    if (is_admin()) {
        return 'zh_CN'; // Force admin to Simplified Chinese
    }
    return $locale;
}
add_filter('locale', 'angola_b2b_force_admin_language', 999);

/**
 * Prevent Polylang from filtering admin language
 */
function angola_b2b_polylang_admin_language_filter($lang) {
    if (is_admin()) {
        return false; // Disable Polylang's admin language filtering
    }
    return $lang;
}
add_filter('pll_preferred_language', 'angola_b2b_polylang_admin_language_filter', 999);

/**
 * Register strings for Polylang translation
 * 为Polylang注册需要翻译的字符串
 */
function angola_b2b_register_polylang_strings() {
    if (!function_exists('pll_register_string')) {
        return;
    }
    
    // Product card strings
    pll_register_string('angola-b2b', '立即询价', 'Product Card');
    pll_register_string('angola-b2b', '查看详情', 'Product Card');
    pll_register_string('angola-b2b', '库存：%d 件', 'Product Card');
    pll_register_string('angola-b2b', '现货', 'Product Card');
    pll_register_string('angola-b2b', '推荐', 'Product Card');
    pll_register_string('angola-b2b', '查看更多', 'Product Card');
    
    // Homepage sections
    pll_register_string('angola-b2b', '现货供应 - 即刻发货', 'Homepage');
    pll_register_string('angola-b2b', '本地库存，即刻发货', 'Homepage');
    pll_register_string('angola-b2b', 'Featured Products', 'Homepage');
    pll_register_string('angola-b2b', 'View All Products', 'Homepage');
    pll_register_string('angola-b2b', 'No featured products at the moment.', 'Homepage');
    pll_register_string('angola-b2b', 'Why Choose Us', 'Homepage');
    pll_register_string('angola-b2b', 'Contact Us Now', 'Homepage');
    
    // Header/Footer
    pll_register_string('angola-b2b', 'Request Quote', 'Header');
    pll_register_string('angola-b2b', 'Select Language', 'Header');
    
    // Product single page
    pll_register_string('angola-b2b', 'Description', 'Product Single');
    pll_register_string('angola-b2b', 'Specifications', 'Product Single');
    pll_register_string('angola-b2b', 'Inquiry', 'Product Single');
    pll_register_string('angola-b2b', 'Specification', 'Product Single');
    pll_register_string('angola-b2b', 'Value', 'Product Single');
    pll_register_string('angola-b2b', 'No specifications available for this product.', 'Product Single');
    pll_register_string('angola-b2b', 'Related Products', 'Product Single');
    pll_register_string('angola-b2b', 'Add to Inquiry', 'Product Single');
    pll_register_string('angola-b2b', 'Contact Us', 'Product Single');
}
add_action('init', 'angola_b2b_register_polylang_strings');

