<?php
/**
 * Functions to run only on Elementor editor
 *
 * @since 0.0.1
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GET POSTS ARRAY (by post type)
 *
 * @param string $post_type - type of post.
 * @return $posts_arr
 */
function micemade_posts_array_func( $post_type = 'post', $id_or_slug = 'slug' ) {

	$args = array(
		'post_type'        => $post_type,
		'posts_per_page'   => -1,
		'suppress_filters' => true,
		'cache_results'    => false, // suppress errors when large number of posts (memory).
	);

	$posts_arr = array();
	$posts_obj = get_posts( $args );
	if ( ! empty( $posts_obj ) ) {

		foreach ( $posts_obj as $single_post ) {

			if ( ! is_object( $single_post ) ) {
				continue;
			}
			if ( 'slug' === $id_or_slug ) {
				$posts_arr[ $single_post->post_name ] = wp_strip_all_tags( $single_post->post_title );
			} elseif ( 'id' === $id_or_slug ) {
				$posts_arr[ $single_post->ID ] = wp_strip_all_tags( $single_post->post_title );
			}
		}
	} else {
		$posts_arr[] = '';
	}

	return $posts_arr;

}
add_filter( 'micemade_posts_array', 'micemade_posts_array_func', 10, 2 );

/**
 * Get terms array
 *
 * @param string $taxonomy - taxonomy from which to get terms.
 * @return $terms_arr
 */
function micemade_elements_terms_func( $taxonomy ) {

	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}

	$terms_arr = array();
	if ( MICEMADE_ELEMENTS_WPML_ON ) { // IF WPML IS ACTIVATED.

		$terms = get_terms( $taxonomy, 'hide_empty=1, hierarchical=0' );
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( icl_object_id( $term->term_id, $taxonomy, false, ICL_LANGUAGE_CODE ) === $term->term_id ) {
					$terms_arr[ $term->slug ] = $term->name;
				}
			}
		} else {
			$terms_arr = array();
		}
	} else {

		$terms = get_terms( $taxonomy, 'hide_empty=1, hierarchical=0' );
		if ( $terms ) {
			foreach ( $terms as $term ) {
				$terms_arr[ $term->slug ] = $term->name;
			}
		} else {
			$terms_arr = array();
		}
	}

	return $terms_arr;

}
add_filter( 'micemade_elements_terms', 'micemade_elements_terms_func', 10, 1 );

/**
 * Get array of all image sizes
 *
 * @param string $size - registered image size.
 * @return $sizes
 * create array of all registered image sizes.
 * dependency - function micemade_title_slug().
 */
function micemade_elements_image_sizes_arr( $size = '' ) {

	global $_wp_additional_image_sizes;

	$sizes = array();

	$intermediate_image_sizes = get_intermediate_image_sizes();
	$additional_image_sizes   = array_keys( $_wp_additional_image_sizes );

	$sizes_arr = array_merge( $intermediate_image_sizes, $additional_image_sizes, array( 'full' ) );

	foreach ( $sizes_arr as $size ) {

		$title          = micemade_title_slug( $size );
		$sizes[ $size ] = $title;
	}

	return $sizes;
}
add_filter( 'micemade_elements_image_sizes', 'micemade_elements_image_sizes_arr', 10, 1 );

if ( ! function_exists( 'micemade_title_slug' ) ) {
	/**
	 * Title from slug
	 *
	 * @param string $slug - slug string.
	 * @return $title
	 */
	function micemade_title_slug( $slug ) {

		$title = ucfirst( $slug );
		$title = str_replace( '_', ' ', $title );
		$title = str_replace( '-', ' ', $title );

		return $title;
	}
}

/**
 * GET LIST OF REVOLUTION SLIDERS
 *
 * @return $slider_arr
 */
function micemade_elements_rev_sliders_f() {

	$slider_arr = array();

	if ( class_exists( 'RevSlider' ) ) {

		// Get slider aliases array.
		$slider      = new RevSlider();
		$arr_sliders = $slider->getAllSliderAliases();

		// Has slides.
		if ( ! empty( $arr_sliders ) ) {

			foreach ( $arr_sliders as $id => $alias ) {
				$slider_arr[ $alias ] = micemade_title_slug( $alias );
			}
		}
	}

	return $slider_arr;
}
add_filter( 'micemade_elements_rev_sliders', 'micemade_elements_rev_sliders_f', 100 );
