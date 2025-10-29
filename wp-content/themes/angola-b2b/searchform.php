<?php
/**
 * Search form template
 *
 * @package Angola_B2B
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'angola-b2b'); ?></span>
        <input type="search" class="search-field" placeholder="<?php esc_attr_e('Search...', 'angola-b2b'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit">
        <span class="dashicons dashicons-search"></span>
        <span class="screen-reader-text"><?php esc_html_e('Search', 'angola-b2b'); ?></span>
    </button>
</form>

