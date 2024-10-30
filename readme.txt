=== Highfly ===
Contributors: plocha
Tags: notifly, notify, newsletter, email, mail, post, comment, notification, subscription, subscribe
Requires at least: 3.8
Tested up to: 4.0
Stable tag: 2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Send notification emails of all new posts and new comments to everyone on a list.

== Description ==

Enter the mail addresses of all recipients into the list and after that the plugin does its job. 

This is a remake of the [Notifly plugin](https://wordpress.org/plugins/notifly/).

You can add support for private content with [Private Highfly](https://wordpress.org/plugins/highfly-private/).

== Differences ==

Highfly is an almost rewrite of the [Notifly plugin](https://wordpress.org/plugins/notifly/). It's a minimalistic but still flexible alternative of the original. What are the differences?

* The code is simpler, clearer and better. 
* There are many filters for plugin adjusting.
* Highfly removes its settings on uninstall.
* The notification mail contains plain text instead of HTML.
* The notification exceptions are removed. I know, it's debatable but I was too lazy to implement it. :D
* Some bug fixes. But I forgot the details argh
* I accept translation files. Available translations:
 * German

Highfly is **not compatible** to Notifly. It uses another option set. But you can copy the address list because it's the same input format.

== Known issues ==

* If a user should receive the WordPress default notification **and** the Highfly notification for the same comment, he receives only the simplier Highfly notification. I know, it would be better if he received only the default mail which contains more details. But my solution is much easier to implement.

== Changelog ==

= 2.0 =
* OOP rewrite of first version
 * More and better filters
* Coupling with core comment notification removed

= 1.0 =
* first version

== Upgrade Notice ==

= 2.0 =
Better and more flexible code base. Now you can use the plugin without activated WordPress default comment notification.

= 1.0 =
First version
