<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_Rev_Slider extends Widget_Base {

	public function get_name() {
		return 'micemade-rev-slider';
	}

	public function get_title() {
		return __( 'Micemade Revolution Slider', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade Revolution Slider', 'micemade-elements' ),
			]
		);
		
		
		$this->add_control(
			'slider',
			[
				'label'		=> esc_html__( 'Select revolution slider', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT2,
				'default'	=> array(),
				'options'	=> apply_filters('micemade_elements_rev_sliders',''),
				'multiple'	=> false
			]
		);
		
		
		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings	= $this->get_settings();
		
		$slider		= ! empty( $settings['slider'] ) ? $settings['slider'] : '';
		
		if( $slider ) {
					
			$slider_shortcode = '[rev_slider alias="'. $slider .'"]';
			
			echo do_shortcode( $slider_shortcode );
			
		}

	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}