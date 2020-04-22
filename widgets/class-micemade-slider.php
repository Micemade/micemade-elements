<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;
use Elementor\Core\Settings\Manager;

class Micemade_Slider extends Widget_Base {

	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_register_script( 'micemade-slider-js', MICEMADE_ELEMENTS_URL . 'assets/js/custom/handlers/slider.js', [ 'elementor-frontend' ], '1.0.0', true );
	}

	public function get_script_depends() {
		return [ 'micemade-slider-js', 'jquery-swiper' ];
	}
	
	public function get_name() {
		return 'micemade-slider';
	}

	public function get_title() {
		return __( 'Micemade Slider', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	/**
	 * Register slides widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Slides', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		// Title - won't appear in templates content.
		$repeater->add_control(
			'slide_title',
			[
				'label'       => __( 'Slide title', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Slide Title', 'micemade-elements' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'content_type',
			[
				'label'   => __( 'Content Type', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'custom'   => __( 'Custom content', 'micemade-elements' ),
					'template' => __( 'Saved Templates', 'micemade-elements' ),
				],
				'default' => 'custom',
			]
		);

		$repeater->add_control(
			'elementor_template',
			[
				'label'       => esc_html__( 'Select Elementor Template', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => apply_filters( 'micemade_posts_array', 'elementor_library', 'id' ),
				'label_block' => true,
				'condition'   => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->start_controls_tabs(
			'slides_repeater',
			[
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);

		// Text content tab.
		$repeater->start_controls_tab(
			'slide_text_tab',
			[
				'label' => __( 'Text', 'micemade-elements' ),
			]
		);

		$repeater->add_control(
			'slide_content',
			[
				'label'      => __( 'Slide content', 'micemade-elements' ),
				'type'       => \Elementor\Controls_Manager::WYSIWYG,
				'default'    => __( 'Slide content', 'micemade-elements' ),
				'show_label' => false,
			]
		);

		$repeater->end_controls_tab();

		// Buttons tab.
		$repeater->start_controls_tab(
			'slide_buttons_tab',
			[
				'label' => __( 'Buttons', 'micemade-elements' ),
			]
		);

		$repeater->add_control(
			'button_1_text',
			[
				'label'       => __( 'Button #1 text', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Button #1 text', 'micemade-elements' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_1_link',
			[
				'label'       => __( 'Button 1 link', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
			]
		);

		$repeater->add_control(
			'button_2_text',
			[
				'label'       => __( 'Button #2 text', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'button_2_link',
			[
				'label'       => __( 'Button 2 link', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
			]
		);

		$repeater->end_controls_tab();

		// Style tab.
		$repeater->start_controls_tab(
			'slide_style_tab',
			[
				'label' => __( 'Style', 'micemade-elements' ),
			]
		);

		$repeater->add_responsive_control(
			'slide_content_padding',
			[
				'label'      => esc_html__( 'Slide padding (custom content)', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.custom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'heading_content_alignment',
			[
				'label'     => __( 'Content alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'horizontal_align',
			[
				'label'                => __( 'Horizontal align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'            => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'   => 'align-items: flex-start; text-align: left',
					'center' => 'align-items: center; text-align: center',
					'right'  => 'align-items: flex-end; text-align: right',
				],

			]
		);

		$repeater->add_control(
			'vertical_align',
			[
				'label'                => __( 'Vertical align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => [
					'top'    => [
						'title' => __( 'Top', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors'            => [
					'{{WRAPPER}} {{CURRENT_ITEM}} ' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],

			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		// After tabs - each slide background and overlay controls.
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'      => 'slide_background',
				'label'     => __( 'Slide background', 'micemade-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .slide-background',
				'condition' => [
					'content_type' => 'custom',
				],
			]
		);
		$repeater->add_control(
			'slide_overlay_color',
			[
				'label'     => __( 'Overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'content_type' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->add_control(
			'slide_overlay_blend_mode',
			[
				'label'     => __( 'Blend Mode', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => __( 'Normal', 'micemade-elements' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn'  => 'Color Burn',
					'hue'         => 'Hue',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'exclusion'   => 'Exclusion',
					'luminosity'  => 'Luminosity',
				],
				'condition' => [
					'content_type' => 'custom',
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slides',
			[
				'label'       => __( 'Slider items', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'slide_title'   => __( 'Slide 1 Title', 'micemade-elements' ),
						'slide_content' => __( 'Item content. Click the edit button to change this text.', 'micemade-elements' ),
					],
					[
						'slide_title'   => __( 'Slide 2 Title', 'micemade-elements' ),
						'slide_content' => __( 'Item content. Click the edit button to change this text.', 'micemade-elements' ),
					],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->add_control(
			'heading_slider_settings',
			[
				'label'     => __( 'Slider settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Pagination.
		$this->add_control(
			'pagination',
			[
				'label'              => __( 'Slider pagination', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => [
					'none'        => __( 'None', 'micemade-elements' ),
					'bullets'     => __( 'Bullets', 'micemade-elements' ),
					'progressbar' => __( 'Progress bar', 'micemade-elements' ),
					'fraction'    => __( 'Fraction', 'micemade-elements' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'     => __( 'Pagination color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar-fill'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-fraction'      => 'color: {{VALUE}};',
				],
				'condition' => [
					'pagination!' => 'none',
				],
			]
		);

		// Slider navigation.
		$this->add_control(
			'slider_arrows',
			[
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'micemade-elements' ),
				'label_on'           => __( 'Yes', 'micemade-elements' ),
				'default'            => 'yes',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'buttons_color',
			[
				'label'     => __( 'Navigation buttons color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'buttons!' => '',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no' => __( 'No', 'micemade-elements' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no' => __( 'No', 'micemade-elements' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => __( 'Pause on Interaction', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no' => __( 'No', 'micemade-elements' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'micemade-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => __( 'Infinite Loop', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no' => __( 'No', 'micemade-elements' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => __( 'Effect', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'micemade-elements' ),
					'fade' => __( 'Fade', 'micemade-elements' ),
				],
				'condition' => [
					'slides_to_show' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Animation Speed', 'micemade-elements' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => __( 'Left', 'micemade-elements' ),
					'rtl' => __( 'Right', 'micemade-elements' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_global_style',
			[
				'label' => __( 'Slides global style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slide_global_padding',
			[
				'label'      => esc_html__( 'Global padding (custom content)', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slide_content_spacing',
			[
				'label'      => __( 'Content elements spacing', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => '20',
				],
				'range'      => [
					'px' => [
						'max'  => 300,
						'min'  => 0,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide > *' => 'margin-top:{{SIZE}}{{UNIT}};margin-bottom:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_slide_title',
			[
				'label'     => __( 'Title', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slide-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slide_title_typography',
				'selector' => '{{WRAPPER}} .slide-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label'     => __( 'Content', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Content color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slide-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'slide_content_typography',
				'selector' => '{{WRAPPER}} .slide-content',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			[
				'label' => __( 'Buttons global style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'buttons_padding',
			[
				'label'      => esc_html__( 'Buttons padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .slide-buttons a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'buttons_back_color',
			[
				'label'     => __( 'Buttons back color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#30aa40',
				'selectors' => [
					'{{WRAPPER}} .slide-buttons a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'buttons_back_hover_color',
			[
				'label'     => __( 'Buttons hover back color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .slide-buttons a:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'buttons_font_color',
			[
				'label'     => __( 'Buttons font color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .slide-buttons a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'buttons_hover_font_color',
			[
				'label'     => __( 'Buttons hover font color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .slide-buttons a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'buttons_border_width',
			[
				'label'      => __( 'Buttons border width', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => '0',
				],
				'range'      => [
					'px' => [
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .slide-buttons a' => 'border-width:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'buttons_border_radius',
			[
				'label'      => __( 'Buttons border radius', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => '3',
				],
				'range'      => [
					'px' => [
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .slide-buttons a' => 'border-radius:{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'buttons_border_color',
			[
				'label'     => __( 'Buttons border color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .slide-buttons a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render slide buttons
	 *
	 * @param string $index - slide index.
	 * @param array  $button_link - slide button link.
	 * @param string $button_text - slide button text.
	 * @param string $button_class - css class.
	 * @return void
	 */
	private function _render_buttons( $index = '', $button_link = [], $button_text = '', $button_class = '' ) {

		$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'button_list', $index );

		$link     = ! empty( $button_link['url'] ) ? $button_link['url'] : '#';
		$link_key = 'link_' . $index;

		// Add general class attribute.
		$this->add_render_attribute( $link_key, 'class', 'micemade-button ' . $button_class );

		// Add link attribute.
		$this->add_render_attribute( $link_key, 'href', $link );
		// Add target attribute.
		if ( $button_link['is_external'] ) {
			$this->add_render_attribute( $link_key, 'target', '_blank' );
		}
		// Add nofollow attribute.
		if ( $button_link['nofollow'] ) {
			$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
		}

		echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
		echo esc_html( $button_text );
		echo '</a>';

	}


	/**
	 * Render slides widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$pagination    = $settings['pagination'];
		$slider_arrows = $settings['slider_arrows'];

		if ( $settings['slides'] ) {

			// CSS classes for main slider container.
			$this->add_render_attribute(
				[
					'container' => [
						'class' => [
							'swiper-container',
							'micemade-elements_slider',
							//'micemade-elements__sliders',
						],
					],
				]
			);

			// Slider container.
			echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';

			echo '<div class="swiper-wrapper elementor-image-carousel">';

			foreach ( $settings['slides'] as $index => $item ) {

				$type = $item['content_type'];

				echo '<div class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . ' swiper-slide ' . esc_attr( $type ) . '">';

				if ( 'template' === $type ) {

					if ( ! empty( $item['elementor_template'] ) ) {
						$template_id = $item['elementor_template'];
						$frontend    = new Frontend;
						echo $frontend->get_builder_content( $template_id, true );
					}
				} elseif ( 'custom' === $type ) {

					// Slide title and text.
					echo '<h2 class="slide-title">' . esc_html( $item['slide_title'] ) . '</h2>';
					echo '<div class="slide-content">' . wp_kses_post( $item['slide_content'] ) . '</div>';

					// Slide buttons.
					echo '<div class="slide-buttons">';
					if ( $item['button_1_text'] ) {
						$this->_render_buttons( $index, $item['button_1_link'], $item['button_1_text'], 'button-1' );
					}
					if ( $item['button_2_text'] ) {
						$this->_render_buttons( $index, $item['button_2_link'], $item['button_2_text'], 'button-2' );
					}
					echo '</div>';

					echo '<div class="slide-overlay"></div>';
					echo '<div class="slide-background"></div>';

				}

				echo '</div>';
			}

			echo '</div>'; // .swiper-wrapper

			// Pagination and arrows.
			if ( 'none' !== $pagination ) {
				echo '<div class="swiper-pagination"></div>';
			}
			if ( $slider_arrows ) {
				echo '<div class="swiper-button-next" screen-reader>' . esc_html__( 'Next', 'micemade-elements' ) . '</div>';
				echo '<div class="swiper-button-prev" screen-reader>' . esc_html__( 'Previous', 'micemade-elements' ) . '</div>';
			}

			echo '</div>'; // .swiper-container

		}
		?>

		<?php
	}

	/**
	 * Render slides widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	// protected function _content_template() {}
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Slider() );
