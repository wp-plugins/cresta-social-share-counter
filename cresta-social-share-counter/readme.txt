=== Cresta Social Share Counter ===
Contributors: CrestaProject
Donate link: http://crestaproject.com/downloads/cresta-social-share-counter/
Tags: social share, social buttons, facebook, twitter, linkedin, pinterest, google plus, floating buttons, social count, social counter
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Share your posts and pages quickly and easily with Cresta Social Share Count showing the share count.

== Description ==

With Cresta Social Share Counter you can share your posts and pages easily and show the social count.

<strong>You can select the following Social Network</strong>
<ul>
	<li>1. Facebook</li>
	<li>2. Twitter</li>
	<li>3. Google Plus</li>
	<li>4. Linkedin</li>
	<li>5. Pinterest</li>
</ul>

<strong>Some features</strong>
<ul>
	<li>Show Social Counter</li>
	<li>Choose up to 5 buttons styles</li>
	<li>Fade animation</li>
	<li>Show the floating social buttons</li>
	<li>Show the social buttons before/after the post or page content</li>
	<li>Use the shortcode <strong>[cresta-social-share]</strong> wherever you want to display the social buttons</li>
</ul>

<p>
<strong>Plugin Homepage</strong><br />
http://crestaproject.com/downloads/cresta-social-share-counter/
</p>

== Installation ==

1. Upload the folder 'cresta-social-share-counter' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Wordpress Main Menu -> CSSC FREE to set the options


== Frequently Asked Questions ==

= Can I choose which pages to display social buttons? =
Yes, in the options page you can choose which pages to display social buttons (home page, pages, posts and archive).

= The shortcode [cresta-social-share] doesn't work in a widget =
Most likely the theme you're using has not enabled the use of shortcodes within text-widget.<br/>
Try to add this snippet in your theme’s functions.php:
`
add_filter('widget_text', 'do_shortcode');
`

= Can I hide the floating social buttons and only show the buttons in content? =
Yes, the floating social buttons can be hidden (from version 1.1).

== Screenshots ==

1. Cresta Social Share Counter Settins Page 1
2. Cresta Social Share Counter Settins Page 2
3. Floating social buttons with social count
4. Social buttons before/after posts/page content

== Changelog ==

= 1.1 =
* Added the option to disable floating buttons
* Minor bug fixes

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
This is the first version of the plugin