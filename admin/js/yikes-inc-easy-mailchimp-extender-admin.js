(function( $ ) {Code ParrotsCode Parrots
	'use strict';
		
		$( document ).ready( function() {

			// Fire off our show/hide custom messages functions on load
			show_custom_messages_based_on_optin_settings();

			// Listen for Update Existing Subscriber / Send Update Email / Double opt-in changes and fire off our show/hide custom messages functions
			$( 'input[name="update-existing-user"]' ).change( show_custom_resub_messages_based_on_optin_settings );
			$( 'input[name="update-existing-email"]' ).change( show_custom_resub_messages_based_on_optin_settings );
			$( 'input[name="single-double-optin"]' ).change( show_custom_success_messages_based_on_optin_settings );
		
			/* Hide Stats, Display Shortcode */
			$( 'body' ).on( 'click' , '.view-yikes-mc-form-shortcode' , function() {
				var clicked = jQuery( this );
				var index = jQuery( this ).parents( 'tr' ).find('.form-id-container').text();
				clicked.parents( 'tr' ).find( '.stat-container' ).toggleClass( 'hidden-class' );
				clicked.parents( 'tr' ).find( '.shortcode' ).toggleClass( 'hidden-class' );
				clicked.toggleText();
				return false;
			});
				 			
			/* Prevent disabled buttons/pagination from doing anything */
			$( 'body' ).on( 'click', '.yikes-easy-mc-wrap .disabled' , function() {
				return false;
			});
			
			/* 
			*	set a max height on initial page load (for some smooth animations) 
			*	- but prevent it from doing so on the edit form page (where we display our upsell banner)
			*/
			if( ! $( 'body' ).hasClass( 'admin_page_yikes-mailchimp-edit-form' ) ) {
				$( '.show-some-love-container' ).css( 'max-height', jQuery( '.show-some-love-container' ).height() );
			}

			$( '.checkbox-settings-list-item' ).click( function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					display_interest_groups_for_list_checkbox_integrations( this );	
				} else {
					remove_interest_groups_for_list_checkbox_integrations( this );
				}
				
			});

			$( 'body' ).on( 'click', '.yikes-mailchimp-toggle-ig', function() {

				var element = $( this );

				if ( element.hasClass( 'dashicons-arrow-down-alt2' ) ) {

					// If we're expanded, slide up.
					element.siblings( '.integration-checkbox-interest-groups-interior' ).slideUp();
					element.removeClass( 'dashicons-arrow-down-alt2' ).addClass( 'dashicons-arrow-right-alt2' );
				} else {

					// If we're collapsed, slide down.
					element.siblings( '.integration-checkbox-interest-groups-interior' ).slideDown();
					element.removeClass( 'dashicons-arrow-right-alt2' ).addClass( 'dashicons-arrow-down-alt2' );
				}
			});
			
			/* Toggle About Yikes Visibility */
			$( 'body' ).on( 'click', '.about-yikes-inc-toggle', function() {
				/* Display About Code Parrots Content */
				jQuery( this ).toggleText();
				jQuery( '.show-some-love-container' ).find( 'h3' ).toggleText();
				/* if has alternate toggle class, toggle reverse */
				if( jQuery( this ).hasClass( 'show-yikes-some-love-toggle' ) ) {
					jQuery( this ).removeClass( 'show-yikes-some-love-toggle' );
					var container_header_height = jQuery( '.show-some-love-container' ).find( 'h3' ).first().height() + 24; /* add the padding on the header */
					var review_yikes_height =  jQuery( '#review-yikes-easy-mc' ).height() + 24; /* add the padding and margins on content */
					var footer_button_first_height = jQuery( '.sidebar-footer-text' ).first().height() + 12; /* add the margin on the footer */
					var footer_button_height = jQuery( '.sidebar-footer-text' ).last().height() + 13; /* add the margin on the footer */
					var total_height = parseInt( container_header_height + review_yikes_height + footer_button_first_height + footer_button_height );			
					jQuery( '.show-some-love-container' ).css( 'max-height', total_height );
					jQuery( '#about-yikes-inc' ).fadeOut( 720, function() {
						jQuery( '.sidebar-footer-text' ).first().fadeIn( 450 );
						jQuery( '#review-yikes-easy-mc' ).fadeIn( 450 );
					})
					return false;
				}
				/* Display About Code Parrots Content */
				jQuery( this ).toggleClass( 'show-yikes-some-love-toggle' );
				var container_header_height = jQuery( '.show-some-love-container' ).find( 'h3' ).first().height() + 24; /* add the padding on the header */
				var about_yikes_text_height =  jQuery( '#about-yikes-inc' ).height() + 18; /* add the padding and margins on content */
				var footer_button_height = jQuery( '.sidebar-footer-text' ).last().height() + 18; /* add the margin on the footer */
				var total_height = parseInt( container_header_height + about_yikes_text_height + footer_button_height );
				jQuery( '.sidebar-footer-text' ).first().hide();
				jQuery( '.show-some-love-container' ).addClass( 'about-yikes-inc' ).css( 'max-height', total_height );
				jQuery( '#about-yikes-inc' ).show().css( 'opacity', 0 );
				jQuery( '#review-yikes-easy-mc' ).fadeOut( 'fast', function() {
					jQuery( '#about-yikes-inc' ).fadeTo( 800, 1 );
				});	
				/* prevent scroll backs */
				return false;
			});
						
			 /* Toggle Text - Stats/Shortcode (manage-forms.php)*/
			$.fn.toggleText = function() {
				var altText = this.data("alt-text");
				if (altText) {
					this.data("alt-text", this.html());
					this.html(altText);
				}
			};
			
		});
	 
})( jQuery );

/* Switch pages on form switch */
function YIKES_Easy_MC_SwitchForm( selected_value ) {
	/* jQuery page redirect to selected for... */
	window.location.replace( object_data.admin_url+'admin.php?page=yikes-mailchimp-edit-form&id='+selected_value );
}

/* Slidetoggle Option Values */
function toggleOptinValue( selected ) {
	if( selected.value == 'true' ) {
		jQuery( '.yks-mailchimp-single-optin-message' ).slideUp( 'fast' , function() {
			jQuery( '.yks-mailchimp-double-optin-message' ).slideDown( 'fast' );
		});
	} else {
		jQuery( '.yks-mailchimp-double-optin-message' ).slideUp( 'fast' , function() {
			jQuery( '.yks-mailchimp-single-optin-message' ).slideDown( 'fast' );
		});
	}
}

/*
*	Toggle the disabled attribute on the delete subscriber button
*	Used on the view subscriber data card
*	@since 6.0
*/
function toggleDeleteSubscriberButton(clicked_button) {
	if( clicked_button.prop( 'checked' ) ) {
		jQuery( '#delete-mailchimp-subscriber' ).removeAttr( 'disabled' );
	} else {
		jQuery( '#delete-mailchimp-subscriber' ).attr( 'disabled', 'disabled' );
	}
}


/*
*	Check if the associated list for this integration has any interest groups
*	used on the integration settings page, fires when the associated list is toggled for a given integration
*	@since 6.0.1
*/
function checkForInterestGroups( altered_list, selected_list_id, integration_type ) {

	var child_length = altered_list.parents( 'li' ).find( '.interest-groups-container' ).children().length;
	if( child_length == 0 ) {
		altered_list.parents( 'li' ).find( '.interest-groups-container' ).append( '<p>' + object_data.locating_interest_groups + '<span class="upgrading-ellipse-one">.</span><span class="upgrading-ellipse-two">.</span><span class="upgrading-ellipse-three">.</span><img class="search-interest-group-preloader" src="'+ object_data.preloader_url + '" /></p>' );
	} else {
		var x = 1;
		altered_list.parents( 'li' ).find( '.interest-groups-container' ).children().each( function() {
			jQuery( this ).fadeOut( 'fast', function() {
				jQuery( this ).remove();
				if( x == child_length ) {
					altered_list.parents( 'li' ).find( '.interest-groups-container' ).append( '<p>' + object_data.locating_interest_groups + '<span class="upgrading-ellipse-one">.</span><span class="upgrading-ellipse-two">.</span><span class="upgrading-ellipse-three">.</span><img class="search-interest-group-preloader" src="'+ object_data.preloader_url + '" /></p>' );
				}
				console.log( x );
				x++;
			});
		});
	}
	
	/* build our data */
	var data = {
		'action' : 'check_list_for_interest_groups',
		'list_id' : selected_list_id, /* grab the form ID to query the API for field data */
		'integration' : integration_type /* Pass the integration ie: contact form 7 */
	}; 
	/* submit our ajax request */
	jQuery.ajax({
		url: object_data.ajax_url,
		type: 'POST',
		data: data,
		dataType: 'html',
		success : function( response, textStatus, jqXHR) { 
			setTimeout( function() {
				/* Append the checkboxes */
				altered_list.parents( 'li' ).find( '.interest-groups-container' ).html( response );
			}, 500);
		},
		error : function( jqXHR, textStatus, errorThrown ) { 
			alert( textStatus+jqXHR.status+jqXHR.responseText+"..." ); 
		},
		complete : function( response, textStatus ) {
			
		}
	});
	console.log( selected_list_id );
}

function remove_interest_groups_for_list_checkbox_integrations( element ) {
	jQuery( element ).parents( 'label' ).next( '.integration-checkbox-interest-groups' ).slideUp( function() { jQuery( this ).remove(); });
}

function display_interest_groups_for_list_checkbox_integrations( element ) {

	// Display loading spinner
	display_interest_groups_loading_spinner( element );

	var list_id     = jQuery( element ).val();
	var integration = jQuery( element ).data( 'integration' ); 

	fetch_interest_groups_by_list_id( list_id, integration, element );
}

function fetch_interest_groups_by_list_id( list_id, integration_type, element ) {
	
	var data = {
		'action'      : 'check_list_for_interest_groups',
		'list_id'     : list_id, 
		'integration' : integration_type 
	}; 

	
	jQuery.ajax({
		url: object_data.ajax_url,
		type: 'POST',
		data: data,
		dataType: 'html',
		success : function( response, textStatus, jqXHR ) { 
			jQuery( element ).parents( 'label' ).children( '.interest-groups-loading' ).remove();
			jQuery( element ).parents( 'label' ).after( response );
		},
		error : function( jqXHR, textStatus, errorThrown ) { 
			alert( textStatus+jqXHR.status+jqXHR.responseText+"..." ); 
		},
		complete : function( response, textStatus ) {
			
		}
	});
}

function display_interest_groups_loading_spinner( element ) {

	// yikes-mailchimp-checkbox-integration-list

	jQuery( element ).parents( 'label' ).append( 
		'<p class="interest-groups-loading">' + object_data.locating_interest_groups + '<span class="upgrading-ellipse-one">.</span> \
			<span class="upgrading-ellipse-two">.</span><span class="upgrading-ellipse-three">.</span> \
			<img class="search-interest-group-preloader" src="'+ object_data.preloader_url + '" /> \
		</p>' 
	);
}


/**
*	Display/hide custom URL redirect input field
*	@since 6.0.4.7
*/
function shouldWeDisplayCustomURL( redirect_url ) {
	if( jQuery( redirect_url ).val() == 'custom_url' ) {
		jQuery( '.custom_redirect_url_label' ).fadeIn();
	} else {
		jQuery( '.custom_redirect_url_label' ).fadeOut();
	}
}

/**
* Wrapper function to call our collection of "show based on optin settings" functions
*/
function show_custom_messages_based_on_optin_settings() {

	// Highlight/show/hide appropriate fields
	show_custom_resub_messages_based_on_optin_settings();
	show_custom_success_messages_based_on_optin_settings();
}

/**
* Hide all of the resubscription messages, help sections, and email sections
*/
function yikes_mc_hide_custom_resub_messages() {

	// Email fields and email help section
	jQuery( '.yikes-easy-mc-custom-messages-email-section, .yikes-easy-mc-custom-messages-email-section-help' ).fadeOut();

	// Message fields
	jQuery( 'label[for="yikes-easy-mc-user-resubscribed-success-message"], label[for="yikes-easy-mc-user-subscribed-message"], \
			label[for="yikes-easy-mc-user-subscribed-update-link"]' ).hide();

	// Corresponding message field descriptions/help sections
	jQuery( '.yikes-easy-mc-user-resubscribed-success-message-help, .yikes-easy-mc-user-subscribed-message-help, \
			.yikes-easy-mc-user-subscribed-update-link-help' ).hide();

	// Email success/failure messages & help sections
	jQuery( 'label[for="yikes-easy-mc-update-email-successful"], label[for="yikes-easy-mc-update-email-failure"], \
			 .yikes-easy-mc-update-email-successful-help, .yikes-easy-mc-update-email-failure-help' ).hide();
}

/**
* Hide all of the double/single optin success messages and help sections
*/
function yikes_mc_hide_custom_optin_messages() {

	// Message fields
	jQuery( 'label[for="yikes-easy-mc-success-single-optin-message"], label[for="yikes-easy-mc-success-message"]' ).hide();

	// Corresponding message field descriptions/help sections
	jQuery( '.yikes-easy-mc-success-single-optin-message-help, .yikes-easy-mc-success-message-help' ).hide();
}

/**
* Show appropriate custom resubscription message (and email section) and custom message help sections based on the selected optin settings
*/
function show_custom_resub_messages_based_on_optin_settings() {

	yikes_mc_hide_custom_resub_messages();

	var update_existing_subscriber	= jQuery( '#update-user' ).is( ':checked' );
	var send_update_email			= jQuery( '#update-email' ).is( ':checked' );

	// If update existing subscriber is not checked, highlight "Error: Re-subscribers not permitted"
	if ( update_existing_subscriber === false ) {
		jQuery( 'label[for="yikes-easy-mc-user-subscribed-message"], .yikes-easy-mc-user-subscribed-message-help' ).fadeIn();
	}

	// If update existing subscriber is checked and send update email is checked, highlight "Success: Re-subscriber with link to email profile update message"
	if ( update_existing_subscriber === true && send_update_email === true ) {
		jQuery( 'label[for="yikes-easy-mc-user-subscribed-update-link"], .yikes-easy-mc-custom-messages-email-section, \
				.yikes-easy-mc-user-subscribed-update-link-help, .yikes-easy-mc-custom-messages-email-section-help, \
				label[for="yikes-easy-mc-update-email-successful"], label[for="yikes-easy-mc-update-email-failure"], \
				.yikes-easy-mc-update-email-successful-help, .yikes-easy-mc-update-email-failure-help' ).fadeIn();

	} 

	// If update existing subscriebr is checked and send update email is not checked, highlight "Success: Re-subscriber"
	if ( update_existing_subscriber === true && send_update_email === false ) {
		jQuery( 'label[for="yikes-easy-mc-user-resubscribed-success-message"], .yikes-easy-mc-user-resubscribed-success-message-help' ).fadeIn();
	}
}

/**
* Show appropriate custom success message and custom success message help sections based on the selected optin settings
*/
function show_custom_success_messages_based_on_optin_settings() {

	yikes_mc_hide_custom_optin_messages();

	var single_optin = jQuery( '#single' ).is( ':checked' );

	// If single optin is checked, highlight the "Success: Single opt-in" message
	if ( single_optin === true ) {
		jQuery( 'label[for="yikes-easy-mc-success-single-optin-message"], .yikes-easy-mc-success-single-optin-message-help ' ).fadeIn();

		// Also, hide the tags error message for now.
		jQuery( '#yikes-tags-error-message' ).fadeOut( 'slow', function() { jQuery( this ).addClass( 'hidden' ) });
	} else {

		// If double optin is checked, highlight the "Success: Double opt-in" message
		jQuery( 'label[for="yikes-easy-mc-success-message"], .yikes-easy-mc-success-message-help' ).fadeIn();

		// Also, show the tags error message for now.
		jQuery( '#yikes-tags-error-message' ).fadeIn( 'slow', function() { jQuery( this ).removeClass( 'hidden' ) });
	}
}