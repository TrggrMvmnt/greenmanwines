<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings
global $THEMEREX_GLOBALS;
$THEMEREX_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
);



// Default Theme Options
if ( !function_exists( 'themerex_options_settings_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_options_settings_theme_setup', 2 );	// Priority 1 for add themerex_filter handlers
	function themerex_options_settings_theme_setup() {
		global $THEMEREX_GLOBALS;
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'themerex_options_reset');

		// Settings 
		$socials_type = themerex_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$THEMEREX_GLOBALS['options_params'] = apply_filters('themerex_filter_theme_options_params', array(
			'list_fonts'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_socials' => ''),
			'list_icons' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_icons' => ''),
			'list_posts_types' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_posts_types' => ''),
			'list_categories' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_categories' => ''),
			'list_menus'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_menus' => ''),
			'list_sidebars'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_sidebars' => ''),
			'list_positions' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_skins' => ''),
			'list_color_schemes'		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_animations_out' => ''),
			'list_filters'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_yesno' => ''),
			'list_on_off' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_showhide' => ''),
			'list_sorting' 				=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_sortings' => ''),
			'list_ordering' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_orderings' => ''),
			'list_locations' 			=> array('$'.$THEMEREX_GLOBALS['theme_slug'].'_get_list_dedicated_locations' => '')
			)
		);


		// Theme options array
		$THEMEREX_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'themerex'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'themerex'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'themerex'),
					"desc" => wp_kses( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'themerex'),
					"desc" => wp_kses( __('Select body style:', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
								. ' <br>' 
								. wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
								. ',<br>'
								. wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
								. (themerex_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
									: '')
								. (themerex_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $THEMEREX_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'themerex'),
					"desc" => wp_kses( __('Add paddings above and below the page content', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'themerex'),
					"desc" => wp_kses( __('Select skin for the theme decoration', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "wineshop",
					"options" => $THEMEREX_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the entire page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'themerex'),
					"desc" => wp_kses( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'themerex'),
					"desc" => wp_kses( __('Color and image for the site background', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'themerex'),
					"desc" => wp_kses( __("Use custom color and/or image as the site background", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'themerex'),
					"desc" => wp_kses( __('Body background color',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#F8F6EE",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'themerex'),
					"desc" => wp_kses( __('Select theme background pattern (first case - without pattern)',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => themerex_get_file_url('images/spacer.png'),
						1 => themerex_get_file_url('images/bg/pattern_1.jpg'),
						2 => themerex_get_file_url('images/bg/pattern_2.jpg'),
						3 => themerex_get_file_url('images/bg/pattern_3.jpg'),
						4 => themerex_get_file_url('images/bg/pattern_4.jpg'),
						5 => themerex_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'themerex'),
					"desc" => wp_kses( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'themerex'),
					"desc" => wp_kses( __('Select theme background image (first case - without image)',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => themerex_get_file_url('images/spacer.png'),
						1 => themerex_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => themerex_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => themerex_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'themerex'),
					"desc" => wp_kses( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'themerex'),
					"desc" => wp_kses( __('Select custom image position',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'themerex'),
					"desc" => wp_kses( __('Always load background images or only for boxed body style', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'themerex'),
						'always' => esc_html__('Always', 'themerex')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'themerex'),
					"desc" => wp_kses( __('Parameters of the video, used as site background', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'themerex'),
					"desc" => wp_kses( __("Show video as the site background", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'themerex'),
					"desc" => wp_kses( __("Youtube code of video", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'themerex'),
					"desc" => wp_kses( __("URL to video-file (uploaded on your site)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'themerex'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'themerex'),
															'update' => esc_html__( 'Select Video', 'themerex')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'themerex'),
					"desc" => wp_kses( __('Use overlay texture for the video background', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'themerex'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'themerex'),
					"desc" => wp_kses( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'themerex'),
					"desc" => wp_kses( __('Select desired style of the page header', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "header_5",
					"options" => $THEMEREX_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'themerex'),
					"desc" => wp_kses( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'themerex'),
					"desc" => wp_kses( __('Select position for the top panel with logo and main menu', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'themerex'),
						'above' => esc_html__('Above slider', 'themerex'),
						'below' => esc_html__('Below slider', 'themerex'),
						'over'  => esc_html__('Over slider', 'themerex')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the top panel', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'themerex'),
					"desc" => wp_kses( __('Show post/page/category title', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'themerex'),
					"desc" => wp_kses( __('Show path to current category (post, page)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'themerex'),
					"desc" => wp_kses( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		"page_title_image" => array(
					"title" => esc_html__('Image of the title', 'themerex'),
					"desc" => wp_kses( __('Select default background image of the page title', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_page_title' => 'yes'
					),
					"std" => "",
					"type" => "media"),
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'themerex'),
					"desc" => wp_kses( __('Select the Main menu style and position', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'themerex'),
					"desc" => wp_kses( __('Select main menu for the current page',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'themerex'),
					"desc" => wp_kses( __('Attach main menu to top of window then page scroll down', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "none",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'themerex'), 
						"none"=>esc_html__("Don't fix menu position", 'themerex')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'themerex'),
					"desc" => wp_kses( __('Use slider background for main menu items', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"type" => "switch",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'themerex'),
					"desc" => wp_kses( __('Select animation to show submenu ', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "slideInUp",
					"type" => "select",
					"options" => $THEMEREX_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'themerex'),
					"desc" => wp_kses( __('Select animation to hide submenu ', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "slideOutDown",
					"type" => "select",
					"options" => $THEMEREX_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'themerex'),
					"desc" => wp_kses( __('Allow relayout main menu if window width less then this value', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'themerex'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'themerex'),
					"desc" => wp_kses( __('Width for dropdown menus in main menu', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'themerex'),
					"desc" => wp_kses( __("Select parts for the user's menu area", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'themerex'),
					"desc" => wp_kses( __('Show user menu area on top of page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'themerex'),
					"desc" => wp_kses( __('Select user menu for the current page',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'themerex'),
					"desc" => wp_kses( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_login" => array( 
					"title" => esc_html__('Show Login/Logout buttons', 'themerex'),
					"desc" => wp_kses( __('Show Login and Logout buttons in the user menu area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_bookmarks" => array(
					"title" => esc_html__('Show bookmarks', 'themerex'),
					"desc" => wp_kses( __('Show bookmarks selector in the user menu', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'themerex'),
					"desc" => wp_kses( __('Show Social icons in the user menu area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		

		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'themerex'),
					"desc" => wp_kses( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'themerex'),
					"desc" => wp_kses( __('Show TOC for the current page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'themerex'),
						'fixed' => esc_html__('Fixed', 'themerex'),
						'float' => esc_html__('Float', 'themerex')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'themerex'),
					"desc" => wp_kses( __('Automatically add "Home" item into table of contents - return to home page of the site', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'themerex'),
					"desc" => wp_kses( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'themerex'),
					"desc" => wp_kses( __("Select or upload logos for the site's header and select it position", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'themerex'),
					"desc" => wp_kses( __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'themerex'),
					"desc" => wp_kses( __('Main logo image', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'themerex'),
					"desc" => wp_kses( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'themerex'),
					"desc" => wp_kses( __('Logo text - display it after logo image', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_slogan' => array(
					"title" => esc_html__('Logo slogan', 'themerex'),
					"desc" => wp_kses( __('Logo slogan - display it under logo image (instead the site tagline)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'themerex'),
					"desc" => wp_kses( __('Height for the logo in the header area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'themerex'),
					"desc" => wp_kses( __('Top offset for the logo in the header area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'themerex'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'themerex'),
					"desc" => wp_kses( __('Select parameters for main slider (you can override it in each category and page)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'themerex'),
					"desc" => wp_kses( __('Do you want to show slider on each page (post)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'themerex'),
					"desc" => wp_kses( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'themerex'),
						"fullwide"=>esc_html__("Fullwide", 'themerex'),
						"fullscreen"=>esc_html__("Fullscreen", 'themerex')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'themerex'),
					"desc" => wp_kses( __("Slider height (in pixels) - only if slider display with fixed height.", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'themerex'),
					"desc" => wp_kses( __('What engine use to show slider?', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'themerex'),
					"desc" => wp_kses( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => themerex_array_merge(array(0 => esc_html__('- Select category -', 'themerex')), $THEMEREX_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'themerex'),
					"desc" => wp_kses( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'themerex'),
					"desc" => wp_kses( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'themerex'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'themerex'),
					"desc" => wp_kses( __("Interval (in ms) for slides change in slider", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'themerex'),
					"desc" => wp_kses( __("Choose pagination style for the slider", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'themerex'),
						'yes'  => esc_html__('Dots', 'themerex'), 
						'over' => esc_html__('Titles', 'themerex')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'themerex'),
					"desc" => wp_kses( __("Do you want to show post's title, reviews rating and description on slides in slider", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'themerex'),
						'slide' => esc_html__('Slide', 'themerex'), 
						'fixed' => esc_html__('Fixed', 'themerex')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'themerex'),
					"desc" => wp_kses( __("Do you want to show post's category on slides in slider", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'themerex'),
					"desc" => wp_kses( __("Do you want to show post's reviews rating on slides in slider", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'themerex'),
					"desc" => wp_kses( __("How many characters show in the post's description in slider. 0 - no descriptions", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'themerex'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'themerex'),
					"desc" => wp_kses( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'themerex'),
					"desc" => wp_kses( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'themerex'),
					"desc" => wp_kses( __('Show / Hide and select main sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'themerex'),
					"desc" => wp_kses( __('Select position for the main sidebar or hide it',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $THEMEREX_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the main sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'themerex'),
					"desc" => wp_kses( __('Select main sidebar content',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"info_sidebars_3" => array(
					"title" => esc_html__('Outer sidebar', 'themerex'),
					"desc" => wp_kses( __('Show / Hide and select outer sidebar (sidemenu, logo, etc.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_outer' => array( 
					"title" => esc_html__('Show outer sidebar',  'themerex'),
					"desc" => wp_kses( __('Select position for the outer sidebar or hide it',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "hide",
					"options" => $THEMEREX_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_outer_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the outer sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_outer_show_logo" => array( 
					"title" => esc_html__('Show Logo', 'themerex'),
					"desc" => wp_kses( __('Show Logo in the outer sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'themerex'),
					"desc" => wp_kses( __('Show Social icons in the outer sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"sidebar_outer_show_menu" => array( 
					"title" => esc_html__('Show Menu', 'themerex'),
					"desc" => wp_kses( __('Show Menu in the outer sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_side" => array(
					"title" => esc_html__('Select menu',  'themerex'),
					"desc" => wp_kses( __('Select menu for the outer sidebar',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right'),
						'sidebar_outer_show_menu' => array('yes')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"sidebar_outer_show_widgets" => array( 
					"title" => esc_html__('Show Widgets', 'themerex'),
					"desc" => wp_kses( __('Show Widgets in the outer sidebar', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_outer" => array( 
					"title" => esc_html__('Select outer sidebar',  'themerex'),
					"desc" => wp_kses( __('Select outer sidebar content',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'sidebar_outer_show_widgets' => array('yes'),
						'show_sidebar_outer' => array('left', 'right')
					),
					"std" => "sidebar_outer",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'themerex'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'themerex'),
					"desc" => wp_kses( __("Select components of the footer, set style and put the content for the user's footer area", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'themerex'),
					"desc" => wp_kses( __('Select style for the footer sidebar or hide it', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"sidebar_footer_icon" => array(
					"title" => esc_html__('Footer top icon', 'themerex'),
					"desc" => wp_kses( __('Select show the footer icon or hide it', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'themerex'),
					"desc" => wp_kses( __('Select footer sidebar for the blog page',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'themerex'),
					"desc" => wp_kses( __('Select columns number for the footer sidebar',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 4,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'themerex'),
					"desc" => wp_kses( __('Select parameters for Testimonials in the Footer', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'themerex'),
					"desc" => wp_kses( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the testimonials area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'themerex'),
					"desc" => wp_kses( __('Number testimonials to show', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'themerex'),
					"desc" => wp_kses( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'themerex'),
					"desc" => wp_kses( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the twitter area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'themerex'),
					"desc" => wp_kses( __('Number twitter to show', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'themerex'),
					"desc" => wp_kses( __('Select parameters for Google map (you can override it in each category and page)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'themerex'),
					"desc" => wp_kses( __('Do you want to show Google map on each page (post)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'themerex'),
					"desc" => wp_kses( __("Map height (default - in pixels, allows any CSS units of measure)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'themerex'),
					"desc" => wp_kses( __("Enter address to show on map center", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'themerex'),
					"desc" => wp_kses( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'themerex'),
					"desc" => wp_kses( __("Enter title to show on map center", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'themerex'),
					"desc" => wp_kses( __("Enter description to show on map center", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'themerex'),
					"desc" => wp_kses( __("Enter desired initial zoom for Google map", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'themerex'),
					"desc" => wp_kses( __("Select style to show Google map", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $THEMEREX_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'themerex'),
					"desc" => wp_kses( __("Select or upload png-image with Google map marker", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'themerex'),
					"desc" => wp_kses( __("Show/Hide contacts area in the footer", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'themerex'),
					"desc" => wp_kses( __('Show contact information area in footer: site logo, contact info and large social icons', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the contacts area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'themerex'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'themerex'),
					"desc" => wp_kses( __('Height for the logo in the footer area (in the contacts area)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 95,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'themerex'),
					"desc" => wp_kses( __("Show/Hide copyright area in the footer", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'themerex'),
					"desc" => wp_kses( __('Show area with copyright information, footer menu and small social icons in footer', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "text",
					"options" => array(
						'none' => esc_html__('Hide', 'themerex'),
						'text' => esc_html__('Text', 'themerex'),
						'menu' => esc_html__('Text and menu', 'themerex'),
						'socials' => esc_html__('Text and Social icons', 'themerex')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'themerex'),
					"desc" => wp_kses( __('Select predefined color scheme for the copyright area', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $THEMEREX_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'themerex'),
					"desc" => wp_kses( __('Select footer menu for the current page',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $THEMEREX_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'themerex'),
					"desc" => wp_kses( __("Copyright text to show in footer area (bottom of site)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "<p><a href='#'>ThemeREX</a> © 2015 All Rights Reserved. <a href='#'>Terms of Use</a> and <a href='#'>Privacy Policy</a>.</p>",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'themerex'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'themerex'),
					"desc" => wp_kses( __('Animation parameters and responsive layouts for the small screens', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'themerex'),
					"desc" => wp_kses( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'themerex'),
					"desc" => wp_kses( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'themerex'),
					"desc" => wp_kses( __('Do you want use extended animations effects on your site?', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'themerex'),
					"desc" => wp_kses( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'themerex'),
					"desc" => wp_kses( __('Do you want use responsive layouts on small screen or still use main layout?', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'themerex'),
					"desc" => wp_kses( __('Specify additional parameters, used to load Google fonts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'themerex'),
					"desc" => wp_kses( __('Select subset, included into used Google fonts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'themerex'),
						'latin-ext' => esc_html__('Latin Extended', 'themerex'),
						'greek' => esc_html__('Greek', 'themerex'),
						'greek-ext' => esc_html__('Greek Extended', 'themerex'),
						'cyrillic' => esc_html__('Cyrillic', 'themerex'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'themerex'),
						'vietnamese' => esc_html__('Vietnamese', 'themerex')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'themerex'),
					"desc" => wp_kses( __('Put here your custom CSS and JS code', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'themerex'),
					"desc" => wp_kses( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'themerex'),
					"desc" => wp_kses( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'themerex'),
					"desc" => wp_kses( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'themerex'),
					"desc" => wp_kses( __('Put here your invisible html/js code: Google analitics, counters, etc',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'themerex'),
					"desc" => wp_kses( __('Put here your css code to correct main theme styles',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'themerex'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'themerex'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'themerex'),
					"desc" => wp_kses( __('Select desired blog streampage parameters (you can override it in each category)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'themerex'),
					"desc" => wp_kses( __('Select desired blog style', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $THEMEREX_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'themerex'),
					"desc" => wp_kses( __('Select desired hover style (only for Blog style = Portfolio)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $THEMEREX_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'themerex'),
					"desc" => wp_kses( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $THEMEREX_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'themerex'),
					"desc" => wp_kses( __('Select article display method: boxed or stretch', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $THEMEREX_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'themerex'),
					"desc" => wp_kses( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $THEMEREX_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'themerex'),
					"desc" => wp_kses( __('What taxonomy use for filter buttons', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $THEMEREX_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'themerex'),
					"desc" => wp_kses( __('Select the desired sorting method for posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'themerex'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'themerex'),
					"desc" => wp_kses( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'themerex'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'themerex'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'themerex'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'themerex'),
					"desc" => wp_kses( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'themerex'),
					"desc" => wp_kses( __('Select desired style for single page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $THEMEREX_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'themerex'),
					"desc" => wp_kses( __('Select icon for output before post/category name in some layouts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'themerex'),
					"override" => "page,post",
					"desc" => wp_kses( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => $THEMEREX_GLOBALS['options_params']['list_alter_sizes']
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'themerex'),
					"desc" => wp_kses( __("Show featured image (if selected) before post content on single pages", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'themerex'),
					"desc" => wp_kses( __('Show area with post title on single pages', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'themerex'),
					"desc" => wp_kses( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'themerex'),
					"desc" => wp_kses( __('Show area with post info on single pages', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'themerex'),
					"desc" => wp_kses( __('Show text before "Read more" tag on single pages', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'themerex'),
					"desc" => wp_kses( __("Show post author information block on single post page", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'themerex'),
					"desc" => wp_kses( __("Show tags block on single post page", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'themerex'),
					"desc" => wp_kses( __("Show related posts block on single post page", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'themerex'),
					"desc" => wp_kses( __("How many related posts showed on single post page", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'themerex'),
					"desc" => wp_kses( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'themerex'),
					"desc" => wp_kses( __('Select the desired sorting method for related posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $THEMEREX_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'themerex'),
					"desc" => wp_kses( __('Select the desired ordering method for related posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $THEMEREX_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'themerex'),
					"desc" => wp_kses( __("Show comments block on single post page", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'themerex'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'themerex'),
					"desc" => wp_kses( __('Select excluded categories, substitute parameters, etc.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'themerex'),
					"desc" => wp_kses( __('Select categories, which posts are exclude from blog page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'themerex'),
					"desc" => wp_kses( __('Select type of the pagination on blog streampages', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'themerex'),
						'slider'   => esc_html__('Slider with page numbers', 'themerex'),
						'viewmore' => esc_html__('"View more" button', 'themerex'),
						'infinite' => esc_html__('Infinite scroll', 'themerex')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'themerex'),
					"desc" => wp_kses( __('Select counters, displayed near the post title', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => $THEMEREX_GLOBALS['options_params']['list_blog_counters'],
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'themerex'),
					"desc" => wp_kses( __('What category display in announce block (over posts thumb) - original or nearest parental', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'themerex'),
						'original' => esc_html__("Original post's category", 'themerex')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'themerex'),
					"desc" => wp_kses( __('Show post date after N days (before - show post age)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'themerex'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'themerex'),
					"desc" => wp_kses( __('Set up list of reviews criterias. You can override it in any category.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'themerex'),
					"desc" => wp_kses( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'themerex'),
					"desc" => wp_kses( __("Maximum level for reviews marks", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'themerex'), 
						'10'=>esc_html__('10 stars', 'themerex'), 
						'100'=>esc_html__('100%', 'themerex')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'themerex'),
					"desc" => wp_kses( __("Show rating marks as text or as stars/progress bars.", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'themerex'), 
						'stars' => esc_html__('As stars or bars', 'themerex')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'themerex'),
					"desc" => wp_kses( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => esc_html__("bad,poor,normal,good,great", 'themerex'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'themerex'),
					"desc" => wp_kses( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'themerex'),
						'users' => esc_html__('By visitors', 'themerex')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'themerex'),
					"desc" => wp_kses( __("Do you want hide second reviews tab in widgets and single posts?", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "show",
					"options" => $THEMEREX_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'themerex'),
					"desc" => wp_kses( __("What visitors can vote: all or only registered", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'themerex'), 
						'registered'=>esc_html__('Only registered', 'themerex')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'themerex'),
					"desc" => wp_kses( __('Add default reviews criterias.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'themerex'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'themerex'),
					"desc" => wp_kses( __('Set up parameters to show images, galleries, audio and video posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'themerex'),
					"desc" => wp_kses( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'themerex'), 
						"2" => esc_html__("Retina", 'themerex')
					),
					"type" => "switch"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'themerex'),
					"desc" => wp_kses( __('Substitute standard Wordpress gallery with our slider on the single pages', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'themerex'),
					"desc" => wp_kses( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'themerex'),
					"desc" => wp_kses( __('Maximum images number from gallery into slider', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'themerex'),
					"desc" => wp_kses( __('Select engine to show popup windows with images and galleries', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "magnific",
					"options" => $THEMEREX_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'themerex'),
					"desc" => wp_kses( __('Substitute audio tag with source from soundcloud to embed player', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'themerex'),
					"desc" => wp_kses( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'themerex'),
					"desc" => wp_kses( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'themerex'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'themerex'),
					"desc" => wp_kses( __("Social networks list for site footer and Social widget", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'themerex'),
					"desc" => wp_kses( __('Select icon and write URL to your profile in desired social networks.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $THEMEREX_GLOBALS['options_params']['list_socials'] : $THEMEREX_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'themerex'),
					"desc" => wp_kses( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'themerex'),
					"desc" => wp_kses( __("Show social share buttons block", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'themerex'),
						'vertical'	=> esc_html__('Vertical', 'themerex'),
						'horizontal'=> esc_html__('Horizontal', 'themerex')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'themerex'),
					"desc" => wp_kses( __("Show share counters after social buttons", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'themerex'),
					"desc" => wp_kses( __('Caption for the block with social share buttons',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'themerex'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'themerex'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $THEMEREX_GLOBALS['options_params']['list_socials'] : $THEMEREX_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'themerex'),
					"desc" => wp_kses( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'themerex'),
					"desc" => wp_kses( __('Your login (username) in Twitter',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'themerex'),
					"desc" => wp_kses( __('Twitter API Consumer key',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'themerex'),
					"desc" => wp_kses( __('Twitter API Consumer secret',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'themerex'),
					"desc" => wp_kses( __('Twitter API Token key',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'themerex'),
					"desc" => wp_kses( __('Twitter API Token secret',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
					
		"info_socials_4" => array(
			"title" => esc_html__('Login via Social network', 'themerex'),
			"desc" => esc_html__("Settings for the Login via Social networks", 'themerex'),
			"type" => "info"),

		"social_login" => array(
			"title" => esc_html__('Social plugin shortcode',  'themerex'),
			"desc" => esc_html__('Social plugin shortcode like [plugin_shortcode]',  'themerex'),
			"divider" => false,
			"std" => "",
			"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'themerex'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'themerex'),
					"desc" => wp_kses( __('Company address, phones and e-mail', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'themerex'),
					"desc" => wp_kses( __('String with contact info in the left side of the site header', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'themerex'),
					"desc" => wp_kses( __('String with open hours in the site header', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "Open hours: 8.00-18.00 Mon-Sat",
					"before" => array('icon'=>'iconadmin-clock'),
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'themerex'),
					"desc" => wp_kses( __('E-mail for send contact form and user registration data', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "admin@example.com",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'themerex'),
					"desc" => wp_kses( __('Company country, post code and city', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "Chicago, IL 60606 ",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'themerex'),
					"desc" => wp_kses( __('Street and house number', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "123, New Lenox",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'themerex'),
					"desc" => wp_kses( __('Phone number', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "123-456-7890",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'themerex'),
					"desc" => wp_kses( __('Fax number', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "098-765-4321",
					"before" => array('icon'=>'iconadmin-phone'),
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'themerex'),
					"desc" => wp_kses( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'themerex'),
					"desc" => wp_kses( __("Message's maxlength in the contact form shortcode", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'themerex'),
					"desc" => wp_kses( __("Message's maxlength in the comments form", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'themerex'),
					"desc" => wp_kses( __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'themerex'),
					"desc" => wp_kses( __("What function you want to use for sending mail?", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'themerex'),
						'mail' => esc_html__('PHP mail', 'themerex')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'themerex'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'themerex'),
					"desc" => wp_kses( __('Enable/disable AJAX search and output settings for it', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'themerex'),
					"desc" => wp_kses( __('Show search field in the top area and side menus', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'themerex'),
					"desc" => wp_kses( __('Use incremental AJAX search for the search field in top of page', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'themerex'),
					"desc" => wp_kses( __('The minimum length of the search string',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'themerex'),
					"desc" => wp_kses( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'themerex'),
					"desc" => wp_kses( __('Select post types, what will be include in search results. If not selected - use all types.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $THEMEREX_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'themerex'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __('Number of the posts to show in search results',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'themerex'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's thumbnail in the search results", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'themerex'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's publish date in the search results", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'themerex'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's author in the search results", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'themerex'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's counters (views, comments, likes) in the search results", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'themerex'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'themerex'),
					"desc" => wp_kses( __('Basic theme functionality settings', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'themerex'),
					"desc" => wp_kses( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'themerex'),
						'both'  => esc_html__('Both', 'themerex'),
						'admin' => esc_html__('Admin', 'themerex'),
						'user'  => esc_html__('User', 'themerex')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'themerex'),
					"desc" => wp_kses( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'themerex'),
					"desc" => wp_kses( __("Allow authors to edit their posts in frontend area)", 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'themerex'),
					"desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'themerex'),
					"desc" => wp_kses( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'themerex'),
					"desc" => wp_kses( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'themerex'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'themerex'),
					"desc" => wp_kses( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => 1200,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'themerex'),
					"desc" => wp_kses( __('Allow to use ThemeREX Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'themerex'),
					"desc" => wp_kses( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'themerex'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services (for example <a href="http://yui.2clics.net/" target="_blank">http://yui.2clics.net/</a>) or utility <b>yuicompressor-x.y.z.jar</b>', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $THEMEREX_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"info_service_2" => array(
					 "title" => esc_html__('API Keys', 'themerex'),
					 "desc" => wp_kses_data( __('API Keys for some Web services', 'themerex') ),
					 "type" => "info"),
					 
		'api_google' => array(
					 "title" => esc_html__('Google API Key', 'themerex'),
					 "desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'themerex') ),
					 "std" => "",
					 "type" => "text"),
					 
		"info_service_3" => array(
					"title" => esc_html__('Clear Wordpress cache', 'themerex'),
					"desc" => wp_kses( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'themerex'),
					"desc" => wp_kses( __('Clear Wordpress cache data', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$THEMEREX_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => esc_html__('Custom stylesheet file', 'themerex'),
			"desc" => wp_kses( __('Path to the custom stylesheet (stored in the uploads folder)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		
		$THEMEREX_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => esc_html__('Custom stylesheet url', 'themerex'),
			"desc" => wp_kses( __('URL to the custom stylesheet (stored in the uploads folder)', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		*/

		
		
	}
}


// Update all temporary vars (start with $themerex_) in the Theme Options with actual lists
if ( !function_exists( 'themerex_options_settings_theme_setup2' ) ) {
	add_action( 'themerex_action_after_init_theme', 'themerex_options_settings_theme_setup2', 1 );
	function themerex_options_settings_theme_setup2() {
		if (themerex_options_is_used()) {
			global $THEMEREX_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($THEMEREX_GLOBALS['options']) > 0) {
				$prefix = '$' . $THEMEREX_GLOBALS['theme_slug'] . '_';
				$prefix_len = themerex_strlen($prefix);
				foreach ($THEMEREX_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (themerex_substr($k1, 0, $prefix_len) == $prefix || themerex_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = themerex_substr(themerex_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($THEMEREX_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$THEMEREX_GLOBALS['options'][$k]['options'] = themerex_array_merge($THEMEREX_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$THEMEREX_GLOBALS['options'][$k]['options'] = $lists[$list_func] = themerex_array_merge($THEMEREX_GLOBALS['options'][$k]['options'], $list_func == 'themerex_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'themerex'), $list_func));
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'themerex_options_reset' ) ) {
	function themerex_options_reset($clear=true) {/*
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(themerex_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'themerex_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "themerex_%"');
				// Add Templates Options
				if (file_exists(themerex_get_file_dir('demo/templates_options.txt'))) {
					$txt = themerex_fgc(themerex_get_file_dir('demo/templates_options.txt'));
					$data = themerex_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = themerex_replace_uploads_url(themerex_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}*/
	}
}
?>