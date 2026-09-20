<?php
/**
 * Homepage for "latest posts" setting.
 *
 * Identical to front-page.php — full-width Nomad-inspired layout.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class('cb-homepage'); ?>>

<?php
get_template_part('template-parts/header');
get_template_part('template-parts/hero');
get_template_part('template-parts/collections');
get_template_part('template-parts/products');
get_template_part('template-parts/trust');
get_template_part('template-parts/footer');
?>

<?php wp_footer(); ?>
</body>
</html>
