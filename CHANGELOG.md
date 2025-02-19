# Changelog
All notable changes to this project will be documented in this file.

## 1.5.8
Release Date: 27th December, 2024

* Added: Better handling of post IDs storage and retrieval.
* Improved: Post scheduling logic and performance.
* Improved: Daily post limit tracking mechanism.
* Fixed: Issue with post type validation in global republish.
* Fixed: Incorrect timestamp handling in some cases.
* Fixed: Post meta cleanup after republishing.
* Updated: PHP Composer Libraries.
* Code Optimization & Performance Improvements.
* Security & Bug Fixes.

## 1.5.7
Release Date: 21st March, 2024

* Improved: WP Option Autoloading Performance.
* Updated: Action Schedular Library to v3.7.3.
* Security & Bug Fixes.
* Compatibility with WordPress v6.5.

## 1.5.6
Release Date: 14th January, 2024

* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.6.2.
* Updated: Action Schedular Library to v3.7.1.

## 1.5.5
Release Date: 10th November, 2023

* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.6.0.
* Updated: Action Schedular Library to v3.6.4.
* Compatibility with WordPress v6.4.

## 1.5.4
Release Date: 10th September, 2023

### Premium Version
* Fixed: Twitter social share issues.
* Fixed: Pinterest social share issues.
* Fixed: Linkedin social share issues.
* And all Free Version Improvements and Fixes.

### Free Version
* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.5.12.
* Updated: Action Schedular Library to v3.6.2.

## 1.5.3
Release Date: 16th July, 2023

* Fixed: Memory exhausted issue on post saving.

## 1.5.2
Release Date: 7th July, 2023

* Fixed: Global Republish was stopped on some cases.

## 1.5.1
Release Date: 5th July, 2023

### Premium Version
* Improved: URL support for Twitter OAuth.
* Improved: Applied RevivePress namespace to composer packages to avoid potential conflicts.
* Fixed: Twitter API v2 Issues.
* And all Free Version Improvements and Fixes.

### Free Version
* Updated: PHP Composer Libraries.
* Updated: Freemius SDK to v2.5.10.
* Updated: Action Schedular Library to v3.6.1.
* Improved: Added high priority to execute action events properly along with other plugins.
* Improved: Auto Regenerate republish events on deletion of the action scheduler events.
* Tweak: Reduced republish batch interval to 10 seconds from 15 seconds.
* Fixed: Action Schedular menu item link.
* Fixed: Some non-translated strings.
* Compatibility with WordPress v6.3.

## 1.5.0
Release Date: 20th April, 2023

### Premium Version
* Fixed: Various conditional logics.
* Fixed: `wp_transition_post_status` nor working on same cases.
* Fixed: Republish schedule can't be started from the current date.
* And all Free Version Improvements and Fixes.

### Free Version
* Added: Tab Based Categorization.
* Added: Option to set date time format of frontend info.
* Added: Code Optimizations.
* Updated: Freemius Library to v2.5.6.
* Fixed: Wrong textdomains.
* Fixed: PHP Fatal error on Activation.
* Compatibility with WordPress v6.2.

## 1.4.9.1
Release Date: 3rd March, 2023

* Added: `Exclude Posts Published Before` option for Republish Rules.
* Fixed: Post republish date include/exclude logic.
* Fixed: Pressing space seperatings Post Titles as individual titles.

## 1.4.9
Release Date: 9th February, 2023

= Premium Version =
* Added: `Republish Tags` Taxonomy to filter post republish conditions based on these tags.
* Tweak: Log History option is now the part of `Misc.` Section.
* And all Free Version Improvements and Fixes.

= Free Version =
* Improved: Conditional logic Optimizations.
* Fixed: Multiple Taxonomies were displaying in dropdown if a taxonomy is attached to more than one post type.

## 1.4.8
Release Date: 22nd January, 2023

= Premium Version =
* Added: Option to select post statuses which can be republished.
* Added: Taxonomies and Author filters to Custom Republish Rules.
* Added: Option to set Post Status after individual post republish.
* Tweak: Only published posts are eligible for Social Share and OneSignal Notification.
* Fixed: Republish on Queue is showing even after successful single republish.
* Fixed: Auto Log History removal not working properly.
* Fixed: Account Name was not showing on Pinterest Edit Template Popup.
* And all Free Version Improvements and Fixes.

= Free Version =
* Updated: Action Schedular Library to v3.5.4.
* Improved: Code Optimizations.
* Fixed: Dashboard CSS issue.
* Fixed: Typos.

## 1.4.7.1
Release Date: 17th January, 2023

* Fixed: Permission issue after deactivation.

## 1.4.7
Release Date: 10th January, 2023

* Fixed: Dashboard CSS styling.
* Fixed: Localizations.
* Tweak: Added check for minimum PHP and WordPress versions.
* Minimum required PHP Version is 7.3.

## 1.4.6
Release Date: 25th December, 2022

### Premium Version
* Added: Pinterest Sharing.
* Added: Link Shortner services.
* Added: Option under Tools to Re-generate missed schedules.
* Fixed: Shareing URL Parameters were not working.
* Improved: Social Account adding mechanism.
* Improved: Updated Facebook SDK.
* Improved: Updated Twitter SDK.
* Minimum required PHP Version is 7.4.
* And all Free Version Improvements and Fixes.

### Free Version
* Tweak: Remove all plugins data if the settings is on.
* Improved: Tools task handling.

## 1.4.5
Release Date: 19th December, 2022

### Premium Version
* Fixed: WPML Activation detection on latest version.
* And all Free Version Improvements and Fixes.

### Free Version
* Fixed: URLs can't be accessed which are not republished.

## 1.4.4
Release Date: 15th December, 2022

### Premium Version
* Added: Option to remove log history automatically.
* Fixed: Custom Post Types Permission issue.
* Fixed: Repost Method settings was not working.
* Tweak: Save republish rule data in post meta instead of database.
* Tweak: Instant social share will be now using Action Schedular.
* And all Free Version Improvements and Fixes.

### Free Version
* Fixed: URLs can be accessed from any permalinks.
* Updated: Freemius Library.

## 1.4.3
Release Date: 19th November, 2022

### Premium Version
* Added: Polylang Support.
* Added: Indexing API Plugin Support.
* Added: Support for Auto Republish Translated posts by WPML and Polylang.
* Added: Option to remove log history automatically.
* Fixed: Custom Post Types Permission issue.

### Free Version
* Minor bug fixes.

## 1.4.2
Release Date: 14th November, 2022

### Premium Version
* Added: Auto GUID Re-generation, can be disabled from settings.

### Free Version
* Tweak: Disabled caching of WP_Query.
* Updated: Action Schedular Library.
* Updated: Freemius Library.
* Compatibility with WordPress v6.1.

## 1.4.1
Release Date: 16th September, 2022

* Tweak: User List Fetching logic.
* Updated: Action Schedular Library.

## 1.4.0
Release Date: 2nd August, 2022

### Premium Version
* Added: Tumblr Share.
* Added: Option to sort posts by actual post published date on frontend.
* Added: Option to share on individual social providers.
* Fixed: Custom User Capabilities.

### Free Version
* Tweak: Load Post Taxonomies and Authors only on Search in Settings to avoid high memory usage.
* Updated: Freemius Library.

## 1.3.9
Release Date: 8th July, 2022

### Premium Version
* Fixed: PHP Fatal Errors in v7.2.
* Fixed: Cache was not clearing automatically.

### Free Version
* Updated: Action Scheduler Library.
* Tweak: Using `date()` instead of `gmdate()` in some places.
* Fixed: Typos.

## 1.3.8
Release Date: 10th June, 2022

### Premium Version
* Fixed: Dashboard Widget rendring issue.
* Fixed: Post Metabox conditional logic.
* Updated: Composer packages.

### Free Version
* Improved: Plugin will now auto-forward to the next available weekday if all weekdays are not selected. It can be disabled by using the filter: `wpar/enable_auto_forward`.
* Fixed: Admin Menu Logo Rendering.

## 1.3.7
Release Date: 9th June, 2022

* Fixed: Republish was not working in some cases.

## 1.3.6
Release Date: 31st May, 2022

* Updated: Action Schedular Library.

## 1.3.5
Release Date: 26th May, 2022

* Fixed: PHP Error if there is any old version of Extended Library is present.
* Fixed: Schedule Generation process.
* Fixed: PHP Error on Some installations.
* Added: Recommended PHP directive values under Tools Tab.

## 1.3.4
Release Date: 16th May, 2022

### Premium Version
* Added: New Logging System.
* Fixed: Action Schedular Error if WPML is active.
* Removed: Rules and Logs Toogle Options from Settings.
* And all Free Version Improvements and Fixes.

### Free Version
* Added: Option Under RevivePress > Dashboard > Misc to regenerate Action Schedular Tables if missing.
* Added: CSS Improvements.
* Improved: Conditional Logic.
* Improved: Republish Interval per Day is now seperated and can be customized.
* Fixed: Incrasing Gap between Two Scheduled Posts.
* Removed: jQuery Cookie Library.
* Compatibility with WordPress v6.0.

## 1.3.3
Release Date: 19th April, 2022

### Premium Version
* Added: RevivePress tags in Permalinks Tags List under Settings > Permalinks.
* Fixed: Action Schedular Error if WPML is active.
* And all Free Version Improvements and Fixes.

### Free Version
* Added: Option Under RevivePress > Dashboard > Misc to regenerate Action Schedular Tables if missing.
* Added: CSS Tweaks.
* Fixed: Conditional logic.
* Minimum Required PHP version is now v7.2

## 1.3.2
Release Date: 10th April, 2022

### Premium Version
* Added: WPML Compatibility.
* Added: Option to add Custom Republish Date & Time on post edit screen and Republush Rules.
* Added: Option to add UTM Parameters in Social Media Share.
* Added: Internal Capabilities to hide Republish Rules from non-admin users.
* Fixed: PHP Error on some installations.
* And all Free Version Improvements and Fixes.

### Free Version
* Added: A function to call the functions externally. See functions.php file for details.
* Added: Admin notice using WP Pointer.
* Added: Auto Fix Permalink mechanism to support the dates, months, years is permalink structure.
* Improvement: Optimized Post Query Memory usage.
* Improvement: Added new Logo.
* Fixed: PHP errors.
* Other various Improvements and fixes.

## 1.3.1
Release Date: 25th February, 2022

### Premium Version
* Added: Ability exclude Sticky Posts.
* Added: OneSignal Support.
* Added: Batch Processing for Social Media share and Notification.
* And all Free Version Improvements and Fixes.

### Free Version
* Improvement: Optimized Post Query Memory usage.
* Improvement: Ability to force include or exclude post ids at same time.
* Other Improvements and fixes.

## 1.3.0
Release Date: 22th March, 2022

#### Rebranded to RevivePress

### Premium Version
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

### Free Version
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

## 1.2.6.1
Release Date: 25th December, 2021

* Fixed: Republish not working after last update.

## 1.2.6
Release Date: 23rd December, 2021

* Fixed: Activation issue.
* Updated Libraries.
* Bug Fixes.

## 1.2.5.1
Release Date: 24th March, 2021

### Premium Version =
* Fixed: Twitter invalid image path issue.
* Fixed: PHP Fatal error on some installations.

## 1.2.5
Release Date: 18th March, 2021

### Premium Version
* Added: Option to use Republish Rules.
* Added: Option to set per social account sharing template.
* Fixed: A bug where sometimes required post metas got deleted upon post save.

### Free Version
* Other improvements and fixes.

## 1.2.4
Release Date: 6th March, 2021

* Added: Improvements and fixes.

## 1.2.3
Release Date: 28th February, 2021

* Fixed: Database Table not found error.

## 1.2.2
Release Date: 23rd February, 2021

### Premium Version
* Added: Social Media Share Feature is added.
* Added: Compatibility with Jetpack auto social share.
* Fixed: PHP error in Dashboard Widget if this plugin is installed on Wordpress version < 5.3.0.
* Fixed: Wrong date showing if Weekly republish is set on post edit screen.
* Tweak: Added some CSS imporvements.
* Tweak: Moved Single Republish Settings to a new seperate tab.
* Other Free version & Misc improvements and fixes.

### Free Version
* Added: Action Schedular to Handle Post republishing more efficiently.
* Added: A Health Check mechanism to properly handle the missed schedule issue.
* Added: A Documentation and Faqs link as Plugin Tab.
* Properly handled the plugin uninstall actions to reduce the server load.
* Remove filter for 5 star rating from review link.
* Other improvements and fixes.
* Tested with WordPress v5.7.
* Freemius SDK update.

## 1.2.1
Release Date: 11th Novemober, 2020

* Fixed: Mising variable bug and date calculation.
* Freemius SDK update.

## 1.2.0 / 1.2.0.1
Release Date: 9th October, 2020

### Premium Version
* Added: Option to set custom republish interval.
* Added: Option to disable republish randomness interval.
* Added: Option to remove post age and to set custom post age.
* Fixed: Orderby query is not working if Random Selection was selected.
* Added: Ability to last hours to get most recent published posts.
* Fixed: Taxonomies were showing wrong in plugin dropdown settings.
* Added: Option to enable/disable post row action republish links.
* Added: Scheduled republishing feature. Please check tahp tab for more details.
* Added: Advanced republish log feature to track every event more efficiently.
* Added: An option to view pending republish posts from All Posts page.
* Fixed: Admin column was not showing proper republish infomation.
* Fixed: High memory usage and freeze issue if republish start date is very far than current date.
* Tweak: Single republishing with 1 to 4 minutes interval is removed by default. But it can be enabled by filter.
* Added: Option to clear post metas for debug purposes and to copy/paste plugin settings.
* Preparing for upcoming Features to handle Post Republish more efficiently. And Social Share is coming soon in the Premium Version :)

### Free Version
* Added: A guide to help users to configure the plugin easily.
* Tweak: It is now designed in a way to easily work with any server enviroment.
* Optimized codes to handle republish process more efficiently to avoid event missing issue.
* Remove filter for 5 star rating from review link.
* Other improvements and fixes.

## 1.1.10
Release Date: 6th August, 2020

* Fixed: Multiple Republish issues.

## 1.1.9
Release Date: 3rd August, 2020

* Fixed: An undefined variable error.
* Fixed: Minor JS issues.
* Tested with WordPress v5.5.

## 1.1.8
Release Date: 23rd July, 2020

### Premium Version
* Added: It is now possible to republish posts with a custom ordering plugin.
* Fixed: Custom Taxonimies attached to WP Core Post Types is not republishing. Please select taxonomies from plugin settings again. Otherwise plugin may not respect taxonomies.
* Preparing for upcoming Features to handle Post Republish more efficiently. And also something new is coming in Premium Version :)

### Free Version
* Tweak: Post republish will be forwarded to the next available dates (at random time) if there are not slots available on current day.
* Fixed: A bug some HTML Tags are removed automatically after republish from post content on some cases.
* Fixed: Unclosed HTML Tag issues.

## 1.1.7
Release Date: 30th June, 2020

### Premium Version
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

### Free Version
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