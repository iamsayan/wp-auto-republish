=== RevivePress – Keep your Old Content Evergreen ===
Contributors: infosatech
Tags: republish, republishing, old posts, old post, repost, old post promoter, post promoter, promotion, SEO, rss, plugin, posts
Requires at least: 5.2
Tested up to: 6.5
Stable tag: 1.5.7
Requires PHP: 7.3
Donate link: https://www.paypal.me/iamsayan/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

RevivePress, the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

== Description ==

RevivePress, the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

[Premium Demo](https://api.wprevivepress.com/premium-demo) | [Get Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=wporg)

Like the RevivePress plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-auto-republish/reviews/#new-post).

== Why would you want to do this? Here are a few reasons: ==

1. New visitors to your site haven't seen your old content. <strong>This will help them discover it.</strong>
2. Old content won't show up in date-based searches on search engines, but resetting the date can make them <strong>look fresh again</strong>.
3. People <strong>like to share and link to new content, </strong>and they determine that by looking at the publication date.
4. It will highlight older posts by moving them back to <strong>front page and in the RSS feed</strong>.
5. RevivePress will improve your <strong>blog visibility, traffic and SEO</strong>!
6. And also <strong>Google likes updated content</strong> if it’s done right.

== What does this plugin do? ==

This plugin helps revive old posts by resetting the published date to the current date and push old posts to your front page, the top of archive pages, and back into RSS feeds.

> <strong>Note</strong>: All basic functionality is FREE. Features such as single post republishing, auto social share, OneSignal Support, repeated republishing & triggering publish events are available in the <Strong>[Premium Edition](https://wprevivepress.com/?utm_source=landing&utm_medium=wporg)</strong>.

### Key Features

* Automatically republish your all posts.
* Set minimum republish interval and randomness interval.
* Display original publication date Before/After post.
* Exclude or include posts by category or tags.
* Force exclude/include posts by their ID.
* Can select post in ASC / DESC order.
* Compatible with any timezone.
* Supports Indexing API by Rank Math.
* Automatically purge site cache (limited) after republishing.

### Premium Features

* Supports all free version features.
* **Automatic Social Media Share**.
* Custom Post Types support.
* Custom Taxonomies support.
* **Individual Post Republishing (also supports repeated)**.
* Republish Per Post Basis.
* Date & Time Based Republishing.
* Automatic Social Media Share.
* Custom Post Republish Interval.
* Set Custom Title for each Republish Event.
* **Full WPML & Polylang Compatibility**.
* Indexing API Plugin Support.
* Automatic Site or Single Post Cache Purge Support (supports most of the cache plugins and hosting platforms)
* Changing Post Status after Republish.
* One Click Republish & Social Share.
* Email Notification upon Republishing.
* Custom Date Range for Republishing.
* Change Post Status after Last Republish.
* WordPress Sticky Posts Support.
* OneSignal Push Notification Support.
* Show all republished history in logs.
* Can Change the Post Name/URL on Every Republish.
* Shows all single upcoming republication in a dashboard widget.
* Shows Republication Info in an Admin Column.
* Can hide last original published info from frontend.

<strong>[Upgrade to RevivePress Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=wporg) now. You can also upgrade to Premium Version directly from your dashboard.</strong>

### Free and Premium Support

Support for the RevivePress plugin on the WordPress forums is free.

Premium world-class support is available via email to all [RevivePress Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=wporg) customers.

> <strong>Note</strong>: Paid customers support is always given priority over free support. Paid customers support is provided via one-to-one email. [Upgrade to Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=wporg) to benefit from priority support.

= Compatibility =

* This plugin is tested with W3 Total Cache, WP Super Cache, WP Rocket, WP Fastest Cache, Cachify, Comet Cache, Zen Cache, LiteSpeed Cache, SG Optimizer, HyperCache, Cache Enabler, Swift Performance Lite, Nginx Cache, Proxy Cache, Nginx Helper Cache, Autoptimize, Breeze (Cloudways), Godaddy Managed WordPress Hosting and WP Engine and fully compatible with WordPress Version 5.2 and beyond and also compatible with any WordPress theme.

= Support =

* Community support via the [support forums](https://wordpress.org/support/plugin/wp-auto-republish) at WordPress.org.

= Contribute =
* Active development of this plugin is handled [on GitHub](https://github.com/iamsayan/wp-auto-republish/).
* Feel free to [fork the project on GitHub](https://github.com/iamsayan/wp-auto-republish/) and submit your contributions via pull request.

= Translations =

* Simplified Chinese (zh_CN) by [Changmeng Hu](https://profiles.wordpress.org/cmhello)

== Installation ==

1. Visit 'Plugins > Add New'
2. Search for 'RevivePress' and install it.
3. Or you can upload the `wp-auto-republish` folder to the `/wp-content/plugins/` directory manually.
4. Activate RevivePress from your Plugins page.
5. After activation go to 'Settings > RevivePress'.
6. Configure settings according to your need and save changes.

6. Configure plugins settings according to your need and save changes.

== Frequently Asked Questions ==

= How to customize original post publication date format on frontend? =

To customize original post publication date, you need to add this following snippet to the end of your active theme's functions.php file:

`add_filter( 'wpar/published_date_format', function() {
    return 'F jS, Y \a\t h:i a';
} );`

= Will it work with my theme? =

Yes, our plugins work independently of themes you are using. As long as your website is running on WordPress, it will work.

= Are posts duplicated? =

By default, no. But you can configure it from plugin settings. The date on posts is updated to the current date making a post appear new. URLs don't change and comments continue to display with the post.

= Doesn't changing the timestamp affect permalinks that include dates?  =

No, plugin can handle it efficiently by tinkering the Permalink Structure.

== Screenshots ==

1. RevivePress - General Tab.
2. RevivePress - Post Options Tab.

== Changelog ==

If you like RevivePress, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/wp-auto-republish/reviews/#new-post). It helps to keep development and support going strong. Thank you!

= 1.5.7 =
Release Date: 21st March, 2024

* Improved: WP Option Autoloading Performance.
* Updated: Action Schedular Library to v3.7.3.
* Security & Bug Fixes.
* Compatibility with WordPress v6.5.

= 1.5.6 =
Release Date: 14th January, 2024

* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.6.2.
* Updated: Action Schedular Library to v3.7.1.

= 1.5.5 =
Release Date: 10th November, 2023

* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.6.0.
* Updated: Action Schedular Library to v3.6.4.
* Compatibility with WordPress v6.4.

= 1.5.4 =
Release Date: 10th September, 2023

= Premium Version =
* Fixed: Twitter social share issues.
* Fixed: Pinterest social share issues.
* Fixed: Linkedin social share issues.
* And all Free Version Improvements and Fixes.

= Free Version =
* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.5.12.
* Updated: Action Schedular Library to v3.6.2.

= 1.5.3 =
Release Date: 16th July, 2023

* Fixed: Memory exhausted issue on post saving.

= 1.5.2 =
Release Date: 7th July, 2023

* Fixed: Global Republish was stopped on some cases.

= 1.5.1 =
Release Date: 5th July, 2023

= Premium Version =
* Improved: URL support for Twitter OAuth.
* Improved: Applied RevivePress namespace to composer packages to avoid potential conflicts.
* Fixed: Twitter API v2 Issues.
* And all Free Version Improvements and Fixes.

= Free Version =
* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.5.10.
* Updated: Action Schedular Library to v3.6.1.
* Improved: Added high priority to execute action events properly along with other plugins.
* Improved: Auto Regenerate republish events on deletion of the action scheduler events.
* Tweak: Reduced republish batch interval to 10 seconds from 15 seconds.
* Fixed: Action Schedular menu item link.
* Fixed: Some non-translated strings.
* Compatibility with WordPress v6.3.

= 1.5.0 =
Release Date: 20th April, 2023

= Premium Version =
* Fixed: Various conditional logics.
* Fixed: `wp_transition_post_status` nor working on same cases.
* Fixed: Republish schedule can't be started from the current date.
* And all Free Version Improvements and Fixes.

= Free Version =
* Added: Tab Based Categorization.
* Added: Option to set date time format of frontend info.
* Added: Code Optimizations.
* Updated: Freemius Library to v2.5.6.
* Fixed: Wrong textdomains.
* Fixed: PHP Fatal error on Activation.
* Compatibility with WordPress v6.2.

= Other Versions =

* View the <a href="https://plugins.svn.wordpress.org/wp-auto-republish/trunk/changelog.txt" target="_blank">Changelog</a> file.