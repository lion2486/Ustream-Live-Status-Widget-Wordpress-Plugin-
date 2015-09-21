<?php

/*
Plugin Name: Ustream Live Status Widget
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A widget to show your live status from ustream and a video preview.
Version: 1.0
Author: lion2486
Author URI: http://codescar.eu

*/

class UstreamLiveStatusWidget extends WP_Widget {

	static $textDomain = "UstreamLiveStatus";
	// constructor
	public function UstreamLiveStatusWidget() {
		parent::WP_Widget('UstreamLiveStatusWidget', $name = __('Ustreal Live Status Widget', self::$textDomain ),
			array( 'description' => __( 'A widget to see the status of your live streaming in Ustream and embeded video player.', self::$textDomain ) )
		);
	}

	private function renderFormInput( $fieldName, $fieldLabel, $inputValue = "", $inputType = "text", $inputExtraAttr = "" ){
		?>
		<p>
			<label for="<?php echo $this->get_field_id( $fieldName ); ?>">
				<?php _e( $fieldLabel, self::$textDomain ); ?>
			</label>
			<input class="widefat"
			       id="<?php echo $this->get_field_id( $fieldName ); ?>"
			       name="<?php echo $this->get_field_name( $fieldName ); ?>"
			       type="<?php echo $inputType; ?>"
			       <?php echo $inputExtraAttr ? $inputExtraAttr : ""; ?>
			       value="<?php echo $inputValue; ?>" />
		</p>
		<?php
	}
	// widget form creation
	public function form($instance) {

		// Check values
		if( $instance ) {
			$instance['UID']               = esc_attr($instance['UID']);
			$instance['CacheInterval']     = esc_attr($instance['CacheInterval']);    //in seconds
			$instance['dev_key']           = esc_attr($instance['dev_key']);

			$instance['JsInitialTimeout']  = esc_attr($instance['JsInitialTimeout']);
			$instance['JsMAXtimeout']      = esc_attr($instance['JsMAXtimeout']);

			$instance['OnlineImg']         = esc_attr($instance['OnlineImg']);
			$instance['OnlineMsg']         = esc_attr($instance['OnlineMsg']);
			$instance['OnlineSlug']        = esc_attr($instance['OnlineSlug']);

			$instance['OfflineImg']        = esc_attr($instance['OfflineImg']);
			$instance['OfflineMsg']        = esc_attr($instance['OfflineMsg']);
			$instance['OfflineSlug']       = esc_attr($instance['OfflineSlug']);

			$instance['VideoPreview']      = esc_attr($instance['VideoPreview']);
			$instance['VideoHeight']       = esc_attr($instance['VideoHeight']);    //in pixels
			$instance['VideoWidth']        = esc_attr($instance['VideoWidth']);    //in pixels

			$instance['FullVideoPage']     = esc_attr($instance['FullVideoPage']);
			$instance['FullVideoWidth']    = esc_attr($instance['FullVideoWidth']);
			$instance['FullVideoHeight']   = esc_attr($instance['FullVideoHeight']);

			$instance['WidgetTitle']       = esc_attr($instance['WidgetTitle']);
			$instance['WidgetText']        = esc_attr($instance['WidgetText']);
		} else {
			$instance = array();
			$instance['UID']               = "";
			$instance['CacheInterval']     = 60;    //in seconds
			$instance['dev_key']           = "";

			$instance['JsInitialTimeout']  = 5000;
			$instance['JsMAXtimeout']      = 60000;

			$instance['OnlineImg']         = plugin_dir_url( __FILE__ ) . "online.png";
			$instance['OnlineMsg']         = __( 'Live Streaming is Available', self::$textDomain);
			$instance['OnlineSlug']        = __( 'Online', self::$textDomain);

			$instance['OfflineImg']        = plugin_dir_url( __FILE__ ) . "offline.png";
			$instance['OfflineMsg']        = __( 'Live Streaming is Unavailable', self::$textDomain);
			$instance['OfflineSlug']       = __( 'Offline', self::$textDomain);

			$instance['VideoPreview']      = true;
			$instance['VideoHeight']       = "130";    //in pixels
			$instance['VideoWidth']        = "220";    //in pixels

			$instance['FullVideoPage']     = true;
			$instance['FullVideoWidth']    = "480";
			$instance['FullVideoHeight']   = "302";

			$instance['WidgetTitle']       = "Your title here";
			$instance['WidgetText']        = "Your text here";
		}

		$this->renderFormInput( "WidgetTitle", "Widget Title", $instance['WidgetTitle']);
		$this->renderFormInput( "WidgetText", "Widget Text", $instance['WidgetText']);
		echo "<hr/>";

		$this->renderFormInput( "UID", "Ustream UID", $instance['UID']);
		$this->renderFormInput( "dev_key", "Ustream dev key", $instance['dev_key']);
		echo "<hr/>";

		$this->renderFormInput( "CacheInterval", "Status Cache Interval (in sec)", $instance['CacheInterval'], "number", "min='0' max='10000'");
		$this->renderFormInput( "JsInitialTimeout", "Initial refresh Interval(in ms)", $instance['JsInitialTimeout'], "number", "min='1000' max='10000'");
		$this->renderFormInput( "JsMAXtimeout", "Max refresh Interval(in ms)", $instance['JsMAXtimeout'], "number", "min='10000' max='1000000'");
		echo "<hr/>";

		$this->renderFormInput( "OnlineImg", "Online Image", $instance['OnlineImg']);
		$this->renderFormInput( "OnlineMsg", "Online Message", $instance['OnlineMsg']);
		$this->renderFormInput( "OnlineSlug", "Online Slug", $instance['OnlineSlug']);
		echo "<hr/>";

		$this->renderFormInput( "OfflineImg", "Offline Image", $instance['OfflineImg']);
		$this->renderFormInput( "OfflineMsg", "Offline Message", $instance['OfflineMsg']);
		$this->renderFormInput( "OfflineSlug", "Offline SLug", $instance['OfflineSlug']);
		echo "<hr/>";

		$this->renderFormInput( "VideoPreview", "Video Preview in Widget", "true", "checkbox", checked( $instance['VideoPreview'], 'true', false ) );
		$this->renderFormInput( "VideoHeight", "Video Preview Height(in px)", $instance['VideoHeight'], "number", "min=\"40\" max=\"1080\"");
		$this->renderFormInput( "VideoWidth", "Video Preview Width(in px)", $instance['VideoWidth'],  "number", "min=\"40\" max=\"1080\"");
		echo "<hr/>";

		$this->renderFormInput( "FullVideoPage", "Full Page Video Player", "true", "checkbox", checked( $instance['FullVideoPage'], 'true', false ) );
		$this->renderFormInput( "FullVideoHeight", "Full Video Player Height(in px)", $instance['FullVideoHeight'], "number", "min=\"40\" max=\"1080\"");
		$this->renderFormInput( "FullVideoWidth", "Full Video Player Width(in px)", $instance['FullVideoWidth'],  "number", "min=\"40\" max=\"1080\"");

	}

	// update widget
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['UID']               = strip_tags($new_instance['UID']);
		$instance['CacheInterval']     = strip_tags($new_instance['CacheInterval']);    //in seconds
		$instance['dev_key']           = strip_tags($new_instance['dev_key']);

		$instance['JsInitialTimeout']  = strip_tags($new_instance['JsInitialTimeout']);
		$instance['JsMAXtimeout']      = strip_tags($new_instance['JsMAXtimeout']);

		$instance['OnlineImg']         = strip_tags($new_instance['OnlineImg']);
		$instance['OnlineMsg']         = strip_tags($new_instance['OnlineMsg']);
		$instance['OnlineSlug']        = strip_tags($new_instance['OnlineSlug']);

		$instance['OfflineImg']        = strip_tags($new_instance['OfflineImg']);
		$instance['OfflineMsg']        = strip_tags($new_instance['OfflineMsg']);
		$instance['OfflineSlug']       = strip_tags($new_instance['OfflineSlug']);

		$instance['VideoPreview']      = strip_tags($new_instance['VideoPreview']);
		$instance['VideoHeight']       = strip_tags($new_instance['VideoHeight']);    //in pixels
		$instance['VideoWidth']        = strip_tags($new_instance['VideoWidth']);   //in pixels

		$instance['FullVideoPage']     = strip_tags($new_instance['FullVideoPage']);
		$instance['FullVideoWidth']    = strip_tags($new_instance['FullVideoWidth']);
		$instance['FullVideoHeight']   = strip_tags($new_instance['FullVideoHeight']);

		$instance['WidgetTitle']       = strip_tags($new_instance['WidgetTitle']);
		$instance['WidgetText']        = strip_tags($new_instance['WidgetText']);

		return $instance;
	}

	// display widget
	public function widget($args, $instance) {
		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['WidgetTitle']);
		$ajax_url = admin_url('admin-ajax.php');

		?>
			<script type="text/javascript">
				var timeout = <?php echo $instance['JsInitialTimeout']; ?>;
				var MAXtimeout = <?php echo $instance['JsMAXtimeout']; ?>;
				var UID = <?php echo $instance['UID']; ?>;

				<?php if( $instance['FullVideoPage'] ) : ?>
					function video_pop(){
						jQuery.ajax({
							url: '<?php echo $ajax_url; ?>',
							type: 'POST',
							data: {
								action: 'UstreamAjax',
								WID: '<?php echo $this->number; ?>',
								popOut: '1'
							},

							success: function (data) {
								var popOut = window.open( "", "<?php echo $instance['WidgetTitle']; ?>",
									"width=<?php echo $instance['FullVideoWidth']+50; ?>,height=<?php echo $instance['FullVideoHeight']+130; ?>" );
								popOut.document.write(data);
							}
						});

					}
				<?php endif; ?>
				jQuery().ready(function(){
					checkLiveVideoStatus();
				});

				var online = false;

				function checkLiveVideoStatus(){

					jQuery.ajax({
						url: '<?php echo $ajax_url; ?>',
						type: 'POST',
						data: {
							action : 'UstreamAjax',
							WID: '<?php echo $this->number; ?>',
							get_status: '1'
						},

						success: function(data){

							if(data != "false"){
								//Status is active!
								if(!online) {
									jQuery("#LiveButton img").attr("src", "<?php echo $instance['OnlineImg']; ?>");
									jQuery("#LiveButton img").attr("alt", "<?php echo $instance['OnlineMsg']; ?>");
									jQuery("#LiveButton img").attr("title", "<?php echo $instance['OnlineMsg']; ?>");
									jQuery("#LiveButton span").text("<?php $instance['OnlineSlug']; ?>");
									jQuery("#LiveButton").attr("title", "<?php echo $instance['OnlineMsg']; ?>");
									UID = data;

									<?php if( $instance['VideoPreview'] ) : ?>
										jQuery.ajax({
											url: '<?php echo $ajax_url; ?>',
											type: 'POST',
											data: {
												action: 'UstreamAjax',
												WID: '<?php echo $this->number; ?>',
												getPlayer: '1'
											},

											success: function (data) {
												jQuery("#VideoPreview").html(data);
												jQuery("#VideoPreview").show("slow");
											}
										});
									<?php endif; ?>
								}
								online = true;
							}else {
								if (online) {

									jQuery("#LiveButton img").attr("src", "<?php echo $instance['OfflineImg']; ?>");
									jQuery("#LiveButton img").attr("alt", "<?php echo $instance['OfflineMsg']; ?>");
									jQuery("#LiveButton img").attr("title", "<?php echo $instance['OfflineMsg']; ?>");
									jQuery("#LiveButton span").text("<?php echo $instance['OfflineSlug']; ?>");
									jQuery("#LiveButton").attr("title", "<?php echo $instance['OfflineMsg']; ?>");

									<?php if( $instance['VideoPreview'] ) : ?>
										jQuery("#VideoPreview").hide("slow");
									<?php endif; ?>
								}
								online = false;
							}

							if(timeout < MAXtimeout)
								timeout += 300;
							setTimeout(checkLiveVideoStatus, timeout);
						}
					});
				}
			</script>
		<?php

		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box UstreamLiveStatus">';

		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}

		 if( $instance['VideoPreview'] ) : ?>
			<div style="display:none;" id="VideoPreview"></div>
		<?php
			endif;
		if( $instance['WidgetText'] ) {
			echo '<p class="wp_widget_plugin_text">'.$instance['WidgetText'].'</p>';
		}
		?>
				<button <?php if( $instance['FullVideoPage'] ) { echo 'onclick="video_pop();"'; } ?> id="LiveButton" style="width: 140px; display: inherit; height: 35px; margin: 4px auto 4px auto;" title="<?php echo $instance['OfflineSlug']; ?>">
					<img src="<?php echo plugin_dir_url( __FILE__ ); ?>offline.png" style="height: 26px; vertical-align: middle;" alt="<?php echo $instance['OfflineMsg']; ?>" title="<?php echo $instance['OfflineMsg']; ?>" /><span><?php echo $instance['OfflineSlug']; ?></span>
				</button>
			</div>
		<?php


		echo $after_widget;
	}

	private static function is_broadcast_live( $UID, $dev_key, $CacheInterval = 60 ){


		$call_url = "http://api.ustream.tv/php/channel/$UID/getValueOf/status?key=$dev_key";

		$CacheFile = "StatusFile".$UID.".cache";

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

	private static function get_player($UID, $WIDTH = 480, $HEIGHT = 302, $AUTOPLAY = "false"){
		$player_object = '
				<object width="'. $WIDTH .'" height="'. $HEIGHT .'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
				<param name="flashvars" value="cid='.$UID.'&amp;autoplay='. $AUTOPLAY .'"/>
				<param name="allowfullscreen" value="true"/>
				<param name="allowscriptaccess" value="always"/>
				<param name="autoplay" value="'. $AUTOPLAY .'" />
				<param name="src" value="http://www.ustream.tv/flash/viewer.swf"/>
				<embed flashvars="cid='.$UID.'&amp;autoplay='. $AUTOPLAY .'" width="'. $WIDTH .'" height="'. $HEIGHT .'" allowfullscreen="true" allowscriptaccess="always" src="http://www.ustream.tv/flash/viewer.swf" type="application/x-shockwave-flash"></embed>
			</object>';
		return $player_object;
	}

	public static function getAjax(){
		if( isset( $_REQUEST['get_status'] )  && $_REQUEST['get_status'] == 1 ){
			$WID = intval( $_REQUEST['WID'] );

			$dummy = new UstreamLiveStatusWidget();
			$settings = $dummy->get_settings();
			if( !isset( $settings[ $WID ] ))
				wp_die( json_encode( false ) );

			$instance = $settings[$WID];

			wp_die( json_encode( self::is_broadcast_live( $instance['UID'], $instance['dev_key'], $instance['CacheInterval'] ) ) );
		}
		elseif( isset( $_REQUEST['getPlayer'] ) && !empty( $_REQUEST['getPlayer'] ) ){

			$WID = intval( $_REQUEST['WID'] );

			$dummy = new UstreamLiveStatusWidget();
			$settings = $dummy->get_settings();
			if( !isset( $settings[ $WID ] ))
				wp_die( json_encode( false ) );

			$instance = $settings[$WID];

			echo self::get_player( $instance['UID'], $instance['VideoWidth'], $instance['VideoHeight'], "true" );
		}
		elseif( isset( $_REQUEST['popOut'] ) ){
			$WID = intval( $_REQUEST['WID'] );

			$dummy = new UstreamLiveStatusWidget();
			$settings = $dummy->get_settings();
			if( !isset( $settings[ $WID ] ))
				wp_die( json_encode( false ) );

			$instance = $settings[$WID];
			?>
				<!doctype HTML>
				<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title><?php echo $instance['WidgetTitle']; ?></title>
				</head>
				<body>
					<h1><?php echo $instance['WidgetTitle']; ?></h1>
					<p><?php echo $instance['WidgetText']; ?></p>
					<?php echo self::get_player( $instance['UID'], $instance['FullVideoWidth'], $instance['FullVideoHeight'] ); ?>
				</body>
				</html>
			<?php

			wp_die();
		}
	}
}

// register widget
add_action( 'widgets_init', create_function( '', 'return register_widget( "UstreamLiveStatusWidget" ); ' ) );
add_action( 'wp_ajax_UstreamAjax', array( 'UstreamLiveStatusWidget', 'getAjax' ) );
add_action( 'wp_ajax_nopriv_UstreamAjax', array( 'UstreamLiveStatusWidget', 'getAjax' ) );
