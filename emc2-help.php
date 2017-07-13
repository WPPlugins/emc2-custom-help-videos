<?php
/*
Plugin Name: EMC2 Custom Video Help 
Plugin URI: http://emc2innovation.com/
Description: WordPress help videos, now available from the dashboard!
Version: 1.2
Author: Eric McNiece
Author URI: http://emc2innovation.com
License: GPL

*/

include('emc2-admin.php');

wp_enqueue_style('emc2-help', plugin_dir_url(__FILE__) . 'emc2-style.css');
wp_enqueue_style('blue-monday', plugin_dir_url(__FILE__). 'jplayer/skin/blue.monday/jplayer.blue.monday.css');

wp_enqueue_script('jplayer', plugin_dir_url(__FILE__) . '/jplayer/jquery.jplayer.min.js');
wp_enqueue_script('emc2-help', plugin_dir_url(__FILE__) . '/emc2-script.js');


// Plugin installer!
register_activation_hook(__FILE__,'emc2help_install');


function emc2help_install () {

	$note = '<h3>Welcome to your new wordpress site!</h3>
			<p>Select a video from the menu above, and it will automatically play. 
			Any associated notes for the video will appear down here.</p>
			<p>Happy blogging!</p>';

	update_option('emc2_server', 'http://emc2innovation.com/emc2chv/');
	get_videos(false, TRUE, 'on', $note);

}


// Run when plugin inits (each page view)
add_action('init','emc2help_functions');
function emc2help_functions()
{
	//echo "Hello World";
}

/* *********************************************************************************
	Clean up that messy dashboard!
********************************************************************************** */
function disable_default_dashboard_widgets() {
	
	if(get_option('emc2_opt_right_now')) remove_meta_box('dashboard_right_now', 'dashboard', 'core');
	if(get_option('emc2_opt_recent_comments'))remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
	if(get_option('emc2_opt_incoming_links')) remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	if(get_option('emc2_opt_plugins')) remove_meta_box('dashboard_plugins', 'dashboard', 'core');

	if(get_option('emc2_opt_quick_press')) remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	if(get_option('emc2_opt_recent_drafts')) remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
	if(get_option('emc2_opt_primary')) remove_meta_box('dashboard_primary', 'dashboard', 'core');
	if(get_option('emc2_opt_secondary')) remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	
}
add_action('admin_menu', 'disable_default_dashboard_widgets');

/* *********************************************************************************
	Add widget to dashboard
********************************************************************************** */
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('emc2-help_widget', 'Theme Help', 'custom_dashboard_help');
}

function custom_dashboard_help() {
	
	// Grab video list!
	$serial = unserialize(get_option('emc2_videos'));
			if(get_option('emc2_opt_debug_mode')) echo '<pre>Post-Save Process: '.print_r($serial, true).'</pre>';

	// Test to make sure some videos are checked
	foreach($serial as $video){ if($video['checked']) $checked = 1;}
	
	// Did we find any videos?
	if((!empty($serial)) && $checked){
	// Output html!
?>

<div id="jp_container_1" class="jp-video jp-video-180p">
    <div class="jp-type-single">
        <div id="jquery_jplayer_1" class="jp-jplayer"></div>
        <div class="jp-gui">
            <div class="jp-video-play">
                <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
            </div>
            <div class="jp-interface">
                <div class="jp-progress">
                    <div class="jp-seek-bar">
                        <div class="jp-play-bar"></div>
                    </div>
                </div>
                <div class="jp-current-time"></div>
                <div class="jp-duration"></div>
                <div class="jp-controls-holder">
                    <ul class="jp-controls">
                        <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                        <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                        <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                        <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                        <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                        <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
                    </ul>
                    <div class="jp-volume-bar">
                        <div class="jp-volume-bar-value"></div>
                    </div>
                    <ul class="jp-toggles">
                        <li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="full screen">full screen</a></li>
                        <li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="restore screen">restore screen</a></li>
                        <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                        <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                    </ul>
                </div>
                <div class="jp-title">
                    <ul>
                        <li id="vid-title">title</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>
<div id="emc2-content">
    <select id="videos" name="videos">
        <option value="">Select a video</option>
        <?php foreach($serial as $id => $video){
			if($video['checked']){
				if(!$i++) $sel = 'selected="selected"'; else $sel='';
				echo '<option name="'.$id.'" value="'.$video['file'].'" '.$sel.'>';
				echo $video['name'];
				echo '</option>';
			}
        } ?>    
    </select>
    <div id="tips">
    <?
        foreach($serial as $id => $video){
			if($video['checked']) echo '<div title="'.$id.'" class="tip">'.$video['note'].'</div>'; 
        }	
    ?>
    </div><!-- emc2-content -->
</div>
<?php
	} // Yeah, we found videos!
	else{ // Nope, no vids
		echo '<h2 class="emc2">No videos found.</h2><h3 class="emc2"> Check the settings page for more info!</h3>';
	}
?>
<div style="clear:both;"></div>
<?                

}

	







