<?php
/**
 * Author: lion2486
 * Date: 7/8/2015
 * Website: http://codescar.eu
 */
global $UID;
$UID[0] = XXXXXXXX;
$UID[1] = XXXXXXX;

require_once('getPlayer.php');

if($_REQUEST['get_status'] == 1){
	//die("true");
	if($r = is_broadcast_live($UID[0])){
		die(json_encode($r));
	}else{
		die(json_encode(is_broadcast_live($UID[1])));
	}
}

function is_broadcast_live($UID){
	//global $UID;
	$dev_key = "XXXXXXXXXXXX";
	
	$call_url = "http://api.ustream.tv/php/channel/$UID/getValueOf/status?key=$dev_key";
		
	
	$CacheFile = "StatusFile".$UID.".cache";
	$CacheInterval = 60;

	if(file_exists($CacheFile) && (time() - filemtime($CacheFile) < $CacheInterval )){
		//we can use local cache file
		$resultsArray = unserialize(file_get_contents($CacheFile));
	}else{
	
		// Get and config the curl session object
		$session = curl_init($call_url);
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		//execute the request and close
		$response = curl_exec($session);
		curl_close($session);
		
		// this line works because we requested $format='php' and not some other output format
		$resultsArray = unserialize($response);
		
		@unlink($CacheFile);
		file_put_contents($CacheFile, $response, LOCK_EX);
	
	}

    // this is your data returned; you could do something more useful here than just echo it
	//print_r( $resultsArray);

	return ($resultsArray['results'] == "live") ? $UID : false;
}

	?>
	
	<script type="text/javascript">
		var timeout = 5000;
		var MAXtimeout = 60000;
		var UID;
		
		function video_pop(){
			window.open("<?php echo plugin_dir_path( __FILE__ ); ?>player.php?player=yes&UID="+UID,"TITLE","width=800,height=600"); //TODO add title
		}
		$().ready(function(){
			checkLiveVideoStatus();
		});

		var online = false;

		function checkLiveVideoStatus(){

			$.get("<?php echo plugin_dir_path( __FILE__ ); ?>is_broadcast_live.php?get_status=1", function(data){
				
				if(data != "false"){
				//Status is active!
					if(!online) {
						$("#LiveButton img").attr("src", "<?php echo plugin_dir_path( __FILE__ ); ?>online.png");
						$("#LiveButton img").attr("alt", "Διαθέσιμο");
						$("#LiveButton img").attr("title", "Διαθέσιμη Ζωντανή μετάδοση");
						$("#LiveButton span").text("Διαθέσιμο");
						$("#LiveButton").attr("title", "Διαθέσιμη Ζωντανή μετάδοση");
						UID = data;

						$.get("<?php echo plugin_dir_path( __FILE__ ); ?>getPlayer.php?width=220&height=130&autoplay=true&getPlayer=" + UID, function (data) {
							$("#VideoPreview").html(data);
							$("#VideoPreview").show('slow');
						});
					}
					online = true;
				}else {
					if (online) {

						$("#LiveButton img").attr("src", "<?php echo plugin_dir_path( __FILE__ ); ?>offline.png");
						$("#LiveButton img").attr("alt", "Μη Διαθέσιμο");
						$("#LiveButton img").attr("title", "Ζωντανή μετάδοση μη διαθέσιμη");
						$("#LiveButton span").text("Μη Διαθέσιμο");
						$("#LiveButton").attr("title", "Ζωντανή μετάδοση μη διαθέσιμη");
						$("#VideoPreview").hide('slow');
					}
					online = false;
				}
					
				if(timeout < MAXtimeout)
					timeout += 100;
				setTimeout(checkLiveVideoStatus, timeout);
			});
		
		}
	</script>
	<div class="box type1" style="border:1px solid #a7adb0;">
		<h2 class="header-gradient"><span><?php _e("ttitle", "UstreamLiveStatus"); ?> </span></h2>
		<div style="display:none;" id="VideoPreview"></div>
    	<p>txttttdfdasa.</p>
		<button onclick='video_pop();' id="LiveButton" style="width: 140px; height: 35px; margin: 4px 40px 4px 40px;" title="Ζωντανή μετάδοση μη διαθέσιμη">
			<img src="/wp-content/themes/empappa/Controllers/live_broadcast/offline.png" style="height: 26px; vertical-align: middle;" alt="Μη Διαθέσιμο" title="Ζωντανή μετάδοση μη διαθέσιμη" /><span>Μη Διαθέσιμο</span>
		</button>
		<?php 
			include_once get_template_directory() . '/Controllers/HomeController.php';
			$next_event = HomeController::getNextEvent(1); 

			$event_date = get_the_date("d/m/Y H:i",$next_event->ID );//get_post_meta($next_event->ID, 'post_date');	

		?>
	</div>
<?php 


