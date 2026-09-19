<?php
/**
 * Header branding — logo / home link.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<a class="cb-header__brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
    <?php echo esc_html(get_bloginfo('name')); ?>
</a>