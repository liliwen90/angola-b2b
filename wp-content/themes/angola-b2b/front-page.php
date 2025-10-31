<?php
/**
 * The front page template (Homepage)
 *
 * @package Angola_B2B
 */

get_header();
?>

<main id="primary" class="site-main homepage">
    
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <div class="hero-background">
            <?php
            $hero_video = get_field('hero_video_url', 'option');
            $hero_image = get_field('hero_image', 'option');
            
            if ($hero_video) :
                ?>
                <video class="hero-video" autoplay muted loop playsinline>
                    <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                </video>
                <?php
            elseif ($hero_image && is_array($hero_image)) :
                $hero_alt = !empty($hero_image['alt']) ? $hero_image['alt'] : get_bloginfo('name');
                ?>
                <img src="<?php echo esc_url($hero_image['url']); ?>" alt="<?php echo esc_attr($hero_alt); ?>" class="hero-image">
                <?php
            endif;
            ?>
        </div>
        <div class="hero-content">
            <h1 class="hero-title anim-fade-down">
                <?php echo esc_html(get_field('hero_title', 'option')); ?>
            </h1>
            <p class="hero-subtitle anim-fade-up">
                <?php echo esc_html(get_field('hero_subtitle', 'option')); ?>
            </p>
            <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="hero-cta anim-scale-in">
                <?php esc_html_e('View Products', 'angola-b2b'); ?>
            </a>
        </div>
    </section>

    <!-- Core Advantages Section -->
    <section class="core-advantages">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e('Why Choose Us', 'angola-b2b'); ?></h2>
            <div class="advantages-grid">
                <?php
                if (have_rows('core_advantages', 'option')) :
                    while (have_rows('core_advantages', 'option')) : the_row();
                        $icon = get_sub_field('advantage_icon');
                        $title = get_sub_field('advantage_title');
                        $description = get_sub_field('advantage_description');
                        ?>
                        <div class="advantage-card">
                            <?php if ($icon && is_array($icon)) : 
                                $icon_alt = !empty($icon['alt']) ? $icon['alt'] : $title;
                            ?>
                                <div class="advantage-icon">
                                    <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon_alt); ?>">
                                </div>
                            <?php endif; ?>
                            <h3 class="advantage-title"><?php echo esc_html($title); ?></h3>
                            <p class="advantage-description"><?php echo esc_html($description); ?></p>
                        </div>
                        <?php
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e('Featured Products', 'angola-b2b'); ?></h2>
            <div class="products-grid">
                <?php
                $featured_products = new WP_Query(array(
                    'post_type' => 'product',
                    'posts_per_page' => 8,
                    'meta_query' => array(
                        array(
                            'key' => 'product_featured',
                            'value' => '1',
                            'compare' => '='
                        )
                    )
                ));

                if ($featured_products->have_posts()) :
                    while ($featured_products->have_posts()) : $featured_products->the_post();
                        get_template_part('template-parts/product/product-card');
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            <div class="section-cta">
                <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>" class="btn btn-primary">
                    <?php esc_html_e('View All Products', 'angola-b2b'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html(get_field('cta_title', 'option')); ?></h2>
                <p class="cta-text"><?php echo esc_html(get_field('cta_text', 'option')); ?></p>
                <?php
                // Get contact page URL from ACF option or find by slug (WordPress 5.9+ compatible)
                $contact_url = get_field('contact_page_url', 'option');
                if (empty($contact_url)) {
                    $contact_page = get_posts(array(
                        'post_type'   => 'page',
                        'name'        => 'contact',
                        'numberposts' => 1,
                    ));
                    $contact_url = !empty($contact_page) ? get_permalink($contact_page[0]->ID) : home_url('/contact/');
                }
                ?>
                <a href="<?php echo esc_url($contact_url); ?>" class="btn btn-light">
                    <?php esc_html_e('Contact Us Now', 'angola-b2b'); ?>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();

