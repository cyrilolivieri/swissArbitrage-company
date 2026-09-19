<?php
/**
 * CompactBox product structured sections template part.
 *
 * Renders the four collapsible sections: Specs, Compatibility, Box contents,
 * and FAQ. Uses existing product meta when available and neutral placeholders
 * otherwise. Only loaded on singular product pages when WooCommerce is active.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('wc_get_product')) {
    return;
}

$product = get_query_var('compactbox_current_product');
if (! $product instanceof WC_Product) {
    $product = wc_get_product();
}

if (! $product instanceof WC_Product) {
    return;
}

if (! function_exists('compactbox_get_product_section_items')) {
    return;
}

$sections = [
    'specs' => [
        'title' => __('Specs', 'compactbox'),
        'items' => compactbox_get_product_section_items($product, 'compactbox_specs'),
        'placeholder' => [
            __('Length / diameter / weight to be defined.', 'compactbox'),
            __('Materials and finishes to be defined.', 'compactbox'),
        ],
    ],
    'compatibility' => [
        'title' => __('Compatibility', 'compactbox'),
        'items' => compactbox_get_product_section_items($product, 'compactbox_compatibility'),
        'placeholder' => [
            __('Compatible devices to be confirmed.', 'compactbox'),
            __('Connector / standard details to be confirmed.', 'compactbox'),
        ],
    ],
    'box' => [
        'title' => __('Box contents', 'compactbox'),
        'items' => compactbox_get_product_section_items($product, 'compactbox_box_contents'),
        'placeholder' => [
            __('1× product unit.', 'compactbox'),
            __('Accessories and documentation to be defined.', 'compactbox'),
        ],
    ],
    'faq' => [
        'title' => __('FAQ', 'compactbox'),
        'items' => compactbox_get_product_section_items($product, 'compactbox_faq'),
        'placeholder' => [
            __('No FAQ entries yet.', 'compactbox'),
        ],
    ],
];
?>

<section class="cb-product__sections" aria-labelledby="cb-product-sections-title">
    <h2 id="cb-product-sections-title" class="cb-product__sections-title screen-reader-text">
        <?php esc_html_e('Product details', 'compactbox'); ?>
    </h2>

    <?php foreach ($sections as $key => $section) : ?>
        <?php
        $items = empty($section['items']) ? $section['placeholder'] : $section['items'];
        $panel_id = 'cb-product-section-' . esc_attr($key);
        $heading_id = $panel_id . '-heading';
        ?>
        <div class="cb-product__section">
            <h3 id="<?php echo esc_attr($heading_id); ?>" class="cb-product__section-heading">
                <button
                    type="button"
                    class="cb-product__accordion-toggle"
                    aria-expanded="false"
                    aria-controls="<?php echo esc_attr($panel_id); ?>"
                >
                    <span class="cb-product__accordion-label">
                        <?php echo esc_html($section['title']); ?>
                    </span>
                    <span class="cb-product__accordion-icon" aria-hidden="true"></span>
                </button>
            </h3>
            <div
                id="<?php echo esc_attr($panel_id); ?>"
                class="cb-product__accordion-panel"
                role="region"
                aria-labelledby="<?php echo esc_attr($heading_id); ?>"
                hidden
            >
                <ul class="cb-product__section-list">
                    <?php foreach ($items as $item) : ?>
                        <li><?php echo esc_html($item); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</section>
