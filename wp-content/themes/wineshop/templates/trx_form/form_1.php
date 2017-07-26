<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'themerex_template_form_1_theme_setup' ) ) {
	add_action( 'themerex_action_before_init_theme', 'themerex_template_form_1_theme_setup', 1 );
	function themerex_template_form_1_theme_setup() {
		themerex_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'themerex')
			));
	}
}

// Template output
if ( !function_exists( 'themerex_template_form_1_output' ) ) {
	function themerex_template_form_1_output($post_options, $post_data) {
		global $THEMEREX_GLOBALS;
		?>
		<form <?php echo balanceTags($post_options['id'] ? ' id="'.esc_attr($post_options['id']).'"' : ''); ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $THEMEREX_GLOBALS['ajax_url']); ?>">
			<div class="sc_form_info">
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'themerex'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_html_e('Name *', 'themerex'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'themerex'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_html_e('E-mail *', 'themerex'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'themerex'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_html_e('Subject', 'themerex'); ?>"></div>
			</div>
			<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'themerex'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_html_e('Message', 'themerex'); ?>"></textarea></div>
			<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'themerex'); ?></button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>