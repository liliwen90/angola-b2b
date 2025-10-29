<?php
/**
 * AJAX Handlers
 * Handle AJAX requests for product filtering, search, etc.
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Product Filter Handler
 */
function angola_b2b_ajax_filter_products() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'angola_b2b_nonce')) {
        wp_send_json_error(array('message' => __('安全验证失败', 'angola-b2b')));
        return;
    }
    
    // Get filter parameters
    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
    $search = isset($_POST['search']) ? sanitize_text_field(wp_unslash($_POST['search'])) : '';
    $sort = isset($_POST['sort']) ? sanitize_text_field(wp_unslash($_POST['sort'])) : 'date-desc';
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    
    // Build query arguments
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );
    
    // Add category filter
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
    
    // Add search query
    if (!empty($search)) {
        $args['s'] = $search;
    }
    
    // Add sorting
    switch ($sort) {
        case 'date-asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'title-asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title-desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;
        default: // date-desc
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
    }
    
    // Execute query
    $query = new WP_Query($args);
    
    $response = array(
        'success'    => true,
        'found'      => $query->found_posts,
        'max_pages'  => $query->max_num_pages,
        'current_page' => $paged,
        'html'       => '',
    );
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/product/product-card');
        }
        
        $response['html'] = ob_get_clean();
        wp_reset_postdata();
    } else {
        $response['html'] = '<p class="no-products">' . esc_html__('未找到产品。', 'angola-b2b') . '</p>';
    }
    
    wp_send_json_success($response);
}
add_action('wp_ajax_angola_b2b_filter_products', 'angola_b2b_ajax_filter_products');
add_action('wp_ajax_nopriv_angola_b2b_filter_products', 'angola_b2b_ajax_filter_products');

/**
 * AJAX Load More Products Handler
 */
function angola_b2b_ajax_load_more_products() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'angola_b2b_nonce')) {
        wp_send_json_error(array('message' => __('安全验证失败', 'angola-b2b')));
        return;
    }
    
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : '';
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );
    
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/product/product-card');
        }
        
        $html = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'html'      => $html,
            'max_pages' => $query->max_num_pages,
        ));
    } else {
        wp_send_json_error(array('message' => __('没有更多产品了。', 'angola-b2b')));
    }
}
add_action('wp_ajax_angola_b2b_load_more_products', 'angola_b2b_ajax_load_more_products');
add_action('wp_ajax_nopriv_angola_b2b_load_more_products', 'angola_b2b_ajax_load_more_products');

/**
 * AJAX Submit Inquiry Handler
 */
function angola_b2b_ajax_submit_inquiry() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'angola_b2b_nonce')) {
        wp_send_json_error(array('message' => __('安全验证失败', 'angola-b2b')));
        return;
    }
    
    // Sanitize input
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_send_json_error(array('message' => __('请填写所有必填字段。', 'angola-b2b')));
        return;
    }
    
    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('请输入有效的邮箱地址。', 'angola-b2b')));
        return;
    }
    
    // Prepare email
    $to = get_option('admin_email');
    $subject = sprintf(__('[%s] 新的产品询价', 'angola-b2b'), get_bloginfo('name'));
    
    $body = sprintf(
        __("您收到一条新的产品询价：\n\n姓名：%s\n邮箱：%s\n公司：%s\n电话：%s\n\n留言：\n%s\n\n", 'angola-b2b'),
        $name,
        $email,
        $company,
        $phone,
        $message
    );
    
    if ($product_id) {
        $product_title = get_the_title($product_id);
        $product_link = get_permalink($product_id);
        $body .= sprintf(
            __("相关产品：%s\n产品链接：%s\n", 'angola-b2b'),
            $product_title,
            $product_link
        );
    }
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );
    
    // Send email
    $sent = wp_mail($to, $subject, $body, $headers);
    
    if ($sent) {
        wp_send_json_success(array('message' => __('提交成功！我们会尽快与您联系。', 'angola-b2b')));
    } else {
        wp_send_json_error(array('message' => __('提交失败，请稍后重试。', 'angola-b2b')));
    }
}
add_action('wp_ajax_angola_b2b_submit_inquiry', 'angola_b2b_ajax_submit_inquiry');
add_action('wp_ajax_nopriv_angola_b2b_submit_inquiry', 'angola_b2b_ajax_submit_inquiry');

