![RevivePress](.github/banner.png "Plugin Banner")

# RevivePress
RevivePress (formerly WP Auto Republish), the all-in-one tool for republishing & cloning old posts and pages which push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

[![WP compatibility](https://plugintests.com/plugins/wp-auto-republish/wp-badge.svg)](https://plugintests.com/plugins/wp-last-modified-info/latest) 
[![PHP compatibility](https://plugintests.com/plugins/wp-auto-republish/php-badge.svg)](https://plugintests.com/plugins/wp-last-modified-info/latest)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/iamsayan/wp-auto-republish)
![PHP](https://img.shields.io/badge/php-v7.3%2B-blue)
![GitHub Release Date](https://img.shields.io/github/release-date/iamsayan/wp-auto-republish?logo=github)
![GitHub last commit](https://img.shields.io/github/last-commit/iamsayan/wp-auto-republish?logo=github)
![GitHub Repo stars](https://img.shields.io/github/stars/iamsayan/wp-auto-republish?style=social)
![GitHub forks](https://img.shields.io/github/forks/iamsayan/wp-auto-republish?style=social)

## Description

The RevivePress (formerly WP Auto Republish) plugin helps revive old posts by resetting the published date to the current date. This will push old posts to your front page, the top of archive pages, and back into RSS feeds. Ideal for sites with a large repository of evergreen content.

[Free Demo](https://api.wprevivepress.com/demo-auto-login-free) | [Premium Demo](https://api.wprevivepress.com/demo-auto-login) | [Get Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=github)

Like RevivePress plugin? Consider leaving a [5 star review](https://wordpress.org/support/plugin/wp-auto-republish/reviews/?rate=5#new-post).

### Why would you want to do this? Here are a few reasons:

1. New visitors to your site haven't seen your old content. <strong>This will help them discover it.</strong>
2. Old content won't show up in date-based searches on search engines, but resetting the date can make them <strong>look fresh again</strong>.
3. People <strong>like to share and link to new content, </strong>and they determine that by looking at the publication date.
4. It will highlight older posts by moving them back to <strong>front page and in the RSS feed</strong>.
5. RevivePress will improve your <strong>blog visibility, traffic and SEO</strong>!
6. And also <strong>Google likes updated content</strong> if it’s done right.

#### What does this plugin do?

This plugin helps revive old posts by resetting the published date to the current date and push old posts to your front page, the top of archive pages, and back into RSS feeds.

#### Key Features

* Automatically republish your all posts.
* Set minimum republish interval and randomness interval.
* Display original publication date Before/After post.
* Exclude or include posts by category or tags.
* Force exclude/include posts by their ID.
* Can select post in ASC / DESC order.
* Compatible with any timezone.
* Supports Indexing API by Rank Math.
* Automatically purge site cache (limited) after republishing.

#### Premium Features

* Supports all free version features.
* **Automatic Social Media Share**.
* Custom post types support.
* Custom taxonomies support.
* **Individual post republishing (also supports repeated)**.
* Scheduled post republishing.
* Date & time based republishing.
* Custom post republish interval.
* Custom title for each republish event.
* Trigger publish event at the time of republish.
* Automatic Site or Single Post Cache Purge Support (supports most of the cache plugins and hosting platforms)
* Custom date range for republishing.
* Change Post Status after Last Republish.
* One click instant republish.
* WordPress Sticky Posts Support.
* OneSignal Push Notification Support.
* Show all republished history in logs.
* Can change the post name on every republish.
* Shows all single upcoming republication in a dashboard widget.
* Shows single republication info in a admin column.
* Can hide last original published info from frontend.

<strong>[Upgrade to RevivePress Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=github) now. You can also upgrade to Premium Version directly from your dashboard.</strong>

### Free and Premium Support

Support for the RevivePress plugin on the WordPress forums is free.

Premium world-class support is available via email to all [RevivePress Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=github) customers.

> <strong>Note</strong>: Paid customers support is always given priority over free support. Paid customers support is provided via one-to-one email. [Upgrade to Premium](https://wprevivepress.com/pricing/?utm_source=landing&utm_medium=github) to benefit from priority support.

#### Compatibility

* This plugin is tested with W3 Total Cache, WP Super Cache, WP Rocket, WP Fastest Cache, Cachify, Comet Cache, Zen Cache, LiteSpeed Cache, SG Optimizer, HyperCache, Cache Enabler, Swift Performance Lite, Nginx Cache, Proxy Cache, Nginx Helper Cache, Autoptimize, Breeze (Cloudways), Godaddy Managed WordPress Hosting and WP Engine and fully compatible with WordPress Version 4.7 and beyond and also compatible with any WordPress theme.

#### Support

* Community support via the [support forums](https://wordpress.org/support/plugin/wp-auto-republish) at WordPress.org.

#### Contribute
* Active development of this plugin is handled [on GitHub](https://github.com/iamsayan/wp-auto-republish).
* Feel free to [fork the project on GitHub](https://github.com/iamsayan/wp-auto-republish) and submit your contributions via pull request.

#### Translations

* Simplified Chinese (zh_CN) by [Changmeng Hu](https://profiles.wordpress.org/cmhello)

## Installation

### From within WordPress
1. Visit 'Plugins > Add New'.
1. Search for 'RevivePress'.
1. Activate WP Last Modified Info from your Plugins page.
1. Go to "after activation" below.

### Manually
1. Upload the `wp-auto-republish` folder to the `/wp-content/plugins/` directory.
1. Activate RevivePress plugin through the 'Plugins' menu in WordPress.
1. Go to "after activation" below.

### After activation
1. After activation go to 'Settings > RevivePress'.
1. Enable/disable options and save changes.

### Frequently Asked Questions

#### How to customize original post publication date format?

To customize original post publication date, you need to add this following snippet to the end of your active theme's functions.php file:

`function wpar_override_time_format() {
    return 'F jS, Y \a\t h:i a';
}
add_filter( 'wpar/published_date_format', 'wpar_override_time_format' );`

#### How to customize the interval of scheduled post and old republish post?

To customize the interval of scheduled post and old republish post, you need to add this following snippet to the end of your active theme's functions.php file:

`function wpar_override_interval() {
    return '7200'; // 2 hours
}
add_filter( 'wpar/scheduled_post_interval', 'wpar_override_interval' );`

#### Are posts duplicated?

No. The date on posts is updated to the current date making a post appear new. URLs don't change and comments continue to display with the post.

#### Doesn't changing the timestamp affect permalinks that include dates?

Yes, permalinks with dates would be not be affected on both free version and Premium Version. RevivePress will handle it easily.

#### The plugin isn't working or have a bug? ####

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/wp-auto-republish) and I will work to fix it.

## Changelog ##
[View Changelog](CHANGELOG.md)
