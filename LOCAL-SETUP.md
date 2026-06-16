# Resilient Philippines Local Setup

## Recommended Stack

Use LocalWP for the first local boot because this site is an older WordPress 5.7.15 install with a classic premium theme.

- PHP: 7.4 for initial restore
- Web server: LocalWP default
- Database: LocalWP default MySQL/MariaDB
- Local URL: `http://resilientphilippines.local`

## Database Restore

The newest Backup Guard archive found locally is:

`wp-content/uploads/backup-guard/sg_backup_opt(full)_20240223063910/sg_backup_opt(full)_20240223063910.sgbp`

Restore it into a disposable LocalWP site first, then replace URLs from:

`https://resilientphilippines.com`

to:

`http://resilientphilippines.local`

Use a serialized-safe tool such as WP-CLI:

```bash
wp search-replace 'https://resilientphilippines.com' 'http://resilientphilippines.local' --all-tables --precise --skip-columns=guid
```

## Local wp-config.php

Replace the old cPanel database credentials with the LocalWP database values. LocalWP commonly uses:

```php
define( 'DB_NAME', 'local' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
define( 'DB_HOST', 'localhost' );
```

Also disable the HTTPS redirect in `.htaccess` during local development.

## Activation Order

1. Activate the `Resilient Philippines Resource Hub` plugin.
2. Activate the `Resilient Humanitarian Hub` child theme.
3. Visit Settings > Permalinks and save once to refresh rewrite rules.
4. Confirm these pages exist:
   - `/resource-hub/`
   - `/submit-resource/`

## Shortcodes

- `[rp_resource_catalog limit="12" filters="true"]`
- `[rp_partner_upload_form]`

## Roles

- `partner_contributor`: can submit partner resources and upload documents.
- `hub_subscriber`: can read member-only resources without backend upload access.
