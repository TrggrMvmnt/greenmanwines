<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('themerex_sc_gap_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_sc_gap_theme_setup' );
	function themerex_sc_gap_theme_setup() {
		add_action('themerex_action_shortcodes_list', 		'themerex_sc_gap_reg_shortcodes');
		if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('themerex_action_shortcodes_list_vc','themerex_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('themerex_sc_gap')) {	
	function themerex_sc_gap($atts, $content = null) {
		if (themerex_in_shortcode_blogger()) return '';
		$output = themerex_gap_start() . do_shortcode($content) . themerex_gap_end();
		return apply_filters('themerex_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	if (function_exists('themerex_utils_require_shortcode')) themerex_utils_require_shortcode("trx_gap", "themerex_sc_gap");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_gap_reg_shortcodes' ) ) {
	//add_action('themerex_action_shortcodes_list', 'themerex_sc_gap_reg_shortcodes');
	function themerex_sc_gap_reg_shortcodes() {
		global $THEMEREX_GLOBALS;
	
		$THEMEREX_GLOBALS['shortcodes']["trx_gap"] = array(
			"title" => esc_html__("Gap", "themerex"),
			"desc" => wp_kses( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", "themerex"),
					"desc" => wp_kses( __("Gap inner content", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('themerex_action_shortcodes_list_vc', 'themerex_sc_gap_reg_shortcodes_vc');
	function themerex_sc_gap_reg_shortcodes_vc() {
		global $THEMEREX_GLOBALS;
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", "themerex"),
			"description" => wp_kses( __("Insert gap (fullwidth area) in the post content", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Structure', 'js_composer'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", "themerex"),
					"description" => wp_kses( __("Gap inner content", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends THEMEREX_VC_ShortCodeCollection {}
	}
}
?>