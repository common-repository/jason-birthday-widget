=== Jason Birthday Widget ===
Contributors: angelorum
Tags: birthday, widget, users, wordpress, incoming, upcoming, ical
Requires at least: 3.0.1
Tested up to: 4.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a widget to displays user's birthdays of today, and next and past birthdays. 
Exports user birthdays to iCal format. Customizable.
You can choose between three widget templates to show in your site.
Fast and lightweight plugin.

== Description ==

Creates a widget to display user's birthdays of today, and next and past birthdays. Responsive and customizable.

Features:
- This plugin adds a field in users profile for the user can enter his birthday date, from a calendar jquery popup.
- Creates a widget to be added to the site. This widget displays to the users who are in birthday today.
- The widget also allows display the next users who will be in birthday, and the past users too. You can choose the amount of users birthday to be shown.
- Widget customizable: amount of past and next birthdays to show, show or hide users avatars..
- Exports user birthdays to iCal format.
- Translations: english and spanish.


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Jason Birthday Widget screen to configure the plugin



== Frequently Asked Questions ==

= How do I modify templates? =

Just go to the plugin's folder in your site (yoursite/wp-content/plugins/jason-birthday.
Find the directory called "templates". You'll see three files. Edit them as you want.

= How do I modify CSS templates? =

Just go to the plugin's folder in your site (yoursite/wp-content/plugins/jason-birthday.
Find the directory called "css". You'll see file "jason_bday_site.css". It contains all CSS of the three templates.
Edit it in the way you need.

= Where in the database are saved the user birthdays? =

Find in the database a table called {wordpress_prefix}jason_birthday_table.
Also, each birthday is saved as a user metadata called 'bday_user'. 

= How do I add translations? =

Go to the folder "languajes" and use the template languaje file "jason-birthday-es_ES.pot" to create your own translation.


== Screenshots ==

1. Widget configuration (in Appearance/widgets).
2. List user birthdays (in administrator menu).
3. You can export user birthdays to a iCalendar .ics file (in administrator menu).
4. Plugin front end with Template 1
5. Plugin front end with Template 2
6. Plugin front end with Template 3

== Changelog ==

= 1.0 =
* First version.
* The plugin was born.


== Upgrade Notice ==

= 1.0 =
Nothing to upgrade by now.


== Useful links ==

* Oficial website: http://codificando.cl/web/?page_id=92
* Developer's email: jason.matamala@gmail.com


