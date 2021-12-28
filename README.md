# Otomaties core

## Installation

```sh
composer require tombroucke/otomaties-core
```
## ACF
- Hide ACF screen in production & staging environments `WP_ENV == 'production' || WP_ENV == 'staging'`

## Admin
- Disable comments, remove from admin. To enable comments: `add_filter('otomaties_open_comments', '__return_true');`
- Add information from revision.txt to footer if file this exists. Content of this file should be "timestamp revision", e.g.: "20211228102819 21828faf52149f5a7f9752617d50789e97e2bb96".

## Branding
- Logo on login screen
- Logo in toolbar
- Admin footer

To disable: `add_filter('otomaties_whitelabel', false);`

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

