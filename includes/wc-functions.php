<?php
/**
 * Main functions for WooCommerce
 *
 * @since 0.0.1
 * @package WordPress
 * @subpackage Micemade Elements
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC Query Arguments
 *
 * @param integer $posts_per_page - total posts number.
 * @param array   $categories - WC categories for filtering.
 * @param array   $exclude_cats categories - exclude WC categories for filtering.
 * @param string  $filters - WC filters - latest/sales/rated/best seller/random.
 * @param integer $offset - offset number skips first items in query.
 * @param array   $products_in - an array of products to use with "post_name__in".
 * @return $args
 *
 * function for filtering WooCommerce posts.
 * with a little help from: https://github.com/woocommerce/woocommerce/blob/master/includes/widgets/class-wc-widget-products.php
 */
function micemade_elements_wc_query_args_func( $posts_per_page, $categories = array(), $exclude_cats = array(), $filters = 'latest', $offset = 0, $products_in = array() ) {

	// if WooCommerce is not active.
	if ( ! 'MICEMADE_ELEMENTS_WOO_ACTIVE' ) {
		return;
	}

	// Default arguments.
	$args = array(
		'posts_per_page'   => $posts_per_page,
		'post_type'        => 'product',
		'post_status'      => 'publish',
		'offset'           => $offset,
		'order'            => 'DESC',
		'suppress_filters' => false,
	);

	$args['orderby'] = 'date menu_order';

	if ( ! empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'slug',
			'operator'         => 'IN',
			'terms'            => $categories,
			'include_children' => true,
		);
	}

	if ( ! empty( $exclude_cats ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'operator' => 'NOT IN',
			'terms'    => $exclude_cats,
			//'include_children' => true,
		);
	}

	if ( 'featured' === $filters ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
		);
	}
	if ( 'best_sellers' === $filters ) {

		$args['meta_key'] = 'total_sales';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';

	} elseif ( 'best_rated' === $filters ) {

		$args['meta_key'] = '_wc_average_rating';
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';

	} elseif ( 'random' === $filters ) {

		$args['orderby'] = 'rand menu_order date';

	} elseif ( 'on_sale' === $filters ) {

		$product_ids_on_sale = wc_get_product_ids_on_sale();
		if ( ! empty( $product_ids_on_sale ) ) {
			$args['post__in'] = $product_ids_on_sale;
		}
	}

	if ( ! empty( $products_in ) ) {
		$args['post_name__in'] = $products_in;
	}

	// Polylang (and WPML) support.
	if ( function_exists( 'pll_current_language' ) ) {
		$current_lang = pll_current_language();
		$args['lang'] = $current_lang;
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		$current_lang = ICL_LANGUAGE_CODE;
		$args['lang'] = $current_lang;
	}

	return $args;

}
add_filter( 'micemade_elements_wc_query_args', 'micemade_elements_wc_query_args_func', 10, 6 );

/**
 * PRODUCT FOR LOOP
 *
 * @param string  $style - slides style.
 * @param string  $img_format - registered image format.
 * @param boolean $posted_in - to show "Posted in" (categories), or not.
 * @param boolean $short_desc - to show Short product description or not.
 * @param boolean $price - to show product price or not.
 * @param boolean $add_to_cart - to show "Add to Cart" button or not.
 * @param string  $css_class - string with custom CSS classes.
 * @return void
 * an DRY effort ...
 */
function micemade_elements_loop_product_func( $style = 'style_1', $img_format = 'thumbnail', $posted_in = true, $short_desc = false, $price = true, $add_to_cart = true, $css_class = '' ) {

	$item_class = apply_filters( 'micemade_elements_product_item_class', [ 'post', 'swiper-slide', 'product' ] );
	echo '<li class="' . esc_attr( implode( ' ', $item_class ) ) . '">';
	?>

		<div class="inner-wrap">

			<?php
			if ( 'style_3' === $style || 'style_4' === $style ) {
				do_action( 'micemade_elements_thumb_back', $img_format );
				echo '<div class="post-overlay"></div>';
			} else {
				do_action( 'micemade_elements_thumb', $img_format );
			}
			?>

			<div class="post-text">

				<h4>
					<a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h4>

				<?php if ( $posted_in ) { ?>
				<div class="meta">

					<?php micemade_elements_posted_in( 'product_cat' ); ?>

				</div>
				<?php } ?>

				<div class="product-details">

				<?php
				if ( $price ) {
					echo '<span class="price-wrap">';
					woocommerce_template_loop_price();
					echo '</span>';
				}
				if ( $add_to_cart ) {
					echo '<span class="add-to-cart-wrap">';
					woocommerce_template_loop_add_to_cart();
					echo '</span>';
				}
				if ( $short_desc ) {

					the_excerpt();

					echo '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" class="micemade-elements-readmore ' . esc_attr( $css_class ) . ' ">' . esc_html( apply_filters( 'micemade_elements_prod_details', esc_html__( 'Product details', 'micemade-elements' ) ) ) . '</a>';
				}
				?>
				</div>

			</div>

		</div>

	</li>

	<?php
}
add_filter( 'micemade_elements_loop_product', 'micemade_elements_loop_product_func', 10, 7 );
/**
 * SIMPLE PRODUCT DATA (as in WC catalog)
 *
 * @param boolean $short_desc - if Short product description will be displayed.
 * @return void
 */
function micemade_elements_simple_prod_data_func( $short_desc = true ) {

	echo '<h3 class="product_title"><a href="' . esc_attr( get_permalink() ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '"> ' . get_the_title() . '</a></h3>';

	if ( $short_desc ) {
		wc_get_template_part( 'single-product/short', 'description' );
	}
	woocommerce_template_loop_price();

	echo '<div class="add-to-cart-wrapper">';
		woocommerce_template_loop_add_to_cart();
	echo '</div>';

}
add_filter( 'micemade_elements_simple_prod_data', 'micemade_elements_simple_prod_data_func', 10, 1 );

/**
 * PRODUCT COUNT PER CATEGORY
 *
 * @param integer $term_id Parameter_Description.
 * @return $prod_count
 * @details html with count of products in category
 */
function micemade_elements_product_count_f( $term_id ) {

	$products_count = get_term_meta( intval( $term_id ), 'product_count_product_cat', true );

	if ( is_wp_error( $products_count ) || ! $products_count ) {
		return;
	}

	$prod_count = '<span class="category__product-count">';
	// translators: %s is for number of product(s).
	$prod_count .= sprintf( _n( '%s product', '%s products', $products_count, 'micemade-elements' ), $products_count );

	$prod_count .= '</span>';

	echo wp_kses_post( $prod_count );
}
add_action( 'micemade_elements_product_count', 'micemade_elements_product_count_f', 100, 3 );
/**
 * Product categories query arguments
 *
 * @param object $query - query object.
 * @return void
 */
function micemade_elements_cat_args( $query ) {

	if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( 'product' ) || $query->is_tax( get_object_taxonomies( 'product' ) ) ) ) {

		if ( isset( $_GET['on_sale'] ) && '' === $_GET['on_sale'] ) {

			$product_ids_on_sale = wc_get_product_ids_on_sale();
			$query->set( 'post__in', $product_ids_on_sale );

		}
		if ( isset( $_GET['featured'] ) && '' === $_GET['featured'] ) {

			$query->set(
				'tax_query',
				array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					),
				)
			);

		}

		if ( isset( $_GET['best_sellers'] ) && '' === $_GET['best_sellers'] ) {

			$query->set( 'meta_key', 'total_sales' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );

		}

		if ( isset( $_GET['best_rated'] ) && '' === $_GET['best_rated'] ) {

			$query->set( 'meta_key', '_wc_average_rating' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );

		}
	}

}
add_action( 'pre_get_posts', 'micemade_elements_cat_args', 999 );

/**
 * Add product items css classes
 * used for products slider
 *
 * @param array  $classes - array of post classes.
 * @param string $add_remove - if "add", then add classes to array.
 * @return $classes
 */
function micemade_elements_product_item_classes_f( $classes, $add_remove, $additional_classes ) {

	if ( 'add' === $add_remove ) {
		$classes = array_merge( $classes, $additional_classes );
	} else {
		$classes = array_diff( $classes, $additional_classes );
	}

	return $classes;
}
add_filter( 'micemade_elements_product_item_classes', 'micemade_elements_product_item_classes_f', 5, 3 );
