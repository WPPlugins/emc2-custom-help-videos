=== EMC2 Custom Help Videos ===
Contributors: emcniece
Donate link: http://emc2innovation.com/
Tags: video, help, dashboard, client, developer
Requires at least: 2.0.2
Tested up to: 3.3.1
Stable tag: 1.2

EMC2 CHV allows developers to place their own tutorial videos on the dashboard for their clients. Train clients while you sleep!

== Description ==

Are you a developer that trains your clients how to use your sites? Maybe you're looking for a way to remind yourself or your employees how to navigate posts and tags! With EMC2 Custom Help Videos, you can easily add a video player to your dashboard.

EMC2 CHV parses an external web page for video files and records them inside the local WordPress installation. This means that developers can host all of their videos in a directory on their local server and update a single location when new videos come out - all of your plugins looking at _your_ video directory will be automatically updated!

The CHV settings page allows developers to select the desired videos (your clients may have different needs) and attach notes to each video - the notes display on the dashboard as the user changes videos. 

EMC2 CHV uses jPlayer (http://jplayer.org) to display videos in an HTML5/flash-fallback format.

WHAT THIS PLUGIN IS NOT:
This plugin does _not_ have videos to go along with it! It's your job to make or find videos and place them on your server. I highly recommend taking a look at http://www.wp101.com/, but it's up to you how you get your videos and where you host them.

To Do list:

*   Serialize emc2_options setting
*   Check boxes to disable dashboard meta boxes
*   Adjustable jPlayer sizes
*   Additional dashboard widget and jPlayer themes
*   Theme-side widgets
*   Shortcode support
*   Remove theme-side script enqueue until theme-side widget and shortcode are written
*   Videos enabled/disabled by default
*   Optional enqueue of jPlayer (in case jPlayer is already present)
*   Clear current video list when server URL is changed

Known bugs:

*   Debug mode does not enable/disable right away - refresh page a second time to check

== Installation ==

Installation is straighforward; configuration requires extra steps!

1. Upload the `/emc2-custom-help-videos/` folder to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure settings at `wp-admin/options-general.php?page=emc2-help/emc2-admin.php`

= Configuration =

EMC2 CHV requires a server to pull videos from. This is the developer's responsibility to set up! The plugin will search the specified directory for videos and will display them accordingly on your WP installation.

1. Upload your videos in 3 HTML5-compatible formats: .mp4, .webm, .ogv and make sure your server displays a list of the videos when you browse to that directory
1. (OPTIONAL) Upload an index.php handler to your video server directory (see FAQ and Config)
1. Enter that directory in the EMC2 CHV settings page and save settings!

== Frequently Asked Questions ==

= I need to test this out. Got any videos I can use? =

Sure. I'm a big fan of WP101 Videos (http://www.wp101.com) and highly recommend signing up for their serivce! They also have their own WordPress widget and I'm sure it's 100x better than this one :)

*   http://emc2innovation.com/emc2chv for an `index.php` formatted list, or
*   http://emc2innovation.com/emc2chv/default for a default Apache output

= Yeah, so, I don't want people looking at my Apache system page... =

See next question's answer.

= My browser gives me a 404 page at my server video directory! =

This might be because you only have files listed and no actual content. Try making an `index.php` file and get it to print the contents of your directory. The `index.php` might look like this:

`<?php 
	$files = scandir('.');
	
	foreach($files as $file){
		if(strpos($file, '.mp4') || strpos($file, '.ogv') || strpos($file, '.webm'))
			echo 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].rawurlencode($file)."\n";	
	}
?>`

This will output code as seen at the http://emc2innovation.com/emc2chv demo directory.

= How exactly do you attach notes? =

Once you have videos showing, click on the name of the video to expand the dropdown.

= I changed my server URL but the old videos still show! =

Yeah, I'm workin' on it... for now, clear the settings using the "Delete Variables" button and start over.

= I'm submitting a bug report or a forum post, what should I include? = 

I'll be asking right away for your plugin and jPlayer version numbers. You can find them at the very bottom of the settings page where the usual WordPress message is!

== Screenshots ==

1. Dashboard view with themed widget.
2. Settings page with demo server and first video expanded. 

== Changelog ==

= 1.2 =
* Added install hook
* Set default video directory, checkbox, and note
* Initialized emc2_videos setting
* Removed style that affected other WP text
* Added plugin settings
* Added ability to disable select dashboard widgets
* Moved video list update into function to prepare for widget and shortcode operation
* Added "Select All" button to videos list

= 1.1 =
* Raw Apache directory output handling added

= 1.0 =
* First post! People are downloading this already? Please upgrade...


== Upgrade Notice ==

= 1.1 =
Directory handling has now been improved!


== Configuration ==

EMC2 CHV requires a server to pull videos from. This is the developer's responsibility to set up! The plugin will search the specified directory for videos and will display them accordingly on your WP installation.

1. Upload your videos in 3 HTML5-compatible formats: .mp4, .webm, .ogv and make sure your server displays a list of the videos when you browse to that directory
1. (OPTIONAL) Upload an index.php handler to your video server directory (see FAQ and Config)
1. Enter that directory in the EMC2 CHV settings page and save settings!

