<?phpclass Yikes_Inc_Easy_Mailchimp_Extender_Uninstaller {	public static function uninstall() {		/* Clear All Transient Data */			// Delete list data transient data			delete_transient( 'yikes-easy-mailchimp-list-data' );			// Delete list account data transient data			delete_transient( 'yikes-easy-mailchimp-account-data' );			// Delete profile data transient data			delete_transient( 'yikes-easy-mailchimp-profile-data' );			// Delete account activity transient data			delete_transient( 'yikes-easy-mailchimp-account-activity' );		/* Clear All Plugin Options */			delete_option( 'yikes-mc-api-key' );			delete_option( 'yikes-mc-api-validation' );			delete_option( 'yikes-mc-debug' );			delete_option( 'yikes-mc-double-optin-message' );			delete_option( 'yikes-mc-flavor' );			delete_option( 'yikes-mc-lists' );			delete_option( 'yikes-mc-optin' );			delete_option( 'yikes-mc-optIn-checkbox' );			delete_option( 'yikes-mc-recaptcha-api-key' );			delete_option( 'yikes-mc-recaptcha-private-api-key' );			delete_option( 'yikes-mc-recaptcha-setting' );			delete_option( 'yikes-mc-single-optin-message' );			delete_option( 'yikes-mc-yks-mailchimp-jquery-datepicker' );			delete_option( 'yikes-mc-yks-mailchimp-optin-checkbox-text' );			delete_option( 'yikes-mc-yks-mailchimp-optIn-default-list' );			delete_option( 'yikes-mc-yks-mailchimp-required-text' );			delete_option( 'yikes-mc-single-optin-message' );			delete_option( 'yikes-mc-api-invalid-key-response' );			delete_option( 'yikes-mc-recaptcha-status' );			delete_option( 'yikes-mc-recaptcha-site-key' );			delete_option( 'yikes-mc-recaptcha-secret-key' );			delete_option( 'yikes-mc-error-messages' );		/* Clean up and delete our custom table from the databse */			global $wpdb;			$table = $wpdb->prefix."yikes_easy_mc_forms";			 //Delete any options thats stored also?			$wpdb->query("DROP TABLE IF EXISTS $table");	}}?>