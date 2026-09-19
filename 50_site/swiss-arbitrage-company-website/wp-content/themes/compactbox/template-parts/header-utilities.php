<?php
/**
 * Header utilities — search, cart, language selector placeholder.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$cart_url  = compactbox_cart_link();
$cart_count = compactbox_cart_count();
?>
<div class="cb-header__utilities">
    <a class="cb-header__utility cb-header__utility--search" href="<?php echo esc_url(home_url('/?s=')); ?>"
       aria-label="<?php echo esc_attr__('Search', 'compactbox'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>
    </a>

    <?php if ($cart_url !== null) : ?>
        <a class="cb-header__utility cb-header__utility--cart" href="<?php echo esc_url($cart_url); ?>"
           aria-label="<?php echo esc_attr__('Cart', 'compactbox'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 6h15l-1.5 9h-12z"/>
                <circle cx="9" cy="20" r="1.5"/>
                <circle cx="18" cy="20" r="1.5"/>
            </svg>
            <?php if ($cart_count !== null) : ?>
                <span class="cb-header__cart-count"><?php echo esc_html((string) $cart_count); ?></span>
            <?php endif; ?>
        </a>
    <?php else : ?>
        <span class="cb-header__utility cb-header__utility--cart-placeholder">
            <?php echo esc_html__('Cart', 'compactbox'); ?>
        </span>
    <?php endif; ?>

    <!-- Polylang language selector placeholder — wired in EPIC-002. -->
    <div class="cb-header__utility cb-header__utility--lang">
        <span class="cb-header__lang-label" aria-label="<?php echo esc_attr__('Language', 'compactbox'); ?>">
            <?php echo esc_html__('EN', 'compactbox'); ?>
        </span>
        <span class="cb-header__lang-list" aria-hidden="true">FR / DE / EN / IT</span>
    </div>
</div>