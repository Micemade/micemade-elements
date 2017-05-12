<?php
function micemade_elements_wc_query_args_func( $filters = 'latest', $categories = array() ) {
	
	if( !'MICEMADE_ELEMENTS_WOO_ACTIVE' ) return; // if WooCommerce is not active
	
	// Fallback / default variables
	$args_filters		= array();
	$tax_query_featured = array();
	$tax_query_cats		= array();
	
	$args_filters['orderby'] = 'menu_order date';
		
	if ( $filters == 'featured' ) {
		$args_filters['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
		);
	}
	
	if( !empty( $categories ) ) {
		$args_filters['tax_query'][] = array(
			'taxonomy'	=> 'product_cat',
			'field'		=> 'slug',
			'operator'	=> 'IN',
			'terms'		=> $categories,
			'include_children' => true
		);
	}
	
	if( $filters == 'best_sellers' ) {
		
		$args_filters['meta_key']	= 'total_sales';
		$args_filters['orderby']	= 'meta_value_num';
	
	}elseif( $filters == 'best_rated' ) {
		
		$args_filters['meta_key']	= '_wc_average_rating';
		$args_filters['orderby']	= 'meta_value_num';
		
	}elseif( $filters == 'random' ) {
	
		$args_filters['orderby'] = 'rand menu_order date';
		
	}elseif( $filters == 'on_sale' ) {
		
		$product_ids_on_sale    = wc_get_product_ids_on_sale();
		if( ! empty( $product_ids_on_sale ) ) {
			$args_filters['post__in'] = $product_ids_on_sale;
		}
	
	}
	
	return $args_filters;
	
}
add_filter( 'micemade_elements_wc_query_args','micemade_elements_wc_query_args_func', 10, 2 );
?>