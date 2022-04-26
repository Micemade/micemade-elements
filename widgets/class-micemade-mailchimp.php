<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Stack;

class Micemade_Mailchimp extends Widget_Base {

	public function get_name() {
		return 'micemade-mc4wp-forms';
	}

	public function get_title() {
		return __( 'Micemade MailChimp 4 WP Forms', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-mailchimp';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'MailChimp for WP', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'mc4wp_slug',
			[
				'label'       => esc_html__( 'Select Newsletter Form', 'micemade-elements' ),
				'description' => esc_html__( '"MailChimp for WP" - with free version only one form is available - purchase Premuim version of "MailChimp 4 WP" for more forms.', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array_merge(
					[ '' => esc_html__( 'Select the newlsetter form', 'micemade-elements' ) ],
					apply_filters( 'micemade_posts_array', 'mc4wp-form' )
				),
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'       => esc_html__( 'Orientation', 'micemade-elements' ),
				'description' => esc_html__( 'stack form elements vertically or horizontally', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'row',
				'options'     => [
					'row'    => esc_html__( 'Horizontal', 'micemade-elements' ),
					'column' => esc_html__( 'Vertical', 'micemade-elements' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .micemade-elements_mc4wp .mc4wp-form-fields' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Form Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					],
					'stretch'    => [
						'title' => __( 'Justify', 'micemade-elements' ),
						'icon'  => 'fa fa-align-justify',
					],

				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mc4wp-form-fields' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'form_width',
			[
				'label'          => __( 'Form Width', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => '',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%' ],
				'range'          => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .mc4wp-form-fields' => 'width: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_responsive_control(
			'email_input_width',
			[
				'label'     => __( 'Email Input Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
					'size' => '',
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mc4wp-form-fields input[type=email]' => 'width: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label'      => esc_html__( 'Input elements margins', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mc4wp-form-fields input, {{WRAPPER}} .mc4wp-form-fields select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings   = $this->get_settings();
		$mc4wp_slug = $settings['mc4wp_slug'];

		if ( ! empty( $mc4wp_slug ) ) {

			if ( $post = get_page_by_path( $mc4wp_slug, OBJECT, 'mc4wp-form' ) ) {
				$id = $post->ID;
			} else {
				$id = 0;
			}

			echo'<div class="micemade-elements_mc4wp elementor-shortcode">';

				echo do_shortcode( '[mc4wp_form id="' . $id . '"]' );

			echo '</div>';
		}

	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Mailchimp() );
