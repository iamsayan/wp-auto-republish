=== RevivePress – Keep your Old Content Evergreen ===
Contributors: infosatech
Tags: republish, republishing, old posts, old post, repost, old post promoter, post promoter, promotion, SEO, rss, plugin, posts
Requires at least: 5.2
Tested up to: 6.0
Stable tag: 1.3.6
Requires PHP: 7.2
Donate link: https://www.paypal.me/iamsayan/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

RevivePress (formerly WP Auto Republish), the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

== Description ==

RevivePress (formerly WP Auto Republish), the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

[Free Demo](https://api.wprevivepress.com/free-demo) | [Premium Demo](https://api.wprevivepress.com/premium-demo) | [Get Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=wporg)

Like the RevivePress plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-auto-republish/reviews/#new-post).

== Why would you want to do this? Here are a few reasons: ==

1. New visitors to your site haven't seen your old content. <strong>This will help them discover it.</strong>
2. Old content won't show up in date-based searches on search engines, but resetting the date can make them <strong>look fresh again</strong>.
3. People <strong>like to share and link to new content, </strong>and they determine that by looking at the publication date.
4. It will highlight older posts by moving them back to <strong>front page and in the RSS feed</strong>.
5. RevivePress (formerly WP Auto Republish) will improve your <strong>blog visibility, traffic and SEO</strong>!
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
* **Full WPML Compatibility**.
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

Support for the RevivePress (formerly WP Auto Republish) plugin on the WordPress forums is free.

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

If you like RevivePress (formerly WP Auto Republish), please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/wp-auto-republish/reviews/#new-post). It helps to keep development and support going strong. Thank you!

= 1.3.6 =
Release Date: 31st May, 2022

* Updated: Action Schedular Library.

= 1.3.5 =
Release Date: 26th May, 2022

* Fixed: PHP Error if there is any old version of Extended Library is present.
* Fixed: Schedule Generation process.
* Fixed: PHP Error on Some installations.
* Added: Recommended PHP directive values under Tools Tab.

= 1.3.4 =
Release Date: 16th May, 2022

= Premium Version =
* Added: New Logging System.
* Fixed: Action Schedular Error if WPML is active.
* Removed: Rules and Logs Toogle Options from Settings.
* And all Free Version Improvements and Fixes.

= Free Version =
* Added: Option Under RevivePress > Dashboard > Misc to regenerate Action Schedular Tables if missing.
* Added: CSS Improvements.
* Improved: Conditional Logic.
* Improved: Republish Interval per Day is now seperated and can be customized.
* Fixed: Incrasing Gap between Two Scheduled Posts.
* Removed: jQuery Cookie Library.
* Compatibility with WordPress v6.0.

= 1.3.3 =
Release Date: 19th April, 2022

= Premium Version =
* Added: RevivePress tags in Permalinks Tags List under Settings > Permalinks.
* Fixed: Action Schedular Error if WPML is active.
* And all Free Version Improvements and Fixes.

= Free Version =
* Added: Option Under RevivePress > Dashboard > Misc to regenerate Action Schedular Tables if missing.
* Added: CSS Tweaks.
* Fixed: Conditional logic.
* Minimum Required PHP version is now v7.2

= 1.3.2 =
Release Date: 10th April, 2022

= Premium Version =
* Added: WPML Compatibility.
* Added: Option to add Custom Republish Date & Time on post edit screen and Republush Rules.
* Added: Option to add UTM Parameters in Social Media Share.
* Added: Internal Capabilities to hide Republish Rules from non-admin users.
* Fixed: PHP Error on some installations.
* And all Free Version Improvements and Fixes.

= Free Version =
* Added: A function to call the functions externally. See functions.php file for details.
* Added: Admin notice using WP Pointer.
* Added: Auto Fix Permalink mechanism to support the dates, months, years is permalink structure.
* Improvement: Optimized Post Query Memory usage.
* Improvement: Added new Logo.
* Fixed: PHP errors.
* Other various Improvements and fixes.

= 1.3.1 =
Release Date: 25th February, 2022

= Premium Version =
* Added: Ability exclude Sticky Posts.
* Added: OneSignal Support.
* Added: Batch Processing for Social Media share and Notification.
* And all Free Version Improvements and Fixes.

= Free Version =
* Improvement: Optimized Post Query Memory usage.
* Improvement: Ability to force include or exclude post ids at same time.
* Other Improvements and fixes.

= 1.3.0 =
Release Date: 22th February, 2022

* Rebranded to RevivePress

= Premium Version =
* Added: Ability to republish posts basted on Thumbnail.
* Added: Ability to republish posts basted on Authors.
* Added: Option to restrict the number of posts allowed to republish within a day.
* Improvement: Added String base interval support. It means you can use postfixes of Year, Month, Week, Day, Hour, Minutes like 1y, 2m, 3w, 4d, 5h, 6i respectively. 
* Improvemnet: Added a swtich under Misc. Options to enable/disable Republish Rules module.
* Improvement: We have added tab based navigation in per post metabox.
* Improvement: Instant Republish settings is not seperated from single republish settings. It has its own control settings.
* Improvement: Social Accounts credentials will be stored on your website so that you don't need to add that evertime when you authorizes your social accounts.
* Improvement: Social Accounts verification system.
* Improvement: Added an option to include the start date in post republish metabox if starts date is greater than today.
* Improvement: Added Order by name in post query setion.
* Removed: Month Specific republish due to some issues. Will be added in the future versions.
* Fixed: A bug where checkboxes in post metabox can't be saved.
* And all Free Version Improvements and Fixes.

= Free Version =
* NEW: Redesigned UI.
* Improvement: Optimized republish process to handle Memory limit.
* Improvement: Added proper guide in every settings.
* Improvement: Modular Settings to handle code in efficient way.
* Improvement: Optimize Post Republish process by doing event segmentation.
* Improvement: Introduces an Updater class to handle upgradation more efficiently.
* Improvement: Added Public Roadmap link in menu item.
* Improvement: Changed default runner interval to 3 minutes from 1 minutes thought it can be modified by filter. We don't recommend to do so.
* Improvement: Optimize plugin to handle large scale republish events.
* Improvement: Added Escaping and WPCS checks.
* Updated: jQuery UI CSS Library annd Timepicker Library.
* Removed: Health Check system which sometimes causes overload.
* Removed: Unused Migration scripts.
* Fixed: Fix for Database Table will run if WooCommerce not active.
* Fixed: Fatal error caused by Action Schedular.
* Fixed: Several typos.
* Tested with WordPress 5.9 and minumum required WordPress version is 5.2.
* PHP 8.0 Compatibility.

= Other Versions =

* View the <a href="https://plugins.svn.wordpress.org/wp-auto-republish/trunk/changelog.txt" target="_blank">Changelog</a> file.

== Upgrade Notice ==

* Major changes are introduced in this version. Please review your settings and save changes.