<?php
/**
 *  WOOCOMMERCE QUERY ARGUMENTS
 *  
 *  @return (array) $args
 *  
 *  for filtering WooCommerce posts
 * 	with a little help from: https://github.com/woocommerce/woocommerce/blob/master/includes/widgets/class-wc-widget-products.php
 */
function micemade_elements_wc_query_args_func( $posts_per_page, $filters = 'latest', $categories = array() ) {

	if( !'MICEMADE_ELEMENTS_WOO_ACTIVE' ) return; // if WooCommerce is not active
	
	// Default variables
	$args		= array(
		'posts_per_page'	=> $posts_per_page,
		'post_type'			=> 'product',
	);
	
	$args['orderby'] = 'menu_order date';
		
	if ( $filters == 'featured' ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
		);
	}
	
	if( !empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'	=> 'product_cat',
			'field'		=> 'slug',
			'operator'	=> 'IN',
			'terms'		=> $categories,
			'include_children' => true
		);
	}
	
	if( $filters == 'best_sellers' ) {
		
		$args['meta_key']	= 'total_sales';
		$args['orderby']	= 'meta_value_num';
	
	}elseif( $filters == 'best_rated' ) {
		
		$args['meta_key']	= '_wc_average_rating';
		$args['orderby']	= 'meta_value_num';
		
	}elseif( $filters == 'random' ) {
	
		$args['orderby'] = 'rand menu_order date';
		
	}elseif( $filters == 'on_sale' ) {
		
		$product_ids_on_sale    = wc_get_product_ids_on_sale();
		if( ! empty( $product_ids_on_sale ) ) {
			$args['post__in'] = $product_ids_on_sale;
		}
	
	}
	
	return $args;
	
}
add_filter( 'micemade_elements_wc_query_args','micemade_elements_wc_query_args_func', 10, 2 );
?>