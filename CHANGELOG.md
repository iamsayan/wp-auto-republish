# Changelog
All notable changes to this project will be documented in this file.

## 1.1.7
Release Date: 30th June, 2020

= Premium Version =
* Added: Option to Set Post Republish Action. It is now possible to clone old posts automatically.
* Added: Option to Set Post types on which you want to display origianl publication date.
* Added: Some checks to handle time restriction properly.
* Added: It is now possible to restrict users from accessing Single Republish Settings via plugin settings.
* Added: It is now possible to show single metabox on particular post types via plugin settings.
* Added: You can get Email Notification after republication is done.
* Added: Post Republication Logs in plugin settings.
* Added: Automatic Single Post Cache clear if Republish Trigger is disabled.
* Added: Hourly and Minutes option to Single Repeat Republishing option. By default, Minutes is disabled, but it can be enabled via filter.
* Added: Time Restriction from Single Republishing for Hourly and Minutes Interval on Post Meta Box also.
* Added: Global Republishing Scheduled will show on admin colums.
* Improved: Added some checks to fix the over memory usage issues.
* And all free versions features & Other many fixes and improvemnts.

= Free Version =
* Added: Modular UI to enhance User Experience.
* Improved: Background Process Mechanism (currently using cron) to ensure that Post Republish Process runs without any multiple republication issues.
* Optmized: Some unused & duplicates codes.
* Tweak: Cron Schedules is displayed on plugin settings page.

## 1.1.6
Release Date: 24th May, 2020

= All Versions =
* Fixed: A bug where plugin causes multiple republish at a same time.
* Fixed: Plugin data can'tbe removed at the time of uninstallation.

## 1.1.5
Release Date: 8th May, 2020

= All Versions =
* Added: Republish event time in plugin settings page.
* Fixed: Fatal errors in less than PHP version 7.0.0. Please update your website's PHP version to 7.0.0 or higher. Support for less than PHP v7.0 will be removed in the upcoming versions.
* Fixed: A bug where global republishing is not working.

## 1.1.4
Release Date: 1st May, 2020

### Premium Version
* Improved: Next republish status will be hidden from columns if single republishing is disabled in plugin settings.
* All free versions fixes and improvemnts.

### Free Version
* Fixed: Wrong date in RSS feeds.
* Fixed: Republishing is not working if both Category and tags are selected.

## 1.1.3
Release Date: 26th April, 2020

### Premium Version
* Added: Option to disable publish event triggering.
* Tweak: Plugin will show notices on block editor if post type is Post.
* Fixed: Global republishing not working when Single republishing with taxonomies is enabled.
* Added: Order by fields to sort posts.
* And all free versions fixes and improvemnts.

## Free Version
* Other minor improvemnts.

## 1.1.2
Release Date: 20th April, 2020

### Premium Version

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

### Free Version

* Added: Second for time fields.
* Improved: Plugin settings saving process.
* Fixed: Duplicate query output.
* Fixed: Background process running mechanism.

## 1.1.1
Release Date: 18th April, 2020

### Premium Version

* NEW: Triggers `wp_publish_post()` at the time of republish.
* Fixed: Post Meta generation issue.
* Fixed: One Click Republish is not working right after post status is changed to publish from quick links.
* Fixed: Some untranslated strings.
* And all free versions fixes and improvemnts.

### Free Version

* Code Cleanup.

## 1.1.0
Release Date: 16th April, 2020

### Premium Version

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

### Free Version

* Tweak: Merged post category and post tag selection button into one in plugin settings.
* Tweak: Start time and End Time will be in seperate row from now.
* Fixed: Property of non-object PHP error.
* Fixed: A bug where sometimes plugin will republish two posts at a time.
* Removed: Random Selection of posts from this plugin.
* Minimum required WordPress version is 4.7 from now.

## 1.0.8
Release Date: 24th March, 2020

* Fixed: A serious bug where a missing syntax causes unexpected behaviour at the time of auto republish.
* Deprecation: `wpar_supported_post_types` filter will be deprecated in the upcoming version as this plugin now supports all custom post types.

## 1.0.7
Release Date: 20th March, 2020

* Added: Ability to select custom post types from plugin settings.
* Added: Ability to select/get posts by decending order.
* Added: Support for Swift Performance Lite Cache Purge.
* Fixed: Some broken links.
* Preparing this plugin for some upcoming major changes.
* Tested with WordPress v5.4.

## 1.0.6
Release Date: 14th January, 2019

* Added: Support for Breeze (Cloudways) Cache Purge.
* Fixed: A bug with godaddy cache purge.

## 1.0.5
Release Date: 5th January, 2019

* Updated: Chinese Translations.
* Fixed: Incorrectly translated strings.

## 1.0.4
Release Date: 3rd January, 2019

* Fixed: A bug where "Save Settings" button is not working if at least one categories and tag is not selected.
* Improved: Adapted ajax mechanism to save plugin settings
* Improved: Input Validation in plugin settings.
* Fixed: Incorrectly translated strings.

## 1.0.3
Release Date: 2nd January, 2019

* Added: Option to set date and time for republishing.
* Added: Chinese translation. Thanks to [@cmhello](https://profiles.wordpress.org/cmhello)
* Tweak: This plugin does not republish you old content if you have already scheduled a post within 1 hour. This interval can be modified by `wpar_scheduled_post_interval` filter.
* Fixed: Incorrectly translated strings.

## 1.0.2
Release Date: 24th December, 2018

* Added: A check to show a notice if permalinks structure contains date.
* Improved: Custom Post Types Support.
* Fixed: An plugin activation error notice.

## 1.0.1
Release Date: 24th December, 2018

* Added: Option to force include/exclude posts by their ID.
* Added: Option to select old post retrive method from database.

## 1.0.0
Release Date: 23rd December, 2018

* Initial release.