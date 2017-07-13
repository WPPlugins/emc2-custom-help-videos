<?php

/* *********************************************************************************
	EMC2 Custom WP Help - Add Options Page
********************************************************************************** */



add_action('admin_menu', 'emc2_help_page');

function emc2_help_page() {
	add_options_page("EMC2 WP Help Video", "EMC2 WP Help Videos",1, (__FILE__), "emc2_help_admin");
}

// Output settings page!
function emc2_help_admin() {
	$DEBUG = 0;	// change to anything to enable debugging
	if(get_option('emc2_opt_debug_mode')) $DEBUG = 1;
	
	echo '<form id="emc2_form" method="post" action="'.str_replace( '%7E', '~', $_SERVER['REQUEST_URI']).'">';
	
	// Delete variables if requested
	if($_POST['emc2_clearvar']){
		delete_option('emc2_videos'); 
		delete_option('emc2_server'); 
		unset($_POST['emc2_clearvar']); // make sure this doesn't happen more than once at a time
		echo '<div id="emc2-msg" class="warn">Variables Cleared.</div>';
	}

	
	if($DEBUG) print 'POST URL: '.$_POST['emc2_server'].'<br />';
	if($DEBUG) print 'Stored URL: '.get_option('emc2_server');	
	if($DEBUG) echo '<pre>Pre-Save Process: '.print_r(unserialize(get_option('emc2_videos')), true).'</pre>';
	
	
	// Update options on save
	if(($_POST['emc2_hidden']) && (get_option('emc2_videos'))){
		update_option('emc2_server', $_POST['emc2_server']);
		$narray = get_videos($_POST['emc2_serv'], true);
		$serial = $narray[2];

		// update video statuses
		foreach($serial as &$video){
			$video['checked'] = '';
			foreach($_POST as $setting => $val){
				if('txt_'.$video['id'] == $setting){ $video['note'] = $val; }
				if('chk_'.$video['id'] == $setting){ $video['checked'] = $val;}
			} // foreach
		} // foreach
		
		if($DEBUG) echo '<pre>Post-Save Process: '.print_r($serial, true).'</pre>';
		
		// Update plugin settings
		$arrOptions = array();
		// SET ALL DAMN CHECKBOXES TO OFF
			update_option('emc2_opt_right_now', '');
			update_option('emc2_opt_recent_comments', '');
			update_option('emc2_opt_incoming_links', '');
			update_option('emc2_opt_plugins', '');
		
			update_option('emc2_opt_quick_press', '');
			update_option('emc2_opt_recent_drafts', '');
			update_option('emc2_opt_primary', '');
			update_option('emc2_opt_secondary', '');
		
			update_option('emc2_opt_debug_mode', '');

		foreach($_POST as $option => $val){
			//echo $option; strpos(
			if(strpos((string)$option, '_opt')){
				update_option($option, $val);
				//echo $option.' | '.$val.'<br />';
			}
		} // foreach
		
		// Do individual updates
		update_option('emc2_videos', serialize($serial));
		if($DEBUG) echo '<pre>Post-Update Process: '.print_r($serial, true).'</pre>';
		echo '<div id="emc2-msg" class="ok">Settings Updated!</div>';
		
	} // if $_POST

	$narray = get_videos(false, false);
	$names = $narray[0];
	$arr = $narray[1];
	if(!$serial) $serial = $narray[2];
	
	if($DEBUG) echo '<pre>Post-Prep Process: '.print_r($serial, true).'</pre>';

	
	// Begin Output!
	echo '<h1>EMC2 Custom WordPress Help Videos</h1><h2><em style="color:#666;">Settings Page</em></h2><br />';
	// display plugin options
	get_emc2_settings();
	
// Print jPlayer!
if($names){
?>
<div id="jp_container_1" class="jp-video jp-video-360p">
    <div class="jp-type-single">
        <div id="jquery_jplayer_2" class="jp-jplayer"></div>
        <div class="jp-gui">
            <div class="jp-video-play">
                <a href="javascript:;" class="jp-video-play-icon threesix" tabindex="1">play</a>
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
                        <li id="vid-title"></li>
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
<?php
} // if $names
// Below: js for option delete confirmation
?>
<script LANGUAGE="JavaScript">
	<!--
	function confirmPost(){
		var agree=confirm("You're about to delete these variables - Are you sure you want to continue?");
		if (agree) return true ;
		else return false ;
	}
	// -->
</script>
<?
	echo '<div id="vidlist">';
	
	echo '<h3>Video Source (Server):</h3>';
	echo '<input size="80" type="text" id="txt_server" name="emc2_server" value="'.get_option('emc2_server').'" /><input type="submit" value="Update Options" />';

	if(empty($arr)){
		  echo '<h3>No Videos Found!</h3>';	
	} else {
	
		// Display available videos and associated comments
		echo '<h3>Available Videos:</h3>';
		echo '<input id="selectall" type="button" value="Select All" />';
		
		foreach($serial as $file){
			echo '<div class="video">';
	
			// Add a 'checked' status to the right boxes
			if($file['checked']) $checked = 'checked="checked"'; else $checked='';
					
			echo '<div class="vname"><input class="chkenable" type="checkbox" name="chk_'.$file['id'].'" title="Enable Video" '.$checked.'><span class="vspan">'.$file['name'].'</span>
				<input rel="'.$file['file'].'" class="testbutton" type="button" value="Play" /></div>';
			echo '<div class="vcont">';
			echo '<h2>Notes:</h2>';
			the_editor($file['note'], 'txt_'.$file['id'], 'title', $media_buttons = false);
			echo '</div>';
			
			echo '</div><!-- video -->';
		} // foreach
	} // if videos exist
	
		echo '<input type="hidden" name="emc2_hidden" value="submit" />';
		echo '<input type="submit" value="Update Options" /><input type="submit" name="emc2_clearvar" value="Delete Variables" onClick="return confirmPost()" />';
	echo '</div><!-- vidlist -->';
	
	if($DEBUG){
		echo '<pre>POST: '.print_r($_POST, true).'</pre>';
		echo '<pre>GET: '.print_r($_GET, true).'</pre>';
		echo '<pre>Videos: '.print_r($names, true).'</pre>';
		echo '<pre>Pre-Serialize: '.print_r($serial, true).'</pre>';
		//echo serialize($serial);

	} // debug
	
	// Final Options Update
	if($_POST['emc2_hidden']){
		update_option('emc2_videos', serialize($serial));
	} // update
	
	// Add some nice footer credits :)
	add_filter('admin_footer_text', 'remove_footer_admin');
	echo '</form>';
} // emc2_help_admin


function remove_footer_admin () {
    echo 'EMC2 CHV v1.2 | <a target="_blank" href="http://jplayer.org/">jPlayer</a> v2.1.0 |  EMC2 Custom Video Help Plugin  | Designed by <a target="_blank" href="http://emc2innovation.com">Eric McNiece</a></p>';
}

    
/* *********************************************************************************
	Update Video List 
********************************************************************************** */
function get_videos($queryString, $update=FALSE, $checkbox='', $defnote=FALSE){
	
	// Get video list from central server
	if(!$queryString) $queryString = get_option('emc2_server'); // need trailing slash
	if(substr($queryString, -1) != '/') update_option('emc2_server', $queryString.= '/');
	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, get_option('emc2_server'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	$output = curl_exec($ch); 
	curl_close($ch); 
	
	preg_match_all('/http:\/\/(.*?)\.(mp4|webm|ogv)/', $output, $arr);	// Contains full file list!
	$arr = $arr[0]; // bump array up a level

	if(empty($arr)){
		// Try looking for file links instead
		preg_match_all('/href="(.*?)\.(mp4|webm|ogv)/', $output, $arr);	// Contains full file list!
		$arr = $arr[0]; // bump array up a level
		foreach($arr as &$video){
			$video = str_replace('href="', get_option('emc2_server'), $video);
		} // foreach
	} // File links alternative matching
	
	if($DEBUG) echo '<pre>Preg Match Array: '.print_r($arr, true).'</pre>';

	//$arr = array_filter($arr);		// clear empty lines
	$names = preg_replace('/\.([^\.]+)$/', '', $arr);
	//echo '<pre>'.print_r($names, true).'</pre>';

	$names = array_unique($names);								// Contains unique names
	natsort($names);
	
	// Load each video into pre-serialized array for distribution across the site
	$serial = array();
	$i=0;
	foreach($names as $file){
		$slpos = strrpos($file, '/');
		$name = substr(str_replace('%20', ' ',$file), $slpos+1);
		$id  = str_replace(array(' ', '.'), '_', $name);

		if((get_option('emc2_chk_'.$id)) || ($checkbox)) $checked = 'checked="checked"'; else $checked='';	// default checkbox action
		if((!$i++) && $defnote) $note = $defnote; else $note = get_option('emc2_txt_'.$id);					// default note action


		// Load values into $serial array for WP options storage		
		$serial[] = array(
			'id' 	=> $id,
			'name' 	=> $name,
			'file'	=> $file,
			'note'	=> $note,
			'checked'	=> $checked
		); 
		

	} // foreach $names

	// Update WP option if flag is set
	if($update) update_option('emc2_videos', serialize($serial));
	
	return(array($names, $arr, $serial));
	
}
	
	
/* *********************************************************************************
	Get Admin Settings
********************************************************************************** */
function get_emc2_settings(){
	// Print out the settings form!

	if(get_option('emc2_opt_right_now')) $emc2_opt_right_now = 'checked="checked"';
	if(get_option('emc2_opt_recent_comments')) $emc2_opt_recent_comments = 'checked="checked"';
	if(get_option('emc2_opt_incoming_links')) $emc2_opt_incoming_links = 'checked="checked"';
	if(get_option('emc2_opt_plugins')) $emc2_opt_plugins = 'checked="checked"';

	if(get_option('emc2_opt_quick_press')) $emc2_opt_quick_press = 'checked="checked"';
	if(get_option('emc2_opt_recent_drafts')) $emc2_opt_recent_drafts = 'checked="checked"';
	if(get_option('emc2_opt_primary')) $emc2_opt_primary = 'checked="checked"';
	if(get_option('emc2_opt_secondary')) $emc2_opt_secondary = 'checked="checked"';

	if(get_option('emc2_opt_debug_mode')) $emc2_opt_debug_mode = 'checked="checked"';

?>	
<div class="video">

			
	<div class="vname"><span class="vspan">Plugin Settings ▼</span></div>
    <div id="psettings" class="vcont">
    	<h2>Plugin Settings:</h2>
        <h4>Disable these dashboard widgets:</h4>
        <div class="emc2-left">
            <label><input class="widget" type="checkbox" name="emc2_opt_right_now" <?php echo $emc2_opt_right_now; ?>/>Right Now Widget</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_recent_comments" <?php echo $emc2_opt_recent_comments; ?> />Recent Comments</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_incoming_links" <?php echo $emc2_opt_incoming_links; ?> />Incoming Links</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_plugins" <?php echo $emc2_opt_plugins; ?> />Plugins</label><br />
		</div>
        <div class="emc2-left">
            <label><input class="widget" type="checkbox" name="emc2_opt_quick_press" <?php echo $emc2_opt_quick_press; ?> />Quick Press</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_recent_drafts" <?php echo $emc2_opt_recent_drafts; ?> />Recent Drafts</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_primary" <?php echo $emc2_opt_primary; ?> />Primary Widgets</label><br />
            <label><input class="widget" type="checkbox" name="emc2_opt_secondary" <?php echo $emc2_opt_secondary; ?> />Secondary Widgets</label><br />
    	</div>
        <div style="clear:both;"></div>
        <h4>Plugin Options:</h4>
        <div class="emc2-left">
            <label><input type="checkbox" name="emc2_opt_debug_mode" <?php echo $emc2_opt_debug_mode; ?> />Enable Debug Mode</label><br />
        </div>
        <div style="clear:both;"></div>
    </div>
</div><!-- video -->
	
<?php
} // get_emc2_settings
	
	
	
	
	