<?php
/**
 * ThemeREX Framework: Theme options manager
 *
 * @package	themerex
 * @since	themerex 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_options_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_options_theme_setup' );
	function themerex_options_theme_setup() {

		if ( is_admin() ) {
			// Add Theme Options in WP menu
			add_action('admin_menu', 								'themerex_options_admin_menu_item');

			if ( themerex_options_is_used() ) {
				// Make custom stylesheet when save theme options
				//add_filter("themerex_filter_save_options",		'themerex_options_save_stylesheet', 10, 3);

				// Ajax Save and Export Action handler
				add_action('wp_ajax_themerex_options_save', 		'themerex_options_save');
				add_action('wp_ajax_nopriv_themerex_options_save',	'themerex_options_save');

				// Ajax Import Action handler
				add_action('wp_ajax_themerex_options_import',		'themerex_options_import');
				add_action('wp_ajax_nopriv_themerex_options_import','themerex_options_import');

				// Prepare global variables
				global $THEMEREX_GLOBALS;
				$THEMEREX_GLOBALS['to_data'] = null;
				$THEMEREX_GLOBALS['to_delimiter'] = ',';
				$THEMEREX_GLOBALS['to_colorpicker'] = 'tiny';			// wp - WP colorpicker, custom - internal theme colorpicker, tiny - external script
			}
		}
		
	}
}


// Add 'Theme options' in Admin Interface
if ( !function_exists( 'themerex_options_admin_menu_item' ) ) {
	//add_action('admin_menu', 'themerex_options_admin_menu_item');
	function themerex_options_admin_menu_item() {
	
		// In this case menu item "Theme Options" add in root admin menu level
		themerex_admin_add_menu_item('menu', array(
			'page_title' => esc_html__('Global Options', 'themerex'),
			'menu_title' => esc_html__('Theme Options', 'themerex'),
			'capability' => 'manage_options',
			'menu_slug'  => 'themerex_options',
			'callback'   => 'themerex_options_page',
			'icon'		 => ''
			), '81.1'
		);
		themerex_admin_add_menu_item('submenu', array(
			'parent'     => 'themerex_options',
			'page_title' => esc_html__('Global Options', 'themerex'),
			'menu_title' => esc_html__('Global Options', 'themerex'),
			'capability' => 'manage_options',
			'menu_slug'  => 'themerex_options',
			'callback'   => 'themerex_options_page',
			'icon'		 => ''
			)
		);
		// Add submenu items for each inheritance item
		$inheritance = themerex_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				// Check if not create Options page 
				if (isset($v['use_options_page']) && !$v['use_options_page']) continue;
				// Create Options page
				$tpl = false;
				if (!empty($v['stream_template'])) {
					$slug = themerex_get_slug($v['stream_template']);
					$title = themerex_strtoproper(str_replace('_', ' ', $slug));
					themerex_admin_add_menu_item('submenu', array(
						'parent'     => 'themerex_options',
						'page_title' => $title.' '.esc_html__('Options', 'themerex'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'themerex_options_'.($slug),
						'callback'   => 'themerex_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
				if (!empty($v['single_template'])) {
					$slug = themerex_get_slug($v['single_template']);
					$title = themerex_strtoproper(str_replace('_', ' ', $slug));
					themerex_admin_add_menu_item('submenu', array(
						'parent'     => 'themerex_options',
						'page_title' => $title.' '.esc_html__('Options', 'themerex'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'themerex_options_'.($slug),
						'callback'   => 'themerex_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
				if (!$tpl) {
					$slug = themerex_get_slug($k);
					$title = themerex_strtoproper(str_replace('_', ' ', $slug));
					themerex_admin_add_menu_item('submenu', array(
						'parent'     => 'themerex_options',
						'page_title' => $title.' '.esc_html__('Options', 'themerex'),
						'menu_title' => $title,
						'capability' => 'manage_options',
						'menu_slug'  => 'themerex_options_'.($slug),
						'callback'   => 'themerex_options_page',
						'icon'		 => ''
						)
					);
					$tpl = true;
				}
			}
		}
	}
}



/* Theme options utils
-------------------------------------------------------------------- */

// Check if theme options are now used
if ( !function_exists( 'themerex_options_is_used' ) ) {
	function themerex_options_is_used() {
		$used = false;
		if (is_admin()) {
			if (isset($_REQUEST['action']) && ($_REQUEST['action']=='themerex_options_save' || $_REQUEST['action']=='themerex_options_import'))		// AJAX: Save or Import Theme Options
				$used = true;
			else if (themerex_strpos($_SERVER['REQUEST_URI'], 'themerex_options')!==false)															// Edit Theme Options
				$used = true;
			else if (themerex_strpos($_SERVER['REQUEST_URI'], 'post-new.php')!==false || themerex_strpos($_SERVER['REQUEST_URI'], 'post.php')!==false) {	// Create or Edit Post (page, product, ...)
				$post_type = themerex_admin_get_current_post_type();
				if (empty($post_type)) $post_type = 'post';
				$used = themerex_get_override_key($post_type, 'post_type')!='';
			} else if (themerex_strpos($_SERVER['REQUEST_URI'], 'edit-tags.php')!==false) {															// Edit Taxonomy
				$inheritance = themerex_get_theme_inheritance();
				if (!empty($inheritance) && is_array($inheritance)) {
					$post_type = themerex_admin_get_current_post_type();
					if (empty($post_type)) $post_type = 'post';
					foreach ($inheritance as $k=>$v) {
						if (!empty($v['taxonomy']) && is_array($v['taxonomy'])) {
							foreach ($v['taxonomy'] as $tax) {
								if ( themerex_strpos($_SERVER['REQUEST_URI'], 'taxonomy='.($tax))!==false && in_array($post_type, $v['post_type']) ) {
									$used = true;
									break;
								}
							}
						}
					}
				}
			} else if ( isset($_POST['meta_box_taxonomy_nonce']) ) {																				// AJAX: Save taxonomy
				$used = true;
			}
		} else {
			$used = (themerex_get_theme_option("allow_editor")=='yes' && 
						(
						(is_single() && current_user_can('edit_posts', get_the_ID())) 
						|| 
						(is_page() && current_user_can('edit_pages', get_the_ID()))
						)
					);
		}
		return apply_filters('themerex_filter_theme_options_is_used', $used);
	}
}


// Load all theme options
if ( !function_exists( 'themerex_load_main_options' ) ) {
	function themerex_load_main_options() {
		global $THEMEREX_GLOBALS;
		$options = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options', array());
		if (is_array($THEMEREX_GLOBALS['options']) && count($THEMEREX_GLOBALS['options']) > 0) {
			foreach ($THEMEREX_GLOBALS['options'] as $id => $item) {
				if (isset($item['std'])) {
					if (isset($options[$id]))
						$THEMEREX_GLOBALS['options'][$id]['val'] = $options[$id];
					else
						$THEMEREX_GLOBALS['options'][$id]['val'] = $item['std'];
				}
			}
		}
		// Call actions after load options
		do_action('themerex_action_load_main_options');
	}
}


// Get custom options arrays (from current category, post, page, shop, event, etc.)
if ( !function_exists( 'themerex_load_custom_options' ) ) {
	function themerex_load_custom_options() {
		global $wp_query, $post, $THEMEREX_GLOBALS;

		$THEMEREX_GLOBALS['custom_options'] = $THEMEREX_GLOBALS['post_options'] = $THEMEREX_GLOBALS['taxonomy_options'] = $THEMEREX_GLOBALS['template_options'] = array();
		$THEMEREX_GLOBALS['theme_options_loaded'] = false;
		
		if ( is_admin() ) {
			$THEMEREX_GLOBALS['theme_options_loaded'] = true;
			return;
		}

		// This way used then user set options in admin menu (new variant)
		$inheritance_key = themerex_detect_inheritance_key();
		if (!empty($inheritance_key)) $inheritance = themerex_get_theme_inheritance($inheritance_key);
		$slug = themerex_detect_template_slug($inheritance_key);
		if ( !empty($slug) ) {
			if (empty($inheritance['use_options_page']) || $inheritance['use_options_page'])
				$THEMEREX_GLOBALS['template_options'] = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options_template_'.trim($slug));
			else
				$THEMEREX_GLOBALS['template_options'] = false;
			// If settings for current slug not saved - use settings from compatible overriden type
			if ($THEMEREX_GLOBALS['template_options']===false && !empty($inheritance['override'])) {
				$slug = themerex_get_template_slug($inheritance['override']);
				if ( !empty($slug) ) $THEMEREX_GLOBALS['template_options'] = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options_template_'.trim($slug));
			}
			if ($THEMEREX_GLOBALS['template_options']===false) $THEMEREX_GLOBALS['template_options'] = array();
		}

		// Load taxonomy and post options
		if (!empty($inheritance_key)) {
			//$inheritance = themerex_get_theme_inheritance($inheritance_key);
			// Load taxonomy options
			if (!empty($inheritance['taxonomy']) && is_array($inheritance['taxonomy'])) {
				foreach ($inheritance['taxonomy'] as $tax) {
					$tax_obj = get_taxonomy($tax);
					$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
					if ($tax == 'category' && is_category()) {		// Current page is category's archive (Categories need specific check)
						$tax_id = (int) get_query_var( 'cat' );
						if (empty($tax_id)) $tax_id = get_query_var( 'category_name' );
						$THEMEREX_GLOBALS['taxonomy_options'] = themerex_taxonomy_get_inherited_properties('category', $tax_id);
						break;
					} else if ($tax == 'post_tag' && is_tag()) {	// Current page is tag's archive (Tags need specific check)
						$tax_id = get_query_var( $tax_query );
						$THEMEREX_GLOBALS['taxonomy_options'] = themerex_taxonomy_get_inherited_properties('post_tag', $tax_id);
						break;
					} else if (is_tax($tax)) {						// Current page is custom taxonomy archive (All rest taxonomies check)
						$tax_id = get_query_var( $tax_query );
						$THEMEREX_GLOBALS['taxonomy_options'] = themerex_taxonomy_get_inherited_properties($tax, $tax_id);
						break;
					}
				}
			}
			// Load post options
			if ( is_singular() && (!empty($THEMEREX_GLOBALS['page_template']) || !themerex_get_global('blog_streampage')) ) {
				$post_id = get_the_ID();
				if ( $post_id == 0 && !empty($wp_query->queried_object_id) ) $post_id = $wp_query->queried_object_id;
				$THEMEREX_GLOBALS['post_options'] = get_post_meta($post_id, 'post_custom_options', true);
				if ( !empty($inheritance['post_type']) && !empty($inheritance['taxonomy'])
					&& ( in_array( get_query_var('post_type'), $inheritance['post_type']) 
						|| ( !empty($post->post_type) && in_array( $post->post_type, $inheritance['post_type']) )
						) 
					) {
					$tax_list = array();
					foreach ($inheritance['taxonomy'] as $tax) {
						$tax_terms = themerex_get_terms_by_post_id( array(
							'post_id'=>$post_id, 
							'taxonomy'=>$tax
							)
						);
						if (!empty($tax_terms[$tax]->terms)) {
							$tax_list[] = themerex_taxonomies_get_inherited_properties($tax, $tax_terms[$tax]);
						}
					}
					if (!empty($tax_list)) {
						foreach($tax_list as $tax_options) {
							if (!empty($tax_options) && is_array($tax_options)) {
								foreach($tax_options as $tk=>$tv) {
									if ( !isset($THEMEREX_GLOBALS['taxonomy_options'][$tk]) || themerex_is_inherit_option($THEMEREX_GLOBALS['taxonomy_options'][$tk]) ) {
										$THEMEREX_GLOBALS['taxonomy_options'][$tk] = $tv;
									}
								}
							}
						}
					}
				}
			}
		}
		
		// Merge Template options with required for current page template
		$layout_name = themerex_get_custom_option(is_singular() && !themerex_get_global('blog_streampage') ? 'single_style' : 'blog_style');
		if (!empty($THEMEREX_GLOBALS['registered_templates'][$layout_name]['theme_options'])) {
			$THEMEREX_GLOBALS['template_options'] = array_merge($THEMEREX_GLOBALS['template_options'], $THEMEREX_GLOBALS['registered_templates'][$layout_name]['theme_options']);
		}
		
		do_action('themerex_action_load_custom_options');

		$THEMEREX_GLOBALS['theme_options_loaded'] = true;

	}
}


// Get theme setting
if ( !function_exists( 'themerex_get_theme_setting' ) ) {
	function themerex_get_theme_setting($option_name, $default='') {
		global $THEMEREX_GLOBALS;
		return isset($THEMEREX_GLOBALS['settings'][$option_name]) ? $THEMEREX_GLOBALS['settings'][$option_name] : $default;
	}
}


// Set theme setting
if ( !function_exists( 'themerex_set_theme_setting' ) ) {
	function themerex_set_theme_setting($option_name, $value) {
		global $THEMEREX_GLOBALS;
		if (isset($THEMEREX_GLOBALS['settings'][$option_name]))
			$THEMEREX_GLOBALS['settings'][$option_name] = $value;
	}
}


// Get theme option. If not exists - try get site option. If not exist - return default
if ( !function_exists( 'themerex_get_theme_option' ) ) {
	function themerex_get_theme_option($option_name, $default = false, $options = null) {
		global $THEMEREX_GLOBALS;
		static $themerex_options = false;
		$val = '';	//false;
		if (is_array($options)) {
			if (isset($option[$option_name])) {
				$val = $option[$option_name]['val'];
			}
		} else if (isset($THEMEREX_GLOBALS['options'][$option_name]['val'])) { // if (isset($THEMEREX_GLOBALS['options'])) {
			$val = $THEMEREX_GLOBALS['options'][$option_name]['val'];
		} else {
			if ($themerex_options===false) $themerex_options = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options', array());
			if (isset($themerex_options[$option_name])) {
				$val = $themerex_options[$option_name];
			} else if (isset($THEMEREX_GLOBALS['options'][$option_name]['std'])) {
				$val = $THEMEREX_GLOBALS['options'][$option_name]['std'];
			}
		}
		if ($val === '') {	//false) {
			if (($val = get_option($option_name, false)) !== false) {
				return $val;
			} else {
				return $default;
			}
		} else {
			return $val;
		}
	}
}


// Return property value from request parameters < post options < category options < theme options
if ( !function_exists( 'themerex_get_custom_option' ) ) {
	function themerex_get_custom_option($name, $defa=null, $post_id=0, $post_type='post', $tax_id=0, $tax_type='category') {
		if (isset($_GET[$name]))
			$rez = $_GET[$name];
		else {
			global $THEMEREX_GLOBALS;
			$hash_name = ($name).'_'.($tax_id).'_'.($post_id);
			if (!empty($THEMEREX_GLOBALS['theme_options_loaded']) && isset($THEMEREX_GLOBALS['custom_options'][$hash_name])) {
				$rez = $THEMEREX_GLOBALS['custom_options'][$hash_name];
			} else {
				if ($tax_id > 0) {
					$rez = themerex_taxonomy_get_inherited_property($tax_type, $tax_id, $name);
					if ($rez=='') $rez = themerex_get_theme_option($name, $defa);
				} else if ($post_id > 0) {
					$rez = themerex_get_theme_option($name, $defa);
					$custom_options = get_post_meta($post_id, 'post_custom_options', true);
					if (isset($custom_options[$name]) && !themerex_is_inherit_option($custom_options[$name])) {
						$rez = $custom_options[$name];
					} else {
						$terms = array();
						$tax = themerex_get_taxonomy_categories_by_post_type($post_type);
						$tax_obj = get_taxonomy($tax);
						$tax_query = !empty($tax_obj->query_var) ? $tax_obj->query_var : $tax;
						if ( ($tax=='category' && is_category()) || ($tax=='post_tag' && is_tag()) || is_tax($tax) ) {		// Current page is taxonomy's archive (Categories and Tags need specific check)
							$terms = array( get_queried_object() );
						} else {
							$taxes = themerex_get_terms_by_post_id(array('post_id'=>$post_id, 'taxonomy'=>$tax));
							if (!empty($taxes[$tax]->terms)) {
								$terms = $taxes[$tax]->terms;
							}
						}
						$tmp = '';
						if (!empty($terms)) {
							for ($cc = 0; $cc < count($terms) && (empty($tmp) || themerex_is_inherit_option($tmp)); $cc++) {
								$tmp = themerex_taxonomy_get_inherited_property($terms[$cc]->taxonomy, $terms[$cc]->term_id, $name);
							}
						}
						if ($tmp!='') $rez = $tmp;
					}
				} else {
					$rez = themerex_get_theme_option($name, $defa);
					if (themerex_get_theme_option('show_theme_customizer') == 'yes' && themerex_get_theme_option('remember_visitors_settings') == 'yes' && function_exists('themerex_get_value_gpc')) {
						$tmp = themerex_get_value_gpc($name, $rez);
						if (!themerex_is_inherit_option($tmp)) {
							$rez = $tmp;
						}
					}
					if (isset($THEMEREX_GLOBALS['template_options'][$name]) && !themerex_is_inherit_option($THEMEREX_GLOBALS['template_options'][$name])) {
						$rez = is_array($THEMEREX_GLOBALS['template_options'][$name]) ? $THEMEREX_GLOBALS['template_options'][$name][0] : $THEMEREX_GLOBALS['template_options'][$name];
					}
					if (isset($THEMEREX_GLOBALS['taxonomy_options'][$name]) && !themerex_is_inherit_option($THEMEREX_GLOBALS['taxonomy_options'][$name])) {
						$rez = $THEMEREX_GLOBALS['taxonomy_options'][$name];
					}
					if (isset($THEMEREX_GLOBALS['post_options'][$name]) && !themerex_is_inherit_option($THEMEREX_GLOBALS['post_options'][$name])) {
						$rez = is_array($THEMEREX_GLOBALS['post_options'][$name]) ? $THEMEREX_GLOBALS['post_options'][$name][0] : $THEMEREX_GLOBALS['post_options'][$name];
					}
				}
				$rez = apply_filters('themerex_filter_get_custom_option', $rez, $name);
				if (!empty($THEMEREX_GLOBALS['theme_options_loaded'])) $THEMEREX_GLOBALS['custom_options'][$hash_name] = $rez;
			}
		}
		return $rez;
	}
}


// Check option for inherit value
if ( !function_exists( 'themerex_is_inherit_option' ) ) {
	function themerex_is_inherit_option($value) {
		while (is_array($value) && count($value)>0) {
			foreach ($value as $val) {
				$value = $val;
				break;
			}
		}
		return themerex_strtolower($value)=='inherit';	//in_array(themerex_strtolower($value), array('default', 'inherit'));
	}
}



/* Theme options manager
-------------------------------------------------------------------- */

// Load required styles and scripts for Options Page
if ( !function_exists( 'themerex_options_load_scripts' ) ) {
	function themerex_options_load_scripts() {
		// ThemeREX fontello styles
		themerex_enqueue_style( 'themerex-fontello-admin-style',	themerex_get_file_url('css/fontello-admin/css/fontello-admin.css'), array(), null);
		themerex_enqueue_style( 'themerex-fontello-style', 			themerex_get_file_url('css/fontello/css/fontello.css'), array(), null);
		themerex_enqueue_style( 'themerex-fontello-animation-style',themerex_get_file_url('css/fontello-admin/css/animation.css'), array(), null);
		// ThemeREX options styles
		themerex_enqueue_style('themerex-options-style',			themerex_get_file_url('core/core.options/css/core.options.css'), array(), null);
		themerex_enqueue_style('themerex-options-datepicker-style',	themerex_get_file_url('core/core.options/css/core.options-datepicker.css'), array(), null);

		// WP core media scripts
		wp_enqueue_media();

		// Color Picker
		global $THEMEREX_GLOBALS;
		//if ($THEMEREX_GLOBALS['to_colorpicker'] == 'wp') {
			themerex_enqueue_style( 'wp-color-picker', false, array(), null);
			themerex_enqueue_script('wp-color-picker', false, array('jquery'), null, true);
		//} else if ($THEMEREX_GLOBALS['to_colorpicker'] == 'tiny') {
			themerex_enqueue_script('themerex-colors-script',		themerex_get_file_url('js/colorpicker/colors.js'), array('jquery'), null, true );	
			//themerex_enqueue_style( 'themerex-colorpicker-style',	themerex_get_file_url('js/colorpicker/jqColorPicker.css'), array(), null);
			themerex_enqueue_script('themerex-colorpicker-script',	themerex_get_file_url('js/colorpicker/jqColorPicker.js'), array('jquery'), null, true );	
		//}

		// Input masks for text fields
		themerex_enqueue_script( 'jquery-input-mask',				themerex_get_file_url('core/core.options/js/jquery.maskedinput.1.3.1.min.js'), array('jquery'), null, true );	
		// ThemeREX core scripts
		themerex_enqueue_script( 'themerex-core-utils-script',		themerex_get_file_url('js/core.utils.js'), array(), null, true );	
		// ThemeREX options scripts
		themerex_enqueue_script( 'themerex-options-script',			themerex_get_file_url('core/core.options/js/core.options.js'), array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-datepicker'), null, true );	
		themerex_enqueue_script( 'themerex-options-custom-script',	themerex_get_file_url('core/core.options/js/core.options-custom.js'), array('themerex-options-script'), null, true );	

		themerex_enqueue_messages();
		themerex_enqueue_popup();
	}
}


// Prepare javascripts global variables
if ( !function_exists( 'themerex_options_prepare_scripts' ) ) {
	function themerex_options_prepare_scripts($override='') {
		global $THEMEREX_GLOBALS;
		if (empty($override)) $override = 'general';
		$json_parse_func = 'eval';	// 'JSON.parse'
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				try {
					THEMEREX_GLOBALS['to_options']	= <?php echo trim($json_parse_func); ?>(<?php echo json_encode( themerex_array_prepare_to_json($THEMEREX_GLOBALS['to_data']) ); ?>);
				} catch(e) {}
				THEMEREX_GLOBALS['to_delimiter']	= "<?php echo esc_attr($THEMEREX_GLOBALS['to_delimiter']); ?>";
				THEMEREX_GLOBALS['to_slug']			= "<?php echo esc_attr($THEMEREX_GLOBALS['to_flags']['slug']); ?>";
				THEMEREX_GLOBALS['to_popup']		= "<?php echo esc_attr(themerex_get_theme_option('popup_engine')); ?>";
				THEMEREX_GLOBALS['to_override']		= "<?php echo esc_attr($override); ?>";
				THEMEREX_GLOBALS['to_export_list']	= [<?php
					if (($export_opts = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options_export_'.($override), false)) !== false) {
						$keys = join('","', array_keys($export_opts));
						if ($keys) echo '"'.($keys).'"';
					}
				?>];
				if (THEMEREX_GLOBALS['to_strings']==undefined) THEMEREX_GLOBALS['to_strings'] = {};
				THEMEREX_GLOBALS['to_strings'].del_item_error			= "<?php esc_html_e("You can't delete last item! To disable it - just clear value in field.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].del_item 				= "<?php esc_html_e("Delete item error!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].recompile_styles			= "<?php esc_html_e("When saving color schemes and font settings, recompilation of .less files occurs. It may take from 5 to 15 secs dependning on your server's speed and size of .less files.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].wait 					= "<?php esc_html_e("Please wait a few seconds!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].reload_page				= "<?php esc_html_e("After 3 seconds this page will be reloaded.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].save_options				= "<?php esc_html_e("Options saved!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].reset_options			= "<?php esc_html_e("Options reset!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].reset_options_confirm	= "<?php esc_html_e("Do you really want reset all options to default values?", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].reset_options_complete	= "<?php esc_html_e("Settings are reset to their default values.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options_header 	= "<?php esc_html_e("Export options", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options_error		= "<?php esc_html_e("Name for options set is not selected! Export cancelled.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options_label		= "<?php esc_html_e("Name for the options set:", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options_label2	= "<?php esc_html_e("or select one of exists set (for replace):", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options_select	= "<?php esc_html_e("Select set for replace ...", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_empty				= "<?php esc_html_e("No exported sets for import!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_options			= "<?php esc_html_e("Options exported!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_link				= "<?php esc_html_e("If need, you can download the configuration file from the following link: %s", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].export_download			= "<?php esc_html_e("Download theme options settings", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_label		= "<?php esc_html_e("or put here previously exported data:", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_label2	= "<?php esc_html_e("or select file with saved settings:", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_header	= "<?php esc_html_e("Import options", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_error		= "<?php esc_html_e("You need select the name for options set or paste import data! Import cancelled.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_failed	= "<?php esc_html_e("Error while import options! Import cancelled.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options_broken	= "<?php esc_html_e("Attention! Some options are not imported:", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_options			= "<?php esc_html_e("Options imported!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].import_dummy_confirm		= "<?php esc_html_e("Attention! During the import process, all existing data will be replaced with new.", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].clear_cache				= "<?php esc_html_e("Cache cleared successfull!", 'themerex'); ?>";
				THEMEREX_GLOBALS['to_strings'].clear_cache_header		= "<?php esc_html_e("Clear cache", 'themerex'); ?>";
			});
		</script>
		<?php 
	}
}


// Build the Options Page
if ( !function_exists( 'themerex_options_page' ) ) {
	function themerex_options_page() {
		global $THEMEREX_GLOBALS;

		//themerex_options_page_start();

		$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
		$mode = themerex_substr($page, 0, 16)=='themerex_options' ? themerex_substr($_REQUEST['page'], 17) : '';
		$override = $slug = '';
		if (!empty($mode)) {
			$inheritance = themerex_get_theme_inheritance();
			if (!empty($inheritance) && is_array($inheritance)) {
				foreach ($inheritance as $k=>$v) {
					$tpl = false;
					if (!empty($v['stream_template'])) {
						$cur_slug = themerex_get_slug($v['stream_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!empty($v['single_template'])) {
						$cur_slug = themerex_get_slug($v['single_template']);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
					if (!$tpl) {
						$cur_slug = themerex_get_slug($k);
						$tpl = true;
						if ($mode == $cur_slug) {
							$override = !empty($v['override']) ? $v['override'] : $k;
							$slug = $cur_slug;
							break;
						}
					}
				}
			}
		}

		$custom_options = empty($override) ? false : get_option($THEMEREX_GLOBALS['theme_slug'] . '_options'.(!empty($slug) ? '_template_'.trim($slug) : ''));

		themerex_options_page_start(array(
			'add_inherit' => !empty($override),
			'subtitle' => empty($slug) 
								? (empty($override) 
									? esc_html__('Global Options', 'themerex')
									: '') 
								: themerex_strtoproper(str_replace('_', ' ', $slug)) . ' ' . esc_html__('Options', 'themerex'),
			'description' => empty($slug) 
								? (empty($override) 
									? wp_kses( __('Global settings affect the entire website\'s display. They can be overriden when editing pages/categories/posts', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] )
									: '') 
								: wp_kses( __('Settings template for a certain post type: affects the display of just one specific post type. They can be overriden when editing categories and/or posts of a certain type', 'themerex'), $THEMEREX_GLOBALS['allowed_tags'] ),
			'slug' => $slug,
			'override' => $override
		));

		if (is_array($THEMEREX_GLOBALS['to_data']) && count($THEMEREX_GLOBALS['to_data']) > 0) {
			foreach ($THEMEREX_GLOBALS['to_data'] as $id=>$field) {
				if (!empty($override) && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				themerex_options_show_field( $id, $field, empty($override) ? null : (isset($custom_options[$id]) ? $custom_options[$id] : 'inherit') );
			}
		}
	
		themerex_options_page_stop();
	}
}


// Start render the options page (initialize flags)
if ( !function_exists( 'themerex_options_page_start' ) ) {
	function themerex_options_page_start($args = array()) {
		$to_flags = array_merge(array(
			'data'				=> null,
			'title'				=> esc_html__('Theme Options', 'themerex'),	// Theme Options page title
			'subtitle'			=> '',								// Subtitle for top of page
			'description'		=> '',								// Description for top of page
			'icon'				=> 'iconadmin-cog',					// Theme Options page icon
			'nesting'			=> array(),							// Nesting stack for partitions, tabs and groups
			'radio_as_select'	=> false,							// Display options[type="radio"] as options[type="select"]
			'add_inherit'		=> false,							// Add value "Inherit" in all options with lists
			'create_form'		=> true,							// Create tag form or use form from current page
			'buttons'			=> array('save', 'reset', 'import', 'export'),	// Buttons set
			'slug'				=> '',								// Slug for save options. If empty - global options
			'override'			=> ''								// Override mode - page|post|category|products-category|...
			), is_array($args) ? $args : array( 'add_inherit' => $args ));
		global $THEMEREX_GLOBALS;
		$THEMEREX_GLOBALS['to_flags'] = $to_flags;
		$THEMEREX_GLOBALS['to_data'] = empty($args['data']) ? $THEMEREX_GLOBALS['options'] : $args['data'];
		// Load required styles and scripts for Options Page
		themerex_options_load_scripts();
		// Prepare javascripts global variables
		themerex_options_prepare_scripts($to_flags['override']);
		?>
		<div class="themerex_options">
		<?php if ($to_flags['create_form']) { ?>
			<form class="themerex_options_form">
		<?php }	?>
				<div class="themerex_options_header">
					<div id="themerex_options_logo" class="themerex_options_logo">
						<span class="<?php echo esc_attr($to_flags['icon']); ?>"></span>
						<h2><?php echo trim($to_flags['title']); ?></h2>
					</div>
		<?php if (in_array('import', $to_flags['buttons'])) { ?>
					<div class="themerex_options_button_import"><span class="iconadmin-download"></span><?php esc_html_e('Import', 'themerex'); ?></div>
		<?php }	?>
		<?php if (in_array('export', $to_flags['buttons'])) { ?>
					<div class="themerex_options_button_export"><span class="iconadmin-upload"></span><?php esc_html_e('Export', 'themerex'); ?></div>
		<?php }	?>
		<?php if (in_array('reset', $to_flags['buttons'])) { ?>
					<div class="themerex_options_button_reset"><span class="iconadmin-spin3"></span><?php esc_html_e('Reset', 'themerex'); ?></div>
		<?php }	?>
		<?php if (in_array('save', $to_flags['buttons'])) { ?>
					<div class="themerex_options_button_save"><span class="iconadmin-check"></span><?php esc_html_e('Save', 'themerex'); ?></div>
		<?php }	?>
					<div id="themerex_options_title" class="themerex_options_title">
						<h2><?php echo trim($to_flags['subtitle']); ?></h2>
						<p> <?php echo trim($to_flags['description']); ?></p>
					</div>
				</div>
				<div class="themerex_options_body">
		<?php
	}
}


// Finish render the options page (close groups, tabs and partitions)
if ( !function_exists( 'themerex_options_page_stop' ) ) {
	function themerex_options_page_stop() {
		global $THEMEREX_GLOBALS;
		echo trim(themerex_options_close_nested_groups('', true));
		?>
				</div> <!-- .themerex_options_body -->
		<?php
		if ($THEMEREX_GLOBALS['to_flags']['create_form']) {
		?>
			</form>
		<?php
		}
		?>
		</div>	<!-- .themerex_options -->
		<?php
	}
}


// Return true if current type is groups type
if ( !function_exists( 'themerex_options_is_group' ) ) {
	function themerex_options_is_group($type) {
		return in_array($type, array('group', 'toggle', 'accordion', 'tab', 'partition'));
	}
}


// Close nested groups until type
if ( !function_exists( 'themerex_options_close_nested_groups' ) ) {
	function themerex_options_close_nested_groups($type='', $end=false) {
		global $THEMEREX_GLOBALS;
		$output = '';
		if ($THEMEREX_GLOBALS['to_flags']['nesting']) {
			for ($i=count($THEMEREX_GLOBALS['to_flags']['nesting'])-1; $i>=0; $i--) {
				$container = array_pop($THEMEREX_GLOBALS['to_flags']['nesting']);
				switch ($container) {
					case 'group':
						$output = '</fieldset>' . ($output);
						break;
					case 'toggle':
						$output = '</div></div>' . ($output);
						break;
					case 'tab':
					case 'partition':
						$output = '</div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
					case 'accordion':
						$output = '</div></div>' . ($container!=$type || $end ? '</div>' : '') . ($output);
						break;
				}
				if ($type == $container)
					break;
			}
		}
		return $output;
	}
}


// Collect tabs titles for current tabs or partitions
if ( !function_exists( 'themerex_options_collect_tabs' ) ) {
	function themerex_options_collect_tabs($type, $id) {
		global $THEMEREX_GLOBALS;
		$start = false;
		$nesting = array();
		$tabs = '';
		if (is_array($THEMEREX_GLOBALS['to_data']) && count($THEMEREX_GLOBALS['to_data']) > 0) {
			foreach ($THEMEREX_GLOBALS['to_data'] as $field_id=>$field) {
				if (!empty($THEMEREX_GLOBALS['to_flags']['override']) && (empty($field['override']) || !in_array($THEMEREX_GLOBALS['to_flags']['override'], explode(',', $field['override'])))) continue;
				if ($field['type']==$type && !empty($field['start']) && $field['start']==$id)
					$start = true;
				if (!$start) continue;
				if (themerex_options_is_group($field['type'])) {
					if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
						if ($nesting) {
							for ($i = count($nesting)-1; $i>=0; $i--) {
								$container = array_pop($nesting);
								if ($field['type'] == $container) {
									break;
								}
							}
						}
					}
					if (empty($field['end'])) {
						if (!$nesting) {
							if ($field['type']==$type) {
								$tabs .= '<li id="'.esc_attr($field_id).'">'
									. '<a id="'.esc_attr($field_id).'_title"'
										. ' href="#'.esc_attr($field_id).'_content"'
										. (!empty($field['action']) ? ' onclick="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
										. '>'
										. (!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '')
										. ($field['title'])
										. '</a>';
							} else
								break;
						}
						array_push($nesting, $field['type']);
					}
				}
			}
	    }
		return $tabs;
	}
}



// Return menu items list (menu, images or icons)
if ( !function_exists( 'themerex_options_menu_list' ) ) {
	function themerex_options_menu_list($field, $clone_val) {
		global $THEMEREX_GLOBALS;

		$to_delimiter = $THEMEREX_GLOBALS['to_delimiter'];

		if ($field['type'] == 'socials') $clone_val = $clone_val['icon'];
		$list = '<div class="themerex_options_input_menu '.(empty($field['style']) ? '' : ' themerex_options_input_menu_'.esc_attr($field['style'])).'">';
		$caption = '';
		if (is_array($field['options']) && count($field['options']) > 0) {
			foreach ($field['options'] as $key => $item) {
				if (in_array($field['type'], array('list', 'icons', 'socials'))) $key = $item;
				$selected = '';
				if (themerex_strpos(($to_delimiter).($clone_val).($to_delimiter), ($to_delimiter).($key).($to_delimiter))!==false) {
					$caption = esc_attr($item);
					$selected = ' themerex_options_state_checked';
				}
				$list .= '<span class="themerex_options_menuitem' 
					. ($selected) 
					. '" data-value="'.esc_attr($key).'"'
					//. (!empty($field['action']) ? ' onclick="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>';
				if (in_array($field['type'], array('list', 'select', 'fonts')))
					$list .= $item;
				else if ($field['type'] == 'icons' || ($field['type'] == 'socials' && $field['style'] == 'icons'))
					$list .= '<span class="'.esc_attr($item).'"></span>';
				else if ($field['type'] == 'images' || ($field['type'] == 'socials' && $field['style'] == 'images'))
					//$list .= '<img src="'.esc_attr($item).'" data-icon="'.esc_attr($key).'" alt="" class="themerex_options_input_image" />';
					$list .= '<span style="background-image:url('.esc_url($item).')" data-src="'.esc_url($item).'" data-icon="'.esc_attr($key).'" class="themerex_options_input_image"></span>';
				$list .= '</span>';
			}
		}
		$list .= '</div>';
		return array($list, $caption);
	}
}


// Return action buttom
if ( !function_exists( 'themerex_options_action_button' ) ) {
	function themerex_options_action_button($data, $type) {
		$class = ' themerex_options_button_'.esc_attr($type).(!empty($data['icon']) ? ' themerex_options_button_'.esc_attr($type).'_small' : '');
		$output = '<span class="' 
					. ($type == 'button' ? 'themerex_options_input_button'  : 'themerex_options_field_'.esc_attr($type))
					. (!empty($data['action']) ? ' themerex_options_with_action' : '')
					. (!empty($data['icon']) ? ' '.esc_attr($data['icon']) : '')
					. '"'
					. (!empty($data['icon']) && !empty($data['title']) ? ' title="'.esc_attr($data['title']).'"' : '')
					. (!empty($data['action']) ? ' onclick="themerex_options_action_'.esc_attr($data['action']).'(this);return false;"' : '')
					. (!empty($data['type']) ? ' data-type="'.esc_attr($data['type']).'"' : '')
					. (!empty($data['multiple']) ? ' data-multiple="'.esc_attr($data['multiple']).'"' : '')
					. (!empty($data['sizes']) ? ' data-sizes="'.esc_attr($data['sizes']).'"' : '')
					. (!empty($data['linked_field']) ? ' data-linked-field="'.esc_attr($data['linked_field']).'"' : '')
					. (!empty($data['captions']['choose']) ? ' data-caption-choose="'.esc_attr($data['captions']['choose']).'"' : '')
					. (!empty($data['captions']['update']) ? ' data-caption-update="'.esc_attr($data['captions']['update']).'"' : '')
					. '>'
					. ($type == 'button' || (empty($data['icon']) && !empty($data['title'])) ? $data['title'] : '')
					. '</span>';
		return array($output, $class);
	}
}


// Theme options page show option field
if ( !function_exists( 'themerex_options_show_field' ) ) {
	function themerex_options_show_field($id, $field, $value=null) {
		global $THEMEREX_GLOBALS;
	
		// Set start field value
		if ($value !== null) $field['val'] = $value;
		if (!isset($field['val']) || $field['val']=='') $field['val'] = 'inherit';
		if (!empty($field['subset'])) {
			$sbs = themerex_get_theme_option($field['subset'], '', $THEMEREX_GLOBALS['to_data']);
			$field['val'] = isset($field['val'][$sbs]) ? $field['val'][$sbs] : '';
		}
		
		if (empty($id))
			$id = 'themerex_options_id_'.str_replace('.', '', mt_rand());
		if (!isset($field['title']))
			$field['title'] = '';
		
		// Divider before field
		$divider = (!isset($field['divider']) && !in_array($field['type'], array('info', 'partition', 'tab', 'toggle'))) || (isset($field['divider']) && $field['divider']) ? ' themerex_options_divider' : '';

		// Setup default parameters
		if ($field['type']=='media') {
			if (!isset($field['before'])) $field['before'] = array();
			$field['before'] = array_merge(array(
					'title' => esc_html__('Choose image', 'themerex'),
					'action' => 'media_upload',
					'type' => 'image',
					'multiple' => false,
					'sizes' => false,
					'linked_field' => '',
					'captions' => array('choose' => esc_html__( 'Choose image', 'themerex'),
										'update' => esc_html__( 'Select image', 'themerex')
										)
				), $field['before']);
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'media_reset'
				), $field['after']);
		}
		if ($field['type']=='color' && ($THEMEREX_GLOBALS['to_colorpicker']=='tiny' || (isset($field['style']) && $field['style']!='wp'))) {
			if (!isset($field['after'])) $field['after'] = array();
			$field['after'] = array_merge(array(
					'icon'=>'iconadmin-cancel',
					'action'=>'color_reset'
				), $field['after']);
		}

		// Buttons before and after field
		$before = $after = $buttons_classes = '';
		if (!empty($field['before'])) {
			list($before, $class) = themerex_options_action_button($field['before'], 'before');
			$buttons_classes .= $class;
		}
		if (!empty($field['after'])) {
			list($after, $class) = themerex_options_action_button($field['after'], 'after');
			$buttons_classes .= $class;
		}
		if ( in_array($field['type'], array('list', 'select', 'fonts')) || ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons')) ) {
			$buttons_classes .= ' themerex_options_button_after_small';
		}
	
		// Is it inherit field?
		$inherit = themerex_is_inherit_option($field['val']) ? 'inherit' : '';
	
		// Is it cloneable field?
		$cloneable = isset($field['cloneable']) && $field['cloneable'];
	
		// Prepare field
		if (!$cloneable)
			$field['val'] = array($field['val']);
		else {
			if (!is_array($field['val']))
				$field['val'] = array($field['val']);
			else if ($field['type'] == 'socials' && (!isset($field['val'][0]) || !is_array($field['val'][0])))
				$field['val'] = array($field['val']);
		}
	
		// Field container
		if (themerex_options_is_group($field['type'])) {					// Close nested containers
			if (empty($field['start']) && (!in_array($field['type'], array('group', 'toggle')) || !empty($field['end']))) {
				echo trim(themerex_options_close_nested_groups($field['type'], !empty($field['end'])));
				if (!empty($field['end'])) {
					return;
				}
			}
		} else {														// Start field layout
			if ($field['type'] != 'hidden') {
				echo '<div class="themerex_options_field'
					. ' themerex_options_field_' . (in_array($field['type'], array('list','fonts')) ? 'select' : $field['type'])
					. (in_array($field['type'], array('media', 'fonts', 'list', 'select', 'socials', 'date', 'time')) ? ' themerex_options_field_text'  : '')
					. ($field['type']=='socials' && !empty($field['style']) && $field['style']=='images' ? ' themerex_options_field_images'  : '')
					. ($field['type']=='socials' && (empty($field['style']) || $field['style']=='icons') ? ' themerex_options_field_icons'  : '')
					. (isset($field['dir']) && $field['dir']=='vertical' ? ' themerex_options_vertical' : '')
					. (!empty($field['multiple']) ? ' themerex_options_multiple' : '')
					. (isset($field['size']) ? ' themerex_options_size_'.esc_attr($field['size']) : '')
					. (isset($field['class']) ? ' ' . esc_attr($field['class']) : '')
					. (!empty($field['columns']) ? ' themerex_options_columns themerex_options_columns_'.esc_attr($field['columns']) : '')
					. ($divider)
					. '">'."\n";
				if ( !in_array($field['type'], array('divider'))) {
					echo '<label class="themerex_options_field_label'
						. (!empty($THEMEREX_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) ? ' themerex_options_field_label_inherit' : '')
						. '"'
						. (!empty($field['title']) ? ' for="'.esc_attr($id).'"' : '')
						. '>' 
						. ($field['title']) 
						. (!empty($THEMEREX_GLOBALS['to_flags']['add_inherit']) && isset($field['std']) 
							? '<span id="'.esc_attr($id).'_inherit" class="themerex_options_button_inherit'
								.($inherit ? '' : ' themerex_options_inherit_off')
								.'" title="' . esc_attr__('Unlock this field', 'themerex') . '"></span>' 
							: '')
						. '</label>'
						. "\n";
				}
				if ( !in_array($field['type'], array('info', 'label', 'divider'))) {
					echo '<div class="themerex_options_field_content'
						. ($buttons_classes)
						. ($cloneable ? ' themerex_options_cloneable_area' : '')
						. '">' . "\n";
				}
			}
		}
	
		// Parse field type
		if (is_array($field['val']) && count($field['val']) > 0) {
		foreach ($field['val'] as $clone_num => $clone_val) {
			
			if ($cloneable) {
				echo '<div class="themerex_options_cloneable_item">'
					. '<span class="themerex_options_input_button themerex_options_clone_button themerex_options_clone_button_del">-</span>';
			}
	
			switch ( $field['type'] ) {
		
			case 'group':
				echo '<fieldset id="'.esc_attr($id).'" class="themerex_options_container themerex_options_group themerex_options_content'.esc_attr($divider).'">';
				if (!empty($field['title'])) echo '<legend>'.(!empty($field['icon']) ? '<span class="'.esc_attr($field['icon']).'"></span>' : '').esc_html($field['title']).'</legend>'."\n";
				array_push($THEMEREX_GLOBALS['to_flags']['nesting'], 'group');
			break;
		
			case 'toggle':
				array_push($THEMEREX_GLOBALS['to_flags']['nesting'], 'toggle');
				echo '<div id="'.esc_attr($id).'" class="themerex_options_container themerex_options_toggle'.esc_attr($divider).'">';
				echo '<h3 id="'.esc_attr($id).'_title"'
					. ' class="themerex_options_toggle_header'.(empty($field['closed']) ? ' ui-state-active' : '') .'"'
					. (!empty($field['action']) ? ' onclick="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. (!empty($field['icon']) ? '<span class="themerex_options_toggle_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="themerex_options_toggle_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div class="themerex_options_content themerex_options_toggle_content"'.(!empty($field['closed']) ? ' style="display:none;"' : '').'>';
			break;
		
			case 'accordion':
				array_push($THEMEREX_GLOBALS['to_flags']['nesting'], 'accordion');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="themerex_options_container themerex_options_accordion'.esc_attr($divider).'">';
				echo '<div id="'.esc_attr($id).'" class="themerex_options_accordion_item">'
					. '<h3 id="'.esc_attr($id).'_title"'
					. ' class="themerex_options_accordion_header"'
					. (!empty($field['action']) ? ' onclick="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>' 
					. (!empty($field['icon']) ? '<span class="themerex_options_accordion_header_icon '.esc_attr($field['icon']).'"></span>' : '') 
					. ($field['title'])
					. '<span class="themerex_options_accordion_header_marker iconadmin-left-open"></span>'
					. '</h3>'
					. '<div id="'.esc_attr($id).'_content" class="themerex_options_content themerex_options_accordion_content">';
			break;
		
			case 'tab':
				array_push($THEMEREX_GLOBALS['to_flags']['nesting'], 'tab');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="themerex_options_container themerex_options_tab'.esc_attr($divider).'">'
						. '<ul>' . trim(themerex_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content"  class="themerex_options_content themerex_options_tab_content">';
			break;
		
			case 'partition':
				array_push($THEMEREX_GLOBALS['to_flags']['nesting'], 'partition');
				if (!empty($field['start']))
					echo '<div id="'.esc_attr($field['start']).'" class="themerex_options_container themerex_options_partition'.esc_attr($divider).'">'
						. '<ul>' . trim(themerex_options_collect_tabs($field['type'], $field['start'])) . '</ul>';
				echo '<div id="'.esc_attr($id).'_content" class="themerex_options_content themerex_options_partition_content">';
			break;
		
			case 'hidden':
				echo '<input class="themerex_options_input themerex_options_input_hidden" type="hidden"'
					. ' name="'.esc_attr($id).'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '" />';
			break;
	
			case 'date':
				if (isset($field['style']) && $field['style']=='inline') {
					echo '<div class="themerex_options_input_date" id="'.esc_attr($id).'_calendar"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. ' data-linked-field="' . (!empty($data['linked_field']) ? $data['linked_field'] : $id) . '"'
						. '></div>'
					. '<input id="'.esc_attr($id).'"'
						. ' data-param="'.esc_attr($id).'"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' value="' . esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
				} else {
					echo '<input class="themerex_options_input themerex_options_input_date' . (!empty($field['mask']) ? ' themerex_options_input_masked' : '') . '"'
						. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
						. ' id="'.esc_attr($id). '"'
						. ' data-param="'.esc_attr($id).'"'
						. ' type="text"'
						. ' value="' . esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. ' data-format="' . (!empty($field['format']) ? $field['format'] : 'yy-mm-dd') . '"'
						. ' data-months="' . (!empty($field['months']) ? max(1, min(3, $field['months'])) : 1) . '"'
						. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
						. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />'
					. ($before)
					. ($after);
				}
			break;
	
			case 'text':
				echo '<input class="themerex_options_input themerex_options_input_text' . (!empty($field['mask']) ? ' themerex_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '')
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
			break;
			
			case 'textarea':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '8';
				echo '<textarea class="themerex_options_input themerex_options_input_textarea"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' cols="'.esc_attr($cols).'"'
					. ' rows="'.esc_attr($rows).'"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. '>'
					. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) 
					. '</textarea>';
			break;
			
			case 'editor':
				$cols = isset($field['cols']) && $field['cols'] > 10 ? $field['cols'] : '40';
				$rows = isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : '10';
				wp_editor( themerex_is_inherit_option($clone_val) ? '' : $clone_val, $id . ($cloneable ? '[]' : ''), array(
					'wpautop' => false,
					'textarea_rows' => $rows
				));
			break;
	
			case 'spinner':
				echo '<input class="themerex_options_input themerex_options_input_spinner' . (!empty($field['mask']) ? ' themerex_options_input_masked' : '') 
					. '" name="'.esc_attr($id). ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (isset($field['min']) ? ' data-min="'.esc_attr($field['min']).'"' : '') 
					. (isset($field['max']) ? ' data-max="'.esc_attr($field['max']).'"' : '') 
					. (!empty($field['step']) ? ' data-step="'.esc_attr($field['step']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />' 
					. '<span class="themerex_options_arrows"><span class="themerex_options_arrow_up iconadmin-up-dir"></span><span class="themerex_options_arrow_down iconadmin-down-dir"></span></span>';
			break;
	
			case 'tags':
				if (!themerex_is_inherit_option($clone_val)) {
					$tags = explode($THEMEREX_GLOBALS['to_delimiter'], $clone_val);
					if (is_array($tags) && count($tags) > 0) {
						foreach ($tags as $tag) {
							if (empty($tag)) continue;
							echo '<span class="themerex_options_tag iconadmin-cancel">'.($tag).'</span>';
						}
					}
				}
				echo '<input class="themerex_options_input_tags"'
					. ' type="text"'
					. ' value=""'
					. ' />'
					. '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
						. ' type="hidden"'
						. ' data-param="'.esc_attr($id).'"'
						. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
						. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "checkbox": 
				echo '<input type="checkbox" class="themerex_options_input themerex_options_input_checkbox"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' value="true"'
					. ($clone_val == 'true' ? ' checked="checked"' : '') 
					. (!empty($field['disabled']) ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<label for="'.esc_attr($id).'" class="' . (!empty($field['disabled']) ? 'themerex_options_state_disabled' : '') . ($clone_val=='true' ? ' themerex_options_state_checked' : '').'"><span class="themerex_options_input_checkbox_image iconadmin-check"></span>' . (!empty($field['label']) ? $field['label'] : $field['title']) . '</label>';
			break;
			
			case "radio":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="themerex_options_radioitem">'
							.'<input class="themerex_options_input themerex_options_input_radio" type="radio"'
								. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
								. ' value="'.esc_attr($key) .'"'
								. ($clone_val == $key ? ' checked="checked"' : '') 
								. ' id="'.esc_attr(($id).'_'.($key)).'"'
								. ' />'
								. '<label for="'.esc_attr(($id).'_'.($key)).'"'. ($clone_val == $key ? ' class="themerex_options_state_checked"' : '') .'><span class="themerex_options_input_radio_image iconadmin-circle-empty'.($clone_val == $key ? ' iconadmin-dot-circled' : '') . '"></span>' . ($title) . '</label></span>';
					}
				}
				echo '<input type="hidden"'
						. ' value="' . esc_attr($clone_val) . '"'
						. ' data-param="' . esc_attr($id) . '"'
						. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
						. ' />';
			break;
			
			case "switch":
				$opt = array();
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						$opt[] = array('key'=>$key, 'title'=>$title);
						if (count($opt)==2) break;
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) || empty($clone_val) ? $opt[0]['key'] : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. '<span class="themerex_options_switch'.($clone_val==$opt[1]['key'] ? ' themerex_options_state_off' : '').'"><span class="themerex_options_switch_inner iconadmin-circle"><span class="themerex_options_switch_val1" data-value="'.esc_attr($opt[0]['key']).'">'.($opt[0]['title']).'</span><span class="themerex_options_switch_val2" data-value="'.esc_attr($opt[1]['key']).'">'.($opt[1]['title']).'</span></span></span>';
			break;
	
			case 'media':
				echo '<input class="themerex_options_input themerex_options_input_text themerex_options_input_media"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '').'"'
					. ' id="'.esc_attr($id).'"'
					. ' data-param="'.esc_attr($id).'"'
					. ' type="text"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"' 
					. (!isset($field['readonly']) || $field['readonly'] ? ' readonly="readonly"' : '') 
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
				. ($before)
				. ($after);
				if (!empty($clone_val) && !themerex_is_inherit_option($clone_val)) {
					$info = pathinfo($clone_val);
					$ext = isset($info['extension']) ? $info['extension'] : '';
					echo '<a class="themerex_options_image_preview" data-rel="popup" target="_blank" href="'.esc_url($clone_val).'">'.(!empty($ext) && themerex_strpos('jpg,png,gif', $ext)!==false ? '<img src="'.esc_url($clone_val).'" alt="" />' : '<span>'.($info['basename']).'</span>').'</a>';
				}
			break;
			
			case 'button':
				list($button, $class) = themerex_options_action_button($field, 'button');
				echo balanceTags($button);
			break;
	
			case 'range':
				echo '<div class="themerex_options_input_range" data-step="'.(!empty($field['step']) ? $field['step'] : 1).'">';
				echo '<span class="themerex_options_range_scale"><span class="themerex_options_range_scale_filled"></span></span>';
				if (themerex_strpos($clone_val, $THEMEREX_GLOBALS['to_delimiter'])===false)
					$clone_val = max($field['min'], intval($clone_val));
				if (themerex_strpos($field['std'], $THEMEREX_GLOBALS['to_delimiter'])!==false && themerex_strpos($clone_val, $THEMEREX_GLOBALS['to_delimiter'])===false)
					$clone_val = ($field['min']).','.($clone_val);
				$sliders = explode($THEMEREX_GLOBALS['to_delimiter'], $clone_val);
				foreach($sliders as $s) {
					echo '<span class="themerex_options_range_slider"><span class="themerex_options_range_slider_value">'.intval($s).'</span><span class="themerex_options_range_slider_button"></span></span>';
				}
				echo '<span class="themerex_options_range_min">'.($field['min']).'</span><span class="themerex_options_range_max">'.($field['max']).'</span>';
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
				echo '</div>';			
			break;
			
			case "checklist":
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) { 
						echo '<span class="themerex_options_listitem'
							. (themerex_strpos(($THEMEREX_GLOBALS['to_delimiter']).($clone_val).($THEMEREX_GLOBALS['to_delimiter']), ($THEMEREX_GLOBALS['to_delimiter']).($key).($THEMEREX_GLOBALS['to_delimiter']))!==false ? ' themerex_options_state_checked' : '') . '"'
							. ' data-value="'.esc_attr($key).'"'
							. '>'
							. esc_attr($title)
							. '</span>';
					}
				}
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'fonts':
				if (is_array($field['options']) && count($field['options']) > 0) {
					foreach ($field['options'] as $key => $title) {
						$field['options'][$key] = $key;
					}
				}
			case 'list':
			case 'select':
				if (!isset($field['options']) && !empty($field['from']) && !empty($field['to'])) {
					$field['options'] = array();
					for ($i = $field['from']; $i <= $field['to']; $i+=(!empty($field['step']) ? $field['step'] : 1)) {
						$field['options'][$i] = $i;
					}
				}
				list($list, $caption) = themerex_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<input class="themerex_options_input themerex_options_input_select" type="text" value="'.esc_attr($caption) . '"'
						. ' readonly="readonly"'
						//. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
						. ' />'
						. ($before)
						. '<span class="themerex_options_field_after themerex_options_with_action iconadmin-down-open" onclick="themerex_options_action_show_menu(this);return false;"></span>';
				}
				echo balanceTags($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') .'"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'images':
				list($list, $caption) = themerex_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="themerex_options_caption_image iconadmin-down-open">'
						//.'<img src="'.esc_url($caption).'" alt="" />'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				echo balanceTags($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
			
			case 'icons':
				if (isset($field['css']) && $field['css']!='' && file_exists($field['css'])) {
					$field['options'] = themerex_parse_icons_classes($field['css']);
				}
				list($list, $caption) = themerex_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='select') {
					echo '<div class="themerex_options_caption_icon iconadmin-down-open"><span class="'.esc_attr($caption).'"></span></div>';
				}
				echo balanceTags($list);
				echo '<input name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' type="hidden"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' value="' . esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />';
			break;
	
			case 'socials':
				if (!is_array($clone_val)) $clone_val = array('url'=>'', 'icon'=>'');
				list($list, $caption) = themerex_options_menu_list($field, $clone_val);
				if (empty($field['style']) || $field['style']=='icons') {
					list($after, $class) = themerex_options_action_button(array(
						'action' => empty($field['style']) || $field['style']=='icons' ? 'select_icon' : '',
						'icon' => (empty($field['style']) || $field['style']=='icons') && !empty($clone_val['icon']) ? $clone_val['icon'] : 'iconadmin-users'
						), 'after');
				} else
					$after = '';
				echo '<input class="themerex_options_input themerex_options_input_text themerex_options_input_socials' 
					. (!empty($field['mask']) ? ' themerex_options_input_masked' : '') . '"'
					. ' name="'.esc_attr($id).($cloneable ? '[]' : '') .'"'
					. ' id="'.esc_attr($id) .'"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text" value="'. esc_attr(themerex_is_inherit_option($clone_val['url']) ? '' : $clone_val['url']) . '"' 
					. (!empty($field['mask']) ? ' data-mask="'.esc_attr($field['mask']).'"' : '') 
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. ($after);
				if (!empty($field['style']) && $field['style']=='images') {
					echo '<div class="themerex_options_caption_image iconadmin-down-open">'
						//.'<img src="'.esc_url($caption).'" alt="" />'
						.'<span style="background-image: url('.esc_url($caption).')"></span>'
						.'</div>';
				}
				echo balanceTags($list);
				echo '<input name="'.esc_attr($id) . '_icon' . ($cloneable ? '[]' : '') .'" type="hidden" value="'. esc_attr(themerex_is_inherit_option($clone_val['icon']) ? '' : $clone_val['icon']) . '" />';
			break;
	
			case "color":
				$cp_style = isset($field['style']) ? $field['style'] : $THEMEREX_GLOBALS['to_colorpicker'];
				echo '<input class="themerex_options_input themerex_options_input_color themerex_options_input_color_'.esc_attr($cp_style).'"'
					. ' name="'.esc_attr($id) . ($cloneable ? '[]' : '') . '"'
					. ' id="'.esc_attr($id) . '"'
					. ' data-param="' . esc_attr($id) . '"'
					. ' type="text"'
					. ' value="'. esc_attr(themerex_is_inherit_option($clone_val) ? '' : $clone_val) . '"'
					. (!empty($field['action']) ? ' onchange="themerex_options_action_'.esc_attr($field['action']).'(this);return false;"' : '')
					. ' />'
					. trim($before);
				if ($cp_style=='custom')
					echo '<span class="themerex_options_input_colorpicker iColorPicker"></span>';
				else if ($cp_style=='tiny')
					echo trim($after);
			break;   
	
			default:
				if (function_exists('themerex_show_custom_field')) {
					echo trim(themerex_show_custom_field($id, $field, $clone_val));
				}
			} 
	
			if ($cloneable) {
				echo '<input type="hidden" name="'.esc_attr($id) . '_numbers[]" value="'.esc_attr($clone_num).'" />'
					. '</div>';
			}
		}	//foreach
		}	//if (count()>0)
	
		if (!themerex_options_is_group($field['type']) && $field['type'] != 'hidden') {
			if ($cloneable) {
				echo '<div class="themerex_options_input_button themerex_options_clone_button themerex_options_clone_button_add">'. esc_html__('+ Add item', 'themerex') .'</div>';
			}
			if (!empty($THEMEREX_GLOBALS['to_flags']['add_inherit']) && isset($field['std']))
				echo  '<div class="themerex_options_content_inherit"'.($inherit ? '' : ' style="display:none;"').'><div>'.esc_html__('Inherit', 'themerex').'</div><input type="hidden" name="'.esc_attr($id).'_inherit" value="'.esc_attr($inherit).'" /></div>';
			if ( !in_array($field['type'], array('info', 'label', 'divider')))
				echo '</div>';
			if (!empty($field['desc']))
				echo '<div class="themerex_options_desc">' . ($field['desc']) .'</div>' . "\n";
			echo '</div>' . "\n";
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'themerex_options_save' ) ) {
	//add_action('wp_ajax_themerex_options_save', 'themerex_options_save');
	//add_action('wp_ajax_nopriv_themerex_options_save', 'themerex_options_save');
	function themerex_options_save() {

		$mode = $_POST['mode'];
		$override = empty($_POST['override']) ? 'general' : $_POST['override'];
		$slug = empty($_POST['slug']) ? '' : $_POST['slug'];
		
		if (!in_array($mode, array('save', 'reset', 'export')) || $override=='customizer')
			return;

		global $THEMEREX_GLOBALS;

		if ( !wp_verify_nonce( $_POST['nonce'], $THEMEREX_GLOBALS['ajax_url'] ) )
			die();
	

		$options = $THEMEREX_GLOBALS['options'];
	
		if ($mode == 'save') {
			parse_str($_POST['data'], $post_data);
		} else if ($mode=='export') {
			parse_str($_POST['data'], $post_data);
			if (!empty($THEMEREX_GLOBALS['post_meta_box']['fields'])) {
				$options = themerex_array_merge($THEMEREX_GLOBALS['options'], $THEMEREX_GLOBALS['post_meta_box']['fields']);
			}
		} else
			$post_data = array();
	
		$custom_options = array();
	
		themerex_options_merge_new_values($options, $custom_options, $post_data, $mode, $override);
	
		if ($mode=='export') {
			$name  = trim(chop($_POST['name']));
			$name2 = isset($_POST['name2']) ? trim(chop($_POST['name2'])) : '';
			$key = $name=='' ? $name2 : $name;
			$export = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options_export_'.($override), array());
			$export[$key] = $custom_options;
			if ($name!='' && $name2!='') unset($export[$name2]);
			update_option($THEMEREX_GLOBALS['theme_slug'] . '_options_export_'.($override), $export);
			$file = themerex_get_file_dir('core/core.options/core.options.txt');
			$url  = themerex_get_file_url('core/core.options/core.options.txt');
			$export = serialize($custom_options);
			themerex_fpc($file, $export);
			$response = array('error'=>'', 'data'=>$export, 'link'=>$url);
			echo json_encode($response);
		} else {
			update_option($THEMEREX_GLOBALS['theme_slug'] . '_options'.(!empty($slug) ? '_template_'.trim($slug) : ''), apply_filters('themerex_filter_save_options', $custom_options, $override, $slug));
			if ($override=='general') {
				themerex_load_main_options();
				//do_action('themerex_action_compile_less');
			}
		}
		
		die();
	}
}


// Ajax Import Action handler
if ( !function_exists( 'themerex_options_import' ) ) {
	//add_action('wp_ajax_themerex_options_import', 'themerex_options_import');
	//add_action('wp_ajax_nopriv_themerex_options_import', 'themerex_options_import');
	function themerex_options_import() {
		global $THEMEREX_GLOBALS;

		if ( !wp_verify_nonce( $_POST['nonce'], $THEMEREX_GLOBALS['ajax_url'] ) )
			die();
	
		$override = $_POST['override']=='' ? 'general' : $_POST['override'];
		$text = stripslashes(trim(chop($_POST['text'])));
		if (!empty($text)) {
			$opt = themerex_unserialize($text);
		} else {
			$key = trim(chop($_POST['name2']));
			$import = get_option($THEMEREX_GLOBALS['theme_slug'] . '_options_export_'.($override), array());
			$opt = isset($import[$key]) ? $import[$key] : false;
		}
		$response = array('error'=>$opt===false ? esc_html__('Error while unpack import data!', 'themerex') : '', 'data'=>$opt);
		echo json_encode($response);
	
		die();
	}
}

// Merge data from POST and current post/page/category/theme options
if ( !function_exists( 'themerex_options_merge_new_values' ) ) {
	function themerex_options_merge_new_values(&$post_options, &$custom_options, &$post_data, $mode, $override) {
		$need_save = false;
		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$field) { 
				if ($override!='general' && (!isset($field['override']) || !in_array($override, explode(',', $field['override'])))) continue;
				if (!isset($field['std'])) continue;
				if ($override!='general' && !isset($post_data[$id.'_inherit'])) continue;
				if ($id=='reviews_marks' && $mode=='export') continue;
				$need_save = true;
				if ($mode == 'save' || $mode=='export') {
					if ($override!='general' && themerex_is_inherit_option($post_data[$id.'_inherit']))
						$new = '';
					else if (isset($post_data[$id])) {
						// Prepare specific (combined) fields
						if (!empty($field['subset'])) {
							$sbs = $post_data[$field['subset']];
							$field['val'][$sbs] = $post_data[$id];
							$post_data[$id] = $field['val'];
						}   	
						if ($field['type']=='socials') {
							if (!empty($field['cloneable'])) {
								if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
									foreach($post_data[$id] as $k=>$v)
										$post_data[$id][$k] = array('url'=>stripslashes($v), 'icon'=>stripslashes($post_data[$id.'_icon'][$k]));
								}
							} else {
								$post_data[$id] = array('url'=>stripslashes($post_data[$id]), 'icon'=>stripslashes($post_data[$id.'_icon']));
							}
						} else if (is_array($post_data[$id])) {
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$post_data[$id][$k] = stripslashes($v);
							}
						} else
							$post_data[$id] = stripslashes($post_data[$id]);
						// Add cloneable index
						if (!empty($field['cloneable'])) {
							$rez = array();
							if (is_array($post_data[$id]) && count($post_data[$id]) > 0) {
								foreach ($post_data[$id] as $k=>$v)
									$rez[$post_data[$id.'_numbers'][$k]] = $v;
							}
							$post_data[$id] = $rez;
						}   	
						$new = $post_data[$id];
						// Post type specific data handling
						if ($id == 'reviews_marks') {
							$new = join(',', $new);
							if (($avg = themerex_reviews_get_average_rating($new)) > 0) {
								$new = themerex_reviews_marks_to_save($new);
							}
						}
					} else
						$new = $field['type'] == 'checkbox' ? 'false' : '';
				} else {
					$new = $field['std'];
				}
				$custom_options[$id] = $new!=='' || $override=='general' ? $new : 'inherit';
			}
	    }
		return $need_save;
	}
}



// Load default theme options
require_once themerex_get_file_dir('includes/theme.options.php');


// Load inheritance system
require_once themerex_get_file_dir('core/core.options/core.options-inheritance.php');

// Load custom fields
if (is_admin()) {
	require_once themerex_get_file_dir('core/core.options/core.options-custom.php');
}
?>