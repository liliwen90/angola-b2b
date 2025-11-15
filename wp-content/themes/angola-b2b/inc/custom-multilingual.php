<?php
/**
 * 自定义多语言系统 - Custom Multilingual System
 * 
 * 基于Cookie的简单多语言解决方案，不依赖任何插件
 * 
 * @package Angola_B2B
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 语言配置
 */
define('ANGOLA_B2B_DEFAULT_LANG', 'en');
define('ANGOLA_B2B_SUPPORTED_LANGS', array(
    'en' => array(
        'name' => 'English',
        'native_name' => 'English',
        'flag' => '🇬🇧',
    ),
    'pt' => array(
        'name' => 'Portuguese',
        'native_name' => 'Português',
        'flag' => '🇵🇹',
    ),
    'zh' => array(
        'name' => 'Simplified Chinese',
        'native_name' => '简体中文',
        'flag' => '🇨🇳',
    ),
    'zh_tw' => array(
        'name' => 'Traditional Chinese',
        'native_name' => '繁體中文',
        'flag' => '🇹🇼',
    ),
));

/**
 * 获取当前语言代码
 * 
 * @return string 当前语言代码（en, pt, zh, zh_tw）
 */
function angola_b2b_get_current_language() {
    // 1. 检查URL参数（用于切换语言）
    if (isset($_GET['lang']) && array_key_exists($_GET['lang'], ANGOLA_B2B_SUPPORTED_LANGS)) {
        $lang = sanitize_text_field($_GET['lang']);
        angola_b2b_set_language($lang);
        return $lang;
    }
    
    // 2. 检查Cookie
    if (isset($_COOKIE['angola_b2b_lang']) && array_key_exists($_COOKIE['angola_b2b_lang'], ANGOLA_B2B_SUPPORTED_LANGS)) {
        return sanitize_text_field($_COOKIE['angola_b2b_lang']);
    }
    
    // 3. 检查浏览器语言偏好（首次访问）
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        
        // 特殊处理繁体中文
        if ($browser_lang === 'zh') {
            $full_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            if (strpos($full_lang, 'zh-TW') !== false || strpos($full_lang, 'zh-HK') !== false) {
                angola_b2b_set_language('zh_tw');
                return 'zh_tw';
            }
            angola_b2b_set_language('zh');
            return 'zh';
        }
        
        // 映射浏览器语言到我们的语言代码
        $lang_map = array(
            'pt' => 'pt',
            'zh' => 'zh',
            'en' => 'en',
        );
        
        if (isset($lang_map[$browser_lang])) {
            $lang = $lang_map[$browser_lang];
            angola_b2b_set_language($lang);
            return $lang;
        }
    }
    
    // 4. 返回默认语言
    return ANGOLA_B2B_DEFAULT_LANG;
}

/**
 * 设置当前语言（保存到Cookie）
 * 
 * @param string $lang 语言代码
 * @return bool 是否成功设置
 */
function angola_b2b_set_language($lang) {
    if (!array_key_exists($lang, ANGOLA_B2B_SUPPORTED_LANGS)) {
        return false;
    }
    
    // 先设置当前请求的语言（立即生效）
    $_COOKIE['angola_b2b_lang'] = $lang;
    
    // 只有在headers未发送时才设置Cookie
    if (!headers_sent()) {
        // 设置Cookie，有效期365天
        $result = setcookie(
            'angola_b2b_lang',
            $lang,
            time() + (365 * 24 * 60 * 60),
            '/',
            '',
            is_ssl(),
            true // httponly
        );
        return $result;
    }
    
    // 如果headers已发送，至少确保当前请求使用正确的语言
    return true;
}

/**
 * 获取语言切换器HTML - MSC Style
 * 
 * @param array $args 配置参数
 * @return string 语言切换器HTML
 */
function angola_b2b_get_language_switcher($args = array()) {
    $defaults = array(
        'show_flag' => false,
        'show_name' => true,
        'class' => 'language-list',
    );
    
    $args = wp_parse_args($args, $defaults);
    $current_lang = angola_b2b_get_current_language();
    
    // MSC风格的语言显示名称（全大写英文）
    $lang_display_names = array(
        'en' => 'ENGLISH',
        'pt' => 'PORTUGUÊS',
        'es' => 'ESPAÑOL',
        'fr' => 'FRANÇAIS',
        'zh' => '中文',
        'zh_tw' => '繁體中文',
    );
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr($args['class']); ?>">
        <?php foreach (ANGOLA_B2B_SUPPORTED_LANGS as $code => $lang_data) : 
            $switch_url = add_query_arg('lang', $code, home_url('/'));
            $is_current = ($code === $current_lang);
            $display_name = isset($lang_display_names[$code]) ? $lang_display_names[$code] : strtoupper($lang_data['native_name']);
        ?>
            <a href="<?php echo esc_url($switch_url); ?>" 
               class="language-item <?php echo $is_current ? 'current' : ''; ?>"
               data-lang="<?php echo esc_attr($code); ?>">
                <?php echo esc_html($display_name); ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * 获取指定字段的翻译值
 * 
 * @param int    $post_id 文章ID
 * @param string $field_base 字段基础名称（如 'title'）
 * @param string $lang 语言代码（可选，默认当前语言）
 * @return string 翻译后的值，如果没有翻译则返回英文版本
 */
function angola_b2b_get_translation($post_id, $field_base, $lang = null) {
    if ($lang === null) {
        $lang = angola_b2b_get_current_language();
    }
    
    // 英语直接返回原始字段
    if ($lang === 'en') {
        return get_field($field_base, $post_id);
    }
    
    // 其他语言尝试获取翻译字段
    $translated_field = $field_base . '_' . $lang;
    $translation = get_field($translated_field, $post_id);
    
    // 如果翻译为空，回退到英文
    if (empty($translation)) {
        $translation = get_field($field_base, $post_id);
    }
    
    return $translation;
}

/**
 * 获取分类法术语的翻译名称
 * 
 * @param object|int $term 术语对象或术语ID
 * @param string $lang 语言代码（可选，默认当前语言）
 * @return string 翻译后的名称
 */
function angola_b2b_get_term_translation($term, $lang = null) {
    if ($lang === null) {
        $lang = angola_b2b_get_current_language();
    }
    
    // 如果传入的是ID，获取术语对象
    if (is_numeric($term)) {
        $term = get_term($term);
    }
    
    if (!$term || is_wp_error($term)) {
        return '';
    }
    
    // 英语直接返回原始名称
    if ($lang === 'en') {
        return $term->name;
    }
    
    // 其他语言尝试获取ACF翻译字段
    $translated_field = 'name_' . $lang;
    $translation = get_field($translated_field, $term);
    
    // 如果翻译为空，回退到英文
    if (empty($translation)) {
        return $term->name;
    }
    
    return $translation;
}

/**
 * 输出语言切换器
 * 
 * @param array $args 配置参数
 */
function angola_b2b_language_switcher($args = array()) {
    echo angola_b2b_get_language_switcher($args);
}

/**
 * 在plugins_loaded钩子上初始化语言系统（更早，确保在输出前设置Cookie）
 */
add_action('plugins_loaded', 'angola_b2b_init_multilingual', 1);
function angola_b2b_init_multilingual() {
    // 如果URL中有lang参数，优先处理（用于语言切换）
    if (isset($_GET['lang']) && array_key_exists($_GET['lang'], ANGOLA_B2B_SUPPORTED_LANGS)) {
        $lang = sanitize_text_field($_GET['lang']);
        angola_b2b_set_language($lang);
    } else {
        // 否则确保语言已经设置（从Cookie或浏览器语言）
        angola_b2b_get_current_language();
    }
}

/**
 * 语言切换样式已移至 assets/css/layout.css 中的 .language-select-dropdown
 */

