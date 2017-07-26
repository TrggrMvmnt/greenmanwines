<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('themerex_sc_title_theme_setup')) {
	add_action( 'themerex_action_before_init_theme', 'themerex_sc_title_theme_setup' );
	function themerex_sc_title_theme_setup() {
		add_action('themerex_action_shortcodes_list', 		'themerex_sc_title_reg_shortcodes');
		if (function_exists('themerex_exists_visual_composer') && themerex_exists_visual_composer())
			add_action('themerex_action_shortcodes_list_vc','themerex_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('themerex_sc_title')) {	
	function themerex_sc_title($atts, $content=null){	
		if (themerex_in_shortcode_blogger()) return '';
		extract(themerex_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= themerex_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= themerex_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !themerex_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !themerex_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(themerex_strpos($image, 'http:')!==false ? $image : themerex_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !themerex_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!themerex_param_is_off($animation) ? ' data-animation="'.esc_attr(themerex_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('themerex_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	if (function_exists('themerex_utils_require_shortcode')) themerex_utils_require_shortcode('trx_title', 'themerex_sc_title');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'themerex_sc_title_reg_shortcodes' ) ) {
	//add_action('themerex_action_shortcodes_list', 'themerex_sc_title_reg_shortcodes');
	function themerex_sc_title_reg_shortcodes() {
		global $THEMEREX_GLOBALS;
	
		$THEMEREX_GLOBALS['shortcodes']["trx_title"] = array(
			"title" => esc_html__("Title", "themerex"),
			"desc" => wp_kses( __("Create header tag (1-6 level) with many styles", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", "themerex"),
					"desc" => wp_kses( __("Title content", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", "themerex"),
					"desc" => wp_kses( __("Title type (header level)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'themerex'),
						'2' => esc_html__('Header 2', 'themerex'),
						'3' => esc_html__('Header 3', 'themerex'),
						'4' => esc_html__('Header 4', 'themerex'),
						'5' => esc_html__('Header 5', 'themerex'),
						'6' => esc_html__('Header 6', 'themerex'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", "themerex"),
					"desc" => wp_kses( __("Title style", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'themerex'),
						'underline' => esc_html__('Underline', 'themerex'),
						'divider' => esc_html__('Divider', 'themerex'),
						'iconed' => esc_html__('With icon (image)', 'themerex')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "themerex"),
					"desc" => wp_kses( __("Title text alignment", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['sc_params']['align']
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", "themerex"),
					"desc" => wp_kses( __("Custom font size. If empty - use theme default", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "themerex"),
					"desc" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'themerex'),
						'100' => esc_html__('Thin (100)', 'themerex'),
						'300' => esc_html__('Light (300)', 'themerex'),
						'400' => esc_html__('Normal (400)', 'themerex'),
						'600' => esc_html__('Semibold (600)', 'themerex'),
						'700' => esc_html__('Bold (700)', 'themerex'),
						'900' => esc_html__('Black (900)', 'themerex')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", "themerex"),
					"desc" => wp_kses( __("Select color for the title", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'themerex'),
					"desc" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => $THEMEREX_GLOBALS['sc_params']['icons']
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'themerex'),
					"desc" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)",  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => $THEMEREX_GLOBALS['sc_params']['images']
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', "themerex"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', "themerex"),
					"desc" => wp_kses( __("Select image (picture) size (if style='iconed')", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'themerex'),
						'medium' => esc_html__('Medium', 'themerex'),
						'large' => esc_html__('Large', 'themerex')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', "themerex"),
					"desc" => wp_kses( __("Select icon (image) position (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'themerex'),
						'left' => esc_html__('Left', 'themerex')
					)
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
if ( !function_exists( 'themerex_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('themerex_action_shortcodes_list_vc', 'themerex_sc_title_reg_shortcodes_vc');
	function themerex_sc_title_reg_shortcodes_vc() {
		global $THEMEREX_GLOBALS;
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", "themerex"),
			"description" => wp_kses( __("Create header tag (1-6 level) with many styles", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'js_composer'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", "themerex"),
					"description" => wp_kses( __("Title content", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", "themerex"),
					"description" => wp_kses( __("Title type (header level)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'themerex') => '1',
						esc_html__('Header 2', 'themerex') => '2',
						esc_html__('Header 3', 'themerex') => '3',
						esc_html__('Header 4', 'themerex') => '4',
						esc_html__('Header 5', 'themerex') => '5',
						esc_html__('Header 6', 'themerex') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", "themerex"),
					"description" => wp_kses( __("Title style: only text (regular) or with icon/image (iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'themerex') => 'regular',
						esc_html__('Underline', 'themerex') => 'underline',
						esc_html__('Divider', 'themerex') => 'divider',
						esc_html__('With icon (image)', 'themerex') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "themerex"),
					"description" => wp_kses( __("Title text alignment", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($THEMEREX_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "themerex"),
					"description" => wp_kses( __("Custom font size. If empty - use theme default", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "themerex"),
					"description" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'themerex') => 'inherit',
						esc_html__('Thin (100)', 'themerex') => '100',
						esc_html__('Light (300)', 'themerex') => '300',
						esc_html__('Normal (400)', 'themerex') => '400',
						esc_html__('Semibold (600)', 'themerex') => '600',
						esc_html__('Bold (700)', 'themerex') => '700',
						esc_html__('Black (900)', 'themerex') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", "themerex"),
					"description" => wp_kses( __("Select color for the title", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", "themerex"),
					"description" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'themerex'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $THEMEREX_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", "themerex"),
					"description" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'themerex'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $THEMEREX_GLOBALS['sc_params']['images'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", "themerex"),
					"description" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'themerex'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", "themerex"),
					"description" => wp_kses( __("Select image (picture) size (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'themerex'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'themerex') => 'small',
						esc_html__('Medium', 'themerex') => 'medium',
						esc_html__('Large', 'themerex') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", "themerex"),
					"description" => wp_kses( __("Select icon (image) position (if style=iconed)", "themerex"), $THEMEREX_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'themerex'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'themerex') => 'top',
						esc_html__('Left', 'themerex') => 'left'
					),
					"type" => "dropdown"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends THEMEREX_VC_ShortCodeSingle {}
	}
}
?>