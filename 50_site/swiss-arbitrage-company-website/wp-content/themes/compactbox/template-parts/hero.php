<?php
/**
 * CompactBox homepage hero template part.
 *
 * Rendered via compactbox_homepage_hero() hooked to astra_content_before,
 * and guarded by is_front_page().
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$placeholder_url = get_stylesheet_directory_uri() . '/assets/images/hero-placeholder.png';

// Prefer the WooCommerce shop URL when available; fall back to /shop/ under home_url().
$shop_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('shop')
    : home_url('/shop/');

// Ensure we always have a non-empty target URL for the CTA.
if (empty($shop_url)) {
    $shop_url = home_url('/shop/');
}

$headline    = __('Compact accessories for Swiss tech setups.', 'compactbox');
$subline     = __('Cables, hubs, stands and organisers — shipped locally, priced fairly.', 'compactbox');
$cta_text    = __('Shop now', 'compactbox');
$image_alt   = __('CompactBox hero placeholder — replace me', 'compactbox');
?>
<section class="cb-hero" aria-labelledby="cb-hero-headline">
    <div class="cb-hero__media">
        <img
            src="<?php echo esc_url($placeholder_url); ?>"
            alt="<?php echo esc_attr($image_alt); ?>"
            width="1920"
            height="1080"
            loading="eager"
            decoding="async"
            onerror="this.style.display='none'"
        />
    </div>
    <div class="cb-hero__overlay" aria-hidden="true"></div>
    <div class="cb-hero__content">
        <h1 id="cb-hero-headline" class="cb-hero__headline">
            <?php echo esc_html($headline); ?>
        </h1>
        <p class="cb-hero__subline">
            <?php echo esc_html($subline); ?>
        </p>
        <a href="<?php echo esc_url($shop_url); ?>" class="cb-hero__cta">
            <?php echo esc_html($cta_text); ?>
        </a>
    </div>
</section>
