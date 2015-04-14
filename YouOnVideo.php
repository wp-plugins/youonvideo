<?php
/* 
Plugin Name: YouOnVideo
Plugin URI: http://video.youongroup.com/
Description: A plugin that inserts YouOnVideo videos into your WordPress site.
Version: 1.0
Author: YouOnVideo
Author URI: http://video.youongroup.com/
License: GPLv2 or later
*/

/*
Copyright 2014  Bruno Gomes  (email : bruno.mike.gomes@gmail.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

include('wp-rest-config.php');
include('wp-rest-client.php');
include('wp-ajax-server.php');
include('wp-shorcodes.php');

/**
* Print icon for top level menu item of this plugin
*
*/
function youonvideo_display_menu_icon(){
	print '<style type="text/css">';
	print '#toplevel_page_youonvideo-plugin-wp-plugin-settings a div img {';
	print ' padding-top: 6px !important;';
	print '}';
	print '</style>';
}
/*
* Add function  to the admin_head hook
*
*/
add_action( 'admin_head', 'youonvideo_display_menu_icon' );

add_action("widgets_init",
	function () { register_widget("YouOnVideo"); });

add_action( 'admin_menu', 'youonvideo_register_my_custom_menu_page' );
add_action( 'admin_init','youonvideo_resources');

add_action( 'wp_ajax_youonvideo_channels', 'YouOnVideoAjax::youonvideo_channels' );
add_action( 'wp_ajax_youonvideo_videos', 'YouOnVideoAjax::youonvideo_videos' );
add_action( 'wp_ajax_youonvideo_playlists', 'YouOnVideoAjax::youonvideo_playlists' );

add_shortcode( 'youonvideoplayer', 'YouOnVideoShortcodes::youonvideo_yoplayer_func' );

add_filter('media_upload_tabs', 'youonvideo_tab',10,1);
add_action( 'media_upload_youonvideo_videos', 'youonvideo_iframe'); // Creating the page

// 1. Load some resources
function youonvideo_resources() {
	wp_register_style('youonvideo_css', plugins_url('resources/css/index.css',__FILE__ ));
	wp_enqueue_style('youonvideo_css');
	wp_register_script( 'youonvideo_js', plugins_url('resources/js/index.js',__FILE__ ));
	wp_enqueue_script('youonvideo_js');	
	wp_register_script( 'youonvideo_underscore', plugins_url('resources/js/underscore.js',__FILE__ ));
	wp_enqueue_script('youonvideo_underscore');	
}

// 2. Register a custom menu entry
function youonvideo_register_my_custom_menu_page() {
	$page_title 	 = "YouOnVideo";
	$menu_title 	 = "YouOnVideo";
	$menu_slug  	 = "youonvideo-plugin/wp-plugin-settings.php";
	$page_path_func  =  function(){ include("views/wp-plugin-settings.php"); };
	$capability 	 = "manage_options";
	$icon_url		 = plugin_dir_url( __FILE__ ) . "resources/images/icon.png";
	$position    	 = 6;
	
	add_menu_page( $page_title, 
		$menu_title, 
		$capability, 
		$menu_slug, 
		$page_path_func, 
		$icon_url, 
		$position);
}


// 3. Add a new tab on adding media in the pot ( LeftSide )

function youonvideo_tab($tabs) {
	
	/* name of custom tab */
	$new_tab = array('youonvideo_videos'=>'YouOnVideo');
	return array_merge($tabs, $new_tab);
}


function youonvideo_create_youonvideos_page() {

	media_upload_header();
	wp_enqueue_style( 'media' );

	include("views/wp-media-chooser.php");
}



function youonvideo_iframe() {
	return wp_iframe( 'youonvideo_create_youonvideos_page');
}



class YouOnVideo extends WP_Widget
{
	public function __construct() {
		parent::__construct("youon_widget", "YouOnVideo",
			array("description" => "A plugin that inserts YouOnVideo videos into your WordPress site."));

		self::youonvideo_check_wordpress_version();
	}
	
	public static function youonvideo_check_wordpress_version() {
		
		global $wp_version;

		if (version_compare($wp_version,"2.6","<"))
		{
			// do something if WordPress version is lower then 2.6
			$youonvideo_exit_msg='YouOnVideo requires WordPress 2.6 or newer. <a 
			href="http://codex.wordpress.org/Upgrading_WordPress">Please 
			update!</a>';
			exit ($youonvideo_exit_msg);
		}
	}
}
?>