<?php
/**
 * Child-Theme functions and definitions
 */

add_action( 'wp_enqueue_scripts', 'greenmans_enqueue_styles');
function greenmans_enqueue_styles() {
	
	// enqueue parent styles
	wp_enqueue_style('parent-theme', get_template_directory_uri() . '/style.css');
	
	// enqueue child styles
	wp_enqueue_style('greenmans', get_stylesheet_directory_uri() .'/css/main.css', array('parent-theme'));
	
}

add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 21;
  return $cols;
}


?>