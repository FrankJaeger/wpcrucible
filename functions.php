<?php defined('ABSPATH') or die("No script kiddies please!");	// For security reasons.

require_once('includes/helper-classes.php');	// Include helper classes.
require_once('includes/shortcodes.php');		// Adds shortcodes.

global $fpwcr_dir;
global $fpwpcr_root;

$fpwpcr_dir = get_template_directory_uri();		// Get template directory.						// Get the root of WP.

$fpwpcr_theme = new fpwpcr_theme();			// Create theme object. For now it do nothing.
$fpwpcr_settings = new fpwpcr_settings();	// Create settings object that creates theme settings page in Appearance section of WP Menu.
$fpwpcr_plugins = new fpwpcr_plugins();		// Notice user to install required plugins.

/* *** Theme Options Page *** */

$fpwpcr_settings->add_section( __( 'Appearance', 'wpcrucible') , 'wpcr-admin-appearance-section', __( 'Here you can change the appearance of your site.', 'wpcrucible' ) );		// Adds appearance section of theme options page.
$fpwpcr_settings->add_section( __( 'Content', 'wpcrucible' ), 'wpcr-admin-content-section', __( 'Set as many sidebars you need. Too many of them may cause performance drop.' ) );		// Adds content section of theme options page.

// Appearance
$fpwpcr_settings->add_upload( __( 'Header Logo', 'wpcrucible' ), 'wpcr-header-logo', 'wpcr-admin-appearance-section', null, $fpwpcr_dir . '/images/logo.png' );		 		// Header logo upload field.
$fpwpcr_settings->add_upload( __( 'Footer Logo', 'wpcrucible' ), 'wpcr-footer-logo', 'wpcr-admin-appearance-section', null, $fpwpcr_dir . '/images/footer-logo.jpg' ); 	 		// Footer logo upload.
$fpwpcr_settings->add_upload( __( 'Hero Image', 'wpcrucible' ), 'wpcr-header-hero-image', 'wpcr-admin-appearance-section', null, $fpwpcr_dir . '/images/home-hero.jpg' ); 		// Hero image upload.
$fpwpcr_settings->add_color( __( 'Header Top Background', 'wpcrucible'), 'wpcr-main-top-header-bg-color', 'wpcr-admin-appearance-section', null, '#f0f0f0' );	// Menu and logo background
$fpwpcr_settings->add_color( __( 'Backgroung Color', 'wpcrucible' ), 'wpcr-main-background-color', 'wpcr-admin-appearance-section', null, '#ffffff' ); 	// BG color picker.
$fpwpcr_settings->add_color( __( 'Theme\'s Main Color', 'wpcrucible' ), 'wpcr-main-color', 'wpcr-admin-appearance-section', null, '#cd261e' ); 		// Change main color of theme.
$fpwpcr_settings->add_checkbox( __( 'Parallax Effect', 'wpcrucible' ), 'wpcr-main-parallax', 'wpcr-admin-appearance-section', null, 1 ); 	// Use parallax ?
$fpwpcr_settings->add_checkbox( __( 'B/W Header Image', 'wpcrucible' ), 'wpcr-main-bw', 'wpcr-admin-appearance-section', null, 1 ); 	//  Black/white header image ?
$fpwpcr_settings->add_textarea( __( 'Custom CSS', 'wpcrucible' ), 'wpcr-main-css', 'wpcr-admin-appearance-section', null, '' );				// Place for custom CSS.

// //Content
$fpwpcr_settings->add_range( __( 'Sidebars Count', 'wpcrucible' ), 'wpcr-content-sidebars', 'wpcr-admin-content-section', null, 0, 50, 1 );	// Count of sidebars.

$fpwpcr_settings->make_my_settings();	// Draw the sections, fields and add default values to options that not exists in the database.

/* *** Theme Content *** */

$fpwpcr_theme->add_sidebars( (int)$fpwpcr_settings->get_value( 'wpcr-content-sidebars' ) );		// Adds as many sidebars as configured in options page.

$fpwpcr_theme->add_styles_and_scripts();	// Enqueue required scripts.
$fpwpcr_theme->add_filters();				// Adds required filters.

$fpwpcr_theme->init(); // Make the things that are configured in theme class, like adding sidebars and menus.

$fpwpcr_metaboxes = new fpwpcr_metaboxes( (int)$fpwpcr_settings->get_value( 'wpcr-content-sidebars' ) );	// Create sidebar metabox at post( page ) edit page and pass number of created sidebars.

?>