=== WP Auto Republish ===
Contributors: Infosatech
Tags: republish, republishing, old posts, old post, old post promoter, post promoter, promotion, SEO, rss, plugin, posts
Requires at least: 4.0
Tested up to: 5.4
Stable tag: 1.0.8
Requires PHP: 5.6
Donate link: https://www.paypal.me/iamsayan/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Republish your old posts automatically by resetting the date to the current date. Revive old posts to users that haven't seen them.

== Description ==

The WP Auto Republish plugin helps revive old posts by resetting the published date to the current date. This will push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

Like WP Auto Republish plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-auto-republish/reviews/?rate=5#new-post).

#### Why would you want to do this? Here are a few reasons:

1. New visitors to your site haven't seen your old content. <strong>This will help them discover it.</strong>
2. Old content won't show up in date-based searches on search engines, but resetting the date can make them <strong>look fresh again</strong>.
3. People <strong>like to share and link to new content, </strong>and they determine that by looking at the publication date.
4. It will highlight older posts by moving them back to <strong>front page and in the RSS feed</strong>.
5. WP Auto Republish will improve your <strong>blog visibility, traffic and SEO</strong>!
6. And also <strong>Google likes updated content</strong> if it’s done right.

#### What does this plugin do?

This plugin helps revive old posts by resetting the published date to the current date and push old posts to your front page, the top of archive pages, and back into RSS feeds.

* Allows you to change old posts published date to current date.
* Allows you to set minimum republish interval and randomness interval.
* Allows you to display original publication date Before/After post.
* Allows you to exculde/include posts by category/tags.
* Allows you to force exclude/include posts by their ID.
* Allows you to set old post selection method.
* Allows wordpress to automatically set new published time according to localtime zone.
* Allows wordpress to automatically purge cache after republishing.

#### Warnings

* DON'T USE THIS PLUGIN IF YOUR PERMALINKS INCLUDE DATES 

#### Compatibility

* This plugin is tested with W3 Total Cache, WP Super Cache, WP Rocket, WP Fastest Cache, Cachify, Comet Cache, Zen Cache, LiteSpeed Cache, Nginx Cache (by Till Krüss), SG Optimizer, HyperCache, Cache Enabler, Swift Performance Lite, Breeze (Cloudways), Godaddy Managed WordPress Hosting and WP Engine and fully compatible with WordPress Version 4.0 and beyond and also compatible with any WordPress theme.

#### Support

* Community support via the [support forums](https://wordpress.org/support/plugin/wp-auto-republish) at WordPress.org.

#### Contribute
* Active development of this plugin is handled [on GitHub](https://github.com/iamsayan/wp-auto-republish/).
* Feel free to [fork the project on GitHub](https://github.com/iamsayan/wp-auto-republish/) and submit your contributions via pull request.

#### Translations

* Simplified Chinese (zh_CN) by [Changmeng Hu](https://profiles.wordpress.org/cmhello)

== Installation ==

1. Visit 'Plugins > Add New'
1. Search for 'WP Auto Republish' and install it.
1. Or you can upload the `wp-auto-republish` folder to the `/wp-content/plugins/` directory manually.
1. Activate WP Auto Republish from your Plugins page.
1. After activation go to 'Settings > WP Auto Republish'.
1. Configure settings according to your need and save changes.

== Frequently Asked Questions ==

= How to customize original post publication date format? =

To customize original post publication date, you need to add this following snippet to the end of your active theme's functions.php file:

`function wpar_override_time_format() {
    return 'F jS, Y \a\t h:i a';
}
add_filter( 'wpar_published_date_format', 'wpar_override_time_format' );`

= How to customize the interval of scheduled post and old republish post? =

To customize the interval of scheduled post and old republish post, you need to add this following snippet to the end of your active theme's functions.php file:

`function wpar_override_interval() {
    return '7200'; // 2 hours
}
add_filter( 'wpar_scheduled_post_interval', 'wpar_override_interval' );`

= Are posts duplicated? =

No. The date on posts is updated to the current date making a post appear new. URLs don't change and comments continue to display with the post.

= Doesn't changing the timestamp affect permalinks that include dates?  =

Yes, permalinks with dates would be affected. This plugin shouldn't be used if your permalinks include dates since those dates will change when a post is republished.

== Screenshots ==

1. This is the admin area of WP Auto Republish.

== Changelog ==

= 1.0.8 =
Release Date: 24th March, 2020

* Fixed: A serious bug where a missing syntax causes unexpected behaviour at the time of auto republish.
* Deprecation: `wpar_supported_post_types` filter will be deprecated in the upcoming version as this plugin now supports all custom post types.

= 1.0.7 =
Release Date: 20th March, 2020

* Added: Ability to select custom post types from plugin settings.
* Added: Ability to select/get posts by decending order.
* Added: Support for Swift Performance Lite Cache Purge.
* Fixed: Some broken links.
* Preparing this plugin for some upcoming major changes.
* Tested with WordPress v5.4.

= 1.0.6 =
Release Date: 14th January, 2019

* Added: Support for Breeze (Cloudways) Cache Purge.
* Fixed: A bug with godaddy cache purge.

= 1.0.5 =
Release Date: 5th January, 2019

* Updated: Chinese Translations.
* Fixed: Incorrectly translated strings.

= 1.0.4 =
Release Date: 3rd January, 2019

* Fixed: A bug where "Save Settings" button is not working if at least one categories and tag is not selected.
* Improved: Adapted ajax mechanism to save plugin settings
* Improved: Input Validation in plugin settings.
* Fixed: Incorrectly translated strings.

= 1.0.3 =
Release Date: 2nd January, 2019

* Added: Option to set date and time for republishing.
* Added: Chinese translation. Thanks to [@cmhello](https://profiles.wordpress.org/cmhello)
* Tweak: This plugin does not republish you old content if you have already scheduled a post within 1 hour. This interval can be modified by `wpar_scheduled_post_interval` filter.
* Fixed: Incorrectly translated strings.

= 1.0.2 =
Release Date: 24th December, 2018

* Added: A check to show a notice if permalinks structure contains date.
* Improved: Custom Post Types Support.
* Fixed: An plugin activation error notice.

= 1.0.1 =
Release Date: 24th December, 2018

* Added: Option to force include/exclude posts by their ID.
* Added: Option to select old post retrive method from database.

= 1.0.0 =
Release Date: 23rd December, 2018

* Initial release.