<?php

/**
 * Process Ajax requests
 *
 *
 *
 */

class YouOnVideoAjax {

	public static function youonvideo_channels() {	

		$youonvideo_resource 		= "channels";
		$youonvideo_api_key 		= get_option('youonvideo_api_key');
		$youonvideo_account_token 	= get_option('youonvideo_account_token');
		
		header('Content-Type: application/json');
		echo YouOnVideoRest::youonvideo_fetch($youonvideo_resource,$youonvideo_api_key,$youonvideo_account_token);
		die();
	}

	public static function youonvideo_playlists() {

		$youonvideo_resource 		= "playlists";
		$youonvideo_channel_id      = "";
		if (isset($_GET['youonvideo_channel_id']) && (ctype_digit($_GET['youonvideo_channel_id']) || (is_int($_GET['youonvideo_channel_id']) && $_GET['youonvideo_channel_id']>-1 ))) {
			$youonvideo_channel_id  = sanitize_text_field($_GET['youonvideo_channel_id']);
		}
		$youonvideo_api_key 		= get_option('youonvideo_api_key');
		$youonvideo_account_token 	= get_option('youonvideo_account_token');
		
		header('Content-Type: application/json');
		echo YouOnVideoRest::youonvideo_fetch($youonvideo_resource,$youonvideo_api_key,$youonvideo_account_token,$youonvideo_channel_id);
		die();
	}

	public static function youonvideo_videos() {
		
		$youonvideo_resource 		= "videos/list";
		$youonvideo_channel_id      = "";
		if (isset($_GET['youonvideo_channel_id']) && (ctype_digit($_GET['youonvideo_channel_id']) || (is_int($_GET['youonvideo_channel_id']) && $_GET['youonvideo_channel_id']>-1))) {
			$youonvideo_channel_id  = sanitize_text_field($_GET['youonvideo_channel_id']);
		}
		$youonvideo_playlist_id     = "";
		if (isset($_GET['youonvideo_playlist_id']) && (ctype_digit($_GET['youonvideo_playlist_id']) || (is_int($_GET['youonvideo_playlist_id']) && $_GET['youonvideo_playlist_id']>-1))) {
			$youonvideo_playlist_id = sanitize_text_field($_GET['youonvideo_playlist_id']);
		}
		$youonvideo_api_key 		= get_option('youonvideo_api_key');
		$youonvideo_account_token 	= get_option('youonvideo_account_token');
		
		header('Content-Type: application/json');
		echo YouOnVideoRest::youonvideo_fetch($youonvideo_resource,$youonvideo_api_key,$youonvideo_account_token,$youonvideo_channel_id,$youonvideo_playlist_id);
		die();

	}

}