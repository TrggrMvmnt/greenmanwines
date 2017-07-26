<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('themerex_sc_button_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_sc_button_theme_setup' );
	function themerex_sc_button_theme_setup() {
		add_action('themerex_action_shortcodes_list', 		'themerex_sc_button_reg_shortcodes');
		if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('themerex_action_shortcodes_list_vc','themerex_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_button id="unique_id" type="square|round" fullsize="0|1" style="global|light|dark" size="mini|medium|big|huge|banner" icon="icon-name" link='#' target='']Button caption[/trx_button]
*/

if (!function_exists('themerex_sc_button')) {	
	function themerex_sc_button($atts, $content=null){	
		if (themerex_in_shortcode_blogger()) return '';
		extract(themerex_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= themerex_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= themerex_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . '; border-color:'. esc_attr($bg_color) .';' : '');
		if (themerex_param_is_on($popup)) themerex_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!themerex_param_is_off($animation) ? ' data-animation="'.esc_attr(themerex_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. (themerex_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('themerex_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	if (function_exists('themerex_utils_require_shortcode')) themerex_utils_require_shortcode('trx_button', 'themerex_sc_button');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_button_reg_shortcodes' ) ) {
	//add_action('themerex_action_shortcodes_list', 'themerex_sc_button_reg_shortcodes');
	function themerex_sc_button_reg_shortcodes() {
		global $THEMEREX_GLOBALS;
	
		$THEMEREX_GLOBALS['shortcodes']["trx_button"] = array(
			"title" => esc_html__("Button", "themerex"),
			"desc" => wp_kses( __("Button with link", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", "themerex"),
					"desc" => wp_kses( __("Button caption", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"type" => array(
					"title" => esc_html__("Button's shape", "themerex"),
					"desc" => wp_kses( __("Select button's shape", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "square",
					"size" => "medium",
					"options" => array(
						'square' => esc_html__('Square', 'themerex'),
						'round' => esc_html__('Round', 'themerex')
					),
					"type" => "switch"
				), 
				"style" => array(
					"title" => esc_html__("Button's style", "themerex"),
					"desc" => wp_kses( __("Select button's style", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "default",
					"dir" => "horizontal",
					"options" => array(
						'filled' => esc_html__('Filled', 'themerex'),
						'border' => esc_html__('Border', 'themerex')
					),
					"type" => "checklist"
				), 
				"size" => array(
					"title" => esc_html__("Button's size", "themerex"),
					"desc" => wp_kses( __("Select button's size", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "small",
					"dir" => "horizontal",
					"options" => array(
						'small' => esc_html__('Small', 'themerex'),
						'medium' => esc_html__('Medium', 'themerex'),
						'large' => esc_html__('Large', 'themerex')
					),
					"type" => "checklist"
				), 
				"icon" => array(
					"title" => esc_html__("Button's icon",  'themerex'),
					"desc" => wp_kses( __('Select icon for the title from Fontello icons set',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $THEMEREX_GLOBALS['sc_params']['icons']
				),
				"color" => array(
					"title" => esc_html__("Button's text color", "themerex"),
					"desc" => wp_kses( __("Any color for button's caption", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", "themerex"),
					"desc" => wp_kses( __("Any color for button's background", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", "themerex"),
					"desc" => wp_kses( __("Align button to left, center or right", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['sc_params']['align']
				), 
				"link" => array(
					"title" => esc_html__("Link URL", "themerex"),
					"desc" => wp_kses( __("URL for link on button click", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", "themerex"),
					"desc" => wp_kses( __("Target for link on button click", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", "themerex"),
					"desc" => wp_kses( __("Open link target in popup window", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => $THEMEREX_GLOBALS['sc_params']['yes_no']
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", "themerex"),
					"desc" => wp_kses( __("Rel attribute for button's link (if need)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => themerex_shortcodes_width(),
				"height" => themerex_shortcodes_height(),
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
if ( !function_exists( 'themerex_sc_button_reg_shortcodes_vc' ) ) {
	//add_action('themerex_action_shortcodes_list_vc', 'themerex_sc_button_reg_shortcodes_vc');
	function themerex_sc_button_reg_shortcodes_vc() {
		global $THEMEREX_GLOBALS;
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", "themerex"),
			"description" => wp_kses( __("Button with link", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", "themerex"),
					"description" => wp_kses( __("Button caption", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Button's shape", "themerex"),
					"description" => wp_kses( __("Select button's shape", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Square', 'themerex') => 'square',
						esc_html__('Round', 'themerex') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Button's style", "themerex"),
					"description" => wp_kses( __("Select button's style", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Filled', 'themerex') => 'filled',
						esc_html__('Border', 'themerex') => 'border'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "size",
					"heading" => esc_html__("Button's size", "themerex"),
					"description" => wp_kses( __("Select button's size", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Small', 'themerex') => 'small',
						esc_html__('Medium', 'themerex') => 'medium',
						esc_html__('Large', 'themerex') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Button's icon", "themerex"),
					"description" => wp_kses( __("Select icon for the title from Fontello icons set", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", "themerex"),
					"description" => wp_kses( __("Any color for button's caption", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", "themerex"),
					"description" => wp_kses( __("Any color for button's background", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", "themerex"),
					"description" => wp_kses( __("Align button to left, center or right", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "themerex"),
					"description" => wp_kses( __("URL for the link on button click", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'themerex'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "themerex"),
					"description" => wp_kses( __("Target for the link on button click", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'themerex'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", "themerex"),
					"description" => wp_kses( __("Open link target in popup window", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'themerex'),
					"value" => array(esc_html__('Open in popup', 'themerex') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", "themerex"),
					"description" => wp_kses( __("Rel attribute for the button's link (if need", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Link', 'themerex'),
					"value" => "",
					"type" => "textfield"
				),
				$THEMEREX_GLOBALS['vc_params']['id'],
				$THEMEREX_GLOBALS['vc_params']['class'],
				$THEMEREX_GLOBALS['vc_params']['animation'],
				$THEMEREX_GLOBALS['vc_params']['css'],
				themerex_vc_width(),
				themerex_vc_height(),
				$THEMEREX_GLOBALS['vc_params']['margin_top'],
				$THEMEREX_GLOBALS['vc_params']['margin_bottom'],
				$THEMEREX_GLOBALS['vc_params']['margin_left'],
				$THEMEREX_GLOBALS['vc_params']['margin_right']
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends THEMEREX_VC_ShortCodeSingle {}
	}
}
?>