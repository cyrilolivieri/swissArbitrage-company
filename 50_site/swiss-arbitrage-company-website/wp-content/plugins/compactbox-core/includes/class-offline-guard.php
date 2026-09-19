<?php
/**
 * Offline guard for the public front-end.
 *
 * @package CompactBox\Core
 */

namespace CompactBox\Core;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class OfflineGuard
 *
 * Blocks public front-end requests with HTTP 503 + noindex while the site is offline.
 * Admin, login, AJAX, REST API, and WP-CLI requests are left untouched.
 */
class OfflineGuard
{
    /**
     * Hook the guard into WordPress.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('template_redirect', [__CLASS__, 'maybe_block'], 1);
    }

    /**
     * Decide whether to block the current request.
     *
     * @return void
     */
    public static function maybe_block(): void
    {
        if (self::is_online()) {
            return;
        }

        if (self::should_skip_request()) {
            return;
        }

        self::serve_503();
    }

    /**
     * Check whether the site is explicitly marked as online.
     *
     * @return bool
     */
    private static function is_online(): bool
    {
        return get_option('compactbox_site_online', 'no') === 'yes';
    }

    /**
     * Determine whether the current request should bypass the offline guard.
     *
     * @return bool
     */
    private static function should_skip_request(): bool
    {
        if (is_admin()) {
            return true;
        }

        if (wp_doing_ajax()) {
            return true;
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }

        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }

        $script_name = sanitize_text_field(wp_unslash($_SERVER['SCRIPT_NAME'] ?? ''));
        if (strpos($script_name, 'wp-login.php') !== false) {
            return true;
        }

        return false;
    }

    /**
     * Serve the 503 offline response and stop execution.
     *
     * @return void
     */
    private static function serve_503(): void
    {
        status_header(503);
        header('X-Robots-Tag: noindex');
        header('Content-Type: text/html; charset=utf-8');

        $title = esc_html__('Offline', 'compactbox-core');
        $message = esc_html__('The site is currently offline. Please check back soon.', 'compactbox-core');

        echo '<!DOCTYPE html>';
        echo '<html lang="' . esc_attr(get_bloginfo('language')) . '" style="margin:0; padding:0;">';
        echo '<head>';
        echo '<meta charset="' . esc_attr(get_bloginfo('charset')) . '">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<meta name="robots" content="noindex">';
        echo '<title>' . esc_html($title) . ' — ' . esc_html(get_bloginfo('name')) . '</title>';
        echo '<style>';
        echo 'body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;background:#fff;color:#111;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:2rem;box-sizing:border-box;text-align:center;}';
        echo 'main{max-width:40rem;}';
        echo 'h1{font-size:1.75rem;font-weight:600;margin:0 0 1rem;}';
        echo 'p{font-size:1rem;line-height:1.5;margin:0;color:#555;}';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<main>';
        echo '<h1>' . esc_html($title) . '</h1>';
        echo '<p>' . esc_html($message) . '</p>';
        echo '</main>';
        echo '</body>';
        echo '</html>';

        exit;
    }
}
