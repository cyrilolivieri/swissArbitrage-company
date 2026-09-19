<?php
/**
 * CompactBox homepage product carousel template part.
 *
 * Rendered via compactbox_homepage_products() hooked to astra_content_before
 * with priority 20, and guarded by is_front_page() plus WooCommerce availability.
 *
 * Queries up to 8 newest published products using wc_get_products().
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

$section_title = __('New arrivals', 'compactbox');
$empty_message = __('No products available yet.', 'compactbox');
?>
<section class="cb-products" aria-labelledby="cb-products-title">
    <div class="cb-products__inner">
        <header class="cb-products__header">
            <h2 id="cb-products-title" class="cb-products__title">
                <?php echo esc_html($section_title); ?>
            </h2>
            <div class="cb-products__nav-group">
                <button
                    type="button"
                    class="cb-products__nav cb-products__prev"
                    aria-label="<?php echo esc_attr__('Previous products', 'compactbox'); ?>"
                >
                    <span aria-hidden="true">‹</span>
                </button>
                <button
                    type="button"
                    class="cb-products__nav cb-products__next"
                    aria-label="<?php echo esc_attr__('Next products', 'compactbox'); ?>"
                >
                    <span aria-hidden="true">›</span>
                </button>
            </div>
        </header>

        <?php if (empty($products)) : ?>
            <div class="cb-products__empty">
                <p><?php echo esc_html($empty_message); ?></p>
            </div>
        <?php else : ?>
            <div class="cb-products__track" role="list">
                <?php foreach ($products as $product) : ?>
                    <?php
                    $product_id   = $product->get_id();
                    $product_url  = $product->get_permalink();
                    $product_name = $product->get_name();
                    $has_image    = (bool) $product->get_image_id();
                    $title_id     = 'cb-product-' . $product_id;
                    ?>
                    <article class="cb-product-card" role="listitem">
                        <a
                            class="cb-product-card__link"
                            href="<?php echo esc_url($product_url); ?>"
                            aria-labelledby="<?php echo esc_attr($title_id); ?>"
                        >
                            <div class="cb-product-card__media <?php echo $has_image ? '' : 'cb-product-card__media--placeholder'; ?>">
                                <?php if ($has_image) : ?>
                                    <?php
                                    echo wp_kses_post(
                                        $product->get_image(
                                            'woocommerce_thumbnail',
                                            [
                                                'class'    => 'cb-product-card__image',
                                                'alt'      => $product_name,
                                                'loading'  => 'lazy',
                                                'decoding' => 'async',
                                            ]
                                        )
                                    );
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div class="cb-product-card__content">
                                <h3 id="<?php echo esc_attr($title_id); ?>" class="cb-product-card__name">
                                    <?php echo esc_html($product_name); ?>
                                </h3>
                                <p class="cb-product-card__price">
                                    <?php echo wp_kses_post($product->get_price_html()); ?>
                                </p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
