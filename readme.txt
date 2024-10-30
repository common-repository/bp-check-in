=== Wbcom Designs – Check-ins for BuddyPress Activity ===  
Contributors: wbcomdesigns, vapvarun  
Donate link: https://wbcomdesigns.com/donate/  
Tags: buddypress, check-ins, BuddyPress Location, activity check-ins  
Requires at least: 5.0.0  
Tested up to: 6.6.2  
Stable tag: 2.3.0  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin allows members to share their location when posting activities. Members can add places they have visited or nearby locations using the Google Place API.

== Key Features ==

- **AutoComplete Feature**: Start typing a location name, and it will suggest nearby places based on your input. You can then select your desired location.
- **Google Map Integration**: All activity posts that include a location are displayed on a Google Map.
- **Google Place API Key**: A valid API key is required, which can be created from the [Google Place Web Service Documentation](https://developers.google.com/places/web-service/).

If you need additional support, please get in touch with us for [BuddyPress Check-ins](https://wbcomdesigns.com/contact).

== Free Add-ons to Enhance Your BuddyPress or BuddyBoss Platform ==

- **[BuddyPress Member Reviews](https://wordpress.org/plugins/bp-user-profile-reviews/)**: Allow members to add ratings or feedback for other community members.
- **[BuddyPress Group Reviews](https://wordpress.org/plugins/review-buddypress-groups/)**: Enable group ratings and reviews.
- **[BuddyPress Activity Social Share](https://wordpress.org/plugins/bp-activity-social-share/)**: Share activities on social platforms like Facebook, Twitter, and LinkedIn.
- **[Private Community with BP Lock](https://wordpress.org/plugins/lock-my-bp/)**: Make your community private, only accessible to logged-in users, while keeping selected pages public.
- **[BuddyPress Job Manager](https://wordpress.org/plugins/bp-job-manager/)**: Integrate WP Job Manager with BuddyPress.
- **[Check-ins for BuddyPress Activity](https://wordpress.org/plugins/bp-check-in/)**: Let members add their location or check-ins to their BuddyPress activities.
- **[BuddyPress Favorite Notification](https://wordpress.org/plugins/bp-favorite-notification/)**: Notify members when their activities receive likes or favorites.
- **[Shortcodes & Elementor Widgets for BuddyPress](https://wordpress.org/plugins/shortcodes-for-buddypress/)**: Use shortcodes and Elementor widgets for displaying BuddyPress activities, member directories, and groups.

== Premium Add-ons ==

- **[BuddyPress Hashtags](https://wbcomdesigns.com/downloads/buddypress-hashtags/)**: Use hashtags in BuddyPress activities and bbPress topics.
- **[BuddyPress Polls](https://wbcomdesigns.com/downloads/buddypress-polls/)**: Let members publish polls in BuddyPress or BuddyBoss activities and groups.
- **[BuddyPress Quotes](https://wbcomdesigns.com/downloads/buddypress-quotes/)**: Enable members to post updates with colorful and interactive backgrounds.
- **[BuddyPress Status & Reaction](https://wbcomdesigns.com/downloads/buddypress-status/)**: Let members set a status and offer reactions to activities.
- **[BuddyPress Profanity](https://wbcomdesigns.com/downloads/buddypress-profanity/)**: Filter and censor inappropriate content in activities and messages.
- **[BuddyPress Sticky Post](https://wbcomdesigns.com/downloads/buddypress-sticky-post/)**: Pin important activities to the top of the activity stream.
- **[BuddyPress Auto Friends](https://wbcomdesigns.com/downloads/buddypress-auto-friends/)**: Automatically assign global friends to new members.
- **[Shortcodes & Elementor Widgets for BuddyPress Pro](https://wbcomdesigns.com/downloads/shortcodes-for-buddypress-pro/)**: Use advanced shortcodes and Elementor widgets for BuddyPress content.

== Check-ins Pro (Premium Version) ==

- **XProfile Mapping**: Sync location fields in BuddyPress XProfile with activity check-ins.
- **Location Fields for Groups**: Add and manage group locations, with maps displayed for group directories.
- **Maps for Member & Group Directories**: Display Google Maps or OpenStreetMap in member and group directories.
- **Google Maps and OpenStreetMap Support**: Choose between Google Maps or OpenStreetMap for displaying location data.
- **Auto-Suggestion**: Provide location suggestions in profile fields, member search, group locations, and group search based on both Google Maps and OpenStreetMap.

== Installation ==

1. Upload the entire `bp-check-in` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.


== Frequently Asked Questions ==
= Does this plugin work with both BuddyPress and BuddyBoss? =
Yes, this plugin works seamlessly with both BuddyPress and BuddyBoss platforms.

= Does this plugin require BuddyPress or BuddyBoss to be installed? =
BuddyPress or BuddyBoss must be installed and activated for the plugin to work.

= What is the use of the API Key option provided in the settings? =
With the Google Places API Key, users can check in to places with autocomplete suggestions when posting in BuddyPress or BuddyBoss activities. Selected locations are displayed on a map.

= Does this plugin require location services? =
Users need to enable location services in their browser for the check-in feature to function correctly.

= How can we add a place when posting? =
Go to your profile's post update section, click the map marker icon (if enabled), and type in the autocomplete box to select a location. Once selected, a map will display the location.

= Where can I see all check-ins? =
The activity dropdown menu has a filter option to show all check-ins, whether you're using BuddyPress or BuddyBoss.

== Screenshots ==

1. screenshot-1 -

2. screenshot-2 -

3. screenshot-3 -

== Changelog ==
= 2.3.0 =
* Fix: Addressed PHPCS errors and notices across multiple components.
* Enhancement: Removed unused polyfills folder and redundant code for optimization.
* Fix: Auto-select for maps address now works with BuddyBoss.
* Fix: Resolved issue with the check-in tab showing only the current address on BuddyBoss.
* Fix: Sub-menu issue when clicking on our themes has been corrected.
* Fix: Fatal error resolved when the xProfile component is disabled.
* Update: Improved string handling in activity location updates.
* Enhancement: Updated conditions and replaced PHP constants with sanitize or wp_unslash.
* Security: Group map nonce verification added, and security improvements applied to prevent vulnerabilities.
* Optimization: Scripts and styles now load only on relevant pages for better performance.
* Enhancement: Added error fallback for more robust error handling.

= 2.2.0 =
* Fix: Resolved issues with the Location field for more accurate data entry.
* Fix: Addressed PHP warnings when deleting places for smoother functionality.
* Fix: Various warning fixes to improve system stability.
* Enhancement: Improved backend options with responsive design fixes for better usability on all devices.
* Update: Applied language fixes for improved localization and clarity.

= 2.1.1 =
* Fix: Resolved issue with check-ins xprofile field (#129).
* Fix: Addressed plugin activation redirect issue with WP v6.5.
* Update: Merged remote-tracking branch 'origin/2.1.1'.
* Fix: Eliminated a warning in the system (#128).
* Fix: Corrected a warning and a hook warning (#126, #127).

= 2.1.0 =
* Fix: Admin verify and not verify button style
* Fix: (#120) Fixed extra fields visible on registration 
* Fix: (#116) Fixed api verification always visible true
* Fix: (#119) Fixed added alert for secure origin 

= 2.0.0 =
* Fix: (#118)Fixed conflict with buddyboss platform photos
* Fix: Fixed add photo issue on member profile with buddyboss

= 1.9.9 =
* Fix: Managed notice UI
* Fix: (#113)fixed bb forums conflict

= 1.9.8 =
* Fix: (#115) Fixed group activity not deleted issue

= 1.9.7 =
* Fix: Updated Admin Wrapper

= 1.9.6 =
* Fix - Removed extra white space from welcome page
* Fix - Reduced setInterval timeout with bb platform
* Fix - (#112) Added RTL support
* Fix - (#110) Fixed Add to my location issue
* Fix - (#109) Fixed nonce verification message issue 
* Fix - Fixed phpcs issue

= 1.9.5 =
* Fix - #108 - BuddyPress activity post form submit issue
* Fix - phpcs issues

= 1.9.4 =
* Fix - Fixed phpcs issues
* Fix - Fixed plugin activation issue
* Fix - (#103) Managed '-at' language translation
* Fix - Managed 'Add a place' language translation

= 1.9.3 =
* Fix - (#102) Update checkin icon and fixes
* Fix - (#102) Managed UI with bb platform 1.8.6

= 1.9.2 =
* Fix - Hide Quotes section when click on Checkin icon
* Fix - Hide Polls section when click on Checkins icon
* Fix - Fixed - Delete Quotes and Polls activity when user click on Checkin Icon
* Fix - Managed embeded activity frontend UI
* Fix - Fixed Checkin Support for activity Embed
* Fix - Fixed check-in panel position
* Fix - Manage map icon with buddyboss
* Fix - (#97) Fixed Location Field calculation Issue in Profile completion widget

= 1.9.1 =
* Fix - (#93) Managed UI with kleo
* Fix - (#94) Update map icon font awesome to svg
* Fix - (#95) Update title content html structure and accordion UI

= 1.9.0 =
* Fix -(#84)Fixed unable to mention friend
* Fix - call js and css when newfeed widget element set

= 1.8.0 =
* Fix - Hide activate option when open another option
* Fix - #108 - Compatibility issue with Checkins

= 1.7.0 =
* Fix - Fixed PHP Notices and warnings
* Fix - #83) Fixed delete location is not working
* Fix - #81) Fixed distort the location UI on single user profile
* Fix - #77) Fixed check in icon not showing if youzer is not installed
* Fix - #79) Fixed xprofile field settings
* Fix - #77) Fixed default youzer setting issue
* Fix - #75) Fixed - Translation issue
* Enhancement- (#78) Changed tab slug as per tab name
* Enhancement- Update BuddyPress Tab's name as per settings

= 1.6.0 =
* Fix: PHPCS fixes
* Added: Youzer support

= 1.5.0 =
* Fix - Alingment issue with rtmedia

= 1.4.0 =
* Fix - Added plugin review notice.
* Fix - Hide Checking toggle to hide other button like Quotes, Polls

= 1.3.0 =

* Fix - updated settings
* Fix - Added condition for BuddyPress plugin already active
* Fix - Checkin listing will be displayed to logged in user at their profile only ( no public listing of checkins)

= 1.2.0 =

* Enhancement- Plugin backend settings ui enhancement.
* Enhancement- BP 4.3.0 compatibility.
* Fix- Google placetypes missing with nouveau #44.
* Fix- Add as my place fix #45.

= 1.1.0 =

* Enhancement- Multisite Support

= 1.0.8 =

* Enhancement- Code Quality Improviement with WPCS
* Fix - Tanslation Fixes

= 1.0.7 =

* Fix - UI Improvements
* Fix - Error with PHP 7.0+ version.

= 1.0.6 =

* Fix - Location fixes

= 1.0.5 =

* Fix - A New option autocomplete is added in check-ins plugin setting. Now you can check either autocomplete or place types options.
* Fix - If you check autocomplete, a autocomplete text box will be shown at the top of the page under textarea on member activity page where you can type and select any location from the list.
* Fix - All activity posts which have any place or location a google map will be shown below them to point that particular place.
* Enhancement - A new x-profile location field is added at BuddyPress profile page from where a user can set location.

= 1.0.4 =

* Fix - Dual File Fixes

= 1.0.3 =

* Fix - Location selection fixes

= 1.0.2 =

* Fix - Fixed Map Linking in activity for specific location

= 1.0.1 =

* Fix - Improved documentation and default Place type selection option

= 1.0.0 =

* Fix - Initial release.
