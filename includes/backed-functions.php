<?php
/**
 * Deprecated methods, backed up for eventual future use
 * in case of errors, replace methods widgets_registered() and widgets_list()
 *
 * @since 0.3.7
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Old_Micemade_Elements {
	public function widgets_registered() {

		// get our own widgets up and running:
		// copied from widgets-manager.php
		if ( class_exists( 'Elementor\Plugin' ) ) {

			if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {

				$the_elementor = Elementor\Plugin::instance();

				if ( isset( $the_elementor->widgets_manager ) ) {

					if ( method_exists( $the_elementor->widgets_manager, 'register_widget_type' ) ) {

						$widgets = self::$instance->widgets_list();

						foreach ( $widgets as $file => $class ) {
							$widget_file   = 'plugins/elementor/' . $file . '.php';
							$template_file = locate_template( $widget_file );

							if ( ! $template_file || ! is_readable( $template_file ) ) {
								$template_file = plugin_dir_path( __FILE__ ) . 'widgets/' . $file . '.php';
							}

							if ( $template_file && is_readable( $template_file ) ) {
								require_once $template_file;

								$widget_class = 'Elementor\\' . $class;

								$the_elementor->widgets_manager->register_widget_type( new $widget_class );
							}
						} // end foreach.
					}
					// end if( method_exists.
				}
				// end if ( isset( $the_elementor.
			}
			// end if ( is_callable( 'Elementor\Plugin'.
		}
		//if ( class_exists( 'Elementor\Plugin' ) ).
	}

	public function widgets_list() {

		$widgets_list = array(
			'micemade-posts-grid' => 'Micemade_Posts_Grid',
		);

		if ( MICEMADE_ELEMENTS_WOO_ACTIVE ) {
			$widgets_list['micemade-wc-products']        = 'Micemade_WC_Products';
			$widgets_list['micemade-wc-products-slider'] = 'Micemade_WC_Products_Slider';
			$widgets_list['micemade-wc-single-product']  = 'Micemade_WC_Single_Product';
			$widgets_list['micemade-wc-categories']      = 'Micemade_WC_Categories';
		}

		if ( MICEMADE_ELEMENTS_REVSLIDER_ON ) {
			$widgets_list['micemade-rev-slider'] = 'Micemade_Rev_Slider';
		}

		return $widgets_list;

	}
}
