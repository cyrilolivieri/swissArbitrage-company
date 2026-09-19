<?php
/**
 * CompactBox WooCommerce single-product template override.
 *
 * Mirrors the default WooCommerce single-product structure but wraps the
 * product page in a .cb-product container so the child theme CSS can render
 * the Nomad-like two-column layout without editing Astra or WooCommerce.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header('shop');

if (! function_exists('woocommerce_content')) {
    ?>
    <main id="primary" class="site-main cb-product" role="main">
        <div class="cb-product__layout">
            <p class="cb-product__offline-notice">
                <?php esc_html_e('Product catalog is temporarily unavailable.', 'compactbox'); ?>
            </p>
        </div>
    </main>
    <?php
    get_footer('shop');
    return;
}
?>

<main id="primary" class="site-main cb-product" role="main">
    <div class="cb-product__layout">
        <?php woocommerce_content(); ?>
    </div>
</main>

<?php
get_footer('shop');
