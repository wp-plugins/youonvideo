var YouOnVideoModule = (function(YouOnVideoModule) {

	function YouOnVideoModule() {
	}

	YouOnVideoModule.prototype = {

		fetch : function(endpoint, payload, type) {

			var self = this;
			var url = window.location.protocol + "//" + window.location.hostname + "/" + endpoint;

			jQuery('.youonvideo.media-chooser-wrapper').addClass('is-loading');
			jQuery('.youonvideo.media-chooser-wrapper .footer a').attr('disabled','disabled');
			jQuery('.youonvideo.media-chooser-wrapper .right-col ul').empty();

			return jQuery.ajax({
				type : type,
				url : url,
				dataType : "JSON",
				data : payload,
				statusCode : {
					204 : function(){
					}
				}
			});
		}
	}

	return YouOnVideoModule;

})(YouOnVideoModule || {}); 

YouOnVideoModule.Channels = (function() {

	return {
		populate : function() {

			var self = this;
			var m = new YouOnVideoModule();   

			m.fetch('wp-admin/admin-ajax.php?action=youonvideo_channels',{},'get')
			.done(function(data, textStatus, jqXHR){

				jQuery('.youonvideo.media-chooser-wrapper').removeClass('is-loading');

       	 		// 1. Populate the channels combo box
	       	 	var options = "";
	       	 	var channel = null;
	       	 	for (var i = 0 ; i <= data.result.data.length - 1 ; i ++ ) {
	       	 		channel = data.result.data[i];
	       	 		options += '<option value="'+encodeURIComponent(channel.channel_id)+'">'+encodeURIComponent(channel.channel_name)+'</option>';
	       	 	};

	       	 	jQuery('.youonvideo.media-chooser-wrapper select[name=channels]')
	       	 	.empty()
	       	 	.html(options);

	       	 	m = null;

				// Bind an event on change
				self.on_change();

				// Fetch the first playlist
				YouOnVideoModule.channel_id = data.result.data[0].channel_id;
				YouOnVideoModule
				.Playlists
				.populate(YouOnVideoModule.channel_id);
			});	
		},
		on_change : function() {
			jQuery('.youonvideo.media-chooser-wrapper select[name=channels]').die();
			jQuery('.youonvideo.media-chooser-wrapper select[name=channels]').on('change',function(event){
				YouOnVideoModule.channel_id = jQuery(this).val();
				// Fetch playlists of channel_id
				YouOnVideoModule
				.Playlists
				.populate(YouOnVideoModule.channel_id);

			});
		}
	}
})();


YouOnVideoModule.Playlists = (function() {

	return {
		populate : function(channel_id) {

			var self = this;
			var m = new YouOnVideoModule();   

			m.fetch('wp-admin/admin-ajax.php?action=youonvideo_playlists',{youonvideo_channel_id:channel_id},'get')
			.done(function(data, textStatus, jqXHR){

				jQuery('.youonvideo.media-chooser-wrapper').removeClass('is-loading');
				jQuery('.youonvideo.media-chooser-wrapper').removeClass('videos-not-found');

				if( data.result.code === 404 ) {
					jQuery('.youonvideo.media-chooser-wrapper').addClass('playlists-not-found');					

				} else {
					jQuery('.youonvideo.media-chooser-wrapper').removeClass('playlists-not-found');

	           	 	// 1. Populate the playlists combo box
	           	 	var options = "";
	           	 	var playlist = null;
	           	 	for (var i = 0 ; i <= data.result.data.length - 1 ; i ++ ) {
	           	 		playlist = data.result.data[i];
	           	 		options += '<option value="'+encodeURIComponent(playlist.id_video_playlist)+'">'+encodeURIComponent(playlist.name)+'</option>';
	           	 	};

	           	 	jQuery('.youonvideo.media-chooser-wrapper select[name=playlists]')
	           	 	.empty()
	           	 	.html(options);

	           	 	m = null;

					// Bind an event on change
					self.on_change();

					// Fetch the first playlist videos
					YouOnVideoModule.playlist_id = data.result.data[0].id_video_playlist;
					YouOnVideoModule
					.Videos
					.populate(YouOnVideoModule.channel_id, YouOnVideoModule.playlist_id);
				}        	 	

			});	
		},
		on_change : function() {

			jQuery('.youonvideo.media-chooser-wrapper select[name=playlists]').die();
			jQuery('.youonvideo.media-chooser-wrapper select[name=playlists]').on('change',function(event){

				YouOnVideoModule.playlist_id = jQuery(this).val();

				// Populate the videos
				YouOnVideoModule
				.Videos
				.populate(YouOnVideoModule.channel_id,YouOnVideoModule.playlist_id);
			});
		}
	}
})();

YouOnVideoModule.Videos = (function() {

	return {

		populate : function(channel_id,playlist_id) {

			var self = this;
			var m = new YouOnVideoModule();   

			m.fetch('wp-admin/admin-ajax.php?action=youonvideo_videos',{youonvideo_channel_id:channel_id,youonvideo_playlist_id:playlist_id},'get')
			.done(function(data, textStatus, jqXHR){

				jQuery('.youonvideo.media-chooser-wrapper').removeClass('is-loading');

				if( data !== null ) {

					jQuery('.youonvideo.media-chooser-wrapper .right-col ul').empty();
					jQuery('.youonvideo.media-chooser-wrapper').removeClass('videos-not-found');

					var template = _.template( jQuery('#youonvideo-video-template').html() );
					var video = "";
					for (var i = 0 ; i <= data.result.data.videos.length - 1 ; i ++ ) {
						video += template(data.result.data.videos[i]);
					};

					jQuery('.youonvideo.media-chooser-wrapper .right-col ul').append(video);

					/*Bind an event on change*/
					self.on_click();

				} else {
					jQuery('.youonvideo.media-chooser-wrapper').addClass('videos-not-found');
				}
			});	
		},
		on_click : function() {

			jQuery('.youonvideo.media-chooser-wrapper .profile-item').die();
			jQuery('.youonvideo.media-chooser-wrapper .profile-item').on('click',function(event){

				if( YouOnVideoModule.video ) {

					YouOnVideoModule.video.$el.removeClass('selected');
					YouOnVideoModule.video = null;
					jQuery('.youonvideo.media-chooser-wrapper .footer a').attr('disabled','disabled');

				}
				jQuery(this).addClass('selected');
				YouOnVideoModule.video = {$el:jQuery(this),video_id:jQuery(this).attr('data-id'),sid:jQuery(this).attr('data-sid')};
				jQuery('.youonvideo.media-chooser-wrapper .footer a').removeAttr('disabled');

				event.stopPropagation();

			});

			jQuery('html,body').on('click',function(event){

				if( YouOnVideoModule.video ) {
					YouOnVideoModule.video.$el.removeClass('selected');
					YouOnVideoModule.video = null;
					jQuery('.youonvideo.media-chooser-wrapper .footer a').attr('disabled','disabled');
				}

			});

			jQuery('.youonvideo.media-chooser-wrapper .footer a').die();
			jQuery('.youonvideo.media-chooser-wrapper .footer a').on('click',function(event){

				if( YouOnVideoModule.video ) {

					var str = '[youonvideoplayer sid="'+YouOnVideoModule.video.sid+'"]';
					parent.window.send_to_editor(str);
					YouOnVideoModule.video.$el.removeClass('selected');
					YouOnVideoModule.video = null;
					jQuery('.youonvideo.media-chooser-wrapper .footer a').attr('disabled','disabled');
				}


				return false;

			});	
		}
	}

})();