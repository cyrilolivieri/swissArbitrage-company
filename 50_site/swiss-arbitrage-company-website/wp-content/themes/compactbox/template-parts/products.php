<?php
/**
 * CompactBox product row — "New arrivals" (Nomad-style product carousel).
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('WooCommerce') || ! function_exists('wc_get_products')) {
    return;
}

$products = wc_get_products([
    'status'  => 'publish',
    'limit'   => 8,
    'orderby' => 'date',
    'order'   => 'DESC',
]);

if (empty($products)) {
    return;
}
?>
<section class="cb-products" aria-labelledby="cb-products-title">
    <div class="cb-products__inner">
        <header class="cb-products__header">
            <h2 id="cb-products-title" class="cb-products__title">New arrivals</h2>
        </header>
        <div class="cb-products__track" role="list">
            <?php foreach ($products as $product) : ?>
                <?php
                $product_id   = $product->get_id();
                $product_url  = $product->get_permalink();
                $product_name = $product->get_name();
                $title_id     = 'cb-product-' . $product_id;
                ?>
                <article class="cb-product-card" role="listitem">
                    <a class="cb-product-card__link" href="<?php echo esc_url($product_url); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
                        <div class="cb-product-card__media">
                            <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', ['class' => 'cb-product-card__image', 'alt' => $product_name, 'loading' => 'lazy', 'decoding' => 'async'])); ?>
                        </div>
                        <div class="cb-product-card__content">
                            <h3 id="<?php echo esc_attr($title_id); ?>" class="cb-product-card__name"><?php echo esc_html($product_name); ?></h3>
                            <p class="cb-product-card__price"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>