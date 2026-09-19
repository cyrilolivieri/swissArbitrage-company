<?php
/**
 * CompactBox homepage collection grid template part.
 *
 * Rendered via compactbox_homepage_collections() hooked to astra_content_before
 * with priority 15, and guarded by is_front_page().
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$image_base_url = get_stylesheet_directory_uri() . '/assets/images/collections/';

// Prefer the WooCommerce shop URL when available; fall back to /shop/ under home_url().
$shop_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('shop')
    : home_url('/shop/');

if (empty($shop_url)) {
    $shop_url = home_url('/');
}

$families = [
    [
        'slug'        => 'cables',
        'name'        => __('Câbles USB-C', 'compactbox'),
        'description' => __('Charge + data, 60–100 W, braided nylon.', 'compactbox'),
        'alt'         => __('Câbles USB-C — placeholder', 'compactbox'),
    ],
    [
        'slug'        => 'hubs',
        'name'        => __('Hubs USB-C', 'compactbox'),
        'description' => __('Compact aluminium hubs with PD pass-through.', 'compactbox'),
        'alt'         => __('Hubs USB-C — placeholder', 'compactbox'),
    ],
    [
        'slug'        => 'stands',
        'name'        => __('Stands laptop', 'compactbox'),
        'description' => __('Foldable aluminium stands, adjustable & portable.', 'compactbox'),
        'alt'         => __('Stands laptop — placeholder', 'compactbox'),
    ],
    [
        'slug'        => 'supports',
        'name'        => __('Supports phone & tablet', 'compactbox'),
        'description' => __('Desk, car, bedside and travel holders.', 'compactbox'),
        'alt'         => __('Supports phone & tablet — placeholder', 'compactbox'),
    ],
    [
        'slug'        => 'adapters',
        'name'        => __('Adaptateurs USB-C', 'compactbox'),
        'description' => __('HDMI, DisplayPort, USB-A, SD and travel adapters.', 'compactbox'),
        'alt'         => __('Adaptateurs USB-C — placeholder', 'compactbox'),
    ],
    [
        'slug'        => 'organizers',
        'name'        => __('Organisateurs', 'compactbox'),
        'description' => __('Cable clips, straps, compact desk accessories.', 'compactbox'),
        'alt'         => __('Organisateurs — placeholder', 'compactbox'),
    ],
];

$section_title = __('Shop by family', 'compactbox');
?>
<section class="cb-collections" aria-labelledby="cb-collections-title">
    <div class="cb-collections__inner">
        <h2 id="cb-collections-title" class="cb-collections__title">
            <?php echo esc_html($section_title); ?>
        </h2>
        <div class="cb-collections__grid">
            <?php foreach ($families as $family) : ?>
                <?php
                $image_url = $image_base_url . $family['slug'] . '.png';
                $link_url  = $shop_url;
                ?>
                <article class="cb-collection-card">
                    <a class="cb-collection-card__link" href="<?php echo esc_url($link_url); ?>" aria-labelledby="<?php echo esc_attr('cb-collection-' . $family['slug']); ?>">
                        <div class="cb-collection-card__media">
                            <img
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($family['alt']); ?>"
                                width="800"
                                height="600"
                                loading="lazy"
                                decoding="async"
                                onerror="this.style.display='none'"
                            />
                        </div>
                        <div class="cb-collection-card__content">
                            <h3 id="<?php echo esc_attr('cb-collection-' . $family['slug']); ?>" class="cb-collection-card__name">
                                <?php echo esc_html($family['name']); ?>
                            </h3>
                            <p class="cb-collection-card__description">
                                <?php echo esc_html($family['description']); ?>
                            </p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
