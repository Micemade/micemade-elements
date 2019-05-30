<?php
/**
 * CUSTOM WP NAV MENU - no walker
 *
 * @param [string] $theme_location Parameter_Description
 * @return html
 *
 * @details custom replacement menu for wp_nav_menu()
 * with a "little" help from :
 * David Baker - https://dtbaker.net/
 * https://stackoverflow.com/questions/8840319/build-a-tree-from-a-flat-array-in-php
 * https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/
 */
namespace Elementor;

class Micemade_Nav_Html {

	public $menu_id;

	public function __construct( $menu_id, $layout ) {

		$this->$menu_id = $menu_id;
		$this->$layout  = $layout;

		$this->get_menu( $this->$menu_id, $this->$layout );
	}

	public function get_menu( $menu_id, $layout ) {

		$flat_menu_array = wp_get_nav_menu_items( $menu_id );

		// Another layer of checking.
		if ( empty( $flat_menu_array ) ) {
			return;
		}

		// Extract only values needed
		// look up for all values with print($flat_menu_array) ).
		$menu = array();
		foreach ( $flat_menu_array as $m ) {
			$menu[ $m->ID ]               = array();
			$menu[ $m->ID ]['ID']         = $m->ID;
			$menu[ $m->ID ]['title']      = $m->title;
			$menu[ $m->ID ]['url']        = $m->url;
			$menu[ $m->ID ]['target']     = $m->target;
			$menu[ $m->ID ]['xfn']        = $m->xfn;
			$menu[ $m->ID ]['desc']       = $m->description;
			$menu[ $m->ID ]['classes']    = $m->classes;
			$menu[ $m->ID ]['attr_title'] = $m->attr_title;
			$menu[ $m->ID ]['parent']     = $m->menu_item_parent;
			$menu[ $m->ID ]['curr']       = $m->object_id;
			$menu[ $m->ID ]['children']   = array();

			$item_meta                 = get_post_meta( $m->ID );
			$menu[ $m->ID ]['urlargs'] = isset( $item_meta['menu-item-urlargs'] ) ? $item_meta['menu-item-urlargs'][0] : '';
			$menu[ $m->ID ]['mega']    = isset( $item_meta['menu-item-select-mega'] ) ? $item_meta['menu-item-select-mega'][0] : '';
			$menu[ $m->ID ]['icon']    = isset( $item_meta['menu-item-select-icon'] ) ? $item_meta['menu-item-select-icon'][0] : '';
		}

		// MAKE MULTIDIMENSIONAL ARRAY FROM FLAT ARRAY / CREATE MENU TREE.
		$menu_tree = $this->build_tree( $menu );
		// if error in build_tree function, abort.
		if ( empty( $menu_tree ) ) {
			return;
		}

		// If menu is vertical.
		$menu_class = ( 'vertical' === $layout ) ? ' sm-vertical' : '';

		// Start menu html output.
		echo '<ul class="micemade-elements-nav-menu sm sm-default' . $menu_class . '">';
		//echo '<ul>';

		$this->build_menu( $menu_tree );

		echo '</ul>';
	}

	/**
	 * Build menu
	 *
	 * @param array $menu_tree
	 * @return html
	 * Recursive function to print out multidimensional array
	 * as menu in nested unordered list format
	 */
	private function build_menu( array &$menu_tree ) {

		foreach ( $menu_tree as $id => $branch ) {

			$this->menu_item_elements( $branch );

			$children = ! empty( $branch['children'] ) ? true : false;
			$mega     = ! empty( $branch['mega'] ) ? $branch['mega'] : '';

			if ( $children || isset( $mega ) ) {

				// If megamenu is set in nav_menu_item custom meta.
				if ( $mega ) {
					echo '<ul class="mega-menu" data-subtype="megamenu">';
					echo apply_filters( 'haumea_render_mega_menu', $mega );
					echo '</ul>';
				} elseif ( $children ) {
					echo '<ul class="sub-menu" data-subtype="submenu">';
					$this->build_menu( $branch['children'] );
					echo '</ul>';
				}

			}

			echo '</li>';
		}

	}

	/**
	 * Build tree menu ( multidimensional array )
	 *
	 * @param array $elements
	 * @param integer $parent_id
	 * @return $branch
	 */
	private function build_tree( array &$elements, $parent_id = 0 ) {
		$branch = array();

		foreach ( $elements as $element ) {
			if ( $element['parent'] == $parent_id ) {
				$children = $this->build_tree( $elements, $element['ID'] );
				if ( $children ) {
					$element['children'] = $children;
				}
				$branch[ $element['ID'] ] = $element;
				//unset($elements[$element['ID']]);
			}
		}
		return $branch;
	}

	/**
	 * Menu item elements
	 *
	 * @param array $item
	 * @return $output
	 */
	private function menu_item_elements( $item ) {

		// Current page id.
		$current_page_id = get_queried_object_id();

		$title      = ! empty( $item['title'] ) ? $item['title'] : '';
		$attr_title = ! empty( $item['attr_title'] ) ? ' title="' . esc_attr( $item['attr_title'] ) . '"' : '';
		$url        = ! empty( $item['url'] ) ? $item['url'] : '#';
		$target     = ! empty( $item['target'] ) ? ' target="' . esc_attr( $item['target'] ) . '"' : '';
		$xfn        = ! empty( $item['xfn'] ) ? ' rel="' . esc_attr( $item['xfn'] ) . '"' : '';
		$desc       = ! empty( $item['desc'] ) ? $item['desc'] : '';
		$classes    = ! empty( $item['classes'] ) ? implode( ' ', $item['classes'] ) : '';
		$current    = ! empty( $item['curr'] ) ? intval( $item['curr'] ) : '';
		$children   = ! empty( $item['children'] ) ? $item['children'] : array();
		// Custom meta.
		$urlargs = ! empty( $item['urlargs'] ) ? $item['urlargs'] : '';
		$mega    = ! empty( $item['mega'] ) ? $item['mega'] : '';
		$icon    = ! empty( $item['icon'] ) ? $item['icon'] : '';

		// CSS classes for menu list item
		$item_class = '';
		if ( $current_page_id === $current ) {
			$item_class .= ' current';
		}
		$item_class .= $this->subs_css( $children, $mega );

		// Output menu item elements.
		$output  = '<li class="menu-item' . esc_attr( $item_class ) . '">';
		$output .= '<a href="' . esc_url( $url . $urlargs ) . '" class="mme-navmenu-link ' . esc_attr( $classes ) . '" ' . $attr_title . $target . $xfn . '>';
		$output .= ( $icon ? '<i class="typcn ' . esc_attr( $icon ) . '"></i>' : '' );
		$output .= ( $title ? '<span class="title">' . esc_html( $title ) . '</span>' : '' );
		$output .= ( $desc ? '<span class="desc">' . esc_html( $desc ) . '</span>' : '' );
		$output .= '</a>';

		echo wp_kses_post( $output );
	}

	/**
	 * CSS Class if there are children
	 *
	 * @param array $children
	 * @return $css
	 */
	function subs_css( $children, $mega ) {
		$css = '';
		if ( $mega ) {
			$css = ' has-mega-menu';
		} elseif ( ! empty( $children ) ) {
			$css = ' has-sub-menu';
		}
		return $css;
	}

} // end class Micemade_Nav_Html
