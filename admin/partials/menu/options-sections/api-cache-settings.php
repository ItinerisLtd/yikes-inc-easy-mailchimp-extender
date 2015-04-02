<!--
	MailChimp API Clear Stored Cache Template
-->
<h3><span><?php _e( 'API Cache Settings' , $this->text_domain ); ?></span></h3>
<div class="inside">
									
	<!-- Settings Form -->
	<form action="<?php echo add_query_arg( array( 'action' => 'yikes-easy-mc-clear-transient-data' , 'nonce' => wp_create_nonce( 'clear-mc-transient-data' ) ) ); ?>" method="post">							
									
		<p class="description"><?php _e( "Delete all MailChimp data stored in your sites cache. Most data is stored in the cache for 1 hour." , $this->text_domain ); ?></p>
			<!-- check if any of our transients contain data -->							
			<?php if ( false === get_transient( 'yikes-easy-mailchimp-list-data' ) && false === get_transient( 'yikes-easy-mailchimp-profile-data' ) && false === get_transient( 'yikes-easy-mailchimp-account-data' ) ) { ?>
				<p><a href="#" class="button-secondary" disabled="disabled" title="<?php _e( 'No MailChimp data found in temporary cache storage.' , $this->text_domain ); ?>"><?php _e( 'Clear MailChimp API Cache' , $this->text_domain ); ?></a></p>
			<?php } else { ?>
				<p><input type="submit" class="button-primary" value="<?php _e( 'Clear MailChimp API Cache' , $this->text_domain ); ?>" /></p>
			<?php } ?>
									
	</form>

</div> <!-- .inside -->