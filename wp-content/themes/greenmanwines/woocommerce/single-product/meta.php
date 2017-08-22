<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php /*if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif;  */ ?>

	<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

	<?php 
	
	$alcohol_content = get_post_meta( $product->id, 'alcohol_content', true );
	
		if ( ! empty( $alcohol_content ) ) {
			echo '<span class="alcohol-content">Alcohol Content: ' . $alcohol_content .  '</span>';
		}	
	?>

	<?php 
	
	$country = get_post_meta( $product->id, 'country', true );
	
		if ( ! empty( $country ) ) {
			echo '<span class="country"> Country: ' . $country .  '</span>';
		}	
	?>
	
	<?php 
	
	$region = get_post_meta( $product->id, 'region', true );
	
		if ( ! empty( $region ) ) {
			echo '<span class="region"> Region: ' . $region .  '</span>';
		}	
	?>

	<?php 
	
	$sub_region = get_post_meta( $product->id, 'sub_region', true );
	
		if ( ! empty( $sub_region ) ) {
			echo '<span class="sub-region"> Sub Region: ' . $sub_region .  '</span>';
		}	
	?>

	<?php 
	
	$winemaker = get_post_meta( $product->id, 'winemaker', true );
	
		if ( ! empty( $winemaker ) ) {
			echo '<span class="winemaker"> Winemaker: ' . $winemaker .  '</span>';
		}	
	?>

	<?php 
		
		$vintage = get_post_meta( $product->id, 'vintage', true );
		
			if ( ! empty( $vintage ) ) {
				echo '<span class="vintage"> Vintage: ' . $vintage .  '</span>';
			}	
		?>
</div>
