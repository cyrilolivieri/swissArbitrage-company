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
    <span class="cb-header__brand-text">COMPACTBOX</span>
</a>