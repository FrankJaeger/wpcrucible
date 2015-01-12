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