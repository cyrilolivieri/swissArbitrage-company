<?php
/**
 * CompactBox homepage hero template part.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$placeholder_url = get_stylesheet_directory_uri() . '/assets/images/hero-nomad.jpg';

$shop_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('shop')
    : home_url('/shop/');

if (empty($shop_url)) {
    $shop_url = home_url('/shop/');
}
?>
<section class="cb-hero" aria-labelledby="cb-hero-headline">
    <div class="cb-hero__media">
        <img src="<?php echo esc_url($placeholder_url); ?>" alt="CompactBox" width="1920" height="1080" loading="eager" decoding="async" />
    </div>
    <div class="cb-hero__overlay" aria-hidden="true"></div>
    <div class="cb-hero__content">
        <span class="cb-hero__badge">ALL NEW</span>
        <p class="cb-hero__preline">Compact accessories</p>
        <h1 id="cb-hero-headline" class="cb-hero__headline">for Swiss tech setups.</h1>
        <p class="cb-hero__subline">Cables, hubs, stands and organisers — shipped locally, priced fairly.</p>
        <a href="<?php echo esc_url($shop_url); ?>" class="cb-hero__cta">Shop Now</a>
    </div>
</section>
