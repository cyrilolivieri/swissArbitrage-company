<?php
/**
 * CompactBox homepage trust storytelling section template part.
 *
 * Rendered via compactbox_homepage_trust() hooked to astra_content_before
 * with priority 25, and guarded by is_front_page().
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$section_title = __('Why CompactBox?', 'compactbox');

$blocks = [
    [
        'id'          => 'delivery',
        'heading'     => __('Swiss delivery', 'compactbox'),
        'description' => __('Shipped locally from our Swiss warehouse to keep transit short and simple.', 'compactbox'),
    ],
    [
        'id'          => 'prices',
        'heading'     => __('Compared prices', 'compactbox'),
        'description' => __('Every price is benchmarked against the local market before it goes live.', 'compactbox'),
    ],
    [
        'id'          => 'quality',
        'heading'     => __('No compromise', 'compactbox'),
        'description' => __('Selected specs, real materials and solid compatibility — nothing else makes the cut.', 'compactbox'),
    ],
];

// Inline SVG icons are simple line-style graphics that accompany, but never replace,
// the text label. They are aria-hidden so the accessible label comes from the heading.
$icons = [
    'delivery' => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 10h13"></path><path d="M15 10l4-4v12"></path><circle cx="7.5" cy="18.5" r="1.5"></circle><circle cx="17.5" cy="18.5" r="1.5"></circle><path d="M9 18.5h7"></path></svg>',
    'prices'   => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
    'quality'  => '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
];
?>
<section class="cb-trust" aria-labelledby="cb-trust-title">
    <div class="cb-trust__inner">
        <h2 id="cb-trust-title" class="cb-trust__title">
            <?php echo esc_html($section_title); ?>
        </h2>
        <div class="cb-trust__grid">
            <?php foreach ($blocks as $block) : ?>
                <?php
                $icon_html = isset($icons[$block['id']]) ? $icons[$block['id']] : '';
                $heading_id = 'cb-trust-' . $block['id'];
                ?>
                <article class="cb-trust__card" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
                    <div class="cb-trust__icon">
                        <?php echo wp_kses($icon_html, [
                            'svg'    => [
                                'xmlns'           => true,
                                'width'           => true,
                                'height'          => true,
                                'viewBox'         => true,
                                'fill'            => true,
                                'stroke'          => true,
                                'stroke-width'    => true,
                                'stroke-linecap'  => true,
                                'stroke-linejoin' => true,
                                'aria-hidden'     => true,
                            ],
                            'path'   => [
                                'd'               => true,
                            ],
                            'circle' => [
                                'cx'              => true,
                                'cy'              => true,
                                'r'               => true,
                            ],
                        ]); ?>
                    </div>
                    <h3 id="<?php echo esc_attr($heading_id); ?>" class="cb-trust__heading">
                        <?php echo esc_html($block['heading']); ?>
                    </h3>
                    <p class="cb-trust__description">
                        <?php echo esc_html($block['description']); ?>
                    </p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
