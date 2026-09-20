<?php
/**
 * CompactBox homepage collection grid template part.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$shop_url = function_exists('wc_get_page_permalink')
    ? wc_get_page_permalink('shop')
    : home_url('/shop/');

if (empty($shop_url)) {
    $shop_url = home_url('/');
}

$base_url = get_stylesheet_directory_uri() . '/assets/images/';

$families = [
    ['slug' => 'cables', 'name' => 'Cables USB-C', 'description' => 'Charge + data, 60-100 W, braided nylon.', 'img' => 'collection-cables.png'],
    ['slug' => 'hubs', 'name' => 'Hubs USB-C', 'description' => 'Compact aluminium hubs with PD pass-through.', 'img' => 'collection-hubs.png'],
    ['slug' => 'stands', 'name' => 'Stands laptop', 'description' => 'Foldable aluminium stands, adjustable and portable.', 'img' => 'collection-stands-scaled.jpg'],
    ['slug' => 'supports', 'name' => 'Supports phone and tablet', 'description' => 'Desk, car, bedside and travel holders.', 'img' => 'collection-supports.jpg'],
    ['slug' => 'adapters', 'name' => 'Adaptateurs USB-C', 'description' => 'HDMI, DisplayPort, USB-A, SD and travel adapters.', 'img' => 'collection-adapters.jpg'],
    ['slug' => 'organizers', 'name' => 'Organisateurs', 'description' => 'Cable clips, straps, compact desk accessories.', 'img' => 'collection-organizers.jpg'],
];
?>
<section class="cb-collections" aria-labelledby="cb-collections-title">
    <div class="cb-collections__inner">
        <h2 id="cb-collections-title" class="cb-collections__title">Shop by family</h2>
        <div class="cb-collections__grid">
            <?php foreach ($families as $family) : ?>
                <article class="cb-collection-card">
                    <a class="cb-collection-card__link" href="<?php echo esc_url($shop_url); ?>" aria-labelledby="<?php echo esc_attr('cb-collection-' . $family['slug']); ?>">
                        <div class="cb-collection-card__media">
                            <img src="<?php echo esc_url($base_url . $family['img']); ?>" alt="<?php echo esc_attr($family['name']); ?>" width="800" height="600" loading="lazy" decoding="async" />
                        </div>
                        <div class="cb-collection-card__content">
                            <h3 id="<?php echo esc_attr('cb-collection-' . $family['slug']); ?>" class="cb-collection-card__name"><?php echo esc_html($family['name']); ?></h3>
                            <p class="cb-collection-card__description"><?php echo esc_html($family['description']); ?></p>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
