<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_team_4_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_team_4_theme_setup', 1 );
	function themerex_template_team_4_theme_setup() {
		themerex_add_template(array(
			'layout' => 'team-4',
			'template' => 'team-4',
			'mode'   => 'team',
			/*'container_classes' => 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side',*/
			'title'  => esc_html__('Team /Style 4/', 'themerex'),
			'thumb_title'  => esc_html__('Medium square image (crop)', 'themerex'),
			'w' => 390,
			'h' => 390
		));
	}
}

// Template output
if ( !function_exists( 'themerex_template_team_4_output' ) ) {
	function themerex_template_team_4_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (themerex_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo balanceTags($post_options['tag_id'] ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''); ?>
				class="sc_team_item sc_team_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo balanceTags($post_options['tag_css']!='' ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!themerex_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(themerex_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<div class="sc_team_item_avatar"><?php echo trim($post_options['photo']); ?>
					<div class="sc_team_item_hover">
						<div class="sc_team_item_info">
							<h5 class="sc_team_item_title"><?php echo balanceTags($post_options['link'] ? '<a href="'.esc_url($post_options['link']).'">' : '') . ($post_data['post_title']) . ($post_options['link'] ? '</a>' : ''); ?></h5>
							<div class="sc_team_item_position"><?php echo trim($post_options['position']);?></div>
							<div class="sc_team_item_description"><?php echo trim(themerex_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : themerex_get_custom_option('post_excerpt_maxlength_masonry'))); ?></div>
							<div class="sc_team_item_socials"><?php echo trim($post_options['socials']); ?></div>
						</div>
					</div>
				</div>
			</div>
		<?php
		if (themerex_param_is_on($post_options['slider']) || $columns > 1) {
			?></div><?php
		}
	}
}
?>