<?php
/**
 * CompactBox header assembly.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<header class="cb-header" role="banner">
    <?php get_template_part('template-parts/header-branding'); ?>
    <div class="cb-header__menu-wrapper" id="cb-header-menu">
        <?php get_template_part('template-parts/header-navigation'); ?>
        <?php get_template_part('template-parts/header-utilities'); ?>
    </div>
    <?php get_template_part('template-parts/header-mobile-toggle'); ?>
</header>