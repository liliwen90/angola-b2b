<?php
/**
 * The footer template
 *
 * @package Angola_B2B
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            <div class="footer-row">
                <!-- Footer Column 1 -->
                <div class="footer-column footer-column-1">
                    <?php
                    if (is_active_sidebar('footer-1')) {
                        dynamic_sidebar('footer-1');
                    } else {
                        ?>
                        <h3><?php echo esc_html(get_bloginfo('name')); ?></h3>
                        <p><?php echo esc_html(get_bloginfo('description')); ?></p>
                        <?php
                    }
                    ?>
                </div>

                <!-- Footer Column 2 -->
                <div class="footer-column footer-column-2">
                    <?php
                    if (is_active_sidebar('footer-2')) {
                        dynamic_sidebar('footer-2');
                    }
                    ?>
                </div>

                <!-- Footer Column 3 -->
                <div class="footer-column footer-column-3">
                    <?php
                    if (is_active_sidebar('footer-3')) {
                        dynamic_sidebar('footer-3');
                    }
                    ?>
                </div>

                <!-- Footer Column 4 -->
                <div class="footer-column footer-column-4">
                    <?php
                    if (is_active_sidebar('footer-4')) {
                        dynamic_sidebar('footer-4');
                    } else {
                        ?>
                        <h3><?php esc_html_e('Contact Us', 'angola-b2b'); ?></h3>
                        <div class="footer-contact">
                            <p><?php esc_html_e('Email: info@example.com', 'angola-b2b'); ?></p>
                            <p><?php esc_html_e('Phone: +1 234 567 8900', 'angola-b2b'); ?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Social Media Links -->
            <div class="footer-social">
                <?php
                $social_links = array(
                    'facebook' => get_field('facebook_url', 'option'),
                    'twitter' => get_field('twitter_url', 'option'),
                    'linkedin' => get_field('linkedin_url', 'option'),
                    'whatsapp' => get_field('whatsapp_url', 'option'),
                );
                
                $social_icons = array(
                    'facebook' => 'dashicons-facebook-alt',
                    'twitter' => 'dashicons-twitter',
                    'linkedin' => 'dashicons-linkedin',
                    'whatsapp' => 'dashicons-whatsapp',
                );
                
                foreach ($social_links as $network => $url) {
                    if (!empty($url)) {
                        printf(
                            '<a href="%1$s" class="social-link social-link-%2$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s"><span class="dashicons %4$s"></span></a>',
                            esc_url($url),
                            esc_attr($network),
                            esc_attr(ucfirst($network)),
                            esc_attr($social_icons[$network])
                        );
                    }
                }
                ?>
            </div>

            <!-- Copyright -->
            <div class="footer-copyright">
                <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'angola-b2b'); ?></p>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'angola-b2b'); ?>" style="display: none;">
            <span class="dashicons dashicons-arrow-up-alt" aria-hidden="true"></span>
            <span class="screen-reader-text"><?php esc_html_e('Back to top', 'angola-b2b'); ?></span>
        </button>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

