<?php
/**
 *  MORE POSTS with AJAX
 *  
 *  @return html
 *  
 *  Uses hook 'micemade_elements_query_args' for query args 
 *  and hook  'micemade_elements_loop_post' for displaying individual post (within loop)
 *  code adapted from https://madebydenis.com/ajax-load-posts-on-wordpress/
 */
add_action('wp_ajax_nopriv_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax');
add_action('wp_ajax_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax');

if ( !function_exists('micemade_elements_more_post_ajax') ) {
	
	function micemade_elements_more_post_ajax() {
 
		global $post;
	    
		$posts_per_page = ( isset($_POST['ppp']) )			? $_POST['ppp'] : 3;
	    $categories 	= ( isset($_POST['categories']) )	? json_decode( $_POST['categories'] ) : array();
	    $style			= ( isset($_POST['style']) )		? $_POST['style'] : 'style_1';
	    $img_format		= ( isset($_POST['img_format']) )	? $_POST['img_format'] : 'thumbnail';
	    $excerpt		= ( isset($_POST['excerpt']) )		? $_POST['excerpt'] : true;
	    $excerpt_limit	= ( isset($_POST['excerpt_limit']) ) ? $_POST['excerpt_limit'] : 20;
	    $meta			= ( isset($_POST['meta']) )			? $_POST['meta'] : true;
	    $css_class		= ( isset($_POST['css_class']) )	? $_POST['css_class'] : '';
	    $grid			= ( isset($_POST['grid']) )			? $_POST['grid'] : '';
	    $offset			= ( isset($_POST['offset']))		? $_POST['offset'] : 0;
 
		
		// Query posts:
		$args	= apply_filters( 'micemade_elements_query_args', $posts_per_page, $categories, $sticky, $offset ); // hook in includes/helpers.php
		$posts	= get_posts( $args );
 
	    if( empty( $posts ) ) return;
		
		foreach ( $posts as $post ) {
				
			setup_postdata( $post ); 
			
			apply_filters( 'micemade_elements_loop_post', $style, $grid , $img_format , $meta , $excerpt, $excerpt_limit, $css_class );// hook in includes/helpers.php
			
		} // end foreach
 
	    wp_reset_postdata();
		
	    wp_die();
	}
}