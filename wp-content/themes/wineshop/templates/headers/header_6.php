<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_header_6_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_header_6_theme_setup', 1 );
	function themerex_template_header_6_theme_setup() {
		themerex_add_template(array(
			'layout' => 'header_6',
			'mode'   => 'header',
			'title'  => esc_html__('Header 6', 'themerex'),
			'icon'   => themerex_get_file_url('templates/headers/images/6.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'themerex_template_header_6_output' ) ) {
	function themerex_template_header_6_output($post_options, $post_data) {
		global $THEMEREX_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
				? ' style="background: url('.esc_url($header_image).') repeat center top"' 
				: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_6 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_6 top_panel_position_<?php echo esc_attr(themerex_get_custom_option('top_panel_position')); ?>">

			<div class="top_panel_middle" <?php echo balanceTags($header_css); ?>>
				<div class="content_wrap">
					<div class="columns_wrap columns_fluid"><div
						class="column-1_4 contact_logo">
							<?php require_once themerex_get_file_dir('templates/headers/_parts/logo.php'); ?>
						</div><div 
						class="column-3_4 menu_main_wrap">
							<a href="#" class="menu_main_responsive_button"><i class="fa fa-bars" aria-hidden="true"></i> <?php esc_html_e('Menu', 'themerex'); ?></a>
							<nav class="menu_main_nav_area">
								<?php
								if (empty($THEMEREX_GLOBALS['menu_main'])) $THEMEREX_GLOBALS['menu_main'] = themerex_get_nav_menu('menu_main');
								if (empty($THEMEREX_GLOBALS['menu_main'])) $THEMEREX_GLOBALS['menu_main'] = themerex_get_nav_menu();
								echo balanceTags($THEMEREX_GLOBALS['menu_main']);
								?>
							</nav>
							<?php
							if (function_exists('themerex_exists_woocommerce') && themerex_exists_woocommerce() && (themerex_is_woocommerce_page() && themerex_get_custom_option('show_cart')=='shop' || themerex_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
								?>
								<div class="menu_main_cart top_panel_icon">
									<?php require_once themerex_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
								</div>
								<?php
							}
							if (themerex_get_custom_option('show_search')=='yes') 
								echo trim(themerex_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
							?>
						</div>
					</div>
				</div>
			</div>

			</div>
		</header>

		<?php
	}
}
?>