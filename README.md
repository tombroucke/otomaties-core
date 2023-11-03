# Otomaties core

## Installation

```sh
composer require tombroucke/otomaties-core
```
## ACF
- Hide ACF screen in production & staging environments `WP_ENV == 'production' || WP_ENV == 'staging'`

## Admin
- Disable comments, remove from admin. To enable comments: `add_filter('otomaties_open_comments', '__return_true');`

## Branding
- Logo on login screen
- Logo in toolbar
- Admin footer

To disable: `add_filter('otomaties_whitelabel', '__return_true');`

## Frontend
- Disable emojis. To enable: `add_filter('otomaties_disable_emojis', '__return_false');`
- Set default image link type to 'file'. To change: `add_filter('otomaties_set_default_image_default_link_type', '__return_false');`
- Clean up head section
- Redirect single search result. To disable: `add_filter('otomaties_redirect_single_search_result', '__return_false');`
- Add shortcode for e-mailaddress obfuscation: `[email]info@example.com[email]` or `[email address="info@example.com"]`

## Security

### Notices for
- `WP_DEBUG` or `DISALLOW_FILE_EDIT` is `TRUE`
- debug.log is publicly accesible
- No security plugin active.

### Login screen
- Add generic error to login screen

### SSL
- Force HTTPS on attachments

### General
- Disable updating of critical options `users_can_register` & `default_role`. To disable: `add_filter('otomaties_disable_update_critical_options', '__return_false');`

### Otomaties Connect
- Otomaties connect can fetch data over the rest API. Secured authentication with a public/private key pair.

#### Connection
In your `.env` file or `wp-config.php` file, add the `OTOMATIES_CONNECT_KEY` constant from the Otomaties connect portal.
```
OTOMATIES_CONNECT_KEY='XXXX-XXXX-XXXX-XXXX'
```
```php
define('OTOMATIES_CONNECT_KEY', 'XXXX-XXXX-XXXX-XXXX');
```

## Revision
- revision.txt needs to be in web root
- format: 'YmdHis {{commit hash}}'
- Show revision in admin footer for administrator role
- Show revision in console for all environments except production

To disable revisions: `add_filter('otomaties_display_revision', '__return_false');`

