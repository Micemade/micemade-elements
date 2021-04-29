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
 * based on code from https://madebydenis.com/ajax-load-posts-on-wordpress/
 */
add_action( 'wp_ajax_nopriv_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax' );
add_action( 'wp_ajax_micemade_elements_more_post_ajax', 'micemade_elements_more_post_ajax' );

if ( ! function_exists( 'micemade_elements_more_post_ajax' ) ) {

	function micemade_elements_more_post_ajax() {

		global $post;

		$post_type     = ( isset( $_POST['post_type'] ) ) ? $_POST['post_type'] : 'post';
		$order         = ( isset( $_POST['order'] ) ) ? $_POST['order'] : 'DESC';
		$orderby       = ( isset( $_POST['orderby'] ) ) ? $_POST['orderby'] : 'menu_order date';
		$orderby_meta  = ( isset( $_POST['orderby_meta'] ) ) ? $_POST['orderby_meta'] : '';
		$taxonomy      = ( isset( $_POST['taxonomy'] ) ) ? $_POST['taxonomy'] : '';
		$ppp           = ( isset( $_POST['ppp'] ) ) ? $_POST['ppp'] : 3;
		$categories    = ( isset( $_POST['categories'] ) ) ? $_POST['categories'] : [];
		$post__in      = ( isset( $_POST['post__in'] ) ) ? $_POST['post__in'] : [];
		$sticky        = ( isset( $_POST['sticky'] ) ) ? $_POST['sticky'] : false;
		$offset        = ( isset( $_POST['offset'] ) ) ? $_POST['offset'] : 0;
		$style         = ( isset( $_POST['style'] ) ) ? $_POST['style'] : 'style_1';
		$show_thumb    = ( isset( $_POST['show_thumb'] ) ) ? $_POST['show_thumb'] : true;
		$img_format    = ( isset( $_POST['img_format'] ) ) ? $_POST['img_format'] : 'thumbnail';
		$excerpt       = ( isset( $_POST['excerpt'] ) ) ? $_POST['excerpt'] : true;
		$excerpt_limit = ( isset( $_POST['excerpt_limit'] ) ) ? $_POST['excerpt_limit'] : 20;
		$meta          = ( isset( $_POST['meta'] ) ) ? $_POST['meta'] : true;
		$meta_ordering = ( isset( $_POST['meta_ordering'] ) ) ? $_POST['meta_ordering'] : [];
		$css_class     = ( isset( $_POST['css_class'] ) ) ? $_POST['css_class'] : '';
		$grid          = ( isset( $_POST['grid'] ) ) ? $_POST['grid'] : '';
		$cm_fields     = ( isset( $_POST['cm_fields'] ) ) ? $_POST['cm_fields'] : '';
		$elm_ordering  = ( isset( $_POST['elm_ordering'] ) ) ? $_POST['elm_ordering'] : '';
		$if_readmore   = ( isset( $_POST['if_readmore'] ) ) ? $_POST['if_readmore'] : true;
		$readmore_text = ( isset( $_POST['readmore_text'] ) ) ? $_POST['readmore_text'] : '';
		

		// Query posts - 'micemade_elements_query_args' hook in includes/helpers.php.
		$args  = apply_filters( 'micemade_elements_query_args', $post_type, $ppp, $order, $orderby, $orderby_meta, $taxonomy, $categories, $post__in, $sticky, $offset );
		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return;
		}

		foreach ( $posts as $post ) {

			setup_postdata( $post );

			// Title, post thumb, excerpt, meta - 'micemade_elements_loop_post' hook in includes/helpers.php.
			apply_filters( 'micemade_elements_loop_post', $style, $grid, $show_thumb, $img_format, $meta, $meta_ordering, $excerpt, $excerpt_limit, $css_class, $taxonomy, $cm_fields, $elm_ordering, $if_readmore, $readmore_text );

		} // end foreach

		wp_reset_postdata();

		wp_die();
	}
}

/**
 * QUICK VIEW - Products popup
 *
 * @return void
 */
function mme_quick_view() {

	global $post;

	$product_id = sanitize_text_field( $_POST['productID'] );
	$lang       = isset( $_POST['lang'] ) ? sanitize_text_field( $_POST['lang'] ) : '';
	// If WPML (or Polylang) active.
	$product_id = $lang ? icl_object_id( $product_id, 'product', false, $lang ) : $product_id;

	$display_args = array(
		'no_found_rows'    => 1,
		'post_status'      => 'publish',
		'post_type'        => 'product',
		//'post_parent'    => 0,
		'suppress_filters' => false,
		'numberposts'      => 1,
		'include'          => $product_id,
	);

	$content = get_posts( $display_args );

	$post = $content[0];
	setup_postdata( $post );

	//global $post, $product, $woocommerce, $wp_query;

	$post_classarr   = get_post_class();
	$post_classarr[] = 'mmqv-wrapper';
	$post_class      = implode( ' ', $post_classarr );

	echo '<div itemscope itemtype="http://schema.org/Product" id="product-' . esc_attr( $product_id ) . '" class="' . esc_attr( $post_class ) . ' product">';
	?>

	<div class="mmqv-product-images">
		<?php
		// Product images, sales flash ...
		woocommerce_show_product_sale_flash();
		// Alternative to woocommerce_show_product_images().
		wc_get_template( 'product-image.php', false, false, MICEMADE_ELEMENTS_DIR . '/includes/templates/' );
		?>
	</div>


	<div class="summary entry-summary mmqv-product-summary">
		<?php
		// DON'T DO SHARETHIS ON QUICK VIEW.
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		// Do single product summary.
		do_action( 'woocommerce_single_product_summary' );
		?>
	</div>

	<div class="clearfix"></div>

	</div>
	<?php
	wp_die();

}
add_action( 'wp_ajax_nopriv_mme_quick_view', 'mme_quick_view' ); // for NOT logged in users.
add_action( 'wp_ajax_mme_quick_view', 'mme_quick_view' ); // for logged in users.
