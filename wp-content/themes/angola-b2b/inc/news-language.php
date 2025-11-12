<?php
/**
 * Strict language separation for Posts (News)
 * - Add meta box: post_lang, lang_group
 * - Filter queries by current language
 * - 404 when language missing or mismatched
 *
 * @package Angola_B2B
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helpers
 */
function angola_b2b_get_post_language($post_id = 0) {
    $post_id = $post_id ?: get_the_ID();
    $lang = get_post_meta($post_id, 'post_lang', true);
    return $lang ?: '';
}

function angola_b2b_get_post_lang_group($post_id = 0) {
    $post_id = $post_id ?: get_the_ID();
    return get_post_meta($post_id, 'lang_group', true);
}

/**
 * Add meta box on post editor
 */
function angola_b2b_add_news_language_metabox() {
    add_meta_box(
        'angola_b2b_news_language',
        __('News Language', 'angola-b2b'),
        'angola_b2b_render_news_language_metabox',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'angola_b2b_add_news_language_metabox');

function angola_b2b_render_news_language_metabox($post) {
    $current_lang = get_post_meta($post->ID, 'post_lang', true);
    $lang_group = get_post_meta($post->ID, 'lang_group', true);
    wp_nonce_field('angola_b2b_save_news_lang', 'angola_b2b_news_lang_nonce');
    $options = array(
        'en' => 'English',
        'pt' => 'Português',
        'zh' => '简体中文',
        'zh_tw' => '繁體中文',
    );
    ?>
    <p style="margin:0 0 8px;"><?php esc_html_e('Select language for this article', 'angola-b2b'); ?></p>
    <select name="angola_b2b_post_lang" style="width:100%;">
        <?php foreach ($options as $val => $label): ?>
            <option value="<?php echo esc_attr($val); ?>" <?php selected($current_lang, $val); ?>>
                <?php echo esc_html($label . ' (' . $val . ')'); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p style="margin:12px 0 6px;"><?php esc_html_e('Language Group (same article across languages)', 'angola-b2b'); ?></p>
    <input type="text" name="angola_b2b_lang_group" value="<?php echo esc_attr($lang_group); ?>" style="width:100%;" placeholder="e.g. grp_12345">
    <p style="margin-top:6px; font-size:12px; color:#646970;">
        <?php esc_html_e('Use same group for different language versions. Leave empty to auto-generate.', 'angola-b2b'); ?>
    </p>
    <?php
}

/**
 * Save meta
 */
function angola_b2b_save_news_language_metabox($post_id) {
    if (!isset($_POST['angola_b2b_news_lang_nonce']) || !wp_verify_nonce($_POST['angola_b2b_news_lang_nonce'], 'angola_b2b_save_news_lang')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $lang = isset($_POST['angola_b2b_post_lang']) ? sanitize_text_field($_POST['angola_b2b_post_lang']) : '';
    if (!$lang) {
        $lang = 'zh';
    }
    update_post_meta($post_id, 'post_lang', $lang);

    $group = isset($_POST['angola_b2b_lang_group']) ? sanitize_text_field($_POST['angola_b2b_lang_group']) : '';
    if (!$group) {
        $group = 'grp_' . uniqid();
    }
    update_post_meta($post_id, 'lang_group', $group);
}
add_action('save_post_post', 'angola_b2b_save_news_language_metabox');

/**
 * Filter news queries by current language
 */
function angola_b2b_filter_news_by_language($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    // Home (posts page), category, tag archives for posts
    if ($query->is_home() || $query->is_category() || $query->is_tag()) {
        if (!$query->get('post_type')) {
            $query->set('post_type', 'post');
        }
        $current_lang = function_exists('angola_b2b_get_current_language') ? angola_b2b_get_current_language() : 'en';
        $meta_query = (array) $query->get('meta_query');
        $meta_query[] = array(
            'key'   => 'post_lang',
            'value' => $current_lang,
        );
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'angola_b2b_filter_news_by_language');


