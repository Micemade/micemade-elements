<?php
/**
 * Plugin Ajax Functions
 *
 * @since 0.0.5
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MORE POSTS with AJAX
 *
 * @return html
 * Uses hook 'micemade_elements_query_args' for query args
 * and hook  'micemade_elements_loop_post' for displaying individual post (within loop)
 * code adapted from https://madebydenis.com/ajax-load-posts-on-wordpress/
 */
add_action( 'wp_ajax_nopriv_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax' );
add_action( 'wp_ajax_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax' );

if ( ! function_exists( 'micemade_elements_more_post_ajax' ) ) {

	function micemade_elements_more_post_ajax() {

		global $post;

		$posts_per_page = ( isset( $_POST['ppp'] ) ) ? $_POST['ppp'] : 3;
		$sticky         = ( isset( $_POST['sticky'] ) ) ? $_POST['sticky'] : false;
		$categories     = ( isset( $_POST['categories'] ) ) ? json_decode( $_POST['categories'] ) : [];
		$style          = ( isset( $_POST['style'] ) ) ? $_POST['style'] : 'style_1';
		$show_thumb     = ( isset( $_POST['show_thumb'] ) ) ? $_POST['show_thumb'] : true;
		$img_format     = ( isset( $_POST['img_format'] ) ) ? $_POST['img_format'] : 'thumbnail';
		$excerpt        = ( isset( $_POST['excerpt'] ) ) ? $_POST['excerpt'] : true;
		$excerpt_limit  = ( isset( $_POST['excerpt_limit'] ) ) ? $_POST['excerpt_limit'] : 20;
		$meta           = ( isset( $_POST['meta'] ) ) ? $_POST['meta'] : true;
		$meta_ordering  = ( isset( $_POST['meta_ordering'] ) ) ? $_POST['meta_ordering'] : [];
		$css_class      = ( isset( $_POST['css_class'] ) ) ? $_POST['css_class'] : '';
		$grid           = ( isset( $_POST['grid'] ) ) ? $_POST['grid'] : '';
		$offset         = ( isset( $_POST['offset'] ) ) ? $_POST['offset'] : 0;

		// Query posts - 'micemade_elements_query_args' hook in includes/helpers.php.
		$args  = apply_filters( 'micemade_elements_query_args', $posts_per_page, $categories, $sticky, $offset );
		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return;
		}

		foreach ( $posts as $post ) {

			setup_postdata( $post );

			// Title, post thumb, excerpt, meta - 'micemade_elements_loop_post' hook in includes/helpers.php.
			apply_filters( 'micemade_elements_loop_post', $style, $grid, $show_thumb, $img_format, $meta, $meta_ordering, $excerpt, $excerpt_limit, $css_class );

		} // end foreach

		wp_reset_postdata();

		wp_die();
	}
}
