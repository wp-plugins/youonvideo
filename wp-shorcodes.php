<?php

/**
*  YouOnVideoShortcodes
*/
class YouOnVideoShortcodes 
{
	
	public static function youonvideo_yoplayer_func($youonvideo_atts) {

		extract( shortcode_atts( array(
			'youonvideo_width' => '500',
			'youonvideo_height' => '300',
			'sid' => 'sid'
			), $youonvideo_atts ) );
		
		return '<iframe src="//vc.youongroup.com/p/'.esc_html($sid).'" width="'.$youonvideo_width.'" height="'.$youonvideo_height.'" scrolling="no" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0"></iframe>';
	}
}
