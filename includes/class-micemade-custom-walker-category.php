<?php
namespace Elementor;
/**
 * Custom Walker for Categories List
 * https://developer.wordpress.org/reference/classes/walker_category/
 */
class Micemade_Custom_Walker_Category extends \Walker_Category {

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

		extract( $args );

		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );

		// If there are term children.
		$termchildren = get_term_children( $category->term_id, $category->taxonomy );
		if ( count( $termchildren ) > 0 ) {
			$sub_icon_html = '<i class="' . esc_attr( $sub_icon ) . ' sub-toggler"></i>';
			$has_children  = ' has-children';
		} else {
			$sub_icon_html = '';
			$has_children  = '';
		}

		// Menu item (term) link.
		$link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( 0 === $use_desc_for_title || empty( $category->description ) ) {
			$link .= 'title="' . esc_attr( sprintf( __( 'View all products filed under %s', 'micemade-elements' ), $cat_name ) ) . '"';
		} else {
			$link .= 'title="' . esc_attr( wp_strip_all_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}
		$link .= '>';
		$link .= $cat_name;
		if ( ! empty( $show_count ) ) {
			$link .= ' (' . intval( $category->count ) . ')';
		}// end menu item link.
		$link .= $sub_icon_html;
		$link .= '</a>';


		if ( 'list' === $args['style'] ) {
			$output .= "\t<li";
			$class   = 'cat-item cat-item-' . $category->term_id . $has_children;

			if ( ! empty( $current_category ) ) {
				$_current_category = get_term( $current_category, $category->taxonomy );
				if ( $category->term_id === $current_category ) {
					$class .= ' current-cat';
				} elseif ( $category->term_id === $_current_category->parent ) {
					$class .= ' current-cat-parent';
				}
			}
			$output .= ' class="' . $class . '"';
			$output .= ">$link\n";

		} else {
			$output .= "\t$link<br />\n";
		}
	}
}
