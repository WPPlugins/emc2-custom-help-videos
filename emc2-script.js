// JavaScript Document
var $j = jQuery.noConflict();
$j(document).ready(function(){

	var vsel1 = $j('#videos').children('option:selected').attr('name');
	var theight1 = $j('#tips').children('.tip[title="'+vsel1+'"]').height();
	console.log(theight1);
	if(theight1 > 0){ $j('#tips').animate({height: parseInt(theight1)+20+'px', opacity:1}, 500, 'swing'); } else{ $j('#tips').animate({height: '0px', opacity:0}, 500, 'swing');}

	// DASHBOARD JPLAYER: Load first video from #vidlist, set title
	var vid2 = $j('#videos').find('option:nth-child(2)').val();
	var name2 = $j('#videos').find('option:nth-child(2)').html();
	
	$j('#jquery_jplayer_1').jPlayer({
		ready: function () {
			$j(this).jPlayer("setMedia", {
				m4v: vid2+".mp4",
				ogv: vid2+".ogv",
				webm: vid2+".webm"
			});
		},
		swfPath: "/wp-content/plugins/emc2-help/jplayer",
		supplied: "webmv, ogv, m4v",
		size: {
			width: "320px",
			height: "180px",
			cssClass: "jp-video-180p"
		}//,
		//errorAlerts:true,
		//warningAlerts:true
	});




	$j('#videos').change(function(index){
		
		// Adjust video
	   $j('#jquery_jplayer_1').jPlayer('setMedia', {
			m4v: $j('#videos option:selected').val()+".mp4",
			ogv: $j('#videos option:selected').val()+".ogv",
			webm: $j('#videos option:selected').val()+".webm"
	   });
		
		// Update title and play
		$j('.jp-title').find('li:first').html($j(this).children('option:selected').html());
		$j('#jquery_jplayer_1').jPlayer('play');
		
		// Bring up the notes!
		var vsel = $j(this).children('option:selected').attr('name');
		$j('#tips').children('.tip').fadeOut(250);
		$j('#tips').children('.tip[title="'+vsel+'"]').fadeIn(500);
		var theight = $j('#tips').children('.tip[title="'+vsel+'"]').height();
		if(theight > 0){ $j('#tips').animate({height: parseInt(theight)+20+'px', opacity:1}, 500, 'swing'); } else{ $j('#tips').animate({height: '0px', opacity:0}, 500, 'swing');}
					
	});
	
	// Admin Page Content Dropdown
	$j('.vspan').click(function(){
		$j(this).parent().parent().children('.vcont').slideToggle(100, 'swing');
	});
	

	// ADMIN PAGE JPLAYER: Load first video from #vidlist, set title
	var vid1 = $j('#vidlist').find('.video:first').find('.testbutton').attr('rel');
	var name1 = $j('#vidlist').find('.video:first').find('.vspan').html();
	//$j('.jp-title').find('li:first').html(name1);
	$j('#vid-title').html(name1);

	$j('#jquery_jplayer_2').jPlayer({
		ready: function () {
			$j(this).jPlayer("setMedia", {
				m4v: vid1+".mp4",
				ogv: vid1+".ogv",
				webm: vid1+".webm"
				//poster: "http://emc2innovation.com/tutorial/Intro%201%20-%20What%20is%20WordPress.png"
			});
		},
		swfPath: "/wp-content/plugins/emc2-help/jplayer",
		supplied: "webmv, ogv, m4v",
		size: {
			width: "640px",
			height: "360px",
			cssClass: "jp-video-360p"
		},
	});

	// Change video on 'test' button click
	$j('.testbutton').click(function(index){
	   $j('#jquery_jplayer_2').jPlayer('setMedia', {
			m4v: $j(this).attr('rel')+".mp4",
			ogv: $j(this).attr('rel')+".ogv",
			webm: $j(this).attr('rel')+".webm"
	   });
	   
		$j('.jp-title').find('li:first').html($j(this).parent().children('.vspan').html());
		$j('#jquery_jplayer_2').jPlayer('play');					
	});

	
	// Select All videos button
	$j('#selectall').click(function(){
		console.log($j('input.chkenable'));
		if($j(this).hasClass('selected')){
			$j(this).removeClass('selected').val('Select All');
			$j('input.chkenable').prop('checked', false);
		} else {
			$j(this).addClass('selected').val('Deselect All');
			$j('.chkenable').prop('checked', true);	
		}
	});


}); // document ready

