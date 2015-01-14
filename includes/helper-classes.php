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

	public function add_styles_and_scripts() {
		add_action( 'wp_enqueue_scripts', array( &$this, 'add_styles_and_scripts_callback' ) );
	}

	public function init() {									// Make full theme initialization, based on provided configuration.
		add_action( 'after_setup_theme', array( &$this, 'init_callback' ) );
	}

	public function add_filters() {
		add_filter( 'wp_title', function( $title ) {
			if ( empty( $title ) && ( is_home() || is_front_page() ) ) {
				return get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
			}
			return $title;
		});
	}


	public function init_callback() {
		load_theme_textdomain( 'wpcrucible', get_template_directory_uri() . '/locale' ); 		// Load translations textdomain.
		register_nav_menu( 'wpcr-main-main-menu', __( 'Primary Header Menu', 'wpcrucible' ) );		// Register primary header menu.												
		if ( array_key_exists( 'menus', self::$config ) ) {									// When at least one menu is added.
			register_nav_menus( self::$config['menus'] );									// register them.
		}

		if ( array_key_exists( 'sidebars', self::$config ) ) {					// When at least one sidebar is added.
			foreach ( self::$config['sidebars'] as $sidebars ) {				// for each sidebar
				register_sidebars( $sidebars['count'], $sidebars['args'] );		// register it.
			}
		}

		add_theme_support('post-thumbnails'); 	// Add thumbnails support.
	}

	public function add_styles_and_scripts_callback() { 
		global $fpwpcr_dir;
		wp_enqueue_style( 'wpcr-style', get_stylesheet_uri() );
		wp_enqueue_style( 'font-awesome', $fpwpcr_dir . '/css/font-awesome.min.css' );
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


	public function add_text_field( $title, $slug, $section_slug, $class = 'wpcr-admin-textfield', $default ) {											// Adds textfield to specified settings section, ex: $settings->add_text_field( 'My textfield', 'prefix-new-textfield-slug', 'settings-section-slug', 'custom-css-class', 'some text' );
		parent::$config['textfields'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function add_textarea( $title, $slug, $section_slug, $class = 'wpcr-admin-textarea', $default ) {											// Adds textarea to specified settings section, ex: $settings->add_textarea( 'My textarea', 'prefix-new-textarea-slug', 'settings-section-slug', 'custom-css-class', 'some text' );
		parent::$config['textareas'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function add_range( $title, $slug, $section_slug, $class = 'wpcr-admin-range', $min = 0, $max = 50, $default ) {											// Adds textarea to specified settings section, ex: $settings->add_range( 'My range', 'prefix-new-range-slug', 'settings-section-slug', 'custom-css-class', 0, 50, 4 );
		parent::$config['ranges'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'min' => $min, 'max' => $max, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function add_number( $title, $slug, $section_slug, $class = 'wpcr-admin-number', $min = 0, $max = 50, $default ) {											// Adds textarea to specified settings section, ex: $settings->add_number( 'My number', 'prefix-new-number-slug', 'settings-section-slug', 'custom-css-class', 0, 50, 4 );
		parent::$config['numbers'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'min' => $min, 'max' => $max, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function add_color( $title, $slug, $section_slug, $class = 'wpcr-admin-color', $default ) {											// Adds color to specified settings section, ex: $settings->add_color( 'My color', 'prefix-new-color-slug', 'settings-section-slug', 'custom-css-class', '#ff00ff' );
		parent::$config['colors'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'default' => $default );		// Get the args and pass it to the $config.
	}
	
	public function add_checkbox( $title, $slug, $section_slug, $class = 'wpcr-admin-checkbox', $default ) {											// Adds checkbox to specified settings section, ex: $settings->add_checkbox( 'My checkbox', 'prefix-new-checkbox-slug', 'settings-section-slug', 'custom-css-class', 1 );
		parent::$config['checkboxs'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function add_upload( $title, $slug, $section_slug, $class = 'wpcr-admin-upload', $default ) {											// Adds upload to specified settings section, ex: $settings->add_upload( 'My upload', 'prefix-new-upload-slug', 'settings-section-slug', 'custom-css-class', 'http://...' );
		parent::$config['uploads'][] = array( 'title' => $title, 'slug' => $slug, 'section-slug' => $section_slug, 'class' => $class, 'default' => $default );		// Get the args and pass it to the $config.
	}

	public function get_value( $slug ) {	// Gets the value of provided field. Ex: $settings->get_value( 'my-slug' );
		$this->options = get_option( 'fpwpcr-theme-options-values' );
		return $this->options[$slug];
	}


	public function make_my_settings() {											// Must be called after setup. Doing all the settings stuff like adding sections, fields, registering data etc.
		add_action( 'admin_init', array( &$this, 'make_my_settings_callback') );	// Do the settings page!
		add_action( 'admin_notices', array(&$this, 'admin_notices_callback' ) ); 	// Adds support for notice and error messages.
	}

	public function get_hero_image_css() {		// Gets the hero image background css in priority: Featured Image > Theme Options (if home) > CSS.
		$theme_options_hero_url = $this->get_value( 'wpcr-header-hero-image' );
		$featured_image_uri = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

		if ( $featured_image_uri[0] == '' ) {
			if ( ( $theme_options_hero_url != '' ) && is_front_page() ) {
				$url = $theme_options_hero_url; 
			} else {
				return '';
			} 
		} else {
				$url = $featured_image_uri[0];
			}

		if ( $url != '' ) {
			return sprintf('%2$s.hero-image_background { background-image: url(\'%1$s\'); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'%1$s\',sizingMethod=\'scale\');}',
					esc_url( $url ),
					( is_front_page() ) ? '.home ' : ''
					);
		}
	}

	public function get_main_color_css() {		// Return css to change site main color.
		$color = $this->get_value( 'wpcr-main-color' );
		if ( !empty( $color ) && ( $color != '#cd261e' ) ) {
			return sprintf('.content-title, .list-marker, .mid-red-button, .big-red-button, .current_page_item a, a:hover, .hero-image_content-title {color: %1$s !important;}.hr-underline {background: %1$s;color: %1$s;}.header-top_menu .current_page_item {border-bottom: 0.20rem solid %1$s;}.hero-image_content-title_white,.home .hero-image_content-title {color: #fff !important;}',
				esc_html($color));
		}
	}

	public function get_parallax_css() {
		$parallax = $this->get_value( 'wpcr-main-parallax' );
		return ( $parallax ) ? '' : '.hero-image_background{background-attachment: scroll;}';
	}

	public function get_bnw_css() {
		$bnw = $this->get_value( 'wpcr-main-bw' );
		return ( $bnw ) ? '' : '.hero-image_background{filter: none;}';
	}

	public function get_background_css() {
		$bg = $this->get_value( 'wpcr-main-background-color' );
		return ( empty($bg) ) ? '' : 'body{background: '.esc_html($bg).';}';
	}

	public function get_header_top_css() {
		$header_bg = $this->get_value( 'wpcr-main-top-header-bg-color' );
		return empty( $header_bg ) ? '' : '.header-top{background: ' . $header_bg . ';}';
	}

	public function get_the_user_css() {
		$css = $this->get_hero_image_css() . $this->get_main_color_css() . $this->get_main_color_css() . $this->get_parallax_css() . $this->get_background_css() . $this->get_header_top_css() . $this->get_bnw_css() . esc_html( $this->get_value( 'wpcr-main-css' ) );
		return $css;
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
		$this->options = get_option( 'fpwpcr-theme-options-values' );

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
					parent::$config['admin-errors'][] = __( '[ Error ] add_text_field@', 'wpcrucible' ) . $textfield['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
								'<input type="text" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" class="%3$s wpcr-admin-textfield" />',
								$textfield['slug'],
								isset( $this->options[$textfield['slug']] ) ? esc_attr( $this->options[$textfield['slug']] ) : '',
								$textfield['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$textfield['section-slug']
						);
				}

				if ( !isset( $this->options[$textfield['slug']] ) ) {
					$this->options[$textfield['slug']] = $textfield['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}


				
			}
		}

		if ( array_key_exists( 'textareas', parent::$config ) ) {			// Adds textareas if defined.
			foreach ( parent::$config['textareas'] as $textarea ) {
				$checklist = array();
				
				if ( parent::is_in_array( $textarea['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_textarea@', 'wpcrucible' ) . $textarea['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
								'<textarea id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s wpcr-admin-textarea">%2$s</textarea>',
								$textarea['slug'],
								isset( $this->options[$textarea['slug']] ) ? esc_attr( $this->options[$textarea['slug']] ) : '',
								$textarea['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$textarea['section-slug']
						);
				}

				if ( !isset( $this->options[$textarea['slug']] ) ) {
					$this->options[$textarea['slug']] = $textarea['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}
			}
		}

		if ( array_key_exists( 'ranges', parent::$config ) ) {			// Adds ranges if defined.
			foreach ( parent::$config['ranges'] as $range ) {
				$checklist = array();
				
				if ( parent::is_in_array( $range['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_range@', 'wpcrucible' ) . $range['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
								'<input type="range" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s wpcr-admin-range" value="%2$s" min="%4$s" max="%5$s" oninput="%6$s.value=this.value" />
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

				if ( !isset( $this->options[$range['slug']] ) ) {
					$this->options[$range['slug']]	= $range['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}
			}
		}

		if ( array_key_exists( 'numbers', parent::$config ) ) {			// Adds numbers if defined.
			foreach ( parent::$config['numbers'] as $number ) {
				$checklist = array();
				
				if ( parent::is_in_array( $number['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_number@', 'wpcrucible' ) . $number['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
								'<input type="number" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" class="%3$s wpcr-admin-number" value="%2$s" min="%4$s" max="%5$s" />',
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
				if ( !isset( $this->options[$number['slug']] ) ) {
					$this->options[$number['slug']]	= $number['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}
			}
		}

		if ( array_key_exists( 'colors', parent::$config ) ) {			// Adds colors if defined.
			foreach ( parent::$config['colors'] as $color ) {
				$checklist = array();
				
				if ( parent::is_in_array( $color['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_color@', 'wpcrucible' ) . $color['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
								'<input type="color" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="%2$s" class="%3$s wpcr-admin-color" />',
								$color['slug'],
								isset( $this->options[$color['slug']] ) ? esc_attr( $this->options[$color['slug']] ) : '',
								$color['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$color['section-slug']
						);
				}

				if ( !isset( $this->options[$color['slug']] ) ) {
					$this->options[$color['slug']] = $color['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}				
			}
		}


		if ( array_key_exists( 'uploads', parent::$config ) ) {			// Adds uploads if defined.
			foreach ( parent::$config['uploads'] as $upload ) {
				$checklist = array();
				
				if ( parent::is_in_array( $upload['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_upload@', 'wpcrucible' ) . $upload['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
				if ( !isset( $this->options[$upload['slug']] ) ) {
					$this->options[$upload['slug']]	= $upload['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
				}
			}
		}

		if ( array_key_exists( 'checkboxs', parent::$config ) ) {			// Adds checkboxs if defined.
			foreach ( parent::$config['checkboxs'] as $checkbox ) {
				$checklist = array();
				
				if ( parent::is_in_array( $checkbox['section-slug'], parent::$config['sections'] ) ) {		// Check if section exists and prints error if not.
					$checklist[] = true;
				} else {
					parent::$config['admin-errors'][] = __( '[ Error ] add_checkbox@', 'wpcrucible' ) . $checkbox['slug'] . __( ': Setings section not found. Settings section must be defined before adding any field! Check add_section() method.', 'wpcrucible' );
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
						$checkbox['slug'],
						$checkbox['title'],
						function() use ( $checkbox ) {
							$checked = isset( $this->options[$checkbox['slug']] ) ? $this->options[$checkbox['slug']] : 1;
							printf(
								'<input type="checkbox" id="%1$s" name="fpwpcr-theme-options-values[%1$s]" value="1" class="%2$s wpcr-admin-checkbox" '. checked( 1, $checked, false ) .' />',
								$checkbox['slug'],
								$checkbox['class']
								);
						},
						'fpwpcr-wpcrucible-options',
						$checkbox['section-slug']
						);
				}
				if ( !isset( $this->options[$checkbox['slug']] ) ) {
					$this->options[$checkbox['slug']] = $checkbox['default'];
					update_option( 'fpwpcr-theme-options-values', $this->options , '', 'yes' );
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




class fpwpcr_metaboxes {
	private $sb_count;
	private $fields_to_save = array(
			'wpcr-admin-page-metabox-sidebar' => array(
				'wpcr-admin-page-metabox-sidebar-number'
				),
			'wpcr-admin-page-metabox-header' => array(
				'wpcr-admin-page-metabox-header-title',
				'wpcr-admin-page-metabox-header-subtitle'
				)
			);

	public function __construct( $sidebars ) {
		$this->sb_count = $sidebars;
		add_action( 'add_meta_boxes', array( &$this, 'add_metaboxes' ) );	// Adds the metaboxes.
		add_action( 'save_post', array( &$this, 'save_metaboxes' ) );
	}

	public function add_metaboxes() {
		$metaboxes = array(									// Array of metaboxes and its configurations.
			'wpcr-admin-page-metabox-sidebar' => array(
				__( 'Sidebar' ),
				function( $post ) {							// Metabox Callback.
					$select_items = '';
					$selected_item = get_post_meta( $post->ID, 'wpcr-admin-page-metabox-sidebar-number', true );	// Get actual value if exists.
					wp_nonce_field( 'wpcr-admin-page-metabox-sidebar', 'wpcr-admin-page-metabox-sidebar-nonce' );	// Adds nonce ( it must be defined! ).

					for ( $i = 1 ; $i <= $this->sb_count ; $i++ ) {													// Print the select options depends on sidebars count.
						$select_items .= sprintf('<option %s value="%d">' . __( 'Sidebar %d' ) . '</option>',
							( $selected_item == $i ) ? 'selected' : '',												// Adds selected attribute if values of item and stored in database match each other.
							$i, 
							$i
							);
					}
					printf('<select id="wpcr-admin-page-metabox-sidebar-number" name="wpcr-admin-page-metabox-sidebar-number" class="wpcr-admin-page-metabox-sidebar-select wpcr-admin-page-metabox-sidebar-select-%1$s wpcr-admin-page-metabox-sidebar-select-js">%2$s</select>',
						$post->ID,
						$select_items
						);
				},
				'page',
				'side',
				'default',
				null
				),
			'wpcr-admin-page-metabox-header' => array(
				__( 'Header Content', 'wpcrucible' ),
				function( $post ) {
					wp_nonce_field( 'wpcr-admin-page-metabox-header', 'wpcr-admin-page-metabox-header-nonce' );
					printf('<table class="wpcr-admin-page-metabox-header-metabox">
								<tr>
									<td class="wpcr-admin-page-metabox-header-cl">
										<label for="wpcr-admin-page-metabox-header-title">%3$s</label>
									</td>
									<td class="wpcr-admin-page-metabox-header-cr">
										<input type="text" id="wpcr-admin-page-metabox-header-title" name="wpcr-admin-page-metabox-header-title" class="wpcr-admin-page-metabox-header-textfield wpcr-admin-page-metabox-header-textfield-%1$s" value="%2$s" />
									</td>
								</tr>
								<tr>
									<td class="wpcr-admin-page-metabox-header-cl">
										<label for="wpcr-admin-page-metabox-header-subtitle">%4$s</label>
									</td>
									<td class="wpcr-admin-page-metabox-header-cr">
										<input type="text" id="wpcr-admin-page-metabox-header-subtitle" name="wpcr-admin-page-metabox-header-subtitle" class="wpcr-admin-page-metabox-header-textfield wpcr-admin-page-metabox-header-textfield-%1$s" value="%5$s" />
									</td>
								</tr>
							</table>',
						$post->ID,
						esc_attr( get_post_meta( $post->ID, 'wpcr-admin-page-metabox-header-title', true ) ),
						__( 'Header Title', 'wpcrucible' ),
						__( 'Header Subtitle', 'wpcrucible' ),
						esc_attr( get_post_meta( $post->ID, 'wpcr-admin-page-metabox-header-subtitle', true ) )
						);
				},
				'page',
				'normal',
				'high',
				null
				)
			);

		foreach( $metaboxes as $metabox_id => $metabox ) {
			add_meta_box( $metabox_id, $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );
		}		
	}

	public function save_metaboxes( $post_id ) {
		foreach ( $this->fields_to_save as $metabox => $fields ) {			
			if ( !isset( $_POST[$metabox.'-nonce'] ) )		// Check that nonce is set, exit if no.
				return $post_id;
	
			$nonce = $_POST[$metabox.'-nonce'];
	
			if ( !wp_verify_nonce( $nonce, $metabox ) )	// Is nonce valid ? Exit when no.
				return $post_id;
	
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// If it's autosave, exit.
				return $post_id;
	
			if ( !current_user_can( 'edit_page', $post_id ) )						// Check if user have permissions to save, exit when no.
				return $post_id;

			foreach ( $fields as $field ) {
				$data = sanitize_text_field( $_POST[$field] );						// Sanitize data.
				update_post_meta( $post_id, $field, $data ); 						// Update data in database.
			}
		}
	}


}


?>