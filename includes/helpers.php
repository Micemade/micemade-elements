<?php
/**
 * Helper functions
 *
 * @since 0.0.1
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

/**
 * COLUMN CSS SELECTORS FOR FLEX GRID
 *
 * @param integer $items_desktop - number of items in row on desktop.
 * @param integer $items_tablet - number of items in row on tablet.
 * @param integer $items_mobile - number of items in row on mobile.
 * @param boolean $no_margin - to show margin or not.
 * @return $style_class
 */
function micemade_elements_grid_class( $items_desktop = 3, $items_tablet = 1, $items_mobile = 1, $no_margin = false ) {

	$style_class   = '';
	$desktop_class = '';
	$tablets_class = '';
	$mobiles_class = '';

	$no_margin = micemade_elements_to_boolean( $no_margin ); // make sure it is not string.

	$column_desktop = array(
		1  => 'mme-col-md-12',
		2  => 'mme-col-md-6',
		3  => 'mme-col-md-4',
		4  => 'mme-col-md-3',
		6  => 'mme-col-md-2',
		12 => 'mme-col-md-1',
	);
	$column_tablet  = array(
		1  => 'mme-col-sm-12',
		2  => 'mme-col-sm-6',
		3  => 'mme-col-sm-4',
		4  => 'mme-col-sm-3',
		6  => 'mme-col-sm-2',
		12 => 'mme-col-sm-1',
	);
	$column_mobile  = array(
		1  => 'mme-col-xs-12',
		2  => 'mme-col-xs-6',
		3  => 'mme-col-xs-4',
		4  => 'mme-col-xs-3',
		6  => 'mme-col-xs-2',
		12 => 'mme-col-xs-1',
	);

	// Generate class selector for desktop.
	if ( array_key_exists( $items_desktop, $column_desktop ) && ! empty( $column_desktop[ $items_desktop ] ) ) {
		$desktop_class = $column_desktop[ $items_desktop ];
	}
	// Generate class selector for tablets.
	if ( array_key_exists( $items_tablet, $column_tablet ) && ! empty( $column_tablet[ $items_tablet ] ) ) {
		$tablets_class = $column_tablet[ $items_tablet ];
	}
	// Generate class selector for mobiles.
	if ( array_key_exists( $items_mobile, $column_mobile ) && ! empty( $column_mobile[ $items_mobile ] ) ) {
		$mobiles_class = $column_mobile[ $items_mobile ];
	}

	$style_class = $no_margin ? ( $desktop_class . ' ' . $tablets_class . ' ' . $mobiles_class . ' mme-zero-margin' ) : ( $desktop_class . ' ' . $tablets_class . ' ' . $mobiles_class );

	// Added fixed full width to small screen - to do controls for small devices (?).
	return $style_class;
}

/**
 * Calculate grid CSS selectors based on Elementor breakpoints.
 *
 * Elementor breakpoints methods here: elementor/core/breakpoints/manager.php
 *
 * @param array $columns - array with column settings.
 * @return string $selectors - a string with css selectors for grid.
 */
function micemade_elements_grid( $columns = array() ) {

	// use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager.
	$bp_manager = new Breakpoints_Manager();

	// Get only active breakpoints ( get_breakpoints_config() for ALL ).
	$bpoints   = array_keys( $bp_manager->get_active_breakpoints() );
	$bpoints[] = 'desktop'; // default breakpoint.
	foreach ( $bpoints as $bp_name ) {
		$selectors_arr[ $bp_name ] = array(
			1  => 'mme-col-' . $bp_name . '-12',
			2  => 'mme-col-' . $bp_name . '-6',
			3  => 'mme-col-' . $bp_name . '-4',
			4  => 'mme-col-' . $bp_name . '-3',
			6  => 'mme-col-' . $bp_name . '-2',
			12 => 'mme-col-' . $bp_name . '-1',
		);
	}

	// Set CSS selectors string for output.
	$selectors = '';
	// Default values.
	$selectors .= ! in_array( 'columns_desktop', $columns, true ) ? 'mme-col-desktop-3 ' : ''; // 4 columns.
	$selectors .= ! in_array( 'columns_tablet', $columns, true ) ? 'mme-col-tablet-6 ' : '';   // 2 columns.
	$selectors .= ! in_array( 'columns_mobile', $columns, true ) ? 'mme-col-mobile-12 ' : '';  // 1 column.

	foreach ( $selectors_arr as $key => $value ) { // or: 'mobile'=> array( 1 => 'mme-col-tablet-12' ...)
		// Element settings for columns number.
		foreach ( $columns as $itemnum => $setting ) { // or: '3' => 'columns_tablet'.
			if ( $itemnum && strpos( $setting, $key ) ) {
				$selectors .= $value[ $itemnum ] . ' ';
			}
		}
	}

	// Additional breakpoints.
	if ( $bp_manager->has_custom_breakpoints() ) {
		$additional = array(
			$bp_manager::BREAKPOINT_KEY_MOBILE_EXTRA,
			$bp_manager::BREAKPOINT_KEY_TABLET_EXTRA,
			$bp_manager::BREAKPOINT_KEY_LAPTOP,
			$bp_manager::BREAKPOINT_KEY_WIDESCREEN,
		);
		foreach ( $additional as $add_bp ) {
			if ( in_array( $add_bp, $bpoints, true ) && ! in_array( 'columns_' . $add_bp, $columns, true ) ) {
				$suffix = '';
				if ( 'mobile_extra' === $add_bp ) {
					$suffix = '-6 ';
				} elseif ( 'tablet_extra' === $add_bp ) {
					$suffix = '-4 ';
				} elseif ( 'laptop' === $add_bp ) {
					$suffix = '-3 ';
				} elseif ( 'widescreen' === $add_bp ) {
					$suffix = '-2 ';
				}
				$selectors .= 'mme-col-' . $add_bp . $suffix;
			}
		}
	}

	return $selectors;
}

/**
 * Converting string to boolean
 *
 * @param string $value - may be a boolean.
 * @return $value
 */
function micemade_elements_to_boolean( $value ) {
	if ( ! isset( $value ) ) {
		return false;
	}
	if ( 'true' === $value || '1' === $value || 'yes' === $value ) {
		$value = true;
	} elseif ( 'false' === $value || '0' === $value || '' === $value || null === $value ) {
		$value = false;
	}

	return (bool) $value; // Make sure you do not touch the value if the value is not a string.
}
/**
 * POSTED META ( posted in / post date / post author )
 *
 * @param string $taxonomy Parameter_Description.
 * @return void
 * echo string html term links list for current post/taxonomy.
 */
function micemade_elements_posted_in( $taxonomy ) {

	$terms     = get_the_terms( get_the_ID(), $taxonomy );
	$posted_in = '';

	if ( $terms && ! is_wp_error( $terms ) ) {

		$posted_in = '<span class="posted_in micemade_elements-terms">';

		$posted_in_terms = array();
		$last_term       = end( $terms );
		foreach ( $terms as $term ) {
			$separator  = ( $term == $last_term ) ? '' : ', ';
			$posted_in .= '<a href="' . esc_url( get_term_link( $term->slug, $taxonomy ) ) . '" rel="tag" tabindex="0">' . esc_html( $term->name . ' ' . $term->ID ) . '</a>' . esc_html( $separator );
		}

		$posted_in .= '</span>';
	}

	echo wp_kses_post( $posted_in );

}

/**
 * Date for post meta function
 *
 * @return void
 */
function micemade_elements_date( $void ) {

	$date = '<span class="published"><time datetime="' . sprintf( get_the_time( esc_html__( 'Y-m-d', 'micemade_elements' ) ) ) . '">' . sprintf( get_the_time( get_option( 'date_format', 'M d, Y' ) ) ) . '</time></span>';

	echo wp_kses_post( $date );
}

/**
 * Author for post meta
 *
 * @return void
 */
function micemade_elements_author( $void ) {
	$author = '<span class="author vcard"><span class="by">' . esc_html__( 'By: ', 'micemade_elements' ) . '</span><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author_meta( 'display_name' ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</a></span>';

	echo wp_kses_post( $author );
}

/**
 * Post custom meta
 *
 * @param int   $post_id - post ID.
 * @param array $cm_fields - array of custom fields.
 * @return void
 */
function micemade_elements_custom_meta( $post_id, $cm_fields ) {
	// $cm_fields already validated as array.
	foreach ( $cm_fields as $field ) {
		// Skip if no key is entered, key doesn't exsist at all.
		if ( empty( $field['cm_key'] ) && ! metadata_exists( 'post', $post_id, $field['cm_key'] ) && empty( $field['cm_after'] ) ) {
			continue;
		}
		$output = '<span class="single-custom-meta">';

		// Custom field label.
		$output .= $field['cm_label'] ? ( '<span class="label">' . $field['cm_label'] . '</span>' ) : '';
		// Collect data for output.
		$value = get_post_meta( $post_id, $field['cm_key'], true );
		// If value is empty or is array, skip.
		if ( ! $value || is_array( $value ) ) {
			continue;
		}
		$output .= '<span class="value">';
		if ( 'timestamp' === $field['cm_type'] ) {
			$format  = get_option( 'date_format', 'F. j, Y.' );
			$output .= date_i18n( $format, $value );
		} else {
			$output .= $value;
		}
		$output .= '</span>';// .value
		$output .= $field['cm_after'] ? ( '<span class="after">' . $field['cm_after'] . '</span>' ) : '';
		$output .= '</span>';// .single-custom-meta
		echo wp_kses_post( $output );
	}
}

/**
 * Post meta ordering (using WP hook priorities)
 *
 * @param array  $meta_ordering - array created with Elementor Repeater Control.
 * @param string $taxonomy - taxonomy string.
 * @return void
 */
function micemade_elements_postmeta( $meta_ordering, $taxonomy ) {

	// $sm as single meta.
	foreach ( $meta_ordering as $key => $sm ) {

		$label    = isset( $sm['meta_sorter_label'] ) ? strtolower( str_replace( ' ', '_', $sm['meta_sorter_label'] ) ) : '';
		$enabled  = isset( $sm['meta_part_enabled'] ) ? $sm['meta_part_enabled'] : '';
		$priority = $key . '0';

		if ( isset( $sm['meta_part_enabled'] ) && 'yes' === $sm['meta_part_enabled'] ) {
			$meta_function = 'micemade_elements_' . $label;
			call_user_func( $meta_function, $taxonomy );
		}
	}

}

/**
 * PLACEHOLDER IMAGE
 *
 * @param string $placeholder_img_url - url string.
 * @return $placeholder_img_url
 */
function micemade_elements_no_image_f( $placeholder_img_url ) {
	$placeholder_img_url = MICEMADE_ELEMENTS_URL . 'assets/img/no-image.svg';
	return $placeholder_img_url;
}
add_filter( 'micemade_elements_no_image', 'micemade_elements_no_image_f' );

/**
 * Post thumbnail
 *
 * @param string $img_format - selected predefined image format.
 * @return void
 */
function micemade_elements_thumb_f( $img_format = 'thumbnail' ) {

	$gallery_shortcode = apply_filters( 'micemade_elements_gallery_ids', '' );

	$atts = array(
		'alt' => the_title_attribute( 'echo=0' ),
	);

	echo '<div class="post-thumb">'; // thumb wrapper.
	echo '<a href="' . esc_url( get_the_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" >'; // post link.
	if ( has_post_thumbnail() ) {

		the_post_thumbnail( $img_format, $atts );

	} elseif ( ! empty( $gallery_shortcode ) ) {

		$gall_image_id = $gallery_shortcode[0]; // get first image from WP gallery.
		$img_atts      = wp_get_attachment_image_src( $gall_image_id, $img_format, $atts );
		$img_url       = $img_atts[0];

		echo '<img src="' . esc_url( $img_url ) . '" class="no-image" alt="' . the_title_attribute( 'echo=0' ) . '" >';

	} else {

		echo '<img src="' . esc_url( apply_filters( 'micemade_elements_no_image', '' ) ) . '" class="no-image" alt="' . the_title_attribute( 'echo=0' ) . '" >';
	}
	echo '</a>';
	echo '</div>';
}
add_action( 'micemade_elements_thumb', 'micemade_elements_thumb_f', 10, 1 );

/**
 * POST THUMB BACKGROUND
 *
 * @param string $img_format - image format string.
 * @return void
 */
function micemade_elements_thumb_back_f( $img_format = 'thumbnail' ) {

	$gallery_shortcode = apply_filters( 'micemade_elements_gallery_ids', '' );

	$img_url = '';

	if ( has_post_thumbnail() ) {

		$img_url = get_the_post_thumbnail_url( get_the_ID(), $img_format );

	} elseif ( ! empty( $gallery_shortcode ) ) {

		$gall_image_id = $gallery_shortcode[0]; // get first image from WP gallery.
		$img_atts      = wp_get_attachment_image_src( $gall_image_id, $img_format );
		$img_url       = $img_atts[0];

	} else {

		$img_url = apply_filters( 'micemade_elements_no_image', '' );
	}

	echo '<div class="post-thumb-back" style="background-image: url(' . esc_url( $img_url ) . ');"></div><div class="post-overlay"></div>';

}
add_action( 'micemade_elements_thumb_back', 'micemade_elements_thumb_back_f', 10, 1 );

/**
 * GET GALLERY IMAGES ID's
 * get id's from WP gallery shortcode
 *
 * @return $ids
 */
function micemade_elements_gallery_ids_f() {
	global $post;
	if ( ! $post ) {
		return;
	}
	$attachment_ids = array();
	$pattern        = get_shortcode_regex();
	$ids            = array();

	// Finds the "gallery" shortcode and puts the image ids in an associative array at $matches[3].
	if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
		$count = count( $matches[3] ); // In case there is more than one gallery in the post.
		for ( $i = 0; $i < $count; $i++ ) {
			$atts = shortcode_parse_atts( $matches[3][ $i ] );
			if ( isset( $atts['ids'] ) ) {
				$att_ids = explode( ',', $atts['ids'] );
				$ids     = array_merge( $ids, $att_ids );
			}
		}
	}
	return $ids;
}
add_filter( 'micemade_elements_gallery_ids', 'micemade_elements_gallery_ids_f' );

/**
 * POSTS QUERY ARGS
 *
 * @param string  $post_type -type of the post.
 * @param integer $ppp - posts per page.
 * @param string  $order - ascending or descending sorting.
 * @param string  $orderby - sorting criteria.
 * @param string  $orderby_meta - sorting criteria by meta.
 * @param string  $taxonomy - taxonomy to filter posts.
 * @param array   $categories - categories to filter posts.
 * @param array   $post__in - handpicked posts (override other filters).
 * @param boolean $sticky - to show sticky or not.
 * @param integer $offset - posts offset.
 * @return $args
 *
 * arguments for get_posts() - DRY effort, mostly because of ajax posts
 * list of all args:https://www.billerickson.net/code/wp_query-arguments/
 */
function micemade_elements_query_args_func( $post_type = 'post', $ppp = 3, $order = 'DESC', $orderby = 'menu_order date', $orderby_meta = '', $taxonomy = 'category', $categories = array(), $post__in = array(), $sticky = false, $offset = 0 ) {

	// Defaults.
	$args = array(
		'posts_per_page'   => $ppp,
		'post_type'        => $post_type,
		'offset'           => $offset,
		'order'            => $order,
		'orderby'          => $orderby,
		'post_status'      => 'publish',
		'suppress_filters' => false,
	);

	// Order (sort) entries.
	if ( $orderby_meta ) {
		$args['meta_key'] = $orderby_meta;
	}

	// Show entries from selected taxonomy and its terms.
	if ( ! empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'         => $taxonomy,
			'field'            => 'slug',
			'operator'         => 'IN',
			'terms'            => $categories,
			'include_children' => true,
		);
	}

	// Show selected entries (excludes sticky).
	if ( ! empty( $post__in ) && ! $sticky ) {
		$args['post__in'] = $post__in;
	}

	// Show only sticky posts.
	if ( $sticky ) {
		$sticky_array = get_option( 'sticky_posts' );
		if ( ! empty( $sticky_array ) ) {
			$args['post__in'] = $sticky_array;
		}
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
add_filter( 'micemade_elements_query_args', 'micemade_elements_query_args_func', 10, 10 );

/**
 * POST TEMPLATE FOR LOOP
 *
 * @param string  $style - posts style.
 * @param string  $grid - posts grid.
 * @param string  $show_thumb - to show featured thumb image od not.
 * @param string  $img_format - image format.
 * @param boolean $meta - to show post meta or not.
 * @param boolean $meta_ordering - array of meta for display.
 * @param boolean $excerpt - to show excerpt or not.
 * @param integer $excerpt_limit - excerpt length.
 * @param string  $css_class - additional css selector.
 * @return void
 * DRY effort, mostly because of ajax posts.
 */
function micemade_elements_loop_post_func( $style = 'style_1', $grid = '', $show_thumb = true, $img_format = 'thumbnail', $meta = true, $meta_ordering = array(), $excerpt = true, $excerpt_limit = 20, $css_class = '', $taxonomy = '', $cm_fields = array(), $elm_ordering = array(), $if_readmore = true, $readmore_text = '' ) {
	$post_id = get_the_ID();
	?>
	<div class="post item <?php echo esc_attr( $grid ); ?>">

		<div class="inner-wrap">

			<?php
			if ( $show_thumb ) {

				if ( 'style_3' === $style || 'style_4' === $style ) {
					do_action( 'micemade_elements_thumb_back', $img_format );
				} else {
					do_action( 'micemade_elements_thumb', $img_format );
				}
			}
			?>

			<div class="post-text">

				<?php
				if ( empty( $elm_ordering ) ) {
					$elm_ordering = array(
						0 => 'title',
						1 => 'meta',
						2 => 'excerpt',
						3 => 'custom_meta',
					);
				}

				// Use for each to re-order post elements.
				foreach ( $elm_ordering as $key => $sm ) {

					$label = isset( $sm['elm_sorter_label'] ) ? strtolower( str_replace( ' ', '_', $sm['elm_sorter_label'] ) ) : '';
					?>
					<?php if ( 'title' === $label ) { ?>
						<h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
					<?php } ?>

					<?php if ( $meta && 'meta' === $label ) { ?>
						<div class="meta">
							<?php micemade_elements_postmeta( $meta_ordering, $taxonomy ); ?>
						</div>
					<?php } ?>

					<?php
					if ( $excerpt && 'excerpt' === $label ) {
						?>
						<p>
							<?php do_action( 'micemade_elements_excerpt', $excerpt_limit ); ?>
						</p>

						<?php
						if ( $if_readmore ) {
							$readmore = $readmore_text ? $readmore_text : __( 'Read more', 'micemade-elements' );
							echo '<a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '" class="micemade-elements-readmore ' . esc_attr( $css_class ) . ' ">' . $readmore . '</a>';
						}
						?>

					<?php } ?>

					<?php
					if ( ! empty( $cm_fields && 'custom_meta' === $label ) ) {
						?>
						<div class="custom-meta">
							<?php micemade_elements_custom_meta( $post_id, $cm_fields ); ?>
						</div>
					<?php } ?>

				<?php } // end foreach ?>

			</div>

		</div>

	</div>
	<?php
}
add_filter( 'micemade_elements_loop_post', 'micemade_elements_loop_post_func', 10, 14 );

/**
 * Custom post excerpt
 *
 * @param integer $limit - words limit for excerpt.
 * @return void
 */
function micemade_elements_excerpt_f( $limit = 20 ) {
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . ' ...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	echo wp_kses_post( $excerpt );
}
add_action( 'micemade_elements_excerpt', 'micemade_elements_excerpt_f', 10, 1 );

/**
 * TERM DATA
 *
 * @param string $taxonomy - taxonomy object.
 * @param string $term - term from taxonomy.
 * @param string $img_format - image format.
 * @return $term_data - array containing: term ID, term title, term link, term image url
 * @details retrieve term data by taxonomy and term slug
 */
function micemade_elements_term_data_f( $taxonomy, $term, $img_format = 'thumbnail' ) {

	if ( ! term_exists( $term, $taxonomy ) ) {
		return array();
	}

	$term_data = array();

	// Term data.
	$term_obj = get_term_by( 'slug', $term, $taxonomy );

	if ( is_wp_error( $term_obj ) || ! is_object( $term_obj ) ) {
		return array();
	}

	// Get term ID for term name, link and term meta for thumbnail ( WC meta "thumbnail_id ").
	$term_id      = $term_obj->term_id;
	$meta         = get_term_meta( $term_id );
	$thumbnail_id = isset( $meta['thumbnail_id'] ) ? $meta['thumbnail_id'][0] : '';

	$term_data['term_id']    = $term_id;
	$term_data['term_title'] = $term_obj->name;

	$term_link = get_term_link( $term_obj->slug, $taxonomy );
	if ( ! is_wp_error( $term_link ) ) {
		$term_data['term_link'] = $term_link;
	} else {
		$term_data['term_link'] = '';
	}

	if ( $thumbnail_id ) {
		$image_atts             = wp_get_attachment_image_src( $thumbnail_id, $img_format );
		$term_data['image_url'] = $image_atts[0];
	} else {
		$term_data['image_url'] = apply_filters( 'micemade_elements_no_image', '' );
	}

	return $term_data;

}
add_filter( 'micemade_elements_term_data', 'micemade_elements_term_data_f', 100, 3 );

/**
 * Get all meta keys.
 *
 * @return array $meta_keys
 */
function micemade_get_meta_keys() {
	global $wpdb;
	$keys      = $wpdb->get_col(
		"
			SELECT meta_key
			FROM $wpdb->postmeta
			GROUP BY meta_key
			ORDER BY meta_key"
	);
	$meta_keys = array();
	foreach ( $keys as $key => $value ) {
		$meta_keys[ $value ] = $value;
	}
	return $meta_keys;
}
