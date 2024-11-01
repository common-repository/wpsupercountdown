=== WPSuperCountdown ===
Contributors: Superlevel
Donate Link: http://www.superlevel.de/wordpress/wpsupercountdown
Tags: counter, countdown, hide, superlevel, fun
Requires at least: 2.5
Tested up to: 2.9.1
Stable tag: 0.8.15

Use Countdowns to make your blog posts more interesting.

== Description ==

With WPSuperCountdown you can hide some content (like whole articles, links, graphics, or videos) for a definable period of time. When the countdown reaches 00:00:00 the hidden content will show up and replace the former counter. Also a cookie will be set so the user don't see the specific counter again but the whole article without latency.

== Screenshots ==

1. View of the Countdown in a blog post

== Installation ==

1. Copy folder `wpsupercountdown` to your `/wp-content/plugins/` directory
1. Activate the plugin in your admin-panel ('Plugins')
1. Place `[countdown:value|content]` anywhere in your post

== Frequently Asked Questions ==

None.

== Integration into a blog post ==

Format:

`[countdown:value|content]`


value: e.g. 5s (five seconds), 30m (30 minutes), 8h (eight hours)

content: any content you want to show up after the time ran out


Example:

`[countdown:10s|Content that will be shown after ten seconds]`

== Changelog ==

0.8.15 (2009-09-07): Release of version 0.8.15