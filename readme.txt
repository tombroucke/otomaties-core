=== Otomaties Core ===
Contributors: tompoezie
Tags: core privacy performance branding
Requires at least: 4.9
Tested up to: 5.4.2
Stable tag: 1.0.3

Boosts WordPress performance, secures install & adds branding. 

== Description ==

= Admin =

* Remove comments menu from Admin menu
* Remove WP logo & comments from toolbar
* Add custom logo to toolbar
* Show custom logo on login page
* Remove welcome panel from dashboard
* Add branding to footer

= Emojis =

* Disable emojis

= Frontend =

* Clean up header (remove generator, feed, ...)
* Close comments
* Redirect to result's single page when there is only 1 search result

= Security =

* Add notices for different security issues
* Replace login error with generic error
* Force https on attachments if available

= Theme =

* Add default theme options (close comments & pingbacks, default link to image)

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload \`otomaties-core\` to the \`/wp-content/mu-plugins/\` directory
2. Add \`otomaties-core.php\` to your mu-plugin loader

== Changelog ==

= 1.0.3 =
* Filter for generic login error

= 1.0.2 =
* Fix translation for error message

= 1.0.1 =
* Fix for pagination with single search result

= 1.0.0 =
* Initial plugin