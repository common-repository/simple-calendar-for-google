=== Simple Calendar for Google ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: block, calendar, google, lists
Requires at least: 4.8
Requires PHP: 8.0
Tested up to: 6.6
Stable tag: 2.22
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Lists Google's public calendars.

== Description ==

Lists Google's public calendars.

= Calendar =
* Display the schedule since current.
* Can specify the ID individually and display it.
* Can display calendars with different IDs together in chronological order.
* In the case of schedule duplication, can set an upper limit for duplicatuion and change the background color if it is exceeded.

= Edit =
* It is displayed with a block.
* It is displayed with a shortcode.
* Can specify for each user.

= How it works =
[youtube https://youtu.be/3hxpSLJ3hVE]

== Installation ==

1. Upload `simple-calendar-for-google` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add a new Page
4. Write a short code. The following text field. `[scalg]`

== Frequently Asked Questions ==

none

== Screenshots ==

1. Calendar
2. Block
3. Shortcode
4. Manage screen

== Changelog ==

= [2.22] 2024/05/28 =
* Fix - Change in the way css are loaded.

= 2.21 =
Changed file_get_contents to wp_remote_get.

= 2.20 =
Rebuilt blocks.

= 2.19 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 2.18 =
Added escaping process.

= 2.17 =
Supported WordPress 6.1.

= 2.16 =
Supported WordPress 6.0.

= 2.15 =
Added the ability to change CSS other than colors.

= 2.14 =
Rebuilt blocks.
Fixed admin screen.
Fixed uninstall.

= 2.13 =
Minor changes.

= 2.12 =
In an environment where user settings cannot be obtained, the administrator's settings will be obtained.

= 2.11 =
Fixed issue with calendar ID deletion.
Added error handler when adding calendar ID.

= 2.10 =
Displays ID and calendar name.

= 2.09 =
Fixed problem of duplicate schedules.

= 2.08 =
Added alternate text for duplicate schedules.

= 2.07 =
Added duplicate schedules and its settings.

= 2.06 =
Displays the calendar name when the calendar contents are private.

= 2.05 =
Fixed admin notices.

= 2.04 =
Changed block ID input method to select box.

= 2.03 =
Fixed time fetching when crossing dates.

= 2.02 =
Text color and background color can be changed.

= 2.01 =
Fixed translation.

= 2.00 =
A calendar can now be specified for each user.
Added support for blocks.

= 1.03 =
Fixed time zone issue.

= 1.02 =
Supported WordPress 5.3.

= 1.01 =
Added shortcode description.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.00 =
Initial release.
