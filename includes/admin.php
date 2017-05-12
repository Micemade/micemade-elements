<?php 
/**
 *	GET POSTS ARRAY (by post type)
 *
 */
add_filter("micemade_posts_array","micemade_posts_array_func", 10, 1);
function micemade_posts_array_func( $post_type = "post") {
	
	$args = array(
		'post_type'			=> $post_type,
		'posts_per_page'	=> -1,
		'suppress_filters'	=> true,
	);
	
	$posts_arr	= array();
	$posts_obj	= get_posts($args);
	if ( $posts_obj ) {
		foreach( $posts_obj as $single_post ) {
			
			$posts_arr[$single_post->post_name] = strip_tags( $single_post->post_title )  ;
		}
	}else{
		$posts_arr[] = '';
	}
	
	
	return $posts_arr; 
	
}

/**
 *	MICEMADE_ELEMENTS_TERMS_FUNC ( micemade_elements_terms hook )
 *	get terms array from taxonomy
 *
 *	return array $terms_arr
 */
function micemade_elements_terms_func( $taxonomy ) {

	if( ! taxonomy_exists( $taxonomy ) ) return;
	
	$terms_arr		= array();
	if( MICEMADE_ELEMENTS_WPML_ON ) { // IF WPML IS ACTIVATED
				
		$terms = get_terms( $taxonomy,'hide_empty=1, hierarchical=0' );
		if ( !empty( $terms ) ){
			foreach ( $terms as $term ) {
				if($term->term_id == icl_object_id($term->term_id, $taxonomy,false,ICL_LANGUAGE_CODE)){
					$terms_arr[$term->slug]= $term->name ;
				}
			}
		}else{
			$terms_arr = array();
		}
		
	}else{
		
		$terms = get_terms( $taxonomy,'hide_empty=1, hierarchical=0');
		if ( $terms ) {
			foreach ($terms as $term) {
				$terms_arr[$term->slug]= $term->name ;
			}
		}else{
			$terms_arr = array();
		}
	}
	
	return $terms_arr;

}
add_filter('micemade_elements_terms','micemade_elements_terms_func', 10, 1);
/**
 *  POSTS QUERY ARGS
 *  
 *  @return (array) posts 
 *  
 *  @details Details
 */
function micemade_elements_query_args_func( $categories = array(), $sticky = false ) {
		
	// Fallback / default variables
	$args		= array();
	
	$args['orderby'] = 'menu_order date';
	
	
	if( !empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'	=> 'category',
			'field'		=> 'slug',
			'operator'	=> 'IN',
			'terms'		=> $categories,
			'include_children' => true
		);
	}
	
	if( $sticky ) {
		$sticky_array = get_option( 'sticky_posts' );
		if( !empty( $sticky_array ) ) {
			$args['post__in'] = $sticky_array;
		}
		
	}
	
	return $args;
	
}
add_filter( 'micemade_elements_query_args','micemade_elements_query_args_func', 10, 2 );

/**
 *	IMAGE SIZES hook
 *	- create array of all registered image sizes
 *	- dependency - function micemade_titleIt()
 */
add_filter('micemade_elements_image_sizes','micemade_elements_image_sizes_arr',10,1);
function micemade_elements_image_sizes_arr( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array();
	$intermediate_image_sizes = get_intermediate_image_sizes();
	$additional_image_sizes = array_keys( $_wp_additional_image_sizes );
	
	$sizes_arr = array_merge( $intermediate_image_sizes, $additional_image_sizes, array("full") );
	
	foreach( $sizes_arr as $size  ) {
		
		$title = micemade_titleIt( $size );
		$sizes[ $size ] = $title;
	}

	return $sizes;
}
function micemade_titleIt( $slug ) {
	
	$title = ucfirst( $slug );
	$title = str_replace("_"," ", $title);
	$title = str_replace("-"," ", $title);
	
	return $title;
}
?>