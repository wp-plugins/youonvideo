<?php 

$youonvideo_error = false;
if( isset($_POST['youonvideo_oscimp_hidden']) && $_POST['youonvideo_oscimp_hidden'] == 'Y' && !empty($_POST['youonvideo_api_key']) && !empty($_POST['youonvideo_account_token']) ) {
	
	$youonvideo_api_key = sanitize_text_field($_POST['youonvideo_api_key']);
	update_option('youonvideo_api_key', $youonvideo_api_key);
	
	$youonvideo_account_token = sanitize_text_field($_POST['youonvideo_account_token']);
	update_option('youonvideo_account_token', $youonvideo_account_token);
	
	// 2. Check if valid credentials
	$youonvideo_res = json_decode(YouOnVideoRest::youonvideo_fetch("channels",$youonvideo_api_key,$youonvideo_account_token));			
	if( isset($youonvideo_res->errors) || (!isset($youonvideo_res)) ) {	// Invalid credentials
		
		$youonvideo_error = true;
		delete_option( 'youonvideo_api_key' );
		delete_option( 'youonvideo_account_token' );
		
	} else {
		
		$youonvideo_submited = true;
		
	}

	?>
	
	<?php 
	if( $youonvideo_submited ) { ?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
	<?php } else { ?>
	<div class="updated"><p><strong><?php _e('Options not saved.' ); ?></strong></p></div>
	<?php }
} else {

	$youonvideo_api_key = get_option('youonvideo_api_key');
	$youonvideo_account_token = get_option('youonvideo_account_token');
}
?>

<div class="youonvideo-wrap" id="youonvideo-wrap">
	<?php    echo "<h2>" . __( 'YouOnVideo', 'youonvideo_oscimp_trdom' ) . "</h2>"; ?>
	
	<form name="youonvideo_oscimp_form" method="post" action="">
		<input type="hidden" name="youonvideo_oscimp_hidden" value="Y">
		
		<?php 
		
		if( $youonvideo_error ) {
			echo "<h4>" . __( 'REST Settings - <span style="color:red">Invalid credentials</span>', 'youonvideo_oscimp_trdom' ) . "</h4>";  
		} else if( isset($youonvideo_submited) && $youonvideo_submited ) {
			echo "<h4>" . __( 'REST Settings - <span style="color:green">Your credentials are valid</span>', 'youonvideo_oscimp_trdom' ) . "</h4>";  
		} else {
			echo "<h4>" . __( 'REST Settings', 'youonvideo_oscimp_trdom' ) . "</h4>";  
		}
		?>
		
		
		<p>
			<label><?php _e("Api key " ); ?></label>
			<input type="text" name="youonvideo_api_key" value="<?php echo esc_html($youonvideo_api_key); ?>" size="45"><?php _e(" Your account api key" ); ?>
		</p>
		
		<p>
			<label><?php _e("Account token " ); ?></label>
			<input type="text" name="youonvideo_account_token" value="<?php echo esc_html($youonvideo_account_token); ?>" size="45">
			<?php _e(" Your account token " ); ?>
		</p>
		
		<p class="submit">
			<input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'youonvideo_oscimp_trdom' ) ?>" />
		</p>
		
	</form>
</div>