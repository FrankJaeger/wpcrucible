<?php defined('ABSPATH') or die("No script kiddies please!");
require_once( 'includes/helper-classes.php' ); 
	$s = new fpwpcr_settings();
	$t = new fpwpcr_theme();
	$logo_uri = esc_url( $s->get_value( 'wpcr-header-logo' ) );
	$menu_args = array(
		'theme_location'  => 'wpcr-main-main-menu',
		'container' 	  => 'div',
		'container_class' => 'menu header-top_menu',
		'menu_class'	  => 'menu-items'
		);
	$header_title = ( get_post_meta( get_the_ID(), 'wpcr-admin-page-metabox-header-title', true ) != '' ) ? esc_html( get_post_meta( get_the_ID(), 'wpcr-admin-page-metabox-header-title', true ) ) : get_the_title();
	$header_subtitle = esc_html ( get_post_meta( get_the_ID(), 'wpcr-admin-page-metabox-header-subtitle', true ) );
	$title_white = empty( $header_subtitle ) ? ' hero-image_content-title_white' : '';	// white title when subtitle's not set fix.
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset'); ?>" />
		<title><?php wp_title(''); ?></title>
		<?php wp_head(); ?>
		<style type="text/css"><?php echo $s->get_the_user_css(); ?></style>
	</head>
	<body <?php body_class(); ?>>
		<div class="header">
			<div class="header-top">
				<div class="content-limiter">
					<div class="logo header-top_logo">
						<span class="vertical-helper"></span>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img class="logo-image" src="<?php echo $logo_uri; ?>" alt="<?php bloginfo( 'name' ); ?>" />
						</a>
					</div>
					<?php wp_nav_menu( $menu_args ); ?>
				</div>
			</div>
		    <div class="hero-image">
		    	<div class="hero-image_background hero-image_overlay"></div>
		    	<div class="hero-image_overlay-bottom hero-image_overlay"></div>
		    	<div class="hero-image_overlay-top hero-image_overlay"></div>
		    	<div class="content-limiter">
		    		<span class="vertical-helper"></span>
		    		<div class="hero-image_content">
		    				<p class="hero-image_content-title<?php echo $title_white; ?>"><?php echo $header_title; ?></p>
		    				<?php if ( $header_subtitle != '' ) { ?>
		    				<p class="hero-image_content-subtitle"><?php echo $header_subtitle; ?></p>
		    				<?php } ?>
		    			<hr class="hero-image_content-underline hr-underline" />
		    			<a class="hero-image_content-arrow" href="#container" title="Scroll down."><i class="fa fa-angle-down"></i></a>
		    		</div>
		    	</div>
		    </div>
		</div>
		<div class="gapper"></div>
		<div class="container" id="container">