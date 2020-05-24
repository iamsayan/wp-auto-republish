=== WP Auto Republish ===
Contributors: Infosatech
Tags: republish, republishing, old posts, old post, old post promoter, post promoter, promotion, SEO, rss, plugin, posts
Requires at least: 4.7
Tested up to: 5.4
Stable tag: 1.1.6
Requires PHP: 5.6
Donate link: https://www.paypal.me/iamsayan/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Republish your old posts automatically by resetting the date to the current date. Revive old posts to users that haven't seen them.

== Description ==

The WP Auto Republish plugin helps revive old posts by resetting the published date to the current date. This will push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

Like WP Auto Republish plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-auto-republish/reviews/?rate=5#new-post).

== Why would you want to do this? Here are a few reasons: ==

1. New visitors to your site haven't seen your old content. <strong>This will help them discover it.</strong>
2. Old content won't show up in date-based searches on search engines, but resetting the date can make them <strong>look fresh again</strong>.
3. People <strong>like to share and link to new content, </strong>and they determine that by looking at the publication date.
4. It will highlight older posts by moving them back to <strong>front page and in the RSS feed</strong>.
5. WP Auto Republish will improve your <strong>blog visibility, traffic and SEO</strong>!
6. And also <strong>Google likes updated content</strong> if it’s done right.

== What does this plugin do? ==

This plugin helps revive old posts by resetting the published date to the current date and push old posts to your front page, the top of archive pages, and back into RSS feeds.

> <strong>Note</strong>: All basic functionality is FREE. Features such as single post republishing, repeated republishing & triggering publish events are available in the <Strong>[Premium Edition](https://sayandatta.in/wp-auto-republish/purchase)</strong>.

### Key Features

* Automatically republish your all posts.
* Set minimum republish interval and randomness interval.
* Display original publication date Before/After post.
* Exclude or include posts by category or tags.
* Force exclude/include posts by their ID.
* Can select post in ASC / DESC order.
* Compatible with any timezone.
* Automatically purge site cache (limited) after republishing.

### Premium Features

* Supports all free version features.
* Custom post types support.
* Custom taxonomies support.
* Individual post republishing (also supports repeated).
* Scheduled post republishing.
* Date & time based republishing.
* Custom post republish interval.
* Custom title for each republish event.
* Trigger publish event at the time of republish.
* Automatic Site or Single Post Cache Purge Support (supports most of the cache plugins and hosting platforms)
* Custom date range for republishing.
* Can use dates in post permalinks.
* Change Post Status after Last Republish.
* One click instant republish.
* Show all republished history in logs.
* Can use dates in post permalinks.
* Can change the post name on every republish.
* Shows all single upcoming republication in a dashboard widget.
* Shows single republication info in a admin column.
* Can hide last original published info from frontend.

<strong>[Upgrade to WP Auto Republish Premium](https://sayandatta.in/wp-auto-republish/purchase) now. You can also upgrade to Premium Version directly from your dashboard.</strong>

### Free and Premium Support

Support for the WP Auto Republish plugin on the WordPress forums is free.

Premium world-class support is available via email to all [WP Auto Republish Premium](https://sayandatta.in/wp-auto-republish/purchase) customers.

> <strong>Note</strong>: Paid customers support is always given priority over free support. Paid customers support is provided via one-to-one email. [Upgrade to Premium](https://sayandatta.in/wp-auto-republish/purchase) to benefit from priority support.

= Compatibility =

* This plugin is tested with W3 Total Cache, WP Super Cache, WP Rocket, WP Fastest Cache, Cachify, Comet Cache, Zen Cache, LiteSpeed Cache, SG Optimizer, HyperCache, Cache Enabler, Swift Performance Lite, Nginx Cache, Proxy Cache, Nginx Helper Cache, Autoptimize, Breeze (Cloudways), Godaddy Managed WordPress Hosting and WP Engine and fully compatible with WordPress Version 4.7 and beyond and also compatible with any WordPress theme.

= Support =

* Community support via the [support forums](https://wordpress.org/support/plugin/wp-auto-republish) at WordPress.org.

= Contribute =
* Active development of this plugin is handled [on GitHub](https://github.com/iamsayan/wp-auto-republish/).
* Feel free to [fork the project on GitHub](https://github.com/iamsayan/wp-auto-republish/) and submit your contributions via pull request.

= Translations =

* Simplified Chinese (zh_CN) by [Changmeng Hu](https://profiles.wordpress.org/cmhello)

== Installation ==

1. Visit 'Plugins > Add New'.
2. Download the plugin file from your email inbox.
3. Upload the `wp-auto-republish-premium` folder to the `/wp-content/plugins/` directory manually.
4. Activate WP Auto Republish Premium from your Plugins page.
5. After activation go to 'Settings > WP Auto Republish Premium'.

6. Configure plugins settings according to your need and save changes.

== Frequently Asked Questions ==

= How to customize original post publication date format? =

To customize original post publication date, you need to add this following snippet to the end of your active theme's functions.php file:

`function wpar_override_time_format() {
    return 'F jS, Y \a\t h:i a';
}
add_filter( 'wpar/published_date_format', 'wpar_override_time_format' );`

= Will it work with my theme? =

Yes, our plugins work independently of themes you are using. As long as your website is running on WordPress, it will work.

= Are posts duplicated? =

No. The date on posts is updated to the current date making a post appear new. URLs don't change and comments continue to display with the post.

= Doesn't changing the timestamp affect permalinks that include dates?  =

Yes, permalinks with dates would be affected only in free version. This plugin shouldn't be used if your permalinks include dates since those dates will change when a post is republished. But in Premium version it is possible to use dates in permalinks.

== Screenshots ==

1. This is the admin area of WP Auto Republish.
2. Premium Version Single Post Settings.

== Changelog ==

If you like WP Auto Republish, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/wp-auto-republish/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!

= 1.1.6 =
Release Date: 24th May, 2020

= All Versions =
* Fixed: A bug where plugin causes multiple republish at a same time.
* Fixed: Plugin data can'tbe removed at the time of uninstallation.

= 1.1.5 =
Release Date: 8th May, 2020

= All Versions =
* Added: Republish event time in plugin settings page.
* Fixed: Fatal errors in less than PHP version 7.0.0. Please update your website's PHP version to 7.0.0 or higher. Support for less than PHP v7.0 will be removed in the upcoming versions.
* Fixed: A bug where global republishing is not working.

= 1.1.4 =
Release Date: 1st May, 2020

= Premium Version =
* Improved: Next republish status will be hidden from columns if single republishing is disabled in plugin settings.
* All free versions fixes and improvemnts.

= Free Version =
* Fixed: Wrong date in RSS feeds.
* Fixed: Republishing is not working if both Category and tags are selected.

= 1.1.3 =
Release Date: 26th April, 2020

= Premium Version =
* Added: Option to disable publish event triggering.
* Tweak: Plugin will show notices on block editor if post type is Post.
* Fixed: Global republishing not working when Single republishing with taxonomies is enabled.
* Added: Order by fields to sort posts.
* And all free versions fixes and improvemnts.

= Free Version =
* Other minor improvemnts.

= 1.1.2 =
Release Date: 20th April, 2020

= Premium Version =
* Tweak: Plugin will show notices on block editor as well.
* Tweak: All metadatas related to Single Republishing can be removed from plugin settings directly.
* Tweak: This plugin will republish single posts at a time with a random interval for SEO purposes. By default it is 5 minutes. I can be changed via filter.
* Tweak: Meta boxes will be displayed if a post is actually published.
* Tweak: One click Republish will work as expected except auto-draft post status.
* Fixed: An issue with repeated single republishing.
* Tweak: Plugin will regerate post permalinks at the time of activation and deactivation.
* Fixed: A wrong argument input which causes unexpected issues in single republishing.
* Fixed: An error in nonce check in One Click Republish from post meta box.
* Fixed: Some untranslated strings.
* Removed: `wp_publish_post()` is replaced with custom function.
* Removed: Cache option from plugin settings as this plugin will triggers publish events automatically. So, all cache plugins will work as expected.
* WPML Compatibility.
* And all free versions fixes and improvemnts.

= Free Version =
* Added: Second for time fields.
* Improved: Plugin settings saving process.
* Improved: Background process running mechanism.
* Fixed: Duplicate query output.

= 1.1.1 =
Release Date: 18th April, 2020

= Premium Version =
* NEW: Triggers `wp_publish_post()` at the time of republish.
* Fixed: Post Meta generation issue.
* Fixed: One Click Republish is not working right after post status is changed to publish from quick links.
* Fixed: Some untranslated strings.
* And all free versions fixes and improvemnts.

= Free Version =
* Code Cleanup.

= 1.1.0 =
Release Date: 16th April, 2020

= Premium Version =
* NEW: Unlimited Custom post types support.
* NEW: Custom taxonomies support.
* NEW: Individual post republishing. That means it is possible to republish any post/page/custom post on a repeated basis (can be daily, weekly, monthly and yearly) or on a particular date.
* NEW: Now it is possible to republish posts in a particular date range (post age between 10 years to 3 years etc.).
* NEW: This plugin will change the title of post at the time of republish automatically if specified. It will help some SEO aspects. Also post permalinks can be changed.
* NEW: Automatically fires the publish events at the time of each republish.
* NEW: It is now possible to clear all the caches of total site or only for a post at the time of republish of that post. No it supports most of the cache plugins and hosting platforms.
* NEW: Previously if you have date/month/year in post permalinks, then can you still use the original info in post permalinks.
* NEW: It is posssible to set any status for posts after repeated republishing.
* NEW: One click instant republish from quick links and from post edit page.
* NEW: Show all republished history in logs in post edit screen.
* NEW: Shows all single upcoming republication in a dashboard widget.
* NEW: Shows single republication info in a admin column.
* NEW: Can hide last original published info from frontend.
* NEW: Added more republish ages and republish intervals.
* And all free versions fixes and improvemnts.

= Free Version =
* Tweak: Merged post category and post tag selection button into one in plugin settings.
* Tweak: Start time and End Time will be in seperate row from now.
* Fixed: Property of non-object PHP error.
* Fixed: A bug where sometimes plugin will republish two posts at a time.
* Removed: Random Selection of posts from this plugin.
* Minimum required WordPress version is 4.7 from now.

= Other Versions =

* View the <a href="https://plugins.svn.wordpress.org/wp-auto-republish/trunk/changelog.txt" target="_blank">Changelog</a> file.