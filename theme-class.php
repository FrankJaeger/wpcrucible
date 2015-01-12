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

		self::$config['sidebars'][] = array( 'count' => $count, 'args' => $args );	// Gets provided args and passing it to the $config.

	}


	public function init() {									// Make full theme initialization, based on provided configuration.
																// When at least one menu is added.
		if ( array_key_exists( 'menus', self::$config ) ) {		// register them.
			register_nav_menus( self::$config['menus'] );		
		}

		if ( array_key_exists( 'sidebars', self::$config ) ) {					// When at least one sidebar is added,
			foreach ( self::$config['sidebars'] as $sidebars ) {				// for each sidebar
				register_sidebars( $sidebars['count'], $sidebars['args'] );		// register it.
			}
		}

	}


}

class fpwpcr_settings extends fpwpcr_theme {					// Extends theme class by settings page functionality.	

	private $options;			
																	
	public function __construct() {														// Creating Theme Options page, when object of class is created.
		add_action( 'admin_menu', array( &$this, 'add_theme_options' ) );				// Calling callback function to draw the settings page.
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_styles' ));	// Providing some backend CSS.
	}


	public function add_section( $title, $slug, $info = '' ) {												// Adds settings section, ex: $settings->add_section( 'My section', 'prefix-my-section-slug', 'This is my settings section' );
		parent::$config['sections'][] = array( 'title' => $title, 'slug' => $slug, 'info' => $info );		// Get the args and pass it to the $config.
	}


	public function add_text_field( $title, $slug, $section_slug ) {
		parent::$config['textfields'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug );
	}


	public function make_my_settings() {											// Must be called after setup. Doing all the settings stuff like adding sections, fields, registering data etc.
		add_action( 'admin_init', array( &$this, 'make_my_settings_callback') );	// Do the settings page!
	}





	public function add_theme_options() {						// Theme page creating callback.
				add_theme_page( 
				__('WP Crucible, Theme options', 'wpcrucible'),
				__('Theme Options', 'wpcrucible'),
				'edit_theme_options',
				'fpwpcr-wpcrucible-options',
				array( &$this, 'display_options_page' )
				);
	}


	public function display_options_page() {																	// Callback function responsible for drawing settings page. Here's the HTML.
		if ( !current_user_can( 'edit_theme_options' ) ) {														// Die when user doesn't have required permissions.
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wpcrucible' ) );	
		}

		$this->options = get_option( 'fpwpcr-theme-options-values' );

		echo '<div class="wrap"><div class="wpcr-admin-title"><img class="wpcr-admin-logo" src="'.get_template_directory_uri().'/images/admin-logo.png" alt="WP Crucible" /><h2 class="wpcr-admin-title_text">' . __('WP Crucible Themes Options', 'wpcrucible') . '</h2></div>';
		echo '<form method="post" action="options.php">';
		settings_fields( 'fpwpcr-theme-options-group' );			// Register sections and fields that will be created next to fpwpcr-theme-options-group options group.
		do_settings_sections( 'fpwpcr-wpcrucible-options' );		// Prints settings sections.
		submit_button( null, 'wpcr-save-button' );				// Prints submit button.
		echo '</form></div>';
	}


	public function enqueue_admin_styles() {																	// Admin CSS callback.
		wp_enqueue_style( 'wpcr-admin-css', get_template_directory_uri() . '/css/admin.css', false, '1.0' );
	}


	public function make_my_settings_callback() {	// Action callback. Here all of fields, sections etc. are adding.
		register_setting(							// Register settings of all of WP Crucible settings fields and store it in fpwpcr-theme-options-values.
			'fpwpcr-theme-options-group',
			'fpwpcr-theme-options-values'
			);

		if ( array_key_exists( 'sections', parent::$config ) ) {			// Adds sections if defined.
			foreach ( parent::$config['sections'] as $section ) {
				add_settings_section(
					$section['slug'],
					$section['title'],
					function() use ( $section ) { echo $section['info']; },
					'fpwpcr-wpcrucible-options'
					);
			}
		}

		if ( array_key_exists( 'textfields', parent::$config ) ) {			// Adds text fields if defined.
			foreach ( parent::$config['textfields'] as $textfield ) {
				add_settings_field(
					$textfield['slug'],
					$textfield['title'],
					function() use ( $textfield ) {
						printf(
							'<input type="text" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" />',
							$textfield['slug'],
							isset( $this->options[$textfield['slug']] ) ? esc_attr( $this->options[$textfield['slug']] ) : ''
							);
					},
					'fpwpcr-wpcrucible-options',
					$textfield['section-slug']
					);
			}
		}

	}


}


?>