<?php
/**
 * Plugin Name: CompactBox Core
 * Description: Core operational plugin for the CompactBox storefront. Currently provides an offline guard and a site-online toggle.
 * Version: 0.1.0
 * Author: CompactBox
 * Text Domain: compactbox-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.1
 *
 * @package CompactBox\Core
 */

namespace CompactBox\Core;

if (! defined('ABSPATH')) {
    exit;
}

define('COMPACTBOX_CORE_VERSION', '0.1.0');
define('COMPACTBOX_CORE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('COMPACTBOX_CORE_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once COMPACTBOX_CORE_PLUGIN_DIR . 'includes/class-offline-guard.php';
require_once COMPACTBOX_CORE_PLUGIN_DIR . 'includes/class-admin-settings.php';

/**
 * Set the site option to offline by default when the plugin is activated.
 *
 * @return void
 */
function compactbox_core_activate(): void
{
    add_option('compactbox_site_online', 'no');
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\compactbox_core_activate');

/**
 * Initialize plugin modules once WordPress is ready.
 *
 * @return void
 */
function compactbox_core_init(): void
{
    OfflineGuard::init();
    AdminSettings::init();
}
add_action('plugins_loaded', __NAMESPACE__ . '\\compactbox_core_init');
