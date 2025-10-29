<?php
/**
 * Template for displaying search forms
 *
 * @package Angola_B2B
 */

$unique_id = uniqid('search-form-');
$search_query = get_search_query();
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>" class="screen-reader-text">
        <?php esc_html_e('搜索:', 'angola-b2b'); ?>
    </label>
    
    <div class="search-form-wrapper">
        <input type="search" 
               id="<?php echo esc_attr($unique_id); ?>" 
               class="search-field" 
               placeholder="<?php echo esc_attr_x('搜索产品...', 'placeholder', 'angola-b2b'); ?>" 
               value="<?php echo esc_attr($search_query); ?>" 
               name="s" 
               required 
               aria-required="true">
        
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('提交搜索', 'angola-b2b'); ?>">
            <span class="dashicons dashicons-search"></span>
            <span class="search-submit-text"><?php esc_html_e('搜索', 'angola-b2b'); ?></span>
        </button>
    </div>
    
    <?php if (angola_b2b_is_product_page()) : ?>
        <input type="hidden" name="post_type" value="product">
    <?php endif; ?>
</form>
