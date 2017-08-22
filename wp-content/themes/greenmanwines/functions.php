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

// Modify the default WooCommerce orderby dropdown
//
// Options: menu_order, popularity, rating, date, price, price-desc
// In this example I'm removing price & price-desc but you can remove any of the options
function tmgmw_woocommerce_catalog_orderby( $orderby ) {
	unset($orderby["rating"]);
	return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "tmgmw_woocommerce_catalog_orderby", 20 );


	
// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
 
// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
 
 
function woocommerce_product_custom_fields(){
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    
    //Custom Product Number Field
    woocommerce_wp_text_input(
        array(
            'id' => 'alcohol_content',
            'placeholder' => '',
            'label' => __('Alcohol Content', 'woocommerce'),
            'type' => 'text'
        )
    );

    //Custom Product Country Field
    woocommerce_wp_text_input(
        array(
            'id' => 'country',
            'placeholder' => '',
            'label' => __('Country', 'woocommerce'),
            'type' => 'text'
            )
    );

    //Custom Product Country Field
    woocommerce_wp_text_input(
        array(
            'id' => 'region',
            'placeholder' => '',
            'label' => __('Region', 'woocommerce'),
            'type' => 'text'
            )
    );

    //Custom Product Country Field
    woocommerce_wp_text_input(
        array(
            'id' => 'sub_region',
            'placeholder' => '',
            'label' => __('Sub Region', 'woocommerce'),
            'type' => 'text'
            )
    );

    //Custom Product Country Field
    woocommerce_wp_text_input(
        array(
            'id' => 'winemaker',
            'placeholder' => '',
            'label' => __('Winemaker', 'woocommerce'),
            'type' => 'text'
            )
    );

    //Custom Product Country Field
    woocommerce_wp_text_input(
        array(
            'id' => 'vintage',
            'placeholder' => '',
            'label' => __('Vintage', 'woocommerce'),
            'type' => 'text'
            )
    );
    
    echo '</div>'; 
}


function woocommerce_product_custom_fields_save($post_id){
    // Custom Product Text Field
    
    $woocommerce_custom_product_alcohol_content = $_POST['alcohol_content'];
    $woocommerce_custom_product_country = $_POST['country'];
    $woocommerce_custom_product_region = $_POST['region'];
    $woocommerce_custom_product_sub_region = $_POST['sub_region'];
    $woocommerce_custom_product_winemaker = $_POST['winemaker'];
    $woocommerce_custom_product_vintage = $_POST['vintage'];
    
    
    
    
    if (!empty($woocommerce_custom_product_alcohol_content))
        update_post_meta($post_id, 'alcohol_content', esc_attr($woocommerce_custom_product_alcohol_content));
    if (!empty($woocommerce_custom_product_country))
        update_post_meta($post_id, 'country', esc_attr($woocommerce_custom_product_country));
    if (!empty($woocommerce_custom_product_region))
        update_post_meta($post_id, 'region', esc_attr($woocommerce_custom_product_region));
    if (!empty($woocommerce_custom_product_sub_region))
        update_post_meta($post_id, 'sub_region', esc_attr($woocommerce_custom_product_sub_region));
    if (!empty($woocommerce_custom_product_winemaker))
        update_post_meta($post_id, 'winemaker', esc_attr($woocommerce_custom_product_winemaker));
    if (!empty($woocommerce_custom_product_vintage))
        update_post_meta($post_id, 'vintage', esc_attr($woocommerce_custom_product_vintage));
}

// TM - add the new Custom field to the catalog order selectionm


function tm_add_postmeta_ordering_args( $args_sort_cw ) {
 
	$tm_orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) :
        apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		
		switch( $tm_orderby_value ) {
			case 'alcohol_content':
				$args_sort_cw['orderby'] = 'meta_value_num';
				$args_sort_cw['order'] = 'desc';
				$args_sort_cw['meta_key'] = 'alcohol_content';
			break;
	}
 
	return $args_sort_cw;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'tm_add_postmeta_ordering_args' );
function tm_add_new_postmeta_orderby( $sortby ) {
   $sortby['alcohol_content'] = __( 'Sort By Alcohol Content', 'woocommerce' );

   return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'tm_add_new_postmeta_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'tm_add_new_postmeta_orderby' );

function tm_shop_display() {

	global $product;
    $alcohol_content = get_post_meta( $product->id, 'alcohol_content', true );
    
   if ( ! empty( $alcohol_content ) ) {
        echo '<div class="product-meta"><span class="product-meta-label alcohol">Alcohol: ' . $alcohol_content . '</span></div>';
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'tm_shop_display', 9 );


// Remove Team Post Type

function delete_post_type(){
	unregister_post_type( 'team' );
}
add_action('init','delete_post_type');


// Remove Product Tabs

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );          // Remove the description tab
    unset( $tabs['reviews'] );          // Remove the reviews tab
    unset( $tabs['additional_information'] );   // Remove the additional information tab

    return $tabs;

}


function gmw_woocommerce_image_dimensions() {
	global $pagenow;
 
	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}
  	$catalog = array(
		'width' 	=> '400',	// px
		'height'	=> '400',	// px
		'crop'		=> 1 		// true
	);
	$single = array(
		'width' 	=> '600',	// px
		'height'	=> '800',	// px
		'crop'		=> 1 		// true
	);
	$thumbnail = array(
		'width' 	=> '250',	// px
		'height'	=> '250',	// px
		'crop'		=> 0 		// false
	);
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}
add_action( 'after_switch_theme', 'gmw_woocommerce_image_dimensions', 1 );