# CompactBox Core

Core operational plugin for the CompactBox WooCommerce storefront.

## Scope

This plugin currently implements a single story:

- **Offline guard** — returns HTTP 503 + `X-Robots-Tag: noindex` + meta noindex for every public front-end request while the site is offline.
- **Settings toggle** — a "Site online" checkbox under **Settings > CompactBox**.

Other CompactBox Core modules (revenue monitor, operator dashboard, order hooks) will be added later under EPIC-005.

## File layout

```
wp-content/plugins/compactbox-core/
├── compactbox-core.php              # Plugin header, activation hook, loader
├── includes/
│   ├── class-offline-guard.php      # Public front-end 503 guard
│   └── class-admin-settings.php     # Settings > CompactBox page
└── README.md                        # This file
```

## Activation

1. Upload or copy the `compactbox-core` folder to `wp-content/plugins/`.
2. Activate **CompactBox Core** from the WordPress admin plugins list.
3. On first activation the option `compactbox_site_online` is created with the value `no` (offline by default).

## Switching the site online

1. In wp-admin, go to **Settings > CompactBox**.
2. Check **Site online** and click **Save Changes**.
3. The option `compactbox_site_online` is stored as `yes` and the public front-end becomes accessible.

To put the site back offline, uncheck the box and save.

## Option reference

| Option | Default | Stored values | Description |
|--------|---------|---------------|-------------|
| `compactbox_site_online` | `no` | `yes` or `no` | When not `yes`, public front-end requests are blocked with 503 + noindex. |

## Excluded request types

The guard never blocks:

- `/wp-admin/` requests (`is_admin()`)
- `wp-login.php`
- `admin-ajax.php` (`wp_doing_ajax()`)
- REST API requests (`REST_REQUEST`)
- WP-CLI (`WP_CLI`)

## Requirements

- WordPress 6.0+
- PHP 8.1+
