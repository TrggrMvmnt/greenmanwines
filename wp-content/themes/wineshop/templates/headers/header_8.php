<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_header_8_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_header_8_theme_setup', 1 );
	function themerex_template_header_8_theme_setup() {
		themerex_add_template(array(
			'layout' => 'header_8',
			'mode'   => 'header',
			'title'  => esc_html__('Header 8', 'themerex'),
			'icon'   => themerex_get_file_url('templates/headers/images/8.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'themerex_template_header_8_output' ) ) {
	function themerex_template_header_8_output($post_options, $post_data) {
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

		<header class="top_panel_wrap top_panel_style_8 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			
			<div class="top_panel_wrap_inner top_panel_inner_style_8 top_panel_position_<?php echo esc_attr(themerex_get_custom_option('top_panel_position')); ?>">
			
				<?php if (themerex_get_custom_option('show_top_panel_top')=='yes') { ?>
				<div class="top_panel_top">
					<div class="content_wrap clearfix">
						<?php
						$top_panel_top_components = array('contact_info', 'login', 'currency', 'bookmarks', 'socials');
						require_once themerex_get_file_dir('templates/headers/_parts/top-panel-top.php');
						?>
					</div>
				</div>
				<?php } ?>

				<div class="top_panel_middle" <?php echo balanceTags($header_css); ?>>
					<div class="content_wrap">
						<div class="contact_logo">
							<?php require themerex_get_file_dir('templates/headers/_parts/logo.php'); ?>
						</div>
						<div class="top_panel_buttons">
							<?php
							if (themerex_get_custom_option('show_search')=='yes') 
								echo trim(themerex_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
							if (function_exists('themerex_exists_woocommerce') && themerex_exists_woocommerce() && (themerex_is_woocommerce_page() && themerex_get_custom_option('show_cart')=='shop' || themerex_get_custom_option('show_cart')=='always') && !(is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART'))) { 
								?>
								<div class="menu_main_cart top_panel_icon">
									<?php require_once themerex_get_file_dir('templates/headers/_parts/contact-info-cart.php'); ?>
								</div>
								<?php
							}
							?>
						</div>
						<div class="menu_pushy_wrap clearfix">
							<a href="#" class="menu_pushy_button icon-menu"><?php esc_html_e('MENU', 'themerex'); ?></a>
						</div>
					</div>
				</div>

			</div>

		</header>

		<nav class="menu_pushy_nav_area pushy pushy-left scheme_<?php echo esc_attr(themerex_get_custom_option('pushy_panel_scheme')); ?>">
			<div class="pushy_inner">
	
				<a href="#" class="close-pushy"></a>
	
				<?php 
				require themerex_get_file_dir('templates/headers/_parts/logo.php');
	
				if (empty($THEMEREX_GLOBALS['menu_main'])) $THEMEREX_GLOBALS['menu_main'] = themerex_get_nav_menu('menu_main');
				if (empty($THEMEREX_GLOBALS['menu_main'])) $THEMEREX_GLOBALS['menu_main'] = themerex_get_nav_menu();
				echo str_replace('menu_main', 'menu_pushy', $THEMEREX_GLOBALS['menu_main']);
	
				$address_1 = themerex_get_theme_option('contact_address_1');
				$address_2 = themerex_get_theme_option('contact_address_2');
				$phone = themerex_get_theme_option('contact_phone');
				$fax = themerex_get_theme_option('contact_fax');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<div class="contact_info">
						<?php if (!empty($address_1) || !empty($address_2)) { ?>
							<address class="contact_address">
								<?php echo balanceTags($address_1) . (!empty($address_1) ? ', ' : '') . ($address_2); ?>
							</address>
						<?php } ?>
						<?php if (!empty($phone) || !empty($fax)) { ?>
							<address class="contact_phones">
								<?php echo esc_html__('Call:', 'themerex') . ' ' . ($phone) . (!empty($phone) ? ', ' : '') . ($fax); ?>
							</address>
						<?php } ?>
					</div>
					<?php
				}
	
				if (themerex_get_custom_option('show_socials')=='yes') {
					?>
					<div class="contact_socials">
						<?php echo trim(themerex_sc_socials(array('size'=>'small'))); ?>
					</div>
					<?php
				}
				?>

			</div>
        </nav>

        <!-- Site Overlay -->
        <div class="site-overlay"></div>
		<?php
	}
}
?>