<?php
/**
 * Inquiry System Functions
 * Handle product inquiry forms and notifications
 *
 * @package Angola_B2B
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display inquiry form
 */
function angola_b2b_inquiry_form($product_id = 0) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    $product_title = $product_id ? get_the_title($product_id) : '';
    
    ob_start();
    ?>
    <div class="inquiry-form-wrapper">
        <form class="inquiry-form" id="product-inquiry-form" data-product-id="<?php echo esc_attr($product_id); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('angola_b2b_nonce')); ?>">
            <h3 class="form-title"><?php esc_html_e('询价', 'angola-b2b'); ?></h3>
            
            <?php if ($product_title) : ?>
                <p class="form-subtitle"><?php echo esc_html(sprintf(__('产品: %s', 'angola-b2b'), $product_title)); ?></p>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="inquiry-name"><?php esc_html_e('姓名', 'angola-b2b'); ?> <span class="required">*</span></label>
                <input type="text" id="inquiry-name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="inquiry-email"><?php esc_html_e('邮箱', 'angola-b2b'); ?> <span class="required">*</span></label>
                <input type="email" id="inquiry-email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="inquiry-company"><?php esc_html_e('公司名称', 'angola-b2b'); ?></label>
                <input type="text" id="inquiry-company" name="company">
            </div>
            
            <div class="form-group">
                <label for="inquiry-phone"><?php esc_html_e('电话', 'angola-b2b'); ?></label>
                <input type="tel" id="inquiry-phone" name="phone">
            </div>
            
            <div class="form-group">
                <label for="inquiry-message"><?php esc_html_e('留言', 'angola-b2b'); ?> <span class="required">*</span></label>
                <textarea id="inquiry-message" name="message" rows="5" required></textarea>
            </div>
            
            <div class="form-message" style="display: none;"></div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary submit-inquiry">
                    <?php esc_html_e('提交询价', 'angola-b2b'); ?>
                </button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Display quick inquiry button
 */
function angola_b2b_quick_inquiry_button($product_id = 0) {
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    ob_start();
    ?>
    <button class="btn btn-primary quick-inquiry-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
        <span class="dashicons dashicons-email-alt"></span>
        <?php esc_html_e('快速询价', 'angola-b2b'); ?>
    </button>
    <?php
    return ob_get_clean();
}

/**
 * WhatsApp inquiry button
 */
function angola_b2b_whatsapp_button($product_id = 0) {
    $whatsapp_number = get_field('whatsapp_number', 'option');
    
    if (empty($whatsapp_number)) {
        return '';
    }
    
    if (!$product_id) {
        $product_id = get_the_ID();
    }
    
    $product_title = $product_id ? get_the_title($product_id) : '';
    $product_url = $product_id ? get_permalink($product_id) : '';
    
    $message = sprintf(
        __('您好！我对以下产品感兴趣：%s %s', 'angola-b2b'),
        $product_title,
        $product_url
    );
    
    $whatsapp_link = sprintf(
        'https://wa.me/%s?text=%s',
        preg_replace('/[^0-9]/', '', $whatsapp_number),
        rawurlencode($message)
    );
    
    ob_start();
    ?>
    <a href="<?php echo esc_url($whatsapp_link); ?>" 
       class="btn btn-whatsapp" 
       target="_blank" 
       rel="noopener noreferrer">
        <span class="dashicons dashicons-whatsapp"></span>
        <?php esc_html_e('WhatsApp咨询', 'angola-b2b'); ?>
    </a>
    <?php
    return ob_get_clean();
}

/**
 * Send inquiry notification email
 */
function angola_b2b_send_inquiry_notification($data) {
    $to = get_option('admin_email');
    $subject = sprintf(__('[%s] 新的产品询价', 'angola-b2b'), get_bloginfo('name'));
    
    // Build email body
    $body = __('您收到一条新的产品询价：', 'angola-b2b') . "\n\n";
    $body .= sprintf(__('姓名：%s', 'angola-b2b'), $data['name']) . "\n";
    $body .= sprintf(__('邮箱：%s', 'angola-b2b'), $data['email']) . "\n";
    
    if (!empty($data['company'])) {
        $body .= sprintf(__('公司：%s', 'angola-b2b'), $data['company']) . "\n";
    }
    
    if (!empty($data['phone'])) {
        $body .= sprintf(__('电话：%s', 'angola-b2b'), $data['phone']) . "\n";
    }
    
    $body .= "\n" . __('留言：', 'angola-b2b') . "\n";
    $body .= $data['message'] . "\n\n";
    
    if (!empty($data['product_id'])) {
        $product_title = get_the_title($data['product_id']);
        $product_link = get_permalink($data['product_id']);
        
        $body .= sprintf(__('相关产品：%s', 'angola-b2b'), $product_title) . "\n";
        $body .= sprintf(__('产品链接：%s', 'angola-b2b'), $product_link) . "\n";
    }
    
    $body .= "\n" . sprintf(__('发送时间：%s', 'angola-b2b'), current_time('mysql')) . "\n";
    $body .= sprintf(__('IP地址：%s', 'angola-b2b'), angola_b2b_get_user_ip()) . "\n";
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
    );
    
    return wp_mail($to, $subject, $body, $headers);
}

/**
 * Send auto-reply to customer
 */
function angola_b2b_send_inquiry_autoreply($data) {
    $to = $data['email'];
    $subject = sprintf(__('[%s] 我们已收到您的询价', 'angola-b2b'), get_bloginfo('name'));
    
    $body = sprintf(__('尊敬的 %s，', 'angola-b2b'), $data['name']) . "\n\n";
    $body .= __('感谢您对我们产品的关注！', 'angola-b2b') . "\n\n";
    $body .= __('我们已经收到您的询价信息，我们的业务团队会尽快与您联系。', 'angola-b2b') . "\n\n";
    
    if (!empty($data['product_id'])) {
        $product_title = get_the_title($data['product_id']);
        $body .= sprintf(__('您询价的产品：%s', 'angola-b2b'), $product_title) . "\n\n";
    }
    
    $body .= __('您的留言：', 'angola-b2b') . "\n";
    $body .= $data['message'] . "\n\n";
    $body .= '---' . "\n";
    $body .= get_bloginfo('name') . "\n";
    
    $contact_email = get_field('contact_email', 'option');
    if ($contact_email) {
        $body .= sprintf(__('邮箱：%s', 'angola-b2b'), $contact_email) . "\n";
    }
    
    $contact_phone = get_field('contact_phone', 'option');
    if ($contact_phone) {
        $body .= sprintf(__('电话：%s', 'angola-b2b'), $contact_phone) . "\n";
    }
    
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
    );
    
    return wp_mail($to, $subject, $body, $headers);
}

/**
 * Get user IP address safely
 */
function angola_b2b_get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_CLIENT_IP']));
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = sanitize_text_field(wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']));
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR']));
    } else {
        $ip = '';
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '';
}

