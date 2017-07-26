<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_polaroid_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_polaroid_theme_setup', 1 );
	function themerex_template_polaroid_theme_setup() {
		themerex_add_template(array(
			'layout' => 'polaroid',
			'template' => 'polaroid',
			'mode'   => 'blogger',
			'container2' => '<div class="sc_blogger_elements"><div class="photostack" %css><div>%s</div></div></div>',
			'title'  => esc_html__('Blogger layout: Polaroid', 'themerex'),
			'thumb_title'  => esc_html__('Small square image (crop)', 'themerex'),
			'w'		 => 300,
			'h'		 => 300
		));
		// Add template specific scripts
		add_action('themerex_action_blog_scripts', 'themerex_template_polaroid_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('themerex_template_polaroid_add_scripts')) {
	//add_action('themerex_action_blog_scripts', 'themerex_template_polaroid_add_scripts');
	function themerex_template_polaroid_add_scripts($style) {
		if (themerex_substr($style, 0, 8) == 'polaroid')
			themerex_enqueue_polaroid();
	}
}

// Template output
if ( !function_exists( 'themerex_template_polaroid_output' ) ) {
	function themerex_template_polaroid_output($post_options, $post_data) {
		$show_title = true;
		$style = $post_options['layout'];
		?>
		<figure class="post_item sc_blogger_item sc_polaroid_item<?php echo balanceTags($post_options['number'] == $post_options['posts_on_page'] && !themerex_param_is_on($post_options['loadmore']) ? ' sc_blogger_item_last' : ''); ?>">
			<a href="<?php echo esc_url($post_data['post_link']); ?>" class="photostack-img"><?php echo trim($post_data['post_thumb']); ?></a>
			<figcaption class="photostack_info">
				<h6 class="post_title sc_title sc_blogger_title sc_polaroid_title photostack-title"><?php echo balanceTags($post_data['post_title']); ?></h6>
				<?php
				if ($post_data['post_excerpt']) {
					echo '<div class="photostack-back">' 
						. (in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) 
							? $post_data['post_excerpt']
							: '<p>'.trim(themerex_strshort($post_data['post_excerpt'], isset($post_options['descr']) 
									? $post_options['descr'] 
									: themerex_get_custom_option('post_excerpt_maxlength_masonry')
									)
							).'</p>')
						. '</div>';
				}
				?>
			</figcaption>
		</figure>
		<?php
	}
}
?>