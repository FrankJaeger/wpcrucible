<?php defined('ABSPATH') or die("No script kiddies please!");

add_shortcode( 'wpcr_section', 'fpwpcr_section_callback' );
add_shortcode( 'wpcr_left', 'fpwpcr_left_callback' );
add_shortcode( 'wpcr_right', 'fpwpcr_right_callback' );
add_shortcode( 'wpcr_image', 'fpwpcr_image_callback' );
add_shortcode( 'wpcr_content', 'fpwpcr_content_callback');
add_shortcode( 'wpcr_button', 'fpwpcr_button_callback');
add_shortcode( 'wpcr_c', 'fpwpcr_c_callback' );
add_shortcode( 'wpcr_list', 'fpwpcr_list_callback' );
add_shortcode( 'wpcr_banner', 'fpwpcr_banner_callback' );
add_shortcode( 'wpcr_gapper', 'fpwpcr_gapper_callback' );
add_shortcode( 'wpcr_restrict', 'fpwpcr_restrict_callback' );

function fpwpcr_section_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'title'   => '',
				'padded'  => '',
				'bg'	  => '',
				'height'  => '',
				'att'	  => '',
				'class'	  => ''
				), $atts );
	$padded = '';
	$att = '';

	switch ( $args['padded'] ) {
		case 'h':
			$padded = 'sections-block_hpadded';
			break;
		case 'v':
			$padded = 'sections-block_vpadded';
			break;
		case 'b':
			$padded = 'sections-block_hpadded sections-block_vpadded';
			break;
	}
	
	switch ( $args['att'] ) {
		case 'fixed':
			$att = 'background-attachment: fixed;';
			break;
		case 'scroll':
			$att = 'background-attachment: scroll;';
	}
	
	$title = empty( $args['title'] ) ? '' : sprintf('<div class="section-title">
					<div class="content-limiter">
						<div class="section-title_shadow"></div>
						<div class="vertical-helper"></div>
						<div class="section-title_full">
							<p class="section-title_text">%s</p>
							<hr class="section-title_underline hr-underline" />
						</div>
					</div>
				</div>', esc_html( $args['title'] ) );
	
	$bg = empty( $args['bg'] ) ? '' : 'background: url(\'' . esc_url( $args['bg'] ) . '\'); background-size: cover;';
	$height = empty( $args['height'] ) ? '' : 'height:' . $args['height'] . ';';
	$class = esc_attr( $args['class'] );
	$section = sprintf('<div class="sections section-full %6$s" style="%3$s%4$s%5$s">
		<div class="content-limiter %2$s">
		%1$s
		</div>
	</div>', $content, $padded, $bg, $att, $height, $class );

 	return $title . do_shortcode( $section );
}

function fpwpcr_left_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'padded'    => '',
				'height'    => '',
				'class'	    => '',
				'vcentered' => 0
				), $atts );
	$padded = '';
	$vc = '';

	switch ( $args['padded'] ) {
		case 'h':
			$padded = 'sections-block_hpadded';
			break;
		case 'v':
			$padded = 'sections-block_vpadded';
			break;
		case 'b':
			$padded = 'sections-block_hpadded sections-block_vpadded';
			break;
	}
	
	foreach ( $args as $arg ) {
		if ( $arg == 'vcentered' ) $vc = 'vertical-align:middle;';
	}
	
	$height = empty( $args['height'] ) ? '' : 'height:' . $args['height'] . ';';
	$class = esc_attr( $args['class'] );
	$section = sprintf('<div class="section-hbh_left sections-block %2$s %4$s" style="%3$s%5$s">%1$s</div>', $content, $padded, $height, $class, $vc );

 	return do_shortcode( $section );
}

function fpwpcr_right_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'padded'  	=> '',
				'height'  	=> '',
				'class'	 	=> '',
				'vcentered' => 0
				), $atts );
	$padded = '';
	$vc = '';

	switch ( $args['padded'] ) {
		case 'h':
			$padded = 'sections-block_hpadded';
			break;
		case 'v':
			$padded = 'sections-block_vpadded';
			break;
		case 'b':
			$padded = 'sections-block_hpadded sections-block_vpadded';
			break;
	}
	
	foreach ( $args as $arg ) {
		if ( $arg == 'vcentered' ) $vc = 'vertical-align:middle;';
	}
	
	$height = empty( $args['height'] ) ? '' : 'height:' . $args['height'] . ';';
	$class = esc_attr( $args['class'] );
	$section = sprintf('<div class="section-hbh_right sections-block %2$s %4$s" style="%3$s%5$s">%1$s</div>', $content, $padded, $height, $class, $vc );

 	return do_shortcode( $section );
}

function fpwpcr_image_callback( $atts ) {
	$args = shortcode_atts( array(
				'height'  	=> '',
				'width' 	=> '',
				'class'	 	=> '',
				'src'		=> '',
				'alt'		=> 'Picture',
				'centered'	=> 0
				), $atts );
	$c = '';
		foreach ( $args as $arg ) {
		if ( $arg == 'centered' ) $c = 'style="text-align:center;"';
	}

	$height = empty( $args['height'] ) ? '' : 'height:' . $args['height'] . ';';
	$width = empty( $args['width'] ) ? '' : 'width:' . $args['width'] . ';';
	$class = esc_attr( $args['class'] );
	$section = sprintf('<div class="single-image" %6$s><img class="single-image_picture %3$s" src="%4$s" alt="%5$s" style="%1$s%2$s" /></div>', $height, $width, $class, esc_url( $args['src'] ), esc_html( $args['alt'] ), $c );

 	return  $section;
}

function fpwpcr_content_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'title'			=> '',
				'title_type'	=> 'small',
				'description'	=> '',
				'class'			=> ''
				), $atts );

	$full = ( !empty( $args['title'] ) && !empty( $args['description'] ) ) ? '<div class="content-title_full">' : '';
	$full_close = ( !empty( $full ) ) ? '</div>' : '';
	$title_type = ( $args['title_type'] == 'big' ) ? ' content-title_big' : '';
	$title = ( !empty( $args['title'] ) ) ? '<div class="content-title'. $title_type .'"><p class="content-title_text">// '. esc_html( $args['title'] ) .'</p></div>' : '';
	$description = ( !empty( $args['description'] ) ) ? '<div class="content-description"><p class="content-description_text">'. esc_html( $args['description'] ) .'</p><hr class="content-description_underline hr-underline" /></div>' : '';

	$section = sprintf('%2$s%4$s%5$s%3$s<div class="content %6$s">%1$s</div>', $content, $full, $full_close, $title, $description, esc_attr( $args['class'] ) );

 	return do_shortcode( $section );
}

function fpwpcr_button_callback( $atts ) {
	$args = shortcode_atts( array(
				'url'	=> '',
				'type'	=> 'small',
				'text'	=> '',
				'class'	=> ''
				), $atts );
	$type = ( $args['type'] == 'big' ) ? 'big-red-button' : 'mid-red-button';

	$section = sprintf('<a class="button %3$s %4$s" href="%2$s">%1$s</a>', esc_html( $args['text'] ), esc_url( $args['url'] ), $type, esc_attr( $args['class'] ) );

 	return $section;
}

function fpwpcr_c_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'class'	=> ''
				), $atts );

	$section = sprintf('<span class="wpcr-main-theme-color %2$s">%1$s</span>', $content, esc_attr( $args['class'] ) );

 	return do_shortcode( $section );
}

function fpwpcr_list_callback( $atts, $content = null ) {
	$args = shortcode_atts( array(
				'class'	=> ''
				), $atts );

	$items = explode( PHP_EOL, $content );
	$ul_elements = '';
	$i = 0;

	foreach ( $items as $item ) {
		$ul_elements .= ( strip_tags( $item ) != '' ) ? sprintf('<li class="wpcr-list-item wpcr-list-item-%1$s"><span class="wpcr-list-item-text">%2$s</span></li> ', $i++, $item ) : '';
	}

	$section = sprintf('<ul class="%1$s %2$s">%3$s</ul>', 'wpcr-list', esc_attr( $args['class'] ), $ul_elements );

 	return do_shortcode( $section );
}

function fpwpcr_banner_callback( $atts ) {
	$args = shortcode_atts( array(
				'text'   => '',
				'b_text' => '',
				'b_url'	 => '',
				'class'	 => ''
				), $atts );

	$section = sprintf('<div class="section-title section-title_custom-contact %4$s">
				<div class="content-limiter">
					<div class="vertical-helper"></div>
					<div class="section-title_full">
						<p class="section-title_text">%1$s</p>
						<hr class="section-title_underline hr-underline section-title_underline-custom_contact" />
						<a class="button big-red-button" href="%3$s">%2$s</a>
					</div>
				</div>
			</div>', esc_html( $args['text'] ), esc_html( $args['b_text'] ), esc_url( $args['b_url'] ), esc_attr( $args['class'] )  );

 	return do_shortcode( $section );
}

function fpwpcr_gapper_callback( $atts ) {
 	return '<div class="gapper"></div>';
}

function fpwpcr_restrict_callback( $atts, $content = null ) {

	$id = get_option( 'fpwpcr_restricted_cf_added' );

	$section = do_shortcode( '[contact-form-7 id="'. $id .'" title="Download Form"]' );

	update_post_meta( get_the_ID(), 'wpcr_restricted_content', $content );

	global $fpwpcr_dir;

	wp_register_script( 'wpcr-customjs', $fpwpcr_dir . '/js/wpcr-rc.js' );
	wp_localize_script( 'wpcr-customjs', 'fpwpcr_rc_data', array(
		'rcs' => $fpwpcr_dir . '/helpers/getrc.php',
		'post_id' 	=> get_the_ID(),
		'root'		=> ABSPATH
		)
		);
	wp_enqueue_script( 'wpcr-customjs' );

 	return $section;
}

?>