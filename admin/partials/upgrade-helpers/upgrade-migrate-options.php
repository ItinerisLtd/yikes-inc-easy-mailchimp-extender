<?php
		
	/* 
	* 	Helper file to migrate our options from previous version to the proper WordPress settings API
	*	Helper File , called inside class-yikes-inc-easy-mailchimp-extender-admin.php ( migrate_old_yks_mc_options() )
	*	@since v5.4
	* 	@Author: Yikes Inc. 
	*	@Contact: http://www.yikesinc.com/
	*/
		
	// enqueue the styles for our migration page..
	wp_enqueue_style( 'yikes_mc_migrate_option_styles' , YIKES_MC_URL . 'admin/css/yikes-inc-easy-mailchimp-migrate-option-styles.css' );
	wp_enqueue_style( 'animate-css' , '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.5/animate.min.css' );
	
	// store our old options
	$old_plugin_options = get_option( 'ykseme_storage' );
	
	$global_error_messages = array(
		'success' => __( $old_plugin_options['single-optin-message'] , $this->text_domain ),
		'general-error' => __( "Whoops! It looks like something went wrong. Please try again." , $this->text_domain ),
		'invalid-email' => __( "Please provide a valid email address." , $this->text_domain ),
		'email-exists-error' => __( "The provided email is already subscribed to this list." , $this->text_domain )
	);
	
	// if old options are defined...
	if( $old_plugin_options ) {
		
		// Verify the NONCE is valid
		check_admin_referer( 'yikes-mc-migrate-options' , 'migrate_options_nonce' );
		
		?>
			
		<div class="wrap">
			<h3><?php _e( 'Migrating old plugin options' , $this->text_domain ); ?><span class="upgrading-ellipse-one">.</span><span class="upgrading-ellipse-two">.</span><span class="upgrading-ellipse-three">.</h3>
			<p><?php _e( 'please be patient while your options are updated and this process completes' , $this->text_domain ); ?></p>
			<!-- empty list, populate when options  get updated -->
			<ul id="options-updated" style="display:none;">
				<hr />
			</ul>
		</div>
				
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				<?php
					// loop over our old options, and store them in a new option value
					foreach( $old_plugin_options as $option_name => $option_value ) {
						// ajax request to update our options one by one..
						// if its an array, we need to json encode it
						if( is_array( $option_value ) ) {
							$option_value = json_encode( $option_value ); 
						}
						?>
							var data = {
								'action': 'migrate_old_plugin_settings',
								'option_name': '<?php echo $option_name; ?>',
								'option_value': '<?php echo $option_value; ?>'
							};
							
							$.post( ajaxurl, data, function(response) {
								jQuery( '#options-updated' ).show();
								jQuery( '#options-updated' ).append( '<li class="animated fadeInDown"><?php echo '<strong>' . ucwords( str_replace( '_' , ' ' , str_replace( '-' , ' ' , $option_name ) ) ) . '</strong> ' . __( "successfully imported." , $this->text_domain ); ?></li>' );	
								// count the length of our settings array,
								// once we hit 17, lets redirectem
								if( jQuery( '#options-updated' ).children( 'li' ).length == 17 ) {
									// finished with the loop...lets let the user know....and then redirect them....
									jQuery( '.wrap' ).find( 'h3' ).text( 'Optons Successfuly Imported' );
									jQuery( '.upgrading-ellipse-one' ).remove();
									jQuery( '.upgrading-ellipse-two' ).remove();
									jQuery( '.upgrading-ellipse-three' ).remove();
									jQuery( '.wrap' ).find( 'h3' ).next().fadeOut();
									jQuery( '#options-updated' ).append( '<li class="animated fadeInDown" style="margin-top:2em;font-size:18px;font-weight:200;"><em><?php _e( "Migration Complete. Please wait..." , $this->text_domain ); ?> </em> <img src="<?php echo admin_url( "images/wpspin_light.gif" ); ?>" /></li>' );
									// redirect our user to the main plugin page...
									setTimeout( function() {
										<?php 
											// migrate options that didnt make it (they were never stored in the 'ykseme_storage' options array)
											add_option( 'yikes-mc-api-validation' , get_option( 'api_validation' , 'invalid_api_key' ) );
											add_option( 'yikes-mc-error-messages' , $global_error_messages );
											
											// delete our old options after a successful migration
											// delete_option( 'ykseme_storage' ); 
											// delete_option( 'api_validation' );
											// delete_option( 'yikes-mc-ssl_verify_peer' );
										?>
										window.location.replace( "<?php echo admin_url( 'admin.php?page=yikes-inc-easy-mailchimp' ); ?>" );
									}, 3600);
								}
							});
					<?php
					}
				?>		
			});
		</script>
			
		<?php
		// delete the options after the import, as we no longer need them
		// delete_option( 'ykseme_storage' );
		// else, die and redirect the user to the main admin page
	} else {
		wp_die( _e( 'Old plugin options do not exist...' , $this->text_domain ) );
		wp_redirect( admin_url() , 301 );
	}