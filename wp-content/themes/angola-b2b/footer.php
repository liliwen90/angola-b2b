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
                        <h3><?php bloginfo('name'); ?></h3>
                        <p><?php bloginfo('description'); ?></p>
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
                <a href="#" class="social-link" aria-label="Facebook"><i class="dashicons dashicons-facebook"></i></a>
                <a href="#" class="social-link" aria-label="Twitter"><i class="dashicons dashicons-twitter"></i></a>
                <a href="#" class="social-link" aria-label="LinkedIn"><i class="dashicons dashicons-linkedin"></i></a>
                <a href="#" class="social-link whatsapp-link" aria-label="WhatsApp"><i class="dashicons dashicons-whatsapp"></i></a>
            </div>

            <!-- Copyright -->
            <div class="footer-copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'angola-b2b'); ?></p>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'angola-b2b'); ?>">
            <span class="dashicons dashicons-arrow-up-alt"></span>
        </button>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

