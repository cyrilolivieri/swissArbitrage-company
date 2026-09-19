<?php
/**
 * Header primary navigation.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<nav class="cb-header__nav" aria-label="<?php echo esc_attr__('Primary', 'compactbox'); ?>">
    <?php
    wp_nav_menu([
        'theme_location'  => 'compactbox-primary',
        'container'       => false,
        'menu_class'      => 'cb-header__menu',
        'fallback_cb'     => false,
    ]);
    ?>
</nav>