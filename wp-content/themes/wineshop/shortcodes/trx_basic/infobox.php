<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('themerex_sc_infobox_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_sc_infobox_theme_setup' );
	function themerex_sc_infobox_theme_setup() {
		add_action('themerex_action_shortcodes_list', 		'themerex_sc_infobox_reg_shortcodes');
		if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('themerex_action_shortcodes_list_vc','themerex_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('themerex_sc_infobox')) {	
	function themerex_sc_infobox($atts, $content=null){	
		if (themerex_in_shortcode_blogger()) return '';
		extract(themerex_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= themerex_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-error-alt';
			else if ($style=='info')
				$icon = 'icon-info';
			else if ($style=='warning')
				$icon = 'icon-attention';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (themerex_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !themerex_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!themerex_param_is_off($animation) ? ' data-animation="'.esc_attr(themerex_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('themerex_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	if (function_exists('themerex_utils_require_shortcode')) themerex_utils_require_shortcode('trx_infobox', 'themerex_sc_infobox');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_infobox_reg_shortcodes' ) ) {
	//add_action('themerex_action_shortcodes_list', 'themerex_sc_infobox_reg_shortcodes');
	function themerex_sc_infobox_reg_shortcodes() {
		global $THEMEREX_GLOBALS;
	
		$THEMEREX_GLOBALS['shortcodes']["trx_infobox"] = array(
			"title" => esc_html__("Infobox", "themerex"),
			"desc" => wp_kses( __("Insert infobox into your post (page)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "themerex"),
					"desc" => wp_kses( __("Infobox style", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'themerex'),
						'info' => esc_html__('Info', 'themerex'),
						'success' => esc_html__('Success', 'themerex'),
						'error' => esc_html__('Error', 'themerex')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", "themerex"),
					"desc" => wp_kses( __("Create closeable box (with close button)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"type" => "switch",
					"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'themerex'),
					"desc" => wp_kses( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $THEMEREX_GLOBALS['sc_params']['icons']
				),
				"color" => array(
					"title" => esc_html__("Text color", "themerex"),
					"desc" => wp_kses( __("Any color for text and headers", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "themerex"),
					"desc" => wp_kses( __("Any background color for this infobox", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", "themerex"),
					"desc" => wp_kses( __("Content for infobox", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => $THEMEREX_GLOBALS['sc_params']['top'],
				"bottom" => $THEMEREX_GLOBALS['sc_params']['bottom'],
				"left" => $THEMEREX_GLOBALS['sc_params']['left'],
				"right" => $THEMEREX_GLOBALS['sc_params']['right'],
				"id" => $THEMEREX_GLOBALS['sc_params']['id'],
				"class" => $THEMEREX_GLOBALS['sc_params']['class'],
				"animation" => $THEMEREX_GLOBALS['sc_params']['animation'],
				"css" => $THEMEREX_GLOBALS['sc_params']['css']
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('themerex_action_shortcodes_list_vc', 'themerex_sc_infobox_reg_shortcodes_vc');
	function themerex_sc_infobox_reg_shortcodes_vc() {
		global $THEMEREX_GLOBALS;
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", "themerex"),
			"description" => wp_kses( __("Box with info or error message", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "themerex"),
					"description" => wp_kses( __("Infobox style", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'themerex') => 'regular',
							esc_html__('Info', 'themerex') => 'info',
							esc_html__('Success', 'themerex') => 'success',
							esc_html__('Error', 'themerex') => 'error',
							esc_html__('Warning', 'themerex') => 'warning'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", "themerex"),
					"description" => wp_kses( __("Create closeable box (with close button)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'themerex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", "themerex"),
					"description" => wp_kses( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "themerex"),
					"description" => wp_kses( __("Any color for the text and headers", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "themerex"),
					"description" => wp_kses( __("Any background color for this infobox", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				$THEMEREX_GLOBALS['vc_params']['id'],
				$THEMEREX_GLOBALS['vc_params']['class'],
				$THEMEREX_GLOBALS['vc_params']['animation'],
				$THEMEREX_GLOBALS['vc_params']['css'],
				$THEMEREX_GLOBALS['vc_params']['margin_top'],
				$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
				$THEMEREX_GLOBALS['vc_params']['margin_left'],
				$THEMEREX_GLOBALS['vc_params']['margin_right']
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends THEMEREX_VC_ShortCodeContainer {}
	}
}
?>