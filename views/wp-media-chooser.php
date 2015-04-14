
<script>
YouOnVideoModule
.Channels
.populate();

</script>

<div id="youonvideo-media-chooser-wrapper" class="youonvideo media-chooser-wrapper playlists-not-found is-loading">
	
	<?php 
	
	$youonvideo_api_key = get_option('youonvideo_api_key');
	$youonvideo_account_token = get_option('youonvideo_account_token');
	
	if( !empty($youonvideo_api_key) && !empty($youonvideo_account_token) ) { ?>
	
	<div class="loading">
		<img width="22px" src="<?php echo dirname(plugin_dir_url( __FILE__ )) . "/resources/images/loader.gif"; ?>" alt="">
	</div>

	<div class="left-col">
		<div class="channels">
			<label>Channels</label>
			<select name="channels" ></select>
		</div>
		<div class="playlists">
			<label>Playlists</label>
			<select name="playlists" ></select>
		</div>
	</div>
	<div class="right-col">
		<div class="playlists-not-found">
			There is no playlists for this channel
		</div>
		<div class="videos-not-found">
			There is no videos in this playlist
		</div>

		<ul></ul>
	</div>

	<div style="clear:both"></div>

	<div class="footer">
		<a href="#" class="button media-button button-primary button-large media-button-select" disabled="disabled">Insert into post</a>
	</div>

	<script id="youonvideo-video-template" type="text/x-handlebars-template">
	<li>
	<div class="profile-item" data-sid="<%= share.sid %>" data-id="64">
	<div class="profile-thumb-container">
	<div class="profile-thumb-icon"><a class="bt-thumb-icon bt-icon-composethumb"></a></div>
	<img src="<%= video_thumb.path + video_thumb.thumb3 %>" width="90" height="58" border="none">
	<div class="video-duration"><%= duration %></div>
	</div>
	
	<div class="profile-title">
	<span class="ft ft-round state-online"></span>
	<p class="text-container">
	<span class="text1"><%= title %></span>
	<span class="slash" style="display:none"> / </span>
	<span class="text2" style="display:none"><%= title %></span>
	</p>
	</div>

	<div class="profile-count"><%= position %></div>
	
	<div style="display: none">
	<div class="set_thumbnail">0</div>
	<div class="content_type">0</div>
	<div class="id_ad_playlist"></div>
	<div class="id_video">1157</div>
	<div class="id_video_profile">449</div>
	<div class="position">1</div>
	<div class="state">5</div>
	<div class="video_duration">00:36:59</div>
	<div class="title">Seminário Vendas</div>
	<div class="video_thumbnail">5743c42a22c0c1c1189179c730694610</div>
	</div>
	
	<div style="display: none">
	<div class="dialogI"></div>
	<div class="dialogR"></div>
	</div>
	</div>
	</li>
	</script>
	
	<?php } else { ?>
	
	<div align="center" style="padding-top:25px">Your credentials are wrong. Please check your credentials.</div>
	
	<?php } ?>
</div>




