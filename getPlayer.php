<?php
/**
 * Author: lion2486
 * Date: 7/8/2015
 * Website: http://codescar.eu
 */
function get_player($UID, $WIDTH = 480, $HEIGHT = 302, $AUTOPLAY = "false"){
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

if(isset($_GET['getPlayer']) && !empty($_GET['getPlayer'])){
	echo get_player($_GET['getPlayer'],
		isset($_GET['width']) ? $_GET['width'] : 480,
		isset($_GET['height']) ? $_GET['height'] : 302,
		isset($_GET['autoplay']) ? $_GET['autoplay'] : 'false'
	);
}
?>