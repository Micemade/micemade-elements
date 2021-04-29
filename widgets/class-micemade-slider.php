<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Schemes\Typography;

class Micemade_Slider extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		if ( ! wp_script_is( 'micemade-slider-js', 'registered' ) ) {
			wp_register_script( 'micemade-slider-js', MICEMADE_ELEMENTS_URL . 'assets/js/custom/handlers/slider.js', array( 'elementor-frontend' ), '1.0.0', true );
		}
		if ( ! wp_script_is( 'micemade-slider-js', 'enqueued' ) ) {
			wp_enqueue_script( 'micemade-slider-js' );
		}
	}

	public function get_script_depends() {
		return array( 'micemade-slider-js', 'jquery-swiper' );
	}

	public function get_name() {
		return 'micemade-slider';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'micemade', 'carousel', 'slider', 'templates' ];
	}

	public function get_title() {
		return __( 'Micemade Slider', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-slideshow';
	}

	public function get_categories() {
		return array( 'micemade_elements' );
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
			array(
				'label' => __( 'Slides', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new \Elementor\Repeater();

		// Title - won't appear in templates content.
		$repeater->add_control(
			'slide_title',
			array(
				'label'       => __( 'Slide title', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Slide Title', 'micemade-elements' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'content_type',
			array(
				'label'   => __( 'Content Type', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'custom'   => __( 'Custom content', 'micemade-elements' ),
					'template' => __( 'Saved Templates', 'micemade-elements' ),
				),
				'default' => 'custom',
			)
		);

		$repeater->add_control(
			'elementor_template',
			array(
				'label'       => esc_html__( 'Select Elementor Template', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => apply_filters( 'micemade_posts_array', 'elementor_library', 'id' ),
				'label_block' => true,
				'condition'   => array(
					'content_type' => 'template',
				),
			)
		);

		$repeater->start_controls_tabs(
			'slides_repeater',
			array(
				'condition' => array(
					'content_type' => 'custom',
				),
			)
		);

		// Text content tab.
		$repeater->start_controls_tab(
			'slide_text_tab',
			array(
				'label' => __( 'Text', 'micemade-elements' ),
			)
		);

		$repeater->add_control(
			'slide_content',
			array(
				'label'      => __( 'Slide content', 'micemade-elements' ),
				'type'       => \Elementor\Controls_Manager::WYSIWYG,
				'default'    => __( 'Slide content', 'micemade-elements' ),
				'show_label' => false,
			)
		);

		$repeater->end_controls_tab();

		// Buttons tab.
		$repeater->start_controls_tab(
			'slide_buttons_tab',
			array(
				'label' => __( 'Buttons', 'micemade-elements' ),
			)
		);

		$repeater->add_control(
			'button_1_text',
			array(
				'label'       => __( 'Button #1 text', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Button #1 text', 'micemade-elements' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'button_1_link',
			array(
				'label'       => __( 'Button 1 link', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
			)
		);

		$repeater->add_control(
			'button_2_text',
			array(
				'label'       => __( 'Button #2 text', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'button_2_link',
			array(
				'label'       => __( 'Button 2 link', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
			)
		);

		$repeater->end_controls_tab();

		// Style tab.
		$repeater->start_controls_tab(
			'slide_style_tab',
			array(
				'label' => __( 'Style', 'micemade-elements' ),
			)
		);

		$repeater->add_responsive_control(
			'slide_content_padding',
			array(
				'label'      => esc_html__( 'Slide padding (custom content)', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.custom' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$repeater->add_control(
			'slide_height',
			array(
				'label'              => __( 'Slide height', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
				'selectors'   => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-wrapper ' => 'height: {{VALUE}}px;',
				),
			)
		);

		$repeater->add_control(
			'heading_content_alignment',
			array(
				'label'     => __( 'Content alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$repeater->add_control(
			'horizontal_align',
			array(
				'label'                => __( 'Horizontal align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-wrapper' => '{{VALUE}}',
				),
				'selectors_dictionary' => array(
					'left'   => 'align-items: flex-start; text-align: left',
					'center' => 'align-items: center; text-align: center',
					'right'  => 'align-items: flex-end; text-align: right',
				),

			)
		);

		$repeater->add_control(
			'vertical_align',
			array(
				'label'                => __( 'Vertical align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .content-wrapper' => 'justify-content: {{VALUE}}',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),

			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		// After tabs - each slide background and overlay controls.
		$repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'slide_background',
				'label'     => __( 'Slide background', 'micemade-elements' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .slide-background',
				'separator' => 'before',
				'condition' => array(
					'content_type' => 'custom',
				),
			)
		);
		$repeater->add_control(
			'slide_overlay_color',
			array(
				'label'     => __( 'Overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'content_type' => 'custom',
				),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .slide-overlay' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'slide_overlay_blend_mode',
			array(
				'label'     => __( 'Blend Mode', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
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
				),
				'condition' => array(
					'content_type' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .slick-slide-inner .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'Slider items', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'slide_title'   => __( 'Dummy slide no.1', 'micemade-elements' ),
						'slide_content' => __( 'Change slide title, content, button(s), and style, or select pre-made Elementor Templates.', 'micemade-elements' ),
					),
					array(
						'slide_title'   => __( 'Dummy slide no.2', 'micemade-elements' ),
						'slide_content' => __( 'Change slide title, content, button(s), and style, or select pre-made Elementor Templates.', 'micemade-elements' ),
					),
				),
				'title_field' => '{{{ slide_title }}}',
			)
		);

		$this->add_control(
			'heading_slider_settings',
			array(
				'label'     => __( 'Slider settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Pagination.
		$this->add_control(
			'pagination',
			array(
				'label'              => __( 'Slider pagination', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => array(
					'none'        => __( 'None', 'micemade-elements' ),
					'bullets'     => __( 'Bullets', 'micemade-elements' ),
					'progressbar' => __( 'Progress bar', 'micemade-elements' ),
					'fraction'    => __( 'Fraction', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		// Slider navigation.
		$this->add_control(
			'buttons',
			array(
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'micemade-elements' ),
				'label_on'           => __( 'Yes', 'micemade-elements' ),
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);


		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'autplay_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>No value under 1000 (ms) will be accepted.</small>' ),
				'content_classes' => 'your-class',
				'condition'       => array(
					'autoplay' => 'yes',
				),
				'separator'       => 'after',
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'infinite',
			array(
				'label'              => __( 'Infinite Loop', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'effect',
			array(
				'label'              => __( 'Effect', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => __( 'Slide', 'micemade-elements' ),
					'fade'  => __( 'Fade', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Animation Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'              => __( 'Direction', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'ltr',
				'options'            => array(
					'ltr' => __( 'Left', 'micemade-elements' ),
					'rtl' => __( 'Right', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		// Section elements style.
		$this->start_controls_section(
			'section_slider_elements_style',
			array(
				'label' => esc_html__( 'Slider elements style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Pagination color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-progressbar-fill'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-fraction'      => 'color: {{VALUE}};',
				),
				'default'   => '#333333',
				'separator' => 'after',
				'condition' => array(
					'pagination!' => 'none',
				),
			)
		);

		$this->add_control(
			'buttons_color',
			array(
				'label'     => __( 'Arrows icon color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-next:before' => 'color: {{VALUE}};',
				),
				'default'   => '#2F2E2EC7',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);
		$this->add_control(
			'buttons_bckcolor',
			array(
				'label'     => __( 'Arrows button color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'background-color: {{VALUE}};',
				),
				'default'   => '#FFFFFF7A',
				'separator' => 'after',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'nav_arrows_vert_position',
			array(
				'label'       => __( 'Arrows vertical position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
				'default'     => array(
					'size' => 50,
					'unit' => '%',
				),
				'range'       => array(
					'%'  => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$this->add_control(
			'arrow_icon',
			array(
				'label'       => __( 'Arrow icons', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'include'     => array(
					'fa fa-arrow-left',
					'fa fa-angle-left',
					'fa fa-angle-double-left',
					'fa fa-arrow-circle-left',
					'fa fa-caret-left',
					'fa fa-chevron-left',
					'fa fa-chevron-circle-left',
					'fa fa-long-arrow-left',
				),
				'default'     => 'fa fa-chevron-left',
			)
		);

		$this->add_responsive_control(
			'nav_arrows_size',
			array(
				'label'       => __( 'Arrows icon size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 12,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-next:before, {{WRAPPER}} .swiper-button-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'nav_arrows_back_size',
			array(
				'label'       => __( 'Arrows button size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 32,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-next:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$this->add_control(
			'nav_arrows_back_roundness',
			array(
				'label'       => __( 'Arrows button roundness', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 12,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'     => __( 'Arrow button border' ),
				'name'      => 'arrow_button_border',
				'selector'  => '{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after',
				'separator' => 'after',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);

		// Slider navigation.
		$this->add_control(
			'show_icon_fix',
			array(
				'label'     => esc_html__( 'Fix icon position', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'fix_icon_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Some icons may misalign with it\'s button. Enable the control above to fix the arrow icon position.' ),
				'content_classes' => 'your-class',
			)
		);

		$this->add_responsive_control(
			'nav_arrows_fix_position',
			array(
				'label'       => __( 'Fix arrow position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
				'default'     => array(
					'size' => 0,
					'unit' => '%',
				),
				'range'       => array(
					'%' => array(
						'max'  => 50,
						'min'  => -50,
						'step' => 1,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev:before' => 'margin-top: {{SIZE}}{{UNIT}};',
					' {{WRAPPER}} .swiper-button-next:before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!'      => '',
					'show_icon_fix' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			array(
				'label' => __( 'Custom slides style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'custom_slides_padding',
			array(
				'label'      => esc_html__( 'Padding (custom content)', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => [
					'top'      => '60',
					'right'    => '60',
					'bottom'   => '60',
					'left'     => '60',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide .content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'slide_content_spacing',
			array(
				'label'      => __( 'Content elements spacing', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '20',
					'unit' => 'px',
				),
				'range'      => array(
					'px' => array(
						'max'  => 300,
						'min'  => 0,
						'step' => 1,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide .content-wrapper > .slide-title, {{WRAPPER}} .swiper-slide .content-wrapper > .slide-content' => 'padding-bottom:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_slide_title',
			array(
				'label'     => __( 'Title', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slide-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slide_title_typography',
				'selector' => '{{WRAPPER}} .slide-title',
				'scheme'   => Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_control(
			'heading_content',
			array(
				'label'     => __( 'Content', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => __( 'Content color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .slide-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'slide_content_typography',
				'selector' => '{{WRAPPER}} .slide-content',
				'scheme'   => Typography::TYPOGRAPHY_1,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			array(
				'label' => __( 'Buttons global style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'buttons_padding',
			array(
				'label'      => esc_html__( 'Buttons padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .slide-buttons a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'buttons_back_color',
			array(
				'label'     => __( 'Buttons back color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#30aa40',
				'selectors' => array(
					'{{WRAPPER}} .slide-buttons a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buttons_back_hover_color',
			array(
				'label'     => __( 'Buttons hover back color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slide-buttons a:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buttons_font_color',
			array(
				'label'     => __( 'Buttons font color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .slide-buttons a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buttons_hover_font_color',
			array(
				'label'     => __( 'Buttons hover font color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => array(
					'{{WRAPPER}} .slide-buttons a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'buttons_border_width',
			array(
				'label'      => __( 'Buttons border width', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '0',
				),
				'range'      => array(
					'px' => array(
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .slide-buttons a' => 'border-width:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'buttons_border_radius',
			array(
				'label'      => __( 'Buttons border radius', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => '3',
				),
				'range'      => array(
					'px' => array(
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .slide-buttons a' => 'border-radius:{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'buttons_border_color',
			array(
				'label'     => __( 'Buttons border color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .slide-buttons a' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover animation', 'micemade-elements' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
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
	private function _render_buttons( $index = '', $button_link = array(), $button_text = '', $button_class = '', $hover_animation ) {

		$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'button_list', $index );

		$link     = ! empty( $button_link['url'] ) ? $button_link['url'] : '#';
		$link_key = 'link_' . $index;

		// Add general class attribute.
		$this->add_render_attribute( $link_key, 'class', 'micemade-button ' . $button_class );
		// Add hover animation class.
		if ( $hover_animation ) {
			$this->add_render_attribute( $link_key, 'class', 'elementor-animation-' . $hover_animation );
		}

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

		$pagination      = $settings['pagination'];
		$buttons         = $settings['buttons'];
		$arrow_icon      = $settings['arrow_icon'];
		$hover_animation = $settings['hover_animation'];

		if ( $settings['slides'] ) {

			// CSS classes for main slider container.
			$this->add_render_attribute(
				array(
					'container' => array(
						'class' => array(
							'swiper-container',
							'micemade-elements_slider big-slider',
						),
						'id'    => 'slider-' . $this->get_id(),
					),
				)
			);

			// Slider container.
			echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';

			echo '<div class="swiper-wrapper elementor-image-carousel">';

			foreach ( $settings['slides'] as $index => $item ) {

				$type    = $item['content_type'];
				$item_id = $item['_id'];
				$this->add_render_attribute(
					array(
						'repeater' . $item_id => array(
							'class' => array(
								'swiper-slide',
								'elementor-repeater-item-' . esc_attr( $item_id ),
								esc_attr( $type ),
							),
							'id'    => esc_attr( $item_id ),
						),
					)
				);

				echo '<div ' . $this->get_render_attribute_string( 'repeater' . $item_id ) . '>';

				if ( 'template' === $type ) {

					if ( ! empty( $item['elementor_template'] ) ) {
						$template_id = $item['elementor_template'];
						$frontend    = new Frontend();
						echo $frontend->get_builder_content( $template_id, true );
					}
				} elseif ( 'custom' === $type ) {

					echo '<div class="content-wrapper">';
					// Slide title and text.
					echo '<h2 class="slide-title">' . esc_html( $item['slide_title'] ) . '</h2>';
					echo '<div class="slide-content">' . wp_kses_post( $item['slide_content'] ) . '</div>';

					// Slide buttons.
					echo '<div class="slide-buttons">';
					if ( $item['button_1_text'] ) {
						$this->_render_buttons( $index, $item['button_1_link'], $item['button_1_text'], 'button-1', $hover_animation );
					}
					if ( $item['button_2_text'] ) {
						$this->_render_buttons( $index, $item['button_2_link'], $item['button_2_text'], 'button-2', $hover_animation );
					}
					echo '</div>';

					echo '<div class="slide-overlay"></div>';
					echo '<div class="slide-background"></div>';
					echo '</div>'; // end content wrapper.

				}

				echo '</div>';
			}

			echo '</div>'; // .swiper-wrapper

			// Pagination and arrows.
			if ( 'none' !== $pagination ) {
				echo '<div class="swiper-pagination"></div>';
			}
			if ( $buttons ) {
				echo '<div class="swiper-button-next ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Next', 'micemade-elements' ) . '</span></div>';
				echo '<div class="swiper-button-prev ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Previous', 'micemade-elements' ) . '</span></div>';
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
