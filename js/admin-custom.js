 jQuery(document).ready(function($) {

 	/* Uploader support */

 	var uploader;

 	$( '.wpcr-admin-upload_label .wpcr-admin-upload_button' ).on( 'click', function(e) {	// When upload button is clicked
 		e.preventDefault();		// prevent it from default click action.
 		textfield = $( this ).parent().find( '.wpcr-admin-upload_textfield' );		// Store textfield attached to clicked button object.

 		if ( uploader ) {		// When uploader object is already created, reopen it and exit from function.
 			uploader.open();
 			return;
 		}

 		uploader = wp.media.frames.file_frame = wp.media({		// Creates uploader object.
 			multiple: false		// No multiple files upload!
 		}); 

 		uploader.on( 'select', function() {		// When image is selected grab its URL and return to the textfield.
 			attachment = uploader.state().get( 'selection' ).first().toJSON(); 
 			textfield.val( attachment.url );
 		});

 		uploader.open();		// Open the uploader.

 	});

 	/* Uploader end */

 });

 jQuery( function( $ ) {

 	/* Page Sidebar Metabox Support */

 	var selected,
    metabox = $( '#wpcr-admin-page-metabox-sidebar, #wpcr-admin-page-metabox-bottom' ),	// Metabox object.
    template_selector = $( '#page_template' );			// Template select object.
  
	( template_selector.val() == 'page-sidebar.php' ) ? metabox.show() : metabox.hide();  // Show or Hide metabox at page loads.
  
  	$( template_selector ).on( 'change', function() {	// On change check if selected template is "Sidebar" and show metabox when is and hide when is no.
    	selected = $(this).val();
    
    	if ( selected == 'page-sidebar.php' ) {
    		metabox.show( 500 );
		} else {
			metabox.hide( 500 );
		}
  	});

  	/* Metabox End */
});