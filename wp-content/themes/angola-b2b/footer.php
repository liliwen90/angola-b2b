<?php
/**
 * The footer template
 *
 * @package Angola_B2B
 */
?>

    <footer id="colophon" class="site-footer">
        <!-- Footer Main -->
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <!-- Company Info -->
                    <div class="footer-column footer-about">
                        <div class="footer-logo">
                            <?php if (has_custom_logo()) : ?>
                                <?php the_custom_logo(); ?>
                            <?php else : ?>
                                <h3 class="footer-brand"><?php echo esc_html(get_bloginfo('name')); ?></h3>
                            <?php endif; ?>
                        </div>
                        <p class="footer-description">
                            <?php
                            $description = get_bloginfo('description');
                            echo $description ? esc_html($description) : esc_html__('Your trusted partner for quality products and reliable service in construction, agriculture, and industrial equipment.', 'angola-b2b');
                            ?>
                        </p>
                        
                        <!-- Social Media -->
                        <div class="footer-social">
                            <a href="#" class="social-icon" target="_blank" rel="noopener" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="social-icon" target="_blank" rel="noopener" aria-label="Twitter">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="#" class="social-icon" target="_blank" rel="noopener" aria-label="LinkedIn">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <a href="#" class="social-icon" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="footer-column footer-links">
                        <h4 class="footer-heading"><?php esc_html_e('Company', 'angola-b2b'); ?></h4>
                        <ul class="footer-menu">
                            <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About Us', 'angola-b2b'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/services')); ?>"><?php esc_html_e('Our Services', 'angola-b2b'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/careers')); ?>"><?php esc_html_e('Careers', 'angola-b2b'); ?></a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'angola-b2b'); ?></a></li>
                        </ul>
                    </div>
                    
                    <!-- Product Categories -->
                    <div class="footer-column footer-products">
                        <h4 class="footer-heading"><?php esc_html_e('Products', 'angola-b2b'); ?></h4>
                        <ul class="footer-menu">
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'product_category',
                                'parent' => 0,
                                'hide_empty' => false,
                                'number' => 4,
                            ));
                            if (!empty($categories) && !is_wp_error($categories)) :
                                foreach ($categories as $category) :
                                    ?>
                                    <li><a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a></li>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="footer-column footer-contact">
                        <h4 class="footer-heading"><?php esc_html_e('Contact Us', 'angola-b2b'); ?></h4>
                        <div class="contact-info">
                            <div class="contact-item">
                                <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div>
                                    <span class="contact-label"><?php esc_html_e('Email', 'angola-b2b'); ?></span>
                                    <a href="mailto:info@example.com">info@example.com</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div>
                                    <span class="contact-label"><?php esc_html_e('Phone', 'angola-b2b'); ?></span>
                                    <a href="tel:+12345678900">+1 234 567 8900</a>
                                </div>
                            </div>
                            <div class="contact-item">
                                <svg class="contact-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div>
                                    <span class="contact-label"><?php esc_html_e('Address', 'angola-b2b'); ?></span>
                                    <span>Luanda, Angola</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <p class="footer-copyright">
                        &copy; <?php echo esc_html(date_i18n('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'angola-b2b'); ?>
                    </p>
                    <div class="footer-legal">
                        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>"><?php esc_html_e('Privacy Policy', 'angola-b2b'); ?></a>
                        <span class="separator">|</span>
                        <a href="<?php echo esc_url(home_url('/terms')); ?>"><?php esc_html_e('Terms & Conditions', 'angola-b2b'); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'angola-b2b'); ?>" style="display: none;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="18 15 12 9 6 15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="screen-reader-text"><?php esc_html_e('Back to top', 'angola-b2b'); ?></span>
        </button>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

