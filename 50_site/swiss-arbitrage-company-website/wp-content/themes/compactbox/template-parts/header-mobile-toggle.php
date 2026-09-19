<?php
/**
 * Header mobile hamburger toggle.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<button class="cb-header__toggle" type="button"
        aria-controls="cb-header-menu"
        aria-expanded="false"
        aria-label="<?php echo esc_attr__('Open menu', 'compactbox'); ?>">
    <span class="cb-header__toggle-bar" aria-hidden="true"></span>
</button>