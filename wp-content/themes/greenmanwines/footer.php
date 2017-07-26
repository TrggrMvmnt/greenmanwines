<?php
/**
 * The template for displaying the footer.
 */

global $THEMEREX_GLOBALS;

				themerex_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (themerex_get_custom_option('body_style')!='fullscreen') themerex_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
					
			// Footer sidebar
			$footer_show  = themerex_get_custom_option('show_sidebar_footer');
			$sidebar_name = themerex_get_custom_option('sidebar_footer');
			if (!themerex_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				$THEMEREX_GLOBALS['current_sidebar'] = 'footer';
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(themerex_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner icon_<?php echo themerex_get_custom_option('sidebar_footer_icon'); ?>">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}
			
			// Footer Testimonials stream
			if (themerex_get_custom_option('show_testimonials_in_footer')=='yes') { 
				$count = max(1, themerex_get_custom_option('testimonials_count'));
				$data = themerex_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(themerex_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo balanceTags($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Footer Twitter stream
			if (themerex_get_custom_option('show_twitter_in_footer')=='yes') { 
				$count = max(1, themerex_get_custom_option('twitter_count'));
				$data = themerex_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(themerex_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo balanceTags($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}


			// Google map
			if ( themerex_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = themerex_get_custom_option('googlemap_address');
				$map_latlng  = themerex_get_custom_option('googlemap_latlng');
				$map_zoom    = themerex_get_custom_option('googlemap_zoom');
				$map_style   = themerex_get_custom_option('googlemap_style');
				$map_height  = themerex_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					echo trim(themerex_sc_googlemap($args));
				}
			}

			// Footer contacts
			if (themerex_get_custom_option('show_contacts_in_footer')=='yes') { 
				$address_1 = themerex_get_theme_option('contact_address_1');
				$address_2 = themerex_get_theme_option('contact_address_2');
				$phone = themerex_get_theme_option('contact_phone');
				$fax = themerex_get_theme_option('contact_fax');
				$email = themerex_get_theme_option('contact_email');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(themerex_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
								<?php require themerex_get_file_dir('templates/_parts/logo-footer.php'); ?>
								<div class="contacts_address">
									<?php if (!empty($address_1)) echo esc_html($address_1) . '<br>'; ?>
									<?php if (!empty($address_2)) echo esc_html($address_2); ?>
									<br>
									<?php if (!empty($phone)) echo esc_html__('Phone:', 'themerex') . ' ' . esc_html($phone) . '<br>'; ?>
									<?php if (!empty($email)) echo 'Email : <a href="mailto:'. esc_html($email) . '">'. esc_html($email) . '</a>'; ?>
								</div>
								<?php echo trim(themerex_sc_socials(array('size'=>"medium"))); ?>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}

			// Copyright area
			$copyright_style = themerex_get_custom_option('show_copyright_in_footer');
			if (!themerex_param_is_off($copyright_style)) {
			?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(themerex_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (empty($THEMEREX_GLOBALS['menu_footer']))	$THEMEREX_GLOBALS['menu_footer'] = themerex_get_nav_menu('menu_footer');
								if (!empty($THEMEREX_GLOBALS['menu_footer']))	echo balanceTags($THEMEREX_GLOBALS['menu_footer']);
							} else if ($copyright_style == 'socials') {
								echo trim(themerex_sc_socials(array('size'=>"tiny")));
							}
							?>
							<div class="copyright_text"><?php echo force_balance_tags(themerex_get_custom_option('footer_copyright')); ?></div>
						</div>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !themerex_param_is_off(themerex_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
require themerex_get_file_dir('templates/_parts/views-counter.php');

// Front customizer
if (themerex_get_custom_option('show_theme_customizer')=='yes') {
	require_once themerex_get_file_dir('core/core.customizer/front.customizer.php');
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_html_e('Scroll to top', 'themerex'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(themerex_get_custom_option('custom_code')); ?>
</div>

<?php echo force_balance_tags(themerex_get_custom_option('gtm_code2')); ?>

<?php wp_footer(); ?>

</body>
</html>