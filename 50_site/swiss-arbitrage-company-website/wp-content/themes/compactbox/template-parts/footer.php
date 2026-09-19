<?php
/**
 * Footer template part — multi-column, Nomad-like site-wide footer.
 *
 * @package CompactBox
 */

if (! defined('ABSPATH')) {
    exit;
}

$legal_address = get_option('compactbox_legal_address', '');
$brand_name    = get_option('compactbox_brand_name', '');

// Fallback placeholders when the operator has not finalized legal details.
if ($brand_name === '') {
    $brand_name = __('CompactBox', 'compactbox');
}
if ($legal_address === '') {
    $legal_address = __('Legal address to be confirmed before go-live.', 'compactbox');
}

$contact_email = sanitize_email(get_option('compactbox_contact_email', ''));
$current_year  = (int) wp_date('Y');

if (! function_exists('compactbox_page_url')) {
    /**
     * Return a WooCommerce page permalink or a sensible fallback.
     *
     * @param string $wc_page WooCommerce page slug (e.g. 'shop', 'cart', 'checkout', 'myaccount').
     * @param string $fallback_path Fallback path appended to home_url when WooCommerce is unavailable.
     * @return string
     */
    function compactbox_page_url(string $wc_page, string $fallback_path): string
    {
        if (function_exists('wc_get_page_permalink')) {
            $url = wc_get_page_permalink($wc_page);
            if ($url !== '') {
                return $url;
            }
        }

        return home_url($fallback_path);
    }
}
?>
<footer class="cb-footer">
    <div class="cb-footer__inner">
        <div class="cb-footer__grid">
            <div class="cb-footer__column cb-footer__column--seller">
                <h2 class="cb-footer__heading"><?php echo esc_html($brand_name); ?></h2>
                <address class="cb-footer__address">
                    <?php echo wp_kses_post(nl2br($legal_address, true)); ?>
                </address>
                <?php if ($contact_email !== '') : ?>
                    <p class="cb-footer__contact">
                        <a class="cb-footer__link" href="<?php echo esc_url('mailto:' . $contact_email); ?>">
                            <?php echo esc_html($contact_email); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <div class="cb-footer__column cb-footer__column--links">
                <h2 class="cb-footer__heading"><?php echo esc_html__('Service & Legal', 'compactbox'); ?></h2>
                <ul class="cb-footer__list">
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(home_url('/cgv/')); ?>">
                            <?php echo esc_html__('Terms & Conditions', 'compactbox'); ?>
                        </a>
                    </li>
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(home_url('/privacy/')); ?>">
                            <?php echo esc_html__('Privacy Policy', 'compactbox'); ?>
                        </a>
                    </li>
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(compactbox_page_url('shop', '/shop/')); ?>">
                            <?php echo esc_html__('Delivery', 'compactbox'); ?>
                        </a>
                    </li>
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(compactbox_page_url('shop', '/shop/')); ?>">
                            <?php echo esc_html__('Returns', 'compactbox'); ?>
                        </a>
                    </li>
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(home_url('/contact/')); ?>">
                            <?php echo esc_html__('Contact', 'compactbox'); ?>
                        </a>
                    </li>
                    <li>
                        <a class="cb-footer__link" href="<?php echo esc_url(home_url('/how-to-order/')); ?>">
                            <?php echo esc_html__('How to Order', 'compactbox'); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="cb-footer__column cb-footer__column--payments">
                <h2 class="cb-footer__heading"><?php echo esc_html__('Payment Methods', 'compactbox'); ?></h2>
                <ul class="cb-footer__payment-list">
                    <li class="cb-footer__payment" role="img" aria-label="Visa">
                        <svg class="cb-footer__payment-icon" viewBox="0 0 48 32" aria-hidden="true">
                            <rect width="48" height="32" rx="3" fill="#1a1f71"/>
                            <path d="M18.5 23h-4.6l3.3-16.4h4.6L18.5 23zM30.7 7.2c-1.4-.5-2.9-.8-4.5-.8-3.2 0-5.5 1.6-5.5 3.9 0 1.7 1.6 2.7 2.8 3.2 1.2.6 1.6.9 1.6 1.4 0 .8-1 1.1-1.9 1.1-1.3 0-2.5-.3-3.6-.9l-.6 3.8c1.2.5 2.6.8 4.2.8 3.4 0 5.6-1.6 5.6-4 0-1.7-1.2-2.6-3-3.4-1.3-.6-1.8-.9-1.8-1.5 0-.6.7-1 1.9-1 1.1 0 2.2.2 3.1.7l.7-3.3zM39.3 7.3l-2.2 11.2h4.2l2.2-11.2h-4.2zM11.6 7.3H6.3L6.2 7.4c3.7 1 6.2 3.4 7.2 6.2L13.5 9c-.3-1.3-1.2-1.7-1.9-1.7z" fill="#ffffff"/>
                        </svg>
                    </li>
                    <li class="cb-footer__payment" role="img" aria-label="Mastercard">
                        <svg class="cb-footer__payment-icon" viewBox="0 0 48 32" aria-hidden="true">
                            <rect width="48" height="32" rx="3" fill="#f5f5f5"/>
                            <circle cx="19" cy="16" r="8" fill="#eb001b"/>
                            <circle cx="29" cy="16" r="8" fill="#f79e1b"/>
                            <path d="M24 9.3c2.1 1.7 3.4 4.4 3.4 7.3s-1.3 5.6-3.4 7.3c-2.1-1.7-3.4-4.4-3.4-7.3s1.3-5.6 3.4-7.3z" fill="#ff5f00"/>
                        </svg>
                    </li>
                    <li class="cb-footer__payment" role="img" aria-label="PayPal">
                        <svg class="cb-footer__payment-icon" viewBox="0 0 48 32" aria-hidden="true">
                            <rect width="48" height="32" rx="3" fill="#ffffff"/>
                            <path d="M18.4 10h6.4c3.3 0 4.7 1.6 4.2 4.6-.5 3.3-2.8 5.1-6.2 5.1h-2.6c-.5 0-.8.3-.9.8l-.8 5.2h-4.2l2.1-14.2c.1-.7.6-1.1 1.3-1.1.4 0 .5.1.7.6z" fill="#003087"/>
                            <path d="M29.2 10h4.1c.7 0 1.1.4 1 1.1l-1.4 9c-.4 2.6-2.6 4.2-5.4 4.2h-2.1c-.5 0-.9.3-1 .8l-.5 3.2c-.1.4-.4.7-.9.7h-3l1.9-12.6c.4-2.4 2.2-4.1 4.8-4.5.8-.1 1.5-.1 2.5.1z" fill="#0070e0"/>
                        </svg>
                    </li>
                    <li class="cb-footer__payment" role="img" aria-label="Twint">
                        <svg class="cb-footer__payment-icon" viewBox="0 0 48 32" aria-hidden="true">
                            <rect width="48" height="32" rx="3" fill="#e10000"/>
                            <path d="M26.5 8h3.4l-4.2 16h-3.4l1.7-6.6h-6l-1.7 6.6h-3.4l4.2-16h3.4l-1.7 6.6h6l1.7-6.6z" fill="#ffffff"/>
                        </svg>
                    </li>
                </ul>
            </div>

            <div class="cb-footer__column cb-footer__column--lang">
                <h2 class="cb-footer__heading"><?php echo esc_html__('Language', 'compactbox'); ?></h2>
                <p class="cb-footer__lang-placeholder">
                    <?php echo esc_html__('FR / DE / EN / IT', 'compactbox'); ?>
                </p>
            </div>
        </div>

        <div class="cb-footer__bottom">
            <p class="cb-footer__copyright">
                <?php
                echo esc_html(
                    sprintf(
                        /* translators: %1$s: current year, %2$s: brand name */
                        __('© %1$s %2$s. All rights reserved.', 'compactbox'),
                        $current_year,
                        $brand_name
                    )
                );
                ?>
            </p>
        </div>
    </div>
</footer>
