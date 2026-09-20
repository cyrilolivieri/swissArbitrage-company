<?php
/**
 * Template Name: CompactBox Homepage
 * Template Post Type: page
 *
 * Standalone Nomad-structure homepage:
 * announce bar + pill header (in header.php), then:
 * 1. Hero
 * 2. Promo banner (dark, full-width)
 * 3. Product row "New arrivals" (hidden until products exist)
 * 4. Category tiles (2x2, image + label)
 * 5. Reviews band
 * 6. Trust / About
 * 7. Footer
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class('cb-homepage'); ?>>

    <?php get_template_part('template-parts/header'); ?>

    <!-- 1. Hero -->
    <?php get_template_part('template-parts/hero'); ?>

    <!-- 2. Promo banner (Nomad "NEW / Level Up" style) -->
    <section class="cb-promo" aria-label="Promotion">
        <a class="cb-promo__inner" href="<?php echo esc_url(home_url('/shop/')); ?>">
            <div class="cb-promo__media">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/collection-cables.png'); ?>" alt="" loading="lazy" decoding="async" />
            </div>
            <div class="cb-promo__content">
                <span class="cb-promo__badge">NEW</span>
                <h2 class="cb-promo__headline">Level Up Your Setup</h2>
                <span class="cb-promo__cta">Shop Now</span>
            </div>
        </a>
    </section>

    <!-- 3. Product row: New arrivals -->
    <?php get_template_part('template-parts/products'); ?>

    <!-- 4. Category tiles (2x2 like Nomad's iPhone/Watch/Accessories grid) -->
    <section class="cb-tiles" aria-label="Categories">
        <div class="cb-tiles__grid">
            <a class="cb-tile" href="<?php echo esc_url(home_url('/shop/')); ?>">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/collection-stands-scaled.jpg'); ?>" alt="Hubs USB-C" loading="lazy" decoding="async" />
                <h3 class="cb-tile__label">Hubs USB-C</h3>
            </a>
            <a class="cb-tile" href="<?php echo esc_url(home_url('/shop/')); ?>">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/collection-cables.png'); ?>" alt="Adaptateurs" loading="lazy" decoding="async" />
                <h3 class="cb-tile__label">Adaptateurs</h3>
            </a>
            <a class="cb-tile" href="<?php echo esc_url(home_url('/shop/')); ?>">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/collection-adapters.jpg'); ?>" alt="Stands laptop" loading="lazy" decoding="async" />
                <h3 class="cb-tile__label">Stands laptop</h3>
            </a>
            <a class="cb-tile" href="<?php echo esc_url(home_url('/shop/')); ?>">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/collection-organizers.jpg'); ?>" alt="Organisateurs" loading="lazy" decoding="async" />
                <h3 class="cb-tile__label">Organisateurs</h3>
            </a>
        </div>
    </section>

    <!-- 5. Reviews band (Nomad "17,000+ 5 Star Reviews" style) -->
    <section class="cb-reviews" aria-label="Customer reviews">
        <p class="cb-reviews__stars" aria-hidden="true">★★★★★</p>
        <h2 class="cb-reviews__title">Trusted by Swiss tech users</h2>
        <p class="cb-reviews__sub">Fast local shipping — fair prices</p>
    </section>

    <!-- 6. Trust -->
    <?php get_template_part('template-parts/trust'); ?>

    <?php get_template_part('template-parts/footer'); ?>

    <?php wp_footer(); ?>
</body>
</html>