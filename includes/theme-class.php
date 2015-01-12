<?php defined('ABSPATH') or die("No script kiddies please!");

class fpwpcr_theme {

	protected static $config = array();

	public function __construct() {
		load_theme_textdomain( 'wpcrucible', get_template_directory_uri() . 'locale' );
	}

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

		if ( array_key_exists( 'sidebars', self::$config ) ) {					// When at least one sidebar is added.
			foreach ( self::$config['sidebars'] as $sidebars ) {				// for each sidebar
				register_sidebars( $sidebars['count'], $sidebars['args'] );		// register it.
			}
		}

	}


	protected final function is_in_array( $value, $array, $strict = false ) {		// Helper function to search in multidimensional arrays.
		foreach ( $array as $item ) {
			if ( ($strict ? $item === $value : $item == $value ) || ( is_array( $item ) && $this->is_in_array( $value, $item, $strict ) ) ) {
				return true;
			}
		}
		return false;
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


	public function add_text_field( $title, $slug, $section_slug, $class = 'wpcr-admin-textfield' ) {											// Adds textfield to specified settings section, ex: $settings->add_text_field( 'My textfield', 'prefix-new-textfield-slug', 'settings-section-slug', 'custom-css-class' );
		parent::$config['textfields'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class );		// Get the args and pass it to the $config.
	}

	public function add_textarea( $title, $slug, $section_slug, $class = 'wpcr-admin-textarea' ) {											// Adds textarea to specified settings section, ex: $settings->add_textarea( 'My textarea', 'prefix-new-textarea-slug', 'settings-section-slug', 'custom-css-class' );
		parent::$config['textareas'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class );		// Get the args and pass it to the $config.
	}

	public function add_range( $title, $slug, $section_slug, $class = 'wpcr-admin-range', $min = 0, $max = 50 ) {											// Adds textarea to specified settings section, ex: $settings->add_range( 'My range', 'prefix-new-range-slug', 'settings-section-slug', 'custom-css-class', 0, 50 );
		parent::$config['ranges'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'min' => $min, 'max' => $max );		// Get the args and pass it to the $config.
	}

	public function add_number( $title, $slug, $section_slug, $class = 'wpcr-admin-number', $min = 0, $max = 50 ) {											// Adds textarea to specified settings section, ex: $settings->add_number( 'My number', 'prefix-new-number-slug', 'settings-section-slug', 'custom-css-class', 0, 50 );
		parent::$config['numbers'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'min' => $min, 'max' => $max );		// Get the args and pass it to the $config.
	}

	public function add_color( $title, $slug, $section_slug, $class = 'wpcr-admin-color' ) {											// Adds color to specified settings section, ex: $settings->add_color( 'My color', 'prefix-new-color-slug', 'settings-section-slug', 'custom-css-class' );
		parent::$config['colors'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class );		// Get the args and pass it to the $config.
	}

	public function add_upload( $title, $slug, $section_slug, $class = 'wpcr-admin-upload' ) {											// Adds upload to specified settings section, ex: $settings->add_upload( 'My upload', 'prefix-new-upload-slug', 'settings-section-slug', 'custom-css-class' );
		parent::$config['uploads'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class );		// Get the args and pass it to the $config.
	}

	public function get_value( $slug ) {	// Gets the value of provided field.
		$this->options = get_option( 'fpwpcr-theme-options-values' );
		return $this->options[$slug];
	}


	public function make_my_settings() {											// Must be called after setup. Doing all the settings stuff like adding sections, fields, registering data etc.
		add_action( 'admin_init', array( &$this, 'make_my_settings_callback') );	// Do the settings page!
		add_action( 'admin_notices', array(&$this, 'admin_notices_callback' ) ); 	// Adds support for notice and error messages.
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

		$this->options = get_option( 'fpwpcr-theme-options-values' );			// Get already saved options values.

		print( '<div class="wrap"><div class="wpcr-admin-title">
					<img class="wpcr-admin-logo" src="'.get_template_directory_uri().'/images/admin-logo.png" alt="WP Crucible" />
					<h2 class="wpcr-admin-title_text">' . __('WP Crucible Theme Options', 'wpcrucible') . '</h2>
				</div>
				<form method="post" action="options.php">' );
		settings_fields( 'fpwpcr-theme-options-group' );			// Register sections and fields that will be created next to fpwpcr-theme-options-group options group.
		do_settings_sections( 'fpwpcr-wpcrucible-options' );		// Prints settings sections.
		submit_button( null, 'wpcr-save-button' );				// Prints submit button.
		print( '</form></div>' );
	}


	public function enqueue_admin_styles() {					// Admin CSS and scripts callback.
		wp_enqueue_media();																
		wp_register_style( 'wpcr-admin-css', get_template_directory_uri() . '/css/admin.css', null, '1.2' );
		wp_enqueue_style( 'wpcr-admin-css' );
		wp_register_script('wpcr-admin-customjs', get_template_directory_uri() . '/js/admin-custom.js', array( 'jquery') );
		wp_enqueue_script( 'wpcr-admin-customjs' );
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
				$checklist = array();
				
				if ( parent::is_in_array( $textfield['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_text_field@' . $textfield['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$textfield['slug'],
						$textfield['title'],
						function() use ( $textfield ) {
							printf(
								'<input type="text" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" class="%3$s" />',
								$textfield['slug'],
								isset( $this->options[$textfield['slug']] ) ? esc_attr( $this->options[$textfield['slug']] ) : '',
								$textfield['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$textfield['section-slug']
						);
				}
			}
		}

		if ( array_key_exists( 'textareas', parent::$config ) ) {			// Adds textareas if defined.
			foreach ( parent::$config['textareas'] as $textarea ) {
				$checklist = array();
				
				if ( parent::is_in_array( $textarea['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_textarea@' . $textarea['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$textarea['slug'],
						$textarea['title'],
						function() use ( $textarea ) {
							printf(
								'<textarea id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s">%2$s</textarea>',
								$textarea['slug'],
								isset( $this->options[$textarea['slug']] ) ? esc_attr( $this->options[$textarea['slug']] ) : '',
								$textarea['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$textarea['section-slug']
						);
				}
			}
		}

		if ( array_key_exists( 'ranges', parent::$config ) ) {			// Adds ranges if defined.
			foreach ( parent::$config['ranges'] as $range ) {
				$checklist = array();
				
				if ( parent::is_in_array( $range['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_range@' . $range['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$range['slug'],
						$range['title'],
						function() use ( $range ) {
							$amount_id = 'wpcr_admin_amount_' . str_replace( '-', '_', $range['slug']);
							printf(
								'<input type="range" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s" value="%2$s" min="%4$s" max="%5$s" oninput="%6$s.value=this.value" />
								<output id="%6$s" name="%6$s" for="%1$s" class="wpcr-admin-amount">%2$s</output>',
								$range['slug'],
								isset( $this->options[$range['slug']] ) ? esc_attr( $this->options[$range['slug']] ) : '',
								$range['class'],
								$range['min'],
								$range['max'],
								$amount_id
								);
						},
						'fpwpcr-wpcrucible-options',
						$range['section-slug']
						);
				}
			}
		}

		if ( array_key_exists( 'numbers', parent::$config ) ) {			// Adds numbers if defined.
			foreach ( parent::$config['numbers'] as $number ) {
				$checklist = array();
				
				if ( parent::is_in_array( $number['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_number@' . $number['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$number['slug'],
						$number['title'],
						function() use ( $number ) {
							printf(
								'<input type="number" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s" value="%2$s" min="%4$s" max="%5$s" />',
								$number['slug'],
								isset( $this->options[$number['slug']] ) ? esc_attr( $this->options[$number['slug']] ) : '',
								$number['class'],
								$number['min'],
								$number['max']
								);
						},
						'fpwpcr-wpcrucible-options',
						$number['section-slug']
						);
				}
			}
		}

		if ( array_key_exists( 'colors', parent::$config ) ) {			// Adds colors if defined.
			foreach ( parent::$config['colors'] as $color ) {
				$checklist = array();
				
				if ( parent::is_in_array( $color['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_color@' . $color['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$color['slug'],
						$color['title'],
						function() use ( $color ) {
							printf(
								'<input type="color" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" class="%3$s" />',
								$color['slug'],
								isset( $this->options[$color['slug']] ) ? esc_attr( $this->options[$color['slug']] ) : '',
								$color['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$color['section-slug']
						);
				}
			}
		}


		if ( array_key_exists( 'uploads', parent::$config ) ) {			// Adds uploads if defined.
			foreach ( parent::$config['uploads'] as $upload ) {
				$checklist = array();
				
				if ( parent::is_in_array( $upload['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_upload@' . $upload['slug'] .': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
					$checklist[] = false;
				}

				foreach ( $checklist as $check ) {
					$check_passed = $check;
					if ( !$check_passed ) {
						break;
					}
				}

				if ( $check_passed ) {		// If there's no errors, add the field.
					add_settings_field(
						$upload['slug'],
						$upload['title'],
						function() use ( $upload ) {
							printf(
								'<label class="%3s_label wpcr-admin-upload_label" for="%1$s">
								<input type="text" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" class="%3$s wpcr-admin-upload_textfield" />
								<input type="button" class="%3$s_button wpcr-admin-upload_button" value="'. __( 'Open Uploader',  'wpcrucible' ) .'" />
								<br />Enter URL or upload a file.
								</label>',
								$upload['slug'],
								isset( $this->options[$upload['slug']] ) ? esc_attr( $this->options[$upload['slug']] ) : '',
								$upload['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$upload['section-slug']
						);
				}
			}
		}


	}


	public function admin_notices_callback() {							// Prints admin notices.
		if ( array_key_exists( 'admin-errors', parent::$config ) ) {
			foreach ( parent::$config['admin-errors'] as $error ) {
				print(
					'<div class="error">
						<p>' . $error . '</p>
					 </div>'
					 );
			}
		}
	}


}


?>