<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'themerex_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_theme_setup', 1 );
	function themerex_theme_setup() {

		// Register theme menus
		add_filter( 'themerex_filter_add_theme_menus',		'themerex_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'themerex_filter_add_theme_sidebars',	'themerex_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'themerex_filter_importer_options',		'themerex_importer_set_options' );

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'themerex_add_theme_menus' ) ) {
	//add_filter( 'themerex_filter_add_theme_menus', 'themerex_add_theme_menus' );
	function themerex_add_theme_menus($menus) {
		//For example:
		//$menus['menu_footer'] = esc_html__('Footer Menu', 'themerex');
		//if (isset($menus['menu_panel'])) unset($menus['menu_panel']);
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'themerex_add_theme_sidebars' ) ) {
	//add_filter( 'themerex_filter_add_theme_sidebars',	'themerex_add_theme_sidebars' );
	function themerex_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'themerex' ),
				'sidebar_outer'		=> esc_html__( 'Outer Sidebar', 'themerex' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'themerex' )
			);
			if (function_exists('themerex_exists_woocommerce') && themerex_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'themerex' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Set theme specific importer options
if ( !function_exists( 'themerex_importer_set_options' ) ) {
	//add_filter( 'themerex_filter_importer_options',	'themerex_importer_set_options' );
	function themerex_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Please, note! The following text strings should not be translated, 
			// since these are article titles, menu locations, etc. used by us in the demo-website. 
			// They are required when setting some of the WP parameters during demo data installation 
			// and cannot be changed/translated into other languages.
			$options['debug'] = themerex_get_theme_option('debug_mode')=='yes';
			$options['domain_dev'] = '_trex2.themerex.dnw';
			$options['domain_demo'] = 'trex2.dev.themerex.net';
			$options['page_on_front'] = 'Header 1, Contacts, Copyright text. Testimonials and Team';	// Homepage title (NOT FOR TRANSLATION)
			$options['page_for_posts'] = 'All posts';													// Blog streampage title (NOT FOR TRANSLATION)
			$options['menus'] = array(																	// Menus locations and names (NOT FOR TRANSLATION)
				'menu-main'	  => 'Main menu',
				'menu-user'	  => 'User menu',
				'menu-footer' => 'Footer menu',
				'menu-outer'  => 'Main menu'
			);
			$options['required_plugins'] = array(														// Required plugins slugs (NOT FOR TRANSLATION)
				'visual_composer',
				'revslider',
				'woocommerce',
//				'booking',			// Attention! Booking Calendar not compatible with bbPress and BuddyPress!
				'buddypress',
				'bbpress',
				'calcfields',
				'essgrids',
				'learndash',
				'tribe_events',
				'trx_donations',
				'responsive_poll'
			);
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
if (!isset($_POST['action']) || $_POST['action']!="heartbeat") {
	require_once get_template_directory().'/fw/loader.php';
}

?>
