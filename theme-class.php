<?php defined('ABSPATH') or die("No script kiddies please!");

class fpwpcr_theme {

	protected static $config = array();

	public function add_menu( $slug, $name ) {		// Adding new menu area in WordPress.
		self::$config['menus'][$slug] = $name;		// Example: $theme->add_menu( 'menu-1', 'Main Menu' ); 
	} 


	public function add_sidebars( $count = 1, $name = false, $class = false ) {		// Adding new sidebar area(s) in WordPress Widgets menu. No args required. Default creates one sidebar.
																					// Example: $theme->add_sidebars(2, 'My Sidebar %d', 'custom-sidebar');
		$args = array(																
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 	=> '</aside>',
			);

		if ($name)  $args['name'] = $name;
		if ($class) $args['class'] = $class;

		self::$config['sidebars'][] = array( 'count' => $count, 'args' => $args );

	}


	public function init() {	
									// Make full theme initialization, based on provided configuration.
		if ( array_key_exists( 'menus', self::$config ) ) {		// When at least one menu is added
			register_nav_menus( self::$config['menus'] );		// Register them
		}

		if ( array_key_exists( 'sidebars', self::$config ) ) {					// When at least one sidebar is added
			foreach ( self::$config['sidebars'] as $sidebars ) {				// For each sidebar
				register_sidebars( $sidebars['count'], $sidebars['args'] );		// Register it
			}
		}



	}


}

class fpwpcr_settings extends fpwpcr_theme {

	public function __construct() {
		add_action( 'admin_menu', array( &$this, 'add_theme_options' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_styles' ));
	}




	public function add_theme_options() {
				add_theme_page( 
				__('WP Crucible, Theme options', 'wpcrucible'),
				__('Theme Options', 'wpcrucible'),
				'edit_theme_options',
				'fpwpcr-wpcrucible-options',
				array( &$this, 'display_options_page' )
				);
	}


	public function display_options_page() {
		if ( !current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wpcrucible' ) );
		}

		echo '<div class="wrap"><div class="wpcr-admin-title"><img class="wpcr-admin-logo" src="'.get_template_directory_uri().'/images/admin-logo.png" alt="WP Crucible" /><h2 class="wpcr-admin-title_text">' . __('WP Crucible Themes Options', 'wpcrucible') . '</h2></div>';
		echo '<form method="post" action="options.php">';
		
		echo '</form></div>';
	}


	public function enqueue_admin_styles() {
		wp_enqueue_style( 'wpcr-admin-css', get_template_directory_uri() . '/css/admin.css', false, '1.0' );
	}

}


?>