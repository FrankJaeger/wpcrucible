<?php

require_once( $_POST['root'] . 'wp-load.php' );

	$rc = get_post_meta( (int) $_POST['id'], 'wpcr_restricted_content', true );
	echo do_shortcode( $rc );

?>