<?php
/**
 * Header utilities — language dropdown, notifications, account, search, cart.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$cart_url  = compactbox_cart_link();
$cart_count = compactbox_cart_count();

$current = function_exists('pll_current_language') ? pll_current_language('slug') : 'fr';

$langs = [];
if (function_exists('pll_languages_list')) {
    $slugs = pll_languages_list();
    foreach ($slugs as $slug) {
        $langs[] = [
            'slug' => $slug,
            'name' => strtoupper($slug),
            'url'  => pll_home_url($slug),
            'current_lang' => $slug === $current,
        ];
    }
}

if (empty($langs)) {
    $langs = [
        ['slug' => 'fr', 'name' => 'Français', 'current_lang' => $current === 'fr', 'url' => home_url('/')],
        ['slug' => 'de', 'name' => 'Deutsch',   'current_lang' => $current === 'de', 'url' => home_url('/de/')],
        ['slug' => 'en', 'name' => 'English',   'current_lang' => $current === 'en', 'url' => home_url('/en/')],
        ['slug' => 'it', 'name' => 'Italiano',  'current_lang' => $current === 'it', 'url' => home_url('/it/')],
    ];
}
?>
<div class="cb-header__utilities">
    <!-- Language dropdown -->
    <div class="cb-header__utility cb-header__utility--lang cb-lang" aria-label="<?php echo esc_attr__('Language', 'compactbox'); ?>" tabindex="0">
        <span class="cb-lang__current"><?php echo esc_html(strtoupper($current)); ?></span>
        <ul class="cb-lang__list">
            <?php foreach ($langs as $lang) : ?>
                <li class="<?php echo $lang['current_lang'] ? 'is-current' : ''; ?>">
                    <a href="<?php echo esc_url($lang['url']); ?>" hreflang="<?php echo esc_attr($lang['slug']); ?>" lang="<?php echo esc_attr($lang['slug']); ?>">
                        <?php echo esc_html(strtoupper($lang['slug'])); ?> — <?php echo esc_html($lang['name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Notifications -->
    <button class="cb-header__utility cb-header__utility--notifications" aria-label="<?php echo esc_attr__('Notifications', 'compactbox'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>
    </button>

    <!-- Account -->
    <a class="cb-header__utility cb-header__utility--account" href="<?php echo esc_url(home_url('/my-account/')); ?>" aria-label="<?php echo esc_attr__('Account', 'compactbox'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
    </a>

    <!-- Search -->
    <a class="cb-header__utility cb-header__utility--search" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="<?php echo esc_attr__('Search', 'compactbox'); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
        </svg>
    </a>

    <!-- Cart -->
    <?php if ($cart_url !== null) : ?>
        <a class="cb-header__utility cb-header__utility--cart" href="<?php echo esc_url($cart_url); ?>" aria-label="<?php echo esc_attr__('Cart', 'compactbox'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 6h15l-1.5 9h-12z"/>
                <circle cx="9" cy="20" r="1.5"/>
                <circle cx="18" cy="20" r="1.5"/>
            </svg>
            <?php if ($cart_count !== null && $cart_count > 0) : ?>
                <span class="cb-header__cart-count"><?php echo esc_html((string) $cart_count); ?></span>
            <?php endif; ?>
        </a>
    <?php else : ?>
        <span class="cb-header__utility cb-header__utility--cart-placeholder" aria-label="<?php echo esc_attr__('Cart', 'compactbox'); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 6h15l-1.5 9h-12z"/>
                <circle cx="9" cy="20" r="1.5"/>
                <circle cx="18" cy="20" r="1.5"/>
            </svg>
        </span>
    <?php endif; ?>
</div>
