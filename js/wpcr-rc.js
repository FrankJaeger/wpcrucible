function fpwpcr_get_rc_data() {

	data = {
		id: fpwpcr_rc_data.post_id,
		root: fpwpcr_rc_data.root
	};

jQuery.post( fpwpcr_rc_data.rcs, data, function( response ) {
	jQuery( '.wpcf7' ).html( response );
});

}
	
