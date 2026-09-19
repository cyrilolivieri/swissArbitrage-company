<?php
/**
 * Admin settings page for CompactBox Core.
 *
 * @package CompactBox\Core
 */

namespace CompactBox\Core;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class AdminSettings
 *
 * Registers a single settings page under Settings > CompactBox with a checkbox
 * that stores the compactbox_site_online option as 'yes' or 'no'.
 */
class AdminSettings
{
    /**
     * Hook the settings page into WordPress.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    /**
     * Add the Settings > CompactBox submenu page.
     *
     * @return void
     */
    public static function add_settings_page(): void
    {
        add_options_page(
            __('CompactBox Settings', 'compactbox-core'),
            __('CompactBox', 'compactbox-core'),
            'manage_options',
            'compactbox',
            [__CLASS__, 'render_page']
        );
    }

    /**
     * Register the compactbox option group and the site_online setting.
     *
     * @return void
     */
    public static function register_settings(): void
    {
        register_setting(
            'compactbox',
            'compactbox_site_online',
            [__CLASS__, 'sanitize_online']
        );
    }

    /**
     * Sanitize the site online value so only 'yes' or 'no' is stored.
     *
     * @param mixed $value The submitted value.
     * @return string 'yes' or 'no'.
     */
    public static function sanitize_online($value): string
    {
        return $value === 'yes' ? 'yes' : 'no';
    }

    /**
     * Render the settings page.
     *
     * @return void
     */
    public static function render_page(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $is_online = get_option('compactbox_site_online', 'no') === 'yes';
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('compactbox');
                do_settings_sections('compactbox');
                ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php esc_html_e('Site status', 'compactbox-core'); ?></th>
                        <td>
                            <label for="compactbox_site_online">
                                <input
                                    type="checkbox"
                                    id="compactbox_site_online"
                                    name="compactbox_site_online"
                                    value="yes"
                                    <?php checked($is_online); ?>
                                >
                                <?php esc_html_e('Site online', 'compactbox-core'); ?>
                            </label>
                            <p class="description">
                                <?php
                                esc_html_e(
                                    'When unchecked, public front-end requests receive HTTP 503 + noindex while wp-admin remains accessible.',
                                    'compactbox-core'
                                );
                                ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
