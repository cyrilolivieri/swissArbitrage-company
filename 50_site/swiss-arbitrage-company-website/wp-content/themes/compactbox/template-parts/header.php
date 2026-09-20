<?php
/**
 * CompactBox header assembly — Nomad-style announcement bar + floating pill nav.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<div class="cb-announce">
    <p>Compact accessories for Swiss tech setups — <a href="<?php echo esc_url(home_url('/shop/')); ?>">Shop Cables, Hubs, and More</a></p>
</div>
<header class="cb-header" role="banner">
    <div class="cb-header__pill">
        <?php get_template_part('template-parts/header-branding'); ?>
        <div class="cb-header__menu-wrapper" id="cb-header-menu">
            <?php get_template_part('template-parts/header-navigation'); ?>
            <?php get_template_part('template-parts/header-utilities'); ?>
        </div>
        <?php get_template_part('template-parts/header-mobile-toggle'); ?>
    </div>
</header>