<?php

/**
 * Performs REST requests to the Youon API
 *
 *
 *
 */

class YouOnVideoRest {


	private static $youonvideo_rest_hostname 	= YOUONVIDEO_REST_HOSTNAME;
	private static $youonvideo_rest_prefix 	= YOUONVIDEO_REST_PREFIX;
	private static $youonvideo_rest_version 	= YOUONVIDEO_REST_VERSION;
	

	public function __construct() {
		
	}


	public static function youonvideo_fetch($youonvideo_resource,$youonvideo_apikey,$youonvideo_account_token,$youonvideo_channel_id=null,$youonvideo_playlist_id=null) {

		$youonvideo_rest_endpoint 	= self::$youonvideo_rest_hostname . self::$youonvideo_rest_prefix . self::$youonvideo_rest_version;
		$youonvideo_channel_id     = $youonvideo_channel_id == null ? "" : "&channel_id=".$youonvideo_channel_id;
		$youonvideo_playlist_id    = $youonvideo_playlist_id == null ? "" : "&playlist_id=".$youonvideo_playlist_id;
		$youonvideo_url 			= $youonvideo_rest_endpoint.$youonvideo_resource."?apikey=".$youonvideo_apikey."&token=".$youonvideo_account_token.$youonvideo_channel_id.$youonvideo_playlist_id;
		
		$youonvideo_ch = curl_init();
		curl_setopt($youonvideo_ch, CURLOPT_URL, $youonvideo_url);
		curl_setopt($youonvideo_ch, CURLOPT_RETURNTRANSFER, 1);
		$youonvideo_contents = curl_exec($youonvideo_ch);
		curl_close($youonvideo_ch);

		return $youonvideo_contents;
	}	
}
