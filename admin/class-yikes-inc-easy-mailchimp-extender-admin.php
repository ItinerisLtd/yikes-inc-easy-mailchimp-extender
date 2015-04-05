<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.yikesinc.com/
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Easy_Mailchimp_Extender
 * @subpackage Yikes_Inc_Easy_Mailchimp_Extender/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yikes_Inc_Easy_Mailchimp_Extender
 * @subpackage Yikes_Inc_Easy_Mailchimp_Extender/admin
 * @author     YIKES Inc. <info@yikesinc.com>
 */
class Yikes_Inc_Easy_Mailchimp_Extender_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $yikes_inc_easy_mailchimp_extender    The ID of this plugin.
	 */
	private $yikes_inc_easy_mailchimp_extender;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Thetext domain of this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    Used for internationalization
	 */
	private $text_domain = 'yikes-inc-easy-mailchimp-extender';
	
			
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $yikes_inc_easy_mailchimp_extender       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $yikes_inc_easy_mailchimp_extender, $version ) {
	
		$this->yikes_inc_easy_mailchimp_extender = $yikes_inc_easy_mailchimp_extender;
		$this->version = $version;
		// check for old plugin options and migrate if exist
		add_action( 'admin_menu' , array( $this , 'register_admin_pages' ) , 11 );
		// check for old plugin options and migrate if exist
		add_action( 'admin_init' , array( $this , 'check_for_old_yks_mc_options' ) );
		// Ajax function to update new options...
		add_action( 'wp_ajax_migrate_old_plugin_settings', array( $this , 'migrate_archived_options' ) );
		// fix menu icon spacing
		add_action( 'admin_head' , array( $this , 'fix_menu_icon_spacing' ) );
		// register our plugin settings
		add_action( 'admin_init', array( $this , 'yikes_easy_mc_settings_init' ) );
		// Include our MailChimp API Wrapper
		include_once( YIKES_MC_PATH . 'includes/MailChimp/MailChimp.php' );
		// Include our ajax processing class
		include_once( YIKES_MC_PATH . 'admin/partials/ajax/class.ajax.php' );
		/***********************/
		/** Create A Form **/
		/**********************/
		if ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == 'yikes-easy-mc-create-form' ) {
			add_action( 'init' , array( $this , 'yikes_easy_mailchimp_create_form' ) );
		}
		/***********************/
		/** Delete A Form **/
		/**********************/
		if ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == 'yikes-easy-mc-delete-form' ) {
			add_action( 'init' , array( $this , 'yikes_easy_mailchimp_delete_form' ) );
		}
		/**********************************/
		/** Duplicate/Clone A Form **/
		/********************************/
		if ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == 'yikes-easy-mc-duplicate-form' ) {
			add_action( 'init' , array( $this , 'yikes_easy_mailchimp_duplicate_form' ) );
		}
		/**********************************/
		/** 	  Update A Form 		**/
		/********************************/
		if ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == 'yikes-easy-mc-update-form' ) {
			add_action( 'init' , array( $this , 'yikes_easy_mailchimp_update_form' ) );
		}
		/**************************************************/
		/** Clear Store MailChimp Transient Data **/
		/*************************************************/
		if ( isset( $_REQUEST[ 'action' ] ) && $_REQUEST[ 'action' ] == 'yikes-easy-mc-clear-transient-data' ) {
			add_action( 'init' , array( $this , 'yikes_easy_mailchimp_clear_transient_data' ) );
		}
	}
		
	/*
	*  Fix the MailChimp icon spacing in the admin menu
	*/
	public function fix_menu_icon_spacing() {
		?>
			<style>
			a[href="admin.php?page=yikes-inc-easy-mailchimp"] .wp-menu-image img {
				padding-top: 5px !important;
			}
			</style>
		<?php
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Easy_Mailchimp_Extender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Easy_Mailchimp_Extender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->yikes_inc_easy_mailchimp_extender, plugin_dir_url( __FILE__ ) . 'css/yikes-inc-easy-mailchimp-extender-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Easy_Mailchimp_Extender_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Easy_Mailchimp_Extender_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_register_script( $this->yikes_inc_easy_mailchimp_extender, plugin_dir_url( __FILE__ ) . 'js/yikes-inc-easy-mailchimp-extender-admin.js', array( 'jquery' , 'jquery-ui-sortable' ), $this->version, false );
		$localized_data = array(
			'admin_url' => admin_url(),
		);
		wp_localize_script( $this->yikes_inc_easy_mailchimp_extender , 'object_data' , $localized_data );
		wp_enqueue_script( $this->yikes_inc_easy_mailchimp_extender );
		
		/*
		*	Enqueue Trevor JS required files
		*	- drag + drop form builder functionality on the edit form page
		*/
		if( get_current_screen()->base == 'admin_page_yikes-mailchimp-edit-form' ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_register_script( 'edit-form-js' , YIKES_MC_URL . 'admin/js/yikes-mc-edit-form.js' , array( 'jquery' ) , $this->version, false );
			$localized_data = array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			);
			wp_localize_script( 'edit-form-js' , 'object' , $localized_data );
			wp_enqueue_script( 'edit-form-js' );
		}
		
	}
	
	
	
	/** Functionality **/
	/******************/
	
	/**
	*	Register our admin pages
	*	used to display data back to the user
	**/
	public function register_admin_pages() {	
				
		/* Top Level Menu 'Easy MailChimp' */
		add_menu_page( 
			__( 'Easy MailChimp' , $this->text_domain ), 
			'Easy MailChimp',
			apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
			'yikes-inc-easy-mailchimp', 
			'', // no callback,
			YIKES_MC_URL . 'includes/images/MailChimp_Assets/Freddie_wink_icon.png'
		);
		
		// Sub Pages
		/*************/
			
			/* Yikes Inc. Easy MailChimp Settings */
								
				/* Yikes Inc. Easy MailChimp Manage Forms */
				add_submenu_page(
					'yikes-inc-easy-mailchimp', 
					__( 'Forms' , $this->text_domain ), 
					__( 'Forms' , $this->text_domain ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-inc-easy-mailchimp', 
					array( &$this, 'generateManageFormsPage' )
				);
				
				/* Yikes Inc. Easy MailChimp Manage Lists */
				add_submenu_page(
					'yikes-inc-easy-mailchimp', 
					__( 'Lists' , $this->text_domain ), 
					__( 'Lists' , $this->text_domain ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-inc-easy-mailchimp-lists', 
					array( &$this, 'generateManageListsPage' )
				);
				
							
			/* Yikes Inc. Easy MailChimp Account Overview */
			if ( get_option('yikes-mc-api-validation') == 'valid_api_key' ) {	
				/* Yikes Inc. Easy MailChimp Settings */
				add_submenu_page(
					'yikes-inc-easy-mailchimp', 
					__( 'Account' , $this->text_domain ), 
					__( 'Account' , $this->text_domain ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-inc-easy-mailchimp-account-overview', 
					array( $this, 'generateAccountDetailsPage' )
				);
			}
		
			/* Yikes Inc. Easy MailChimp Pop Ups Settings */
				add_submenu_page(
					'yikes-inc-easy-mailchimp', 
					__( 'Pop Ups' , $this->text_domain ), 
					__( 'Pop Ups' , $this->text_domain ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-inc-easy-mailchimp-popups', 
					array( &$this, 'generatePopUpSettingsPage' )
				);
				
			/* Yikes Inc. Easy MailChimp Settings */
			add_submenu_page(
				'yikes-inc-easy-mailchimp', 
				__( 'Settings.' , $this->text_domain ), 
				__( 'Settings' , $this->text_domain ), 
				apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
				'yikes-inc-easy-mailchimp-settings', 
				array( $this, 'generatePageOptions' )
			);
			
			/* About the Yikes Inc. Team */
			add_submenu_page(
				'yikes-inc-easy-mailchimp', 
				__( 'Support' , $this->text_domain ), 
				__( 'Support' , $this->text_domain ), 
				apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
				'yikes-inc-easy-mailchimp-support', 
				array( $this, 'generateSupportPage' )
			);
			
			/* About the Yikes Inc. Team */
			add_submenu_page(
				'yikes-inc-easy-mailchimp', 
				__( 'About Yikes Inc.' , $this->text_domain ), 
				__( 'About Yikes Inc.' , $this->text_domain ), 
				apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
				'yikes-inc-easy-mailchimp-about-yikes', 
				array( $this, 'generatePageAboutYikes' )
			);
			
			/** Hidden Pages **/
			
				/* Add Hidden Edit Form Page */
				add_submenu_page(
					'options.php', 
					__( 'Edit Form' , 'yikes-inc-easy-mailchimp-extender' ), 
					__( 'Edit Form' , 'yikes-inc-easy-mailchimp-extender' ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-mailchimp-edit-form', 
					array( $this, 'generateEditFormPage' )
				);
				
				/* Add Hidden Migrate Options Page */
				add_submenu_page( 
					'options-writing.php', 
					__( 'Yikes Inc. Easy MailChimp Extender Upgrade Options Structure' , $this->text_domain ), 
					'Yikes Inc. Easy MailChimp Extender Upgrade Options Structure', 
					'manage_options', 
					'yikes-inc-easy-mailchimp-update' ,
					array( $this , 'migrate_old_yks_mc_options' )
				);
				
				/* Add Hidden Welcome Page */
				add_submenu_page(
					'options.php', 
					__( 'Welcome' , 'yikes-inc-easy-mailchimp-extender' ), 
					__( 'Welcome' , 'yikes-inc-easy-mailchimp-extender' ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-mailchimp-welcome', 
					array( $this, 'generateWelcomePage' )
				);
				
				/* Add Hidden Welcome Page */
				add_submenu_page(
					'options.php', 
					__( 'View List' , 'yikes-inc-easy-mailchimp-extender' ), 
					__( 'View List' , 'yikes-inc-easy-mailchimp-extender' ), 
					apply_filters( 'yks_mailchimp_user_role' , 'manage_options' ), 
					'yikes-mailchimp-view-list', 
					array( $this, 'generateViewListPage' )
				);
			
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Manage Forms Page
	* 
	* @since    1.0.0
	*/
	function generateManageFormsPage() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/manage-forms.php'; // include our manage forms page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Manage Pop Ups Page
	* 
	* @since    1.0.0
	*/
	function generatePopUpSettingsPage() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/manage-popups.php'; // include our manage forms page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Manage Lists Page
	* 
	* @since    1.0.0
	*/
	function generateManageListsPage() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/lists.php'; // include our lists page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Account Details Page
	* 
	* @since    1.0.0
	*/
	function generateAccountDetailsPage() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/account-details.php'; // include our account details page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Support Page
	* 
	* @since    1.0.0
	*/
	function generateSupportPage() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/support.php'; // include our options page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Edit Form Page
	* 
	* @since    1.0.0
	*/
	function generateEditFormPage() {
		require_once YIKES_MC_PATH . 'admin/partials/edit-form.php'; // include our options page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp View List Page
	* 
	* @since    1.0.0
	*/
	function generateViewListPage() {
		require_once YIKES_MC_PATH . 'admin/partials/view-list.php'; // include our options page
	}
	
	/**
	*	Register our plugin settings, and display them on our settings page
	*
	* @since v.5.4
	**/
	function yikes_easy_mc_settings_init() { 
		
		register_setting( 'yikes_inc_easy_mc_general_settings_page', 'yikes-mc-api-key', array( $this , 'yikes_mc_validate_api_key' ) );
		register_setting( 'yikes_inc_easy_mc_general_settings_page', 'single-optin-message' );
		register_setting( 'yikes_inc_easy_mc_general_settings_page', 'double-optin-message' );
		
		// register_setting( 'yikes_inc_easy_mailchimp_account_settings', 'flavor' ); <== no longer an option - always will be <div>'s
		// register_setting( 'yikes_inc_easy_mailchimp_account_settings', 'yks-mailchimp-jquery-datepicker' );
		// register_setting( 'yikes_inc_easy_mailchimp_account_settings', 'yks-mailchimp-required-text' );
		
		/* Register General Settings Section */
		add_settings_section(
			'yikes_easy_mc_settings_general_section_callback',
			__( '', $this->text_domain ), 
			'', 
			'yikes_inc_easy_mc_general_settings_page'
		);

		/* Register Visual Represetnation of Connection */
		add_settings_field( 
			'connection', 
			__( 'API Connection', $this->text_domain ), 
			'yikes_inc_easy_mc_visual_representation_of_connection_callback', // callback + validation inside of admin/partials/menu/options.php
			'yikes_inc_easy_mc_general_settings_page', 
			'yikes_easy_mc_settings_general_section_callback' 
		);
		
		/* Register checkbox Setting */
		add_settings_field( 
			'yikes-mc-api-key', 
			__( 'MailChimp API Key', $this->text_domain ), 
			'yikes_inc_easy_mc_api_key_field_callback', // callback + validation inside of admin/partials/menu/options.php
			'yikes_inc_easy_mc_general_settings_page', 
			'yikes_easy_mc_settings_general_section_callback' 
		);
		
		/* Default Icon Element */
		add_settings_field( 
			'single-optin-message', 
			__( 'Single Optin Confirmation Message', $this->text_domain ), 
			'yikes_inc_easy_mc_single_optin_field_callback',  // callback + validation inside of admin/partials/menu/options.php
			'yikes_inc_easy_mc_general_settings_page', 
			'yikes_easy_mc_settings_general_section_callback' 
		);
		
		/* Delete Custom Icon Pack On Uninstall Setting */
		add_settings_field( 
			'double-optin-message', 
			__( 'Double Optin Confirmation Message', $this->text_domain ), 
			'yikes_inc_easy_mc_double_optin_field_callback',  // callback + validation inside of admin/partials/menu/options.php
			'yikes_inc_easy_mc_general_settings_page', 
			'yikes_easy_mc_settings_general_section_callback' 
		);

	}
	
	/** 
	*	Options Sanitation & Validation
	*	@since complete re-write
	**/
	function yikes_mc_validate_api_key( $input ) {
		$old = get_option( 'yikes-mc-api-key' , '' );
		$api_key = trim( $input );
		// only re-run the API request if our API key has changed
		if( $old != $api_key ) {
			// initialize MailChimp Class
			try {
				$MailChimp = new MailChimp( $api_key );
				// retreive our list data
				$validate_api_key_response = $MailChimp->call( 'helper/ping' , array( 'apikey' => $api_key ) );
				update_option( 'yikes-mc-api-validation' , 'valid_api_key' );
			} catch ( Exception $e ) {
				update_option( 'yikes-mc-api-invalid-key-response' , $e->getMessage() );
				update_option( 'yikes-mc-api-validation' , 'invalid_api_key' );
			}	
		}
		// returned the api key
		return $api_key;
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp Extender Options Page
	* 
	* @since    1.0.0
	*/
	function generatePageOptions() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/options.php'; // include our options page
	}
	
	/**
	* Generate Yikes Inc. Easy MailChimp About Yikes Inc. Team Page
	* 
	* @since    1.0.0
	*/
	function generatePageAboutYikes() {
		require_once YIKES_MC_PATH . 'admin/partials/menu/about-yikes.php'; // include our options page
	}

	
	/**
	*	Check if users API key is valid, if not	
	*	this function will apply a disabled attribute
	*	to form fields. (input, dropdowns, buttons etc.)
	* 	@since v5.5 re-write
	**/
	public function is_user_mc_api_valid_form( $echo=true ) {
		if( $echo == true ) {
			if( get_option( 'yikes-mc-api-validation' , 'invalid_api_key' ) == 'invalid_api_key' ) {
				echo 'disabled="disabled"';
			}
		} else {
			if( get_option( 'yikes-mc-api-validation' , 'invalid_api_key' ) == 'invalid_api_key' ) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	/**
	 * Check for existing plugin options
	 *	if they exist, we need to migrate our options to 
	 * the correct WordPress options API (old plugin stored options wierdly)
	 *
	 * @since    1.0.0
	 * @param      string    $yikes_inc_easy_mailchimp_extender       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function check_for_old_yks_mc_options() {
		$old_plugin_options = get_option( 'ykseme_storage' );
		if( apply_filters( 'yikes_mc_old_options_filter' , $old_plugin_options ) ) {
			// display a notice to the user that they should 'migrate' 
			// from the old plugin settings to the new ones
			add_action( 'admin_notices', array( $this , 'display_options_migrate_notice' ) , 11 );
		}
	}
	
	/**
	 * Migrate our old options , to the new options API
	 * moving from 5.5 and beyond..
	 * @since
	*/
	public function migrate_old_yks_mc_options() {
		// include our migrate options helper file
		include_once YIKES_MC_PATH . 'admin/partials/upgrade-helpers/upgrade-migrate-options.php';
	}
	
	/** 
		Admin Notices 
		- Notifications displayed at the top of admin pages, back to the user
	**/
	
		/**
		 * Check for existing plugin options
		 *	if they exist, we need to migrate our options to 
		 * the correct WordPress options API (old plugin stored options wierdly)
		 *
		 * @since    1.0.0
		 * @param      string    $yikes_inc_easy_mailchimp_extender       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function display_options_migrate_notice() {
			?>
				<div class="yikes-easy-mc-updated migrate-options-notice">
					<p><?php _e( "It looks like you're upgrading from a previous version of", $this->text_domain ); ?> <strong>YIKES Inc. Easy MailChimp Extender</strong>. <?php _e( "In the latest version", $this->text_domain ); ?> <strong>YIKES Inc. Easy MailChimp Extender</strong>, <?php _e( "the options data structure has changed. We'eve also moved lists into its own database table to allow for some higher level customization. Now you can easily create multiple forms and assign them to the same mailing list." , $this->text_domain ); ?></p>
					<p><?php _e( "Before you continue, its strongly recommended you the perform the migration to ensure the plugin continues to function properly.", $this->text_domain ); ?></p>
					
					<p>
						<!-- button -->
						<form>
							<input type="hidden" name="yikes-mc-update-option-structure" value="yikes-mc-update-option-structure" />
							<a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=yikes-inc-easy-mailchimp-update' ), 'yikes-mc-migrate-options' , 'migrate_options_nonce' ); ?>" class="button-secondary"><?php _e( 'Perform Migration' , $this->text_domain ); ?></a>
						</form>
					
					</p>
					
				</div>
			<?php
		}
		
		/*
		*	Search through multi dimensional array
		*	and return the index ( used to find the list name assigned to a form )
		*	- http://stackoverflow.com/questions/6661530/php-multi-dimensional-array-search
		*/
		function findMCListID($id, $array) {
		   foreach ($array as $key => $val) {
			   if ($val['id'] === $id) {
				   return $key;
			   }
		   }
		   return null;
		} // end
		
		/* Ajax Migrate Options */
		function migrate_archived_options() {
			// all options prefixed with 'yikes-mc-'
			$option_name = 'yikes-mc-'.$_POST['option_name'];
			$option_value = $_POST['option_value'];	
			if( json_decode( $option_value ) ) {
				// decode our lists() array, and store it
				$opt_value = json_decode( $option_value );
			} else {
				$opt_value = $option_value;
			}
			// must strip slashes, and convert \ to / to properly store our path in the db
			add_option( $option_name , str_replace( '\\' , '/' , stripslashes( $opt_value ) ) );
			// Create some starter forms for the user
			// based on previously imported lists (to our old version)
			if( $option_name == 'yikes-mc-lists' ) {
				global $wpdb;
				$new_options = json_decode( str_replace( '\\' , '/' , stripslashes( $option_value ) ) , true );
				if( !empty( $new_options ) ) {	
					// loop over our imported lists, and create an entry in our custom table
					// for each list, which will be used
					foreach( $new_options as $option ) {
						$list_id = $option['id'];
						$form_name = $option['name'];
						$fields = $option['fields']; // $option['fields']; // didn't get stored in our yikes-mc-list option --- where is it??
						$custom_styles = isset( $option['custom_styles'] ) ? json_encode( $option['custom_styles'] ) : '0'; // store as an array with all of our styles
						$custom_template = isset( $option['custom_template'] ) ? json_encode( $option['custom_template'] ) : '0'; // store template data as an array ( active , template used )
						$send_welcome_email = isset( $option['yks_mailchimp_send_welcome_'.$list_id] ) ? '0' : '1';
						$redirect_user_on_submit = isset( $option['yks_mailchimp_redirect_'.$list_id] ) ? '1' : '0';
						$redirect_page = isset( $option['page_id_'.$list_id] ) ? $option['page_id_'.$list_id] : '';
						
						/* Working Insert Function */
						$wpdb->insert(
							$wpdb->prefix . 'yikes_easy_mc_forms',
							array(
								'list_id' => $list_id,
								'form_name' => $form_name,
								'form_description' => '',
								'fields' => json_encode( $fields ),
								'custom_styles' => $custom_styles,
								'custom_template' => $custom_template,
								'send_welcome_email' => $send_welcome_email,
								'redirect_user_on_submit' => $redirect_user_on_submit,
								'redirect_page' => $redirect_page,
								'submission_settings' => '',
								'optin_settings' => '',
								'error_settings' => '',
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%d',
								'%d',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
							)
						);
					}
				}
			}
			wp_die(); // this is required to terminate immediately and return a proper response
		}
		
		
		/*
		*	generate_options_pages_sidebar_menu();
		*	Render our sidebar menu on all of the setings pages (general, form, checkbox, recaptcha, popup, debug etc. )
		*	@since v5.6 - complete re-write
		*/
		public function generate_options_pages_sidebar_menu() {
			if( isset( $_REQUEST['section'] ) ) {
				$selected = $_REQUEST['section'];
			}
			?>
				<h3><span><?php _e( 'Additional Settings' , $this->text_domain ); ?></span></h3>
				<div class="inside">
					<ul id="settings-nav">
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'general-settings' || !isset( $_REQUEST['section'] ) ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="<?php echo add_query_arg( array( 'section' => 'general-settings' ) ); ?>"><?php _e( 'General Settings' , $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'form-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="<?php echo add_query_arg( array( 'section' => 'form-settings' ) ); ?>"><?php _e( 'Form Settings', $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'checkbox-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="#"><?php _e( 'Checkbox Settings' , $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'recaptcha-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="#"><?php _e( 'ReCaptcha Settings' , $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'popup-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="#"><?php _e( 'Pop Up Settings' , $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] ==  'checkbox-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="#"><?php _e( 'Debug Settings' , $this->text_domain ); ?></a></li>
						<li><?php if( isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'api-cache-settings' ) { ?><div class="option-menu-selected-arrow"></div><?php } ?><a href="<?php echo add_query_arg( array( 'section' => 'api-cache-settings' ) ); ?>"><?php _e( 'API Cache Settings' , $this->text_domain ); ?></a></li>
					</ul>
				</div> <!-- .inside -->
			<?php
		}
		
		/*
		*	generate_manage_forms_sidebar();
		*	Render our sidebar menu on all of the setings pages (general, form, checkbox, recaptcha, popup, debug etc. )
		*	@since v5.6 - complete re-write
		*/
		public function generate_manage_forms_sidebar( $lists ) {
			// create a custom URL to allow for creating fields
			$url = add_query_arg(
				array(
					'action' => 'yikes-easy-mc-create-form',
					'nonce' => wp_create_nonce( 'create_mailchimp_form' )
				)
			);
			?>
				<h3><?php _e( 'Create a New Signup Form' , $this->text_domain ); ?></h3>
				
				<div class="inside">
																
					<p class="description"><?php _e( "Give your form a name and select a MailChimp list to assign users to, then click 'Create'.", $this->text_domain ); ?></p>
					
					<form id="import-list-to-site" method="POST" style="margin-top:10px;" action="<?php echo $url; ?>">
						<input type="hidden" name="import-list-to-site" value="1" />
						<!-- Name your new form -->
						<label for="form-name"><strong><?php _e( 'Form Name' , $this->text_domain ); ?></strong>
							<input type="text" style="margin-top:5px;" class="widefat" placeholder="<?php _e( 'Form Name' , $this->text_domain ); ?>" name="form-name" id="form-name" <?php $this->is_user_mc_api_valid_form( true ); ?> required>
						</label>
						<!-- Name your new form -->
						<label for="form-description" style="display:block;margin-top:.5em;"><strong><?php _e( 'Form Description' , $this->text_domain ); ?></strong>
							<textarea class="widefat" style="margin-top:5px;resize:vertical;max-height:65px;" placeholder="<?php _e( 'Form Description' , $this->text_domain ); ?>" name="form-description" id="form-description" <?php $this->is_user_mc_api_valid_form( true ); ?>></textarea>
						</label>
						<!-- Associate this form with a list! -->
						<label for="associated-list" style="display:block;margin-top:.5em;"><strong><?php _e( 'Associated List' , $this->text_domain ); ?></strong>
							<select name="associated-list" id="associated-list" style="width:100%;margin-top:5px;" <?php $this->is_user_mc_api_valid_form( true ); ?>>
								<?php
									if( isset( $lists ) && !empty( $lists ) ) {
										foreach( $lists as $mailing_list ) {
											?>
												<option value="<?php echo $mailing_list['id']; ?>"><?php echo stripslashes( $mailing_list['name'] ) . ' (' . $mailing_list['stats']['member_count'] . ') '; ?></option>
											<?php
										}
									} else {
										?>
											<option><?php echo __( "Please enter a valid API key." , $this->text_domain ); ?></option>
										<?php
									}
								?>
							</select>
						</label>
						<?php 
							if( $this->is_user_mc_api_valid_form( false ) ) {
								echo submit_button( __( 'Create' ) , 'primary' , '' , false , array( 'style' => 'margin:.75em 0 .5em 0;' ) ); 
							} else {
								echo '<p class="description">' . __( "Please enter a valid MailChimp API key to get started." , $this->text_domain ) . '</p>';
								?>
									<a href="<?php echo admin_url( 'admin.php?page=yikes-inc-easy-mailchimp-settings&settings-updated=true' ); ?>"><?php _e( 'general settings' , $this->text_domain ); ?></a>
								<?php
							}
						?>
					</form>	

				</div> <!-- .inside -->
			<?php
		}
		
		/*
		*	Generate a dropdown of post and pages
		*	so the user can send the user to on form submission
		*/
		public function generate_page_redirect_dropdown( $redirect, $redirect_page ) {
				$post_types = get_post_types();
				?>
				<label id="redirect-user-to-selection-label" for="redirect-user-to-selection" <?php if( $redirect == '0' ) { echo 'style="display:none;"'; } ?>>
					<?php _e( "Select A Page or Post" , $this->text_domain ); ?>
					<select id="redirect-user-to-selection" name="redirect-user-to-selection">
				<?php
					// loop over registered post types, and query!
						foreach( $post_types as $registered_post_type ) {
							// exclude a few built in custom post types
							if( !in_array( $registered_post_type , array( 'attachment' , 'revision' , 'nav_menu_item' ) ) ) {
								// run our query, to retreive the posts
								$pages = get_posts( array(
									'order' => 'ASC',
									'orderby' => 'post_title',
									'post_type' => $registered_post_type,
									'post_status' => 'publish',
									'numberposts' => -1
								) );
								// only show cpt's that have posts assigned
								if( !empty( $pages ) ) {
									?>
									<optgroup label="<?php echo ucwords( str_replace( '_' , ' ' , $registered_post_type ) ); ?>">
									<?php
										foreach( $pages as $page ) {
											?><option <?php selected( $redirect_page , $page->ID ); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option><?php
										}
									?>
									</optgroup>
									<?php
								}
							}
						}
					?>
					</select>
				</label>
			<?php
		}
		
		/*
		*	Generate a list of Yikes INC News Articles
		*	via RSS
		* 	Displayed in sidebars and on about page
		*/
		public function generate_yikes_RSS_feed() {
			?><div class="inside"><h2><?php _e( 'Recent Yikes Inc. News', $this->text_domain ); ?></h2>

			<?php // Get RSS Feed(s)
			include_once( ABSPATH . WPINC . '/feed.php' );

			// Get a SimplePie feed object from the specified feed source.
			$rss = fetch_feed( 'http://www.yikesinc.com/feed/' );

			$maxitems = 0;

			if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

				// Figure out how many total items there are, but limit it to 5. 
				$maxitems = $rss->get_item_quantity( 5 ); 

				// Build an array of all the items, starting with element 0 (first element).
				$rss_items = $rss->get_items( 0, $maxitems );

			endif;
			?>

			<ul>
				<?php if ( $maxitems == 0 ) : ?>
					<li><?php _e( 'No items', $this->text_domain ); ?></li>
				<?php else : ?>
					<?php // Loop through each feed item and display each item as a hyperlink. ?>
					<?php foreach ( $rss_items as $item ) : ?>
						<li class="yikes-news-article"><span class="dashicons dashicons-arrow-right yikes-news-article-arrow"></span>
							<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
								title="<?php printf( __( 'Posted %s', $this->text_domain ), $item->get_date('j F Y | g:i a') ); ?>" target="_blank">
								<?php echo esc_html( $item->get_title() ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul></div><?php
		}
		
		/*
		*	generate_show_some_love_container()
		*	Generate a container, with some author info
		*
		* 	Displayed in sidebars
		*/
		public function generate_show_some_love_container() {
			?>
				<div class="postbox yikes-easy-mc-postbox">
							
					<!-- review us container -->
					<div id="review-wp-svg-icons" class="inside">
						<p class="description" style="margin:1em 0 !important;"><?php _e( "This plugin made with" , $this->text_domain ); ?> <span class="dashicons dashicons-heart yikes-love"></span> <?php _e( "by" , $this->text_domain ); ?> <a href="http://www.yikesinc.com" target="_blank" title="Yikes Inc.">yikes inc.</a></p>
									
						<?php _e( 'Leave a review' , $this->text_domain ); ?>
						<p style="margin-top:5px;margin-bottom:1.5em;">
							<a href="https://wordpress.org/support/view/plugin-reviews/yikes-inc-easy-mailchimp-extender" target="_blank" style="text-decoration:none;">
								<b class="dashicons dashicons-star-filled" style="font-size:1.75em;"></b>
								<b class="dashicons dashicons-star-filled" style="font-size:1.75em;"></b>
								<b class="dashicons dashicons-star-filled" style="font-size:1.75em;"></b>
								<b class="dashicons dashicons-star-filled" style="font-size:1.75em;"></b>
								<b class="dashicons dashicons-star-filled" style="font-size:1.75em;"></b>
							</a>
						</p>
								
						<?php _e( 'Tweet about it' , $this->text_domain ); ?>
						<p style="margin-top:5px;margin-bottom:1.5em;">
							<a style="text-decoration:none;color:#55ACEE;" href="https://twitter.com/intent/tweet?text=I%20am%20using%20Easy%20MailChimp%20for%20WordPress%20by%20%40YikesInc%20-%20it's%20absolutely%20great%21%20-%20https%3A%2F%2Fwordpress.org/plugins/yikes-inc-easy-mailchimp-extender%2F" title="<?php _e( 'Tweet it!' , $this->text_domain ); ?>" target="_blank">
								<span class="dashicons dashicons-twitter"></span>
							</a>		
						</p>
									
						<?php _e( 'Vote that the plugin works' , $this->text_domain ); ?>
						<p style="margin-top:5px;margin-bottom:1em !important;">
							<a href="https://wordpress.org/plugins/yikes-inc-easy-mailchimp-extender/" target="_blank">
								<?php _e( 'Vote for Compatibility' , $this->text_domain ); ?>
							</a>
						</p>
					</div>
							
				</div>
			<?php
		}
		
		/*
		*	generate_form_editor( $list_id )
		*	Submit an API request to get our merge variables, and build up a small form editor
		*	for users to 'customize' their form
		*	-
		* @parameters - $list_id - pass in the list ID to retreive merge variables from
		*/
		public function generate_form_editor( $form_fields , $list_id , $merge_variables ) {
		
			// if no list id, die!
			if( !$list_id ) {
				die( __( "We've encountered an error. No list ID was sent." , $this->text_domain ) );
			}		
			
			if( !empty( $form_fields ) ) {
			
				// find any fields that are assigned to this form, that don't exist in MailChimp
				// or else were going to run into issues when we submit the form

				$available_merge_variables = array();
				$assigned_fields= array();
				
				foreach( $merge_variables['data'][0]['merge_vars'] as $merge_tag ) {
					$available_merge_variables[] = $merge_tag['tag'];
				}
				
				foreach( $form_fields as $field => $value ) {
					$assigned_fields[] = $field;
				}
				
				$excluded_fields = array_diff( $assigned_fields , $available_merge_variables );
				
				$i = 1;
				foreach( $form_fields as $field ) {
					// print_r( $field );
					?>
					<section class="draggable" id="<?php echo $field['merge']; ?>">
						<!-- top -->
						<a href="#" class="expansion-section-title settings-sidebar">
							<span class="dashicons dashicons-plus"></span><?php echo $field['label']; ?>
							<?php if( in_array( $field['merge'] , $excluded_fields ) ) { ?>
								<img src="<?php echo YIKES_MC_URL . 'includes/images/warning.svg'; ?>" style="position:absolute;margin-left:5px;width:18px;" title="<?php _e( 'Field no longer exists.' , $this->text_domain ); ?>" alt="<?php _e( 'Field no longer exists.' , $this->text_domain ); ?>">
							<?php } ?>
							<span style="float:right;"><small><?php echo __( 'type' , $this->text_domain ) . ' : ' . $field['type']; ?></small></span>
						</a>
						<!-- expansion section -->
						<div class="yikes-mc-settings-expansion-section">
							
							<?php if( in_array( $field['merge'] , $excluded_fields ) ) { ?>
								<p class="yikes-mc-warning-message"><?php _e( "This field no longer exists in this list. Delete this field from the form to prevent issues on the front end." , $this->text_domain ); ?></p>
							<?php } ?>
							
							<!-- store the label -->
							<input type="hidden" name="field[<?php echo $field['merge'] ?>][label]" value="<?php echo $field['label']; ?>" />
							<input type="hidden" name="field[<?php echo $field['merge'] ?>][type]" value="<?php echo $field['type']; ?>" />
							<input type="hidden" name="field[<?php echo $field['merge'] ?>][merge]" value="<?php echo $field['merge']; ?>" />
							<input type="hidden" class="field-<?php echo $field['merge']; ?>-position position-input" name="field[<?php echo $field['merge'] ?>][position]" value="<?php echo $i++; ?>" />
							
							<?php if ( $field['type'] == 'radio' || $field['type'] == 'dropdown' || $field['type'] == 'select' ) { ?>
								<input type="hidden" name="field[<?php echo $field['merge'] ?>][choices]" value='<?php echo stripslashes( $field['choices'] ); ?>' />			
							<?php } ?>
							
							<!-- Single or Double Optin -->
							<p style="margin-top:0;margin:0;"><!-- necessary to prevent skipping on slideToggle(); -->
								
								<table class="form-table" style="margin-top:0;">
									<!-- Default Value -->
									<?php switch( $field['type'] ) { 
										
										case 'text':
										case 'email':
										case 'url':
										case 'number';
										case 'birthday':
										case 'zip':
										case 'phone':
									?>
									<!-- Placeholder -->
									<tr valign="top">
										<td scope="row">
											<label for="placeholder">
												<?php _e( 'Placeholder' , $this->text_domain ); ?>
											</label>
										</td>
										<td>
											<input type="text" class="widefat" name="field[<?php echo $field['merge'] ?>][placeholder]" value="<?php echo isset( $field['placeholder'] ) ? stripslashes( $field['placeholder'] ) : '' ; ?>" />
											<p class="description"><small><?php _e( "Assign a placeholder value to this field.", $this->text_domain );?></small></p>
										</td>
									</tr>
									<?php
										break;
									}
									?>
									<!-- Default Value -->
									<?php switch( $field['type'] ) { 
										default:
										case 'text':
										case 'number':
										case 'url':
									?>
										<tr valign="top">
											<td scope="row">
												<label for="placeholder">
													<?php _e( 'Default Value' , $this->text_domain ); ?>
												</label>
											</td>
											<td>
												<input <?php if( $field['type'] != 'number' ) { ?> type="text" <?php } else { ?> type="number" <?php } ?> class="widefat" name="field[<?php echo $field['merge'] ?>][default]" <?php if( $field['type'] != 'url' ) { ?> value="<?php echo isset( $field['default'] ) ? $field['default'] : ''; ?>" <?php } else { ?> value="<?php echo isset( $field['default'] ) ? esc_url( $field['default'] ) : ''; ?>" <?php } ?> />
												<p class="description"><small><?php _e( "Assign a default value to populate this field with on initial page load.", $this->text_domain );?></small></p>
											</td>
										</tr>
									<?php 
											break;
										
										case 'radio':
										?>
											<tr valign="top">
												<td scope="row">
													<label for="placeholder">
														<?php _e( 'Default Selection' , $this->text_domain ); ?>
													</label>
												</td>
												<td>
													<?php if( !isset( $field['default_choice'] ) ) { $field['default_choice'] =  json_decode( stripslashes( $field['choices'] ) , true )[0]; }
													foreach( json_decode( stripslashes( $field['choices'] ) , true ) as $choice => $value ) { ?>
														<input type="radio" name="field[<?php echo $field['merge'] ?>][default_choice]" value="<?php echo $value; ?>" <?php checked( $field['default_choice'] , $value ); ?>><?php echo $value; ?>
													<?php } ?>
													<p class="description"><small><?php _e( "Select the option that should be selected by default.", $this->text_domain );?></small></p>
												</td>
											</tr>
											
										<?php
											break;
											
										case 'dropdown':
										?>
											<tr valign="top">
												<td scope="row">
													<label for="placeholder">
														<?php _e( 'Default Selection' , $this->text_domain ); ?>
													</label>
												</td>
												<td>
													<select type="default" name="field[<?php echo $field['merge'] ?>][default_choice]">
														<?php foreach( json_decode( stripslashes( $field['choices'] ) , true ) as $choice => $value ) { ?>
															<option value="<?php echo $choice; ?>" <?php selected( $field['default_choice'] , $choice ); ?>><?php echo $value; ?></option>
														<?php } ?>
													</select>
													<p class="description"><small><?php _e( "Which option should be selected by default?", $this->text_domain );?></small></p>
												</td>
											</tr>
											
										<?php
											break;
									?>
									
									<?php } // end Default Value ?>
									
									<!-- Additional Classes -->
									<tr valign="top">
										<td scope="row">
											<label for="placeholder">
												<?php _e( 'Additional Classes' , $this->text_domain ); ?>
											</label>
										</td>
										<td>
											<input type="text" class="widefat" name="field[<?php echo $field['merge'] ?>][additional-classes]" value="<?php echo isset( $field['additional-classes'] ) ? $field['additional-classes'] : '' ; ?>" />
											<p class="description"><small><?php _e( "Assign additional classes to this field.", $this->text_domain );?></small></p>
										</td>
									</tr>
									<!-- Required Toggle -->
									<tr valign="top">
										<td scope="row">
											<label for="field-required">
												<?php _e( 'Field Required?' , $this->text_domain ); ?>
											</label>
										</td>
										<td>
											<?php $checked = isset( $field['require'] ) ? $field['require'] : '0'; ?>
											<input type="checkbox" class="widefat" value="1" name="field[<?php echo $field['merge'] ?>][require]" <?php checked( $checked , 1 ); ?> <?php if( $field['merge'] == 'EMAIL' ) {  ?> disabled="disabled" checked="checked" title="<?php echo __( 'Email is a required field.' , $this->text_domain ); } ?>">
											<p class="description"><small><?php _e( "Require this field to be filled in before the form can be submitted.", $this->text_domain );?></small></p>
										</td>
									</tr>
									<!-- Visible Toggle -->
									<tr valign="top">
										<td scope="row">
											<label for="hide-field">
												<?php _e( 'Hide Field' , $this->text_domain ); ?>
											</label>
										</td>
										<td>
											<?php $hide = isset( $field['hide'] ) ? $field['hide'] : '0'; ?>
											<input type="checkbox" class="widefat" value="1" name="field[<?php echo $field['merge'] ?>][hide]" <?php checked( $hide , 1 ); ?> <?php if( $field['merge'] == 'EMAIL' ) {  ?> disabled="disabled" title="<?php echo __( 'Cannot toggle email field visibility.' , $this->text_domain ); } ?>">
											<p class="description"><small><?php _e( "Hide this field from being displayed on the front end.", $this->text_domain );?></small></p>
										</td>
									</tr>
									<!-- Toggle Buttons -->
									<tr valign="top">
										<td scope="row">
											&nbsp;
										</td>
										<td>
											<span style="font-size:small;float:right;">
												<a href="#" class="close-form-expansion"><?php _e( "Close" , $this->text_domain ); ?></a> |
												<a href="#" class="remove-field" alt="<?php echo $field['merge']; ?>"><?php _e( "Remove Field" , $this->text_domain ); ?></a>
											</span>
										</td>
									</tr>
								</table>
							</p>		
												
						</div>
					</section>
					<?php
				}	
			} else {
				?>
					<h4 style="margin:4em 0;width:100%;text-align:center;"><em><?php _e( 'No fields assigned to this form. Select some fields to add to this form from the right hand column.' , $this->text_domain ); ?></em></h4>
				<?php
			}
		}
		
		/*
		*	build_available_merge_vars( $list_id )
		*	Submit an API request to get our merge variables, and build up a small form editor
		*	for users to 'customize' their form
		*	-
		* @parameters - $list_id - pass in the list ID to retreive merge variables from
		*/
		public function build_available_merge_vars( $form_fields , $available_merge_variables ) {
			$fields_assigned_to_form = array();
			if( !empty( $form_fields ) ) {
				foreach( $form_fields as $assigned_field ) {
					$fields_assigned_to_form[] = $assigned_field['merge'];
				}
			}
			if( !empty( $available_merge_variables['data'][0] ) ) {	
				?><ul id="available-fields"><?php
				foreach( $available_merge_variables['data'][0]['merge_vars'] as $merge_var ) {
					if( in_array( $merge_var['tag'] , $fields_assigned_to_form ) ) {
						?>
							<li class="available-form-field not-available" alt="<?php echo $merge_var['tag']; ?>" data-attr-field-type="<?php echo $merge_var['field_type']; ?>" data-attr-form-id="<?php echo $available_merge_variables['data'][0]['id']; ?>" title="<?php _e( 'Already assigned to your form' , $this->text_domain ); ?>" disabled="disabled"><?php echo $merge_var['name']; ?></li>
						<?php
					} else {
						?>
							<li class="available-form-field" alt="<?php echo $merge_var['tag']; ?>" data-attr-field-type="<?php echo $merge_var['field_type']; ?>" data-attr-form-id="<?php echo $available_merge_variables['data'][0]['id']; ?>"><?php echo $merge_var['name']; ?></li>
						<?php
					}
				}
				?></ul>
				<a href="#" class="add-field-to-editor button-secondary" style="display:none;"><small><?php _e( 'Add to Form Builder' , $this->text_domain ); ?></small></a>
				<?php
			}
		}
		
		/* 
		*	Create A New Form!
		*	Probably Move these to its own file, 
		*	and include it here for easy maintenance 
		*	- must clean up db tables , ensure what data is going in and what is needed...
		*/
		public function yikes_easy_mailchimp_create_form() {
			$nonce = $_REQUEST['nonce'];
			if( !wp_verify_nonce( $nonce, 'create_mailchimp_form' ) ) {
				die( __( "We've run into an error. The security check didn't pass. Please try again." , $this->text_domain ) );
			}
			global $wpdb;
				/* Default values */
				// setup our default submission settings serialized array
				$submission_settings = json_encode(
					array(
						'ajax' => 1,
						'redirect_on_submission' => 0,
						'redirect_page' => 1,
						'hide_form_post_signup' => 0
					)
				);
				// setup our default optin settings serialized array
				$optin_settings = json_encode(
					array(
						'optin' => 0,
						'update_existing_user' => 1,
						'send_welcome_email' => 1,
					)
				);
				/* End default values */
				/* Working Insert Function */
				$wpdb->insert(
					$wpdb->prefix . 'yikes_easy_mc_forms',
					array(
						'list_id' => $_POST['associated-list'],
						'form_name' => stripslashes( $_POST['form-name'] ),
						'form_description' => stripslashes( $_POST['form-description'] ),
						'fields' => '',
						'custom_styles' => 0,
						'custom_template' => 0,
						'send_welcome_email' => 1,
						'redirect_user_on_submit' => 0,
						'redirect_page' => '',
						'submission_settings' => $submission_settings,
						'optin_settings' => $optin_settings,
						'error_messages' => $error_settings,
						'impressions' => 0,
						'submissions' => 0,
					),
					array(
						'%s', // list id
						'%s', // form name
						'%s', // form description
						'%s', // fields
						'%s', // custom styles
						'%d',	//custom template
						'%d',	// send welcome email
						'%s',	// redirect user
						'%s',	// redirect page
						'%s',	// submission
						'%s',	// optin
						'%s', // error
					)
				);
				// redirect the user to the new form edit page
			wp_redirect( admin_url( 'admin.php?page=yikes-mailchimp-edit-form&id='.$wpdb->insert_id ) );
			exit();
			die();
		}
		
		/* 
		*	Delete A Form !
		*	Probably Move these to its own file, 
		*	and include it here for easy maintenance 
		*	- must clean up db tables , ensure what data is going in and what is needed...
		*/
		public function yikes_easy_mailchimp_delete_form() {
			// grab & store our variables ( associated list & form name )
			$nonce = $_REQUEST['nonce'];
			$post_id_to_delete = $_REQUEST['mailchimp-form'];
			// verify our nonce
			if( !wp_verify_nonce( $nonce, 'delete-mailchimp-form-'.$post_id_to_delete ) ) {
				wp_die( __( "We've run into an error. The security check didn't pass. Please try again." , $this->text_domain ) , __( "Failed nonce validation" , $this->text_domain ) , array( 'response' => 500 , 'back_link' => true ) );
			}
			global $wpdb;
				/* Working Insert Function */
				$wpdb->delete(
					$wpdb->prefix . 'yikes_easy_mc_forms',
					array(
						'id' => $post_id_to_delete
					),
					array(
						'%d',
					)
				);
			// redirect the user to the manage forms page, display confirmation
			wp_redirect( admin_url( 'admin.php?page=yikes-inc-easy-mailchimp&deleted-form=true' ) );
			exit();
			die();
		}
		
		/* 
		*	Duplicate an entire form !
		*	Probably Move these to its own file, 
		*/
		public function yikes_easy_mailchimp_duplicate_form() {
			// grab & store our variables ( associated list & form name )
			$nonce = $_REQUEST['nonce'];
			$post_id_to_clone = $_REQUEST['mailchimp-form'];
			// verify our nonce
			if( !wp_verify_nonce( $nonce, 'duplicate-mailchimp-form-'.$post_id_to_clone ) ) {
				wp_die( __( "We've run into an error. The security check didn't pass. Please try again." , $this->text_domain ) , __( "Failed nonce validation" , $this->text_domain ) , array( 'response' => 500 , 'back_link' => true ) );
			}
			global $wpdb;
				/* Working Insert Function */
				$form_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "yikes_easy_mc_forms WHERE id = ".$post_id_to_clone."");
				// assign a new title to our form, so they're not confused
				$form_data->form_name = $form_data->form_name . ' - Clone -';
				$wpdb->insert(
					$wpdb->prefix . 'yikes_easy_mc_forms',
					array(
						'list_id' => $form_data->list_id,
						'form_name' => $form_data->form_name,
						'fields' => $form_data->fields,
						'custom_styles' => $form_data->custom_styles,
						'custom_template' => $form_data->custom_template,
						'send_welcome_email' => $form_data->send_welcome_email,
						'redirect_user_on_submit' => $form_data->redirect_user_on_submit,
						'redirect_page' => $form_data->redirect_page,
					),
					array(
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%d',
						'%s',
					)
				);
			// redirect the user to the manage forms page, display confirmation
			wp_redirect( admin_url( 'admin.php?page=yikes-inc-easy-mailchimp&duplicated-form=true' ) );
			exit();
			die();
		}
		
		/* 
		*	Update an entire form !
		*	Probably Move these to its own file, 
		*/
		public function yikes_easy_mailchimp_update_form() {
			// grab & store our variables ( associated list & form name )
			$nonce = $_REQUEST['nonce'];
			$form_id = $_REQUEST['id'];
			
			// store our values!
			$list_id = $_POST['associated-list'];
			$form_name = stripslashes( $_POST['form-name'] );
			$form_description = stripslashes( $_POST['form-description'] );
			$send_welcome_email = $_POST['send-welcome-email'];
			$redirect_user_on_submit = $_POST['redirect-user-on-submission'];
			$redirect_page = $_POST['redirect-user-to-selection'];
			$custom_styles = $_POST['custom-styles'];
			$assigned_fields = json_encode( $_POST['field'] );
			
			// setup our custom styles serialized array
			if( isset( $custom_styles ) ) {
				$custom_styles = json_encode( array(
					'active' => $_POST['custom-styles'],
					'background_color' => $_POST['form-background-color'],
					'font_color' => $_POST['form-font-color'],
					'submit_button_color' => $_POST['form-submit-button-color'],
					'submit_button_text_color' => $_POST['form-submit-button-text-color'],
					'form_padding' => $_POST['form-padding'],
					'form_width' => $_POST['form-width'],
					'form_alignment' => $_POST['form-alignment'],
					'label_visible' => $_POST['label-visible']
				) );
			} else {
				$custom_styles = 0;
			}
			
			// setup our submission settings serialized array
			$submission_settings = json_encode(
				array(
					'ajax' => $_POST['form-ajax-submission'],
					'redirect_on_submission' => $_POST['redirect-user-on-submission'],
					'redirect_page' => $_POST['redirect-user-to-selection'],
					'hide_form_post_signup' => $_POST['hide-form-post-signup']
				)
			);
			
			// setup our optin settings serialized array
			$optin_settings = json_encode(
				array(
					'optin' => $_POST['single-double-optin'],
					'update_existing_user' => $_POST['update-existing-user'],
					'send_welcome_email' => $_POST['send-welcome-email'],
				)
			);
			
			// setup our error settings serialized array
			$error_settings = json_encode(
				array(
					'success' => trim( $_POST['yikes-easy-mc-success-message'] ) ? trim( $_POST['yikes-easy-mc-success-message'] ) : '',
					'general-error' => trim( $_POST['yikes-easy-mc-general-error-message'] ) ? trim( $_POST['yikes-easy-mc-general-error-message'] ) : '',
					'invalid-email' => trim( $_POST['yikes-easy-mc-invalid-email-message'] ) ? trim( $_POST['yikes-easy-mc-invalid-email-message'] ) : '',
					'email-already-subscribed' => trim( $_POST['yikes-easy-mc-user-subscribed-message'] ) ? trim( $_POST['yikes-easy-mc-user-subscribed-message'] ) : '',
				)
			);
			
			// verify our nonce
			if( !wp_verify_nonce( $nonce, 'update-mailchimp-form-'.$form_id ) ) {
				wp_die( __( "We've run into an error. The security check didn't pass. Please try again." , $this->text_domain ) , __( "Failed nonce validation" , $this->text_domain ) , array( 'response' => 500 , 'back_link' => true ) );
			}
			
				global $wpdb;
				/* Working Insert Function */
				$form_data = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "yikes_easy_mc_forms WHERE id = ".$form_id."");
				$wpdb->update( 
					$wpdb->prefix . 'yikes_easy_mc_forms',
					array( 
						'list_id' => $list_id,
						'form_name' => $form_name,
						'form_description' => $form_description,
						'fields' => $assigned_fields,
						'custom_styles' => $custom_styles,
						'custom_template' => 0,
						'send_welcome_email' => $send_welcome_email,
						'redirect_user_on_submit' => $redirect_user_on_submit,
						'redirect_page' => $redirect_page,
						'submission_settings' => $submission_settings,
						'optin_settings' => $optin_settings,
						'error_messages' => $error_settings,
					),
					array( 'ID' => $form_id ), 
					array(
						'%s', // list id
						'%s', // form name
						'%s', // form description
						'%s', // fields
						'%s', // custom styles
						'%d',	//custom template
						'%d',	// send welcome email
						'%s',	// redirect user
						'%s',	// redirect page
						'%s',	// submission
						'%s',	// optin
						'%s', // error
					), 
					array( '%d' ) 
				);
			// redirect the user to the manage forms page, display confirmation
			wp_redirect( admin_url( 'admin.php?page=yikes-mailchimp-edit-form&id=' . $form_id . '&updated-form=true' ) );
			exit();
			die();
		}
		
		/* 
		*	Clear Transient Data !
		*	Probably Move these to its own file, 
		*/
		public function yikes_easy_mailchimp_clear_transient_data() {
			// grab & store our variables ( associated list & form name )
			$nonce = $_REQUEST['nonce'];
			// verify our nonce
			if( !wp_verify_nonce( $nonce, 'clear-mc-transient-data' ) ) {
				wp_die( __( "We've run into an error. The security check didn't pass. Please try again." , $this->text_domain ) , __( "Failed nonce validation" , $this->text_domain ) , array( 'response' => 500 , 'back_link' => true ) );
			}
			// Delete list data transient
			delete_transient( 'yikes-easy-mailchimp-list-data' );
			// Delete list account data
			delete_transient( 'yikes-easy-mailchimp-account-data' );
			// Delete list account data
			delete_transient( 'yikes-easy-mailchimp-profile-data' );
			// redirect the user to the manage forms page, display confirmation
			wp_redirect( admin_url( 'admin.php?page=yikes-inc-easy-mailchimp-settings&section=api-cache-settings&transient-cleared=true' ) );
			exit();
			die();
		}
		
				
}