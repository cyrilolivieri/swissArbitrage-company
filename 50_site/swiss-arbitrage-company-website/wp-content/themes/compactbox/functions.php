<?php

/**
 * CompactBox child theme bootstrap.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('compactbox_setup')) {
    /**
     * General child theme setup.
     *
     * @return void
     */
    function compactbox_setup(): void
    {
        register_nav_menus([
            'compactbox-primary' => __('CompactBox Primary', 'compactbox'),
        ]);
    }

    add_action('after_setup_theme', 'compactbox_setup');
}

if (! function_exists('compactbox_header')) {
    /**
     * Render the CompactBox header template part.
     *
     * @return void
     */
    function compactbox_header(): void
    {
        if (is_front_page()) {
            return;
        }
        get_template_part('template-parts/header');
    }
}

if (! function_exists('compactbox_cart_count')) {
    /**
     * Return the WooCommerce cart item count, or null when unavailable.
     *
     * @return int|null
     */
    function compactbox_cart_count(): ?int
    {
        if (! function_exists('WC') || WC() === null || WC()->cart === null) {
            return null;
        }

        return (int) WC()->cart->get_cart_contents_count();
    }
}

if (! function_exists('compactbox_cart_link')) {
    /**
     * Return the cart link URL or null when WooCommerce is unavailable.
     *
     * @return string|null
     */
    function compactbox_cart_link(): ?string
    {
        if (! function_exists('wc_get_cart_url')) {
            return null;
        }

        return wc_get_cart_url();
    }
}

// Attach header to an Astra header hook. Astra's primary header action is
// `astra_header`; we render before it so the child header is sticky and
// the Astra header can remain as a fallback or be disabled by the operator.
add_action('astra_header_before', 'compactbox_header');

if (! function_exists('compactbox_homepage_hero')) {
    /**
     * Render the full-width homepage hero.
     *
     * Hooked to astra_content_before and guarded by is_front_page()
     * so the hero appears only on the homepage.
     *
     * @return void
     */
    function compactbox_homepage_hero(): void
    {
        if (! is_front_page()) {
            return;
        }

        get_template_part('template-parts/hero');
    }

    add_action('astra_content_before', 'compactbox_homepage_hero');
}

if (! function_exists('compactbox_homepage_collections')) {
    /**
     * Render the homepage collection grid below the hero.
     *
     * Hooked to astra_content_before at priority 15 so it renders
     * after the hero (default priority 10) on the homepage only.
     *
     * @return void
     */
    function compactbox_homepage_collections(): void
    {
        if (! is_front_page()) {
            return;
        }

        get_template_part('template-parts/collections');
    }

    add_action('astra_content_before', 'compactbox_homepage_collections', 15);
}

if (! function_exists('compactbox_homepage_products')) {
    /**
     * Render the homepage product carousel below the collection grid.
     *
     * Hooked to astra_content_before at priority 20 so it renders
     * after collections (priority 15) on the homepage only.
     *
     * @return void
     */
    function compactbox_homepage_products(): void
    {
        if (! is_front_page()) {
            return;
        }

        if (! class_exists('WooCommerce') || ! function_exists('wc_get_products')) {
            return;
        }

        get_template_part('template-parts/products');
    }

    add_action('astra_content_before', 'compactbox_homepage_products', 20);
}

if (! function_exists('compactbox_homepage_trust')) {
    /**
     * Render the homepage trust storytelling section below the product carousel.
     *
     * Hooked to astra_content_before at priority 25 so it renders
     * after products (priority 20) on the homepage only.
     *
     * @return void
     */
    function compactbox_homepage_trust(): void
    {
        if (! is_front_page()) {
            return;
        }

        get_template_part('template-parts/trust');
    }

    add_action('astra_content_before', 'compactbox_homepage_trust', 25);
}

if (! function_exists('compactbox_footer')) {
    /**
     * Render the CompactBox footer template part.
     *
     * Hooked to astra_footer_before with default priority so the footer
     * renders on every public front-end page (no is_front_page() guard).
     *
     * @return void
     */
    function compactbox_footer(): void
    {
        if (is_front_page()) {
            return;
        }
        get_template_part('template-parts/footer');
    }

    add_action('astra_footer_before', 'compactbox_footer');
}

// --- Homepage: suppress Astra native header/footer/CSS on front page ---
add_action('wp', function (): void {
    if (! is_front_page()) {
        return;
    }
    // Remove Astra native header/footer markup
    remove_action('astra_header', 'astra_header_markup');
    remove_action('astra_footer', 'astra_footer_markup');
    // Remove Astra content wrappers so our sections are full-width
    remove_action('astra_primary_content_top', 'astra_primary_content_top');
    remove_action('astra_primary_content_bottom', 'astra_primary_content_bottom');
});

add_action('wp_enqueue_scripts', function (): void {
    if (! is_front_page()) {
        return;
    }
    // Dequeue Astra's theme CSS so our child theme CSS is the only stylesheet
    wp_dequeue_style('astra-theme-css');
    wp_dequeue_style('astra-theme-css-inline-css');
}, 20);

if (! function_exists('compactbox_assets')) {
    /**
     * Enqueue child theme styles and scripts.
     *
     * @return void
     */
    function compactbox_assets(): void
    {
        $stylesheet_path = get_stylesheet_directory() . '/style.css';
        $script_path     = get_stylesheet_directory() . '/assets/js/compactbox.js';

        $style_mtime = file_exists($stylesheet_path) ? filemtime($stylesheet_path) : false;
        $style_version = $style_mtime !== false ? (string) $style_mtime : '1.0.0';

        $script_mtime = file_exists($script_path) ? filemtime($script_path) : false;
        $script_version = $script_mtime !== false ? (string) $script_mtime : '1.0.0';

        wp_enqueue_style(
            'compactbox-style',
            get_stylesheet_directory_uri() . '/style.css',
            ['astra-theme-css'],
            $style_version
        );

        wp_enqueue_script(
            'compactbox-script',
            get_stylesheet_directory_uri() . '/assets/js/compactbox.js',
            [],
            $script_version,
            true
        );

        // Cache-bust inline assets used by front-page.php
        wp_localize_script('compactbox-script', 'compactboxAssets', [
            'themeUrl' => get_stylesheet_directory_uri(),
            'version'  => $style_version,
        ]);
    }

    add_action('wp_enqueue_scripts', 'compactbox_assets');
}

if (! function_exists('compactbox_get_product_section_items')) {
    /**
     * Helper to read a product meta field as a list of lines.
     *
     * Expects the meta value to be a newline-separated string. Falls back to
     * an empty array so the template part can show placeholders.
     *
     * @param WC_Product $product      The current product.
     * @param string     $meta_key     The meta key to read.
     * @param int        $max_items    Maximum number of items to return.
     * @return string[]
     */
    function compactbox_get_product_section_items($product, string $meta_key, int $max_items = 10): array
    {
        if (! is_object($product) || ! method_exists($product, 'get_meta')) {
            return [];
        }

        $raw = $product->get_meta($meta_key, true);
        if (empty($raw) || ! is_string($raw)) {
            return [];
        }

        $lines = array_filter(array_map('trim', explode("\n", wp_unslash($raw))));
        return array_slice($lines, 0, $max_items);
    }
}

if (! function_exists('compactbox_single_product_body_class')) {
    /**
     * Add a product-page body class for CSS scoping.
     *
     * @param string[] $classes Existing body classes.
     * @return string[]
     */
    function compactbox_single_product_body_class(array $classes): array
    {
        if (! function_exists('is_singular') || ! is_singular('product')) {
            return $classes;
        }

        $classes[] = 'cb-product-page';
        return $classes;
    }

    add_filter('body_class', 'compactbox_single_product_body_class');
}

if (! function_exists('compactbox_single_product_sections')) {
    /**
     * Render the structured product sections after the add-to-cart form.
     *
     * Hooked to woocommerce_single_product_summary at priority 31 so it renders
     * after the add-to-cart form (priority 30), matching the Nomad-like layout.
     *
     * @return void
     */
    function compactbox_single_product_sections(): void
    {
        if (! function_exists('is_singular') || ! is_singular('product')) {
            return;
        }

        if (! function_exists('wc_get_product')) {
            return;
        }

        $product = wc_get_product();
        if (! $product instanceof WC_Product) {
            return;
        }

        set_query_var('compactbox_current_product', $product);
        get_template_part('template-parts/product-sections');
    }

    add_action('woocommerce_single_product_summary', 'compactbox_single_product_sections', 31);
}

if (! function_exists('compactbox_single_product_shipping_estimate')) {
    /**
     * Render a shipping estimate line after the product price.
     *
     * Hooked to woocommerce_single_product_summary at priority 11 so it appears
     * right after the price (priority 10). The text is a neutral placeholder
     * that operators can customize via translation or a future settings page.
     *
     * @return void
     */
    function compactbox_single_product_shipping_estimate(): void
    {
        if (! function_exists('is_singular') || ! is_singular('product')) {
            return;
        }

        ?>
        <p class="cb-product__shipping"><?php esc_html_e('Shipping estimate calculated at checkout.', 'compactbox'); ?></p>
        <?php
    }

    add_action('woocommerce_single_product_summary', 'compactbox_single_product_shipping_estimate', 11);
}

if (! function_exists('compactbox_single_product_json_ld')) {
    /**
     * Inject Schema.org Product JSON-LD into the page head.
     *
     * Runs only on singular product pages when WooCommerce is active and the
     * product has a valid price. Uses the first gallery image or a placeholder.
     *
     * @return void
     */
    function compactbox_single_product_json_ld(): void
    {
        if (! function_exists('is_singular') || ! is_singular('product')) {
            return;
        }

        if (! function_exists('WC') || ! class_exists('WooCommerce')) {
            return;
        }

        if (! function_exists('wc_get_product')) {
            return;
        }

        $product = wc_get_product();
        if (! $product instanceof WC_Product) {
            return;
        }

        $price = $product->get_price();
        if (! is_numeric($price) || (float) $price <= 0) {
            return;
        }

        $image_id  = $product->get_image_id();
        $image_url = '';
        if (! empty($image_id)) {
            $image_src = wp_get_attachment_image_url((int) $image_id, 'woocommerce_single');
            if (is_string($image_src)) {
                $image_url = $image_src;
            }
        }

        $placeholder_url = '';
        $placeholder_path = get_stylesheet_directory() . '/assets/images/hero-placeholder.png';
        if (file_exists($placeholder_path)) {
            $placeholder_url = get_stylesheet_directory_uri() . '/assets/images/hero-placeholder.png';
        }

        $availability = $product->is_in_stock()
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';

        $currency = function_exists('get_woocommerce_currency')
            ? get_woocommerce_currency()
            : 'CHF';

        $description = $product->get_short_description();
        if (empty($description)) {
            $description = $product->get_description();
        }

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => esc_html($product->get_name()),
            'description' => wp_strip_all_tags($description),
            'image'       => $image_url ?: $placeholder_url,
            'url'         => esc_url($product->get_permalink()),
            'offers'      => [
                '@type'         => 'Offer',
                'priceCurrency' => $currency,
                'price'         => (string) $price,
                'availability'  => $availability,
                'url'           => esc_url($product->get_permalink()),
            ],
        ];

        $json = wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if (! is_string($json)) {
            return;
        }

        echo "\n" . '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>' . "\n";
    }

    add_action('wp_head', 'compactbox_single_product_json_ld', 20);
}

