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
 * @param string  $no_outofstock - string with "yes" or "no" value.
 * @param string  $orderby - string with ordering criteria value.
 * @param string  $order - string with "ASC" or "DESC" value.
 *
 * function for filtering WooCommerce posts.
 * with a little help from: https://github.com/woocommerce/woocommerce/blob/master/includes/widgets/class-wc-widget-products.php
 */
function micemade_elements_wc_query_args_func( $posts_per_page, $categories = array(), $exclude_cats = array(), $filters = 'latest', $offset = 0, $products_in = array(), $no_outofstock = 'yes', $orderby = 'date', $order = 'DESC' ) {

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
		'orderby'          => $orderby,
		'order'            => $order,
		'suppress_filters' => false,
	);

	// Relation is used only if multiple tax_query array items.
	if ( 'featured' === $filters || ! empty( $categories ) || ! empty( $exclude_cats ) ) {
		$args['tax_query'] = array( 'relation' => 'AND' );
	}

	// Tax query for product visibility.
	$product_visibility_terms  = wc_get_product_visibility_term_ids();
	$product_visibility_not_in = array( $product_visibility_terms['exclude-from-catalog'] );

	if ( 'yes' === $no_outofstock ) {
		$product_visibility_not_in[] = $product_visibility_terms['outofstock'];
	}

	$args['tax_query'][] = array(
		'taxonomy' => 'product_visibility',
		'field'    => 'term_taxonomy_id',
		'terms'    => $product_visibility_not_in,
		'operator' => 'NOT IN',
	);
	// Tax query array for featured products.
	if ( 'featured' === $filters ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'IN',
		);
	}
	// Tax query array for categories.
	if ( ! empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => 'product_cat',
			'field'            => 'slug',
			'operator'         => 'IN',
			'terms'            => $categories,
			'include_children' => true,
		);
	}
	// Tax query array for excluding categories.
	if ( ! empty( $exclude_cats ) ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'operator' => 'NOT IN',
			'terms'    => $exclude_cats,
			// 'include_children' => true,
		);
	}
	// Filters - meta query array.
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
	// Array of selected products.
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
add_filter( 'micemade_elements_wc_query_args', 'micemade_elements_wc_query_args_func', 10, 9 );

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
function micemade_elements_loop_product_func( $style = 'style_1', $item_classes = '', $img_format = 'thumbnail', $posted_in = true, $short_desc = false, $price = true, $add_to_cart = true, $css_class = '', $quickview = true, $icon = 'fas fa-eye' ) {

	echo '<li class="item ' . esc_attr( $item_classes ) . '">';
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
				// Add to Cart/Select options.
				echo '<span class="add-to-cart-wrap quickview-wrap">';
				if ( $add_to_cart ) {
					woocommerce_template_loop_add_to_cart();
				}
				// Quick View.
				if ( $quickview ) {
					do_action( 'micemade_elements_quick_view', $icon );
				}
				echo '</span>';

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
add_filter( 'micemade_elements_loop_product', 'micemade_elements_loop_product_func', 10, 10 );

/**
 * SIMPLE PRODUCT DATA (as in WC catalog)
 *
 * @param boolean $short_desc - if Short product description will be displayed.
 * @return void
 */
function micemade_elements_simple_prod_data_func( $short_desc = true ) {

	echo '<h3 class="product_title"><a href="' . esc_attr( get_permalink() ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '"> ' . esc_html( get_the_title() ) . '</a></h3>';

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
 * @return $output
 * @details html with count of products in category
 */
function micemade_elements_product_count_f( $term_id, $filters = array(), $prod_count_pre = '', $prod_count_ape = '' ) {

	// Micemade_elements_term_posts_count function is used for additional query args.
	$products_count = micemade_elements_term_posts_count( 'product_cat', intval( $term_id ), $filters );

	// If no products in category.
	if ( ! $products_count ) {
		return;
	}

	$output  = '<span class="category__product-count">';
	$output .= esc_html( $prod_count_pre );
	$output .= number_format_i18n( $products_count );
	$output .= esc_html( $prod_count_ape );
	// translators: %s is for number of product(s).
	// $output .= wp_sprintf( _n( '%s product', '%s products', $products_count, 'micemade-elements' ), number_format_i18n( $products_count ) ); // bug (not translating properly) ?
	$output .= '</span>';

	echo wp_kses_post( $output );
}
add_action( 'micemade_elements_product_count', 'micemade_elements_product_count_f', 100, 4 );

/**
 * Function to get post count from given term or terms and its/their children
 *
 * @param (string)           $taxonomy query posts with taxonomy.
 * @param (int|array|string) $term Single integer value, or array of integers.
 * @param (array)            $add_query_args Array of arguments to pass to WP_Query.
 * @return $q->found_posts
 */
function micemade_elements_term_posts_count( $taxonomy = 'category', $term = '', $add_query_args = array() ) {
	// Validate and sanitize our parameters, on failure, return false.
	if ( ! $term ) {
		return false;
	}
	if ( $term !== 'all' ) {
		if ( ! is_array( $term ) ) {
			$term = filter_var( $term, FILTER_VALIDATE_INT );
		} else {
			$term = filter_var_array( $term, FILTER_VALIDATE_INT );
		}
	}
	if ( $taxonomy !== 'category' ) {
		$taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}
	}

	// Default args for counting items.
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'product',
		'fields'         => 'ids',
		'post_status'    => 'publish',
	);

	// Show only products on sale (if additional query arg "on_sale" is set).
	if ( in_array( 'on_sale', $add_query_args, true ) ) {
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		if ( ! empty( $product_ids_on_sale ) ) {
			$args['post__in'] = $product_ids_on_sale;
		}
	}

	// Product visibiliy taxonomy (for excluding hidden products)
	// and showing only featured (if additional query arg "featured" is set).
	$featured = false;
	if ( in_array( 'featured', $add_query_args, true ) ) {
		$featured = true;
	}

	// Taxonomy query - multiple taxonomies query (product_cat, product_visiblity).
	$args['tax_query'] = array(
		'relation' => 'AND',
		array(
			'taxonomy' => $taxonomy,
			'field'    => 'id',
			'terms'    => $term,
		),
		array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'exclude-from-catalog',
			'operator' => 'NOT IN',
		),
	);

	if ( $featured ) {
		$args['tax_query'][]= array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			//'operator' => 'AND',
		);
	}

	$q = new WP_Query( $args );

	// Return the post count.
	return $q->found_posts;
}

/**
 * Product categories query arguments
 *
 * @param object $query - query object.
 * @return void
 */
function micemade_elements_cat_args( $query ) {

	if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( 'product' ) || $query->is_tax( get_object_taxonomies( 'product' ) ) ) ) {

		// If "on_sale" is set, and no value added (sanitize).
		if ( isset( $_GET['on_sale'] ) && '' === $_GET['on_sale'] ) {
			$product_ids_on_sale = wc_get_product_ids_on_sale();
			$query->set( 'post__in', $product_ids_on_sale );
		}
		// If "featured" is set, and no value added (sanitize).
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
		// If "best_sellers" is set, and no value added (sanitize).
		if ( isset( $_GET['best_sellers'] ) && '' === $_GET['best_sellers'] ) {
			$query->set( 'meta_key', 'total_sales' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'DESC' );
		}
		// If "best_rated" is set, and no value added (sanitize).
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

/**
 * QUICK VIEW BUTTON
 *
 * @return void
 */
function micemade_elements_quick_view_f( $icon = '' ) {

	// WPML (and Polylang) support.
	if ( function_exists( 'pll_current_language' ) ) {
		$id        = icl_object_id( get_the_ID(), 'product', false, ICL_LANGUAGE_CODE );
		$lang_code = ICL_LANGUAGE_CODE;
	} else {
		$id        = get_the_ID();
		$lang_code = '';
	}

	$class = apply_filters( 'mme_quick_view_css_classes', 'icon-button button' );

	echo '<a href="#qv-holder-' . esc_attr( $id ) . '" class="mme-quick-view ' . esc_attr( $class ) . '" data-id="' . esc_attr( $id ) . '"' . ( $lang_code ? ' data-lang="' . esc_attr( $lang_code ) . '"' : '' ) . ' title="' . esc_attr__( 'Quick view', 'micemade_elements' ) . '">';

	\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
	echo '</a>';
}
add_action( 'micemade_elements_quick_view', 'micemade_elements_quick_view_f', 10, 1 );

/**
 * Enqueue single product JS scripts
 *
 * @return void
 */
function micemade_elements_single_product_scripts_e() {

	$list       = 'enqueued';
	$variations = wp_script_is( 'wc-add-to-cart-variation', $list );
	$single_js  = wp_script_is( 'wc-single-product', $list );
	$zoom       = wp_script_is( 'zoom', $list );
	$flexslider = wp_script_is( 'flexslider', $list );
	$photoswipe = wp_script_is( 'photoswipe-ui-default', $list );
	$wc_assets  = '/woocommerce/assets/js/';

	if ( ! $variations ) {
		wp_register_script( 'wc-add-to-cart-variation', WP_PLUGIN_URL . $wc_assets . 'frontend/add-to-cart-variation.min.js', array( 'jquery', 'wp-util', 'jquery-blockui' ), WC_VERSION, true );
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}
	if ( ! $single_js ) {
		wp_register_script( 'wc-single-product', WP_PLUGIN_URL . $wc_assets . 'frontend/single-product.min.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'wc-single-product' );
	}
	if ( ! $zoom ) {
		wp_register_script( 'zoom', WP_PLUGIN_URL . $wc_assets . 'zoom/jquery.zoom.min.js', array( 'jquery' ), '1.7.21', true );
		wp_enqueue_script( 'zoom' );
	}
	if ( ! $flexslider ) {
		wp_register_script( 'flexslider', WP_PLUGIN_URL . $wc_assets . 'flexslider/jquery.flexslider.min.js', array( 'jquery' ), '2.7.2', true );
		wp_enqueue_script( 'flexslider' );
	}
	if ( ! $photoswipe ) {
		wp_register_script( 'photoswipe-ui-default', WP_PLUGIN_URL . $wc_assets . 'photoswipe/photoswipe-ui-default.min.js', array( 'photoswipe' ), '4.1.1', true );
		wp_enqueue_script( 'photoswipe-ui-default' );
		wp_enqueue_style( 'photoswipe-default-skin' );
		add_action( 'wp_footer', 'woocommerce_photoswipe' );
	}

}
add_action( 'micemade_elements_single_product_scripts', 'micemade_elements_single_product_scripts_e' );

add_action(
	'elementor/editor/after_enqueue_scripts',
	function () {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			micemade_elements_single_product_scripts_e();
		}
	}
);

// function alert() {
// 	echo '<script>alert("Admin head after WC scripts!");</script>';
// }
// add_action( 'wp_head', 'alert' );
