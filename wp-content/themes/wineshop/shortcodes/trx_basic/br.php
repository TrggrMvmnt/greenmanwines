<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('themerex_sc_br_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_sc_br_theme_setup' );
	function themerex_sc_br_theme_setup() {
		add_action('themerex_action_shortcodes_list', 		'themerex_sc_br_reg_shortcodes');
		if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('themerex_action_shortcodes_list_vc','themerex_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('themerex_sc_br')) {	
	function themerex_sc_br($atts, $content = null) {
		if (themerex_in_shortcode_blogger()) return '';
		extract(themerex_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('themerex_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	if (function_exists('themerex_utils_require_shortcode')) themerex_utils_require_shortcode("trx_br", "themerex_sc_br");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_br_reg_shortcodes' ) ) {
	//add_action('themerex_action_shortcodes_list', 'themerex_sc_br_reg_shortcodes');
	function themerex_sc_br_reg_shortcodes() {
		global $THEMEREX_GLOBALS;
	
		$THEMEREX_GLOBALS['shortcodes']["trx_br"] = array(
			"title" => esc_html__("Break", "themerex"),
			"desc" => wp_kses( __("Line break with clear floating (if need)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", "themerex"),
					"desc" => wp_kses( __("Clear floating (if need)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'themerex'),
						'left' => esc_html__('Left', 'themerex'),
						'right' => esc_html__('Right', 'themerex'),
						'both' => esc_html__('Both', 'themerex')
					)
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('themerex_action_shortcodes_list_vc', 'themerex_sc_br_reg_shortcodes_vc');
	function themerex_sc_br_reg_shortcodes_vc() {
		global $THEMEREX_GLOBALS;
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", "themerex"),
			"description" => wp_kses( __("Line break or Clear Floating", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", "themerex"),
					"description" => wp_kses( __("Select clear side (if need)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'themerex') => 'none',
						esc_html__('Left', 'themerex') => 'left',
						esc_html__('Right', 'themerex') => 'right',
						esc_html__('Both', 'themerex') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends THEMEREX_VC_ShortCodeSingle {}
*/
	}
}
?>