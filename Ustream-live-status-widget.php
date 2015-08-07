<?php

/*
Plugin Name: Ustream Live Status Widget
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A widget to show your live status from ustream and a video preview.
Version: 1.0
Author: lion2486
Author URI: http://codescar.eu

*/

class UstreamLiveStatusConfig{
	public $UID = array( "1111", "2222");

	public $CacheInterval = 60;             //in seconds

	public $dev_key       = "";

	public $JsInitialTimeout    = 5000;
	public $JsMAXtimeout        = 60000;

	public $OnlineImg   = "";
	public $OnlineMsg   = "";
	public $OnlineSlug  = "";

	public $OfflineImg  = "";
	public $OfflineMsg  = "";
	public $OfflineSlug = "";

	public $VideoPreview    = true;
	public $VideoHeight     = "130";    //in pixels
	public $VideoWidth      = "220";    //in pixels

};

