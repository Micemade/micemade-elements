<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Micemade_WC_Categories extends Widget_Base {

	/**
	 * Array of additional query args
	 *
	 * @var array
	 */
	public $add_query_args_array;

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
		return 'micemade-wc-categories';
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
		return array( 'micemade', 'woocommerce', 'categories', 'grid', 'slider' );
	}

	public function get_title() {
		return __( 'Micemade WC Categories', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	protected function _register_controls() {

		// $slider_style = new Micemade_Slider_Style_Controls();
		// $slider_style->_register_controls();

		$this->start_controls_section(
			'section_main',
			array(
				'label' => esc_html__( 'Micemade WC Categories', 'micemade-elements' ),
			)
		);

		// Get product categories and first default category item.
		$get_cats    = apply_filters( 'micemade_elements_terms', 'product_cat' );
		$default_cat = ! empty( $get_cats ) ? array_key_first( $get_cats ) : [];

		$this->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => [ $default_cat ],
				'options'     => $get_cats,
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_query_args_array = array(
			'on_sale'      => esc_html__( 'Show only products on sale', 'micemade-elements' ),
			'featured'     => esc_html__( 'Show only featured products', 'micemade-elements' ),
			'best_sellers' => esc_html__( 'Order by best seller products first', 'micemade-elements' ),
			'best_rated'   => esc_html__( 'Order by best rated products', 'micemade-elements' ),
		);

		$this->add_control(
			'add_query_args',
			array(
				'label'       => __( 'Product filters (per category)', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => [],
				'options'     => $this->add_query_args_array,
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_control(
			'add_query_text',
			array(
				'label'       => __( 'Text for filters', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Featured products in ...', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => [
					'add_query_args!' => [],
				],
			)
		);

		$this->add_control(
			'add_query_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Prepend your custom text for additional filters to category title.</small>' ),
				'content_classes' => 'your-class',
				'separator'       => 'after',
				'condition'       => array(
					'add_query_args!' => array(),
				),
			)
		);

		$this->add_control(
			'grid_or_slider',
			array(
				'label'   => __( 'Grid or slider', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'   => __( 'Grid', 'micemade-elements' ),
					'slider' => __( 'Slider', 'micemade-elements' ),
				),
			)
		);

		// Heading for grid options.
		$this->add_control(
			'heading_grid',
			array(
				'label'     => __( 'Categories grid options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);
		// Heading for slider options
		$this->add_control(
			'heading_slider',
			array(
				'label'     => __( 'Categories slider options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		// Items in grid row.
		$items_num = array(
			1 => __( 'One', 'micemade-elements' ),
			2 => __( 'Two', 'micemade-elements' ),
			3 => __( 'Three', 'micemade-elements' ),
			4 => __( 'Four', 'micemade-elements' ),
			6 => __( 'Six', 'micemade-elements' ),
		);

		$this->add_control(
			'cats_per_row',
			array(
				'label'     => __( 'Items per row', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 3,
				'options'   => $items_num,
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);

		$this->add_control(
			'cats_per_row_tab',
			array(
				'label'     => __( 'Items per row (tablets)', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => $items_num,
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);

		$this->add_control(
			'cats_per_row_mob',
			array(
				'label'     => __( 'Items per row (mobiles)', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => $items_num,
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'horiz_spacing',
			array(
				'label'     => __( 'Grid horizontal spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'max'  => 50,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'  => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				),
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'vert_spacing',
			array(
				'label'     => __( 'Grid bottom spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '20',
				),
				'range'     => array(
					'px' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'  => 'margin-bottom:-{{SIZE}}px;',
				),
				'condition' => array(
					'grid_or_slider' => 'grid',
				),
			)
		);

		$this->add_control(
			'posts_per_slide',
			array(
				'label'              => __( 'Items per slide', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => array(
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->add_control(
			'posts_per_slide_tab',
			array(
				'label'              => __( 'Items per slide (tablets)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 2,
				'options'            => $items_num,
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->add_control(
			'posts_per_slide_mob',
			array(
				'label'              => __( 'Items per slide (mobiles)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 1,
				'options'            => $items_num,
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);
		// end slides to show.

		// Space between the slides.
		$this->add_responsive_control(
			'space',
			array(
				'label'              => __( 'Space between', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 30,
				'min'                => 0,
				'step'               => 10,
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
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
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		// Slider navigation.
		$this->add_control(
			'buttons',
			array(
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'elementor' ),
				'label_on'           => __( 'Yes', 'elementor' ),
				'default'            => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
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
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay'       => 'yes',
					'grid_or_slider' => 'slider',
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

		// Loop the slider.
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
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
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
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Animation Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
				'condition'          => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->end_controls_section();

		// Section elements style.
		$this->start_controls_section(
			'section_slider_elements_style',
			array(
				'label'     => esc_html__( 'Slider elements style', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'grid_or_slider' => 'slider',
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Pagination color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet-active'    => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-fraction'         => 'color: {{VALUE}};',
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
				'raw'             => __( '<small>Some icons may misalign with it\'s button. Enable the control above to fix the arrow icon position.</small>' ),
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

		// Start layout section.
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Category item layout', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'Base style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => array(
					'style_1' => __( 'Style one', 'micemade-elements' ),
					'style_2' => __( 'Style two', 'micemade-elements' ),
					'style_3' => __( 'Style three', 'micemade-elements' ),
					'style_4' => __( 'Style four', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => esc_html__( 'Show category image', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'img_format',
			array(
				'label'     => esc_html__( 'Image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => array(
					'image!' => '',
				),
			)
		);

		$this->add_control(
			'prod_count',
			array(
				'label'     => esc_html__( 'Show products count', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'prod_count_pre',
			array(
				'label'       => __( 'Prepend text to products count', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Number of products: ', 'micemade-elements' ),
				'placeholder' => __( 'Prepend text to products count', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => array(
					'prod_count!' => '',
				),
			)
		);

		$this->add_control(
			'prod_count_ape',
			array(
				'label'       => __( 'Append text to products count', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Add or change appended text', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => array(
					'prod_count!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'categories_height',
			array(
				'label'     => __( 'Items height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 250,
				),
				'range'     => array(
					'px' => array(
						'max'  => 800,
						'min'  => 0,
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category' => 'height: {{SIZE}}px;',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'cat_image_width',
			array(
				'label'     => __( 'Category image width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category .category__image' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'style' => 'style_2',
				),
			)
		);

		$this->add_responsive_control(
			'post_text_align',
			array(
				'label'     => __( 'Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category__text-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'cat_vertical_alignment',
			array(
				'label'     => __( 'Vertical Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'flex-start' => __( 'Top', 'micemade-elements' ),
					'center'     => __( 'Middle', 'micemade-elements' ),
					'flex-end'   => __( 'Bottom', 'micemade-elements' ),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .category__text-wrap' => 'align-self: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_1', 'style_2', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'cat_title_padding',
			array(
				'label'      => esc_html__( 'Item padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .category__text-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'inner_spacing',
			array(
				'label'     => __( 'Item margin', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '0',
				),
				'range'     => array(
					'px' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .category__inner-wrap' => 'margin:{{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Colors, typography, border', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'tabs_button_style' );
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'title_text_color',
			array(
				'label'     => __( 'Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filters_text_color',
			array(
				'label'     => __( 'Additional filters text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category .category__add-query-text' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'add_query_text!' => '',
				),
			)
		);

		$this->add_control(
			'prod_count_text_color',
			array(
				'label'     => __( 'Products count text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category .category__product-count' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'prod_count!' => '',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Category overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category__overlay' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label' => __( 'Hover', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Title Color on Hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'filters_text_color_hover',
			array(
				'label'     => __( 'Additional filters text hover color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category:hover .category__add-query-text' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'add_query_text!' => '',
				),
			)
		);
		$this->add_control(
			'prod_count_text_color_hover',
			array(
				'label'     => __( 'Products count text hover color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .category:hover .category__product-count' => 'color: {{VALUE}};',
				),
				'separator' => 'before',
				'condition' => array(
					'prod_count!' => '',
				),
			)
		);

		$this->add_control(
			'background_color_hover',
			array(
				'label'     => __( 'Category overlay color on hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .category:hover .category__overlay' => 'background-color: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Title typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .category__title',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'filters_text_typography',
				'label'     => __( 'Filters text typography', 'micemade-elements' ),
				'scheme'    => Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .category__add-query-text',
				'separator' => 'before',
				'condition' => array(
					'add_query_text!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_count_typography',
				'label'     => __( 'Products count typography', 'micemade-elements' ),
				'scheme'    => Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .category__product-count',
				'separator' => 'before',
				'condition' => array(
					'prod_count!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .category__inner-wrap',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_anim_effects',
			array(
				'label' => __( 'Animation and hover effects', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'hover_style',
			array(
				'label'     => __( 'Image hover effect', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'blur_image',
				'options'   => array(
					'no_image_effect' => __( 'No image hover effect', 'micemade-elements' ),
					'blur_image'      => __( 'Blur image', 'micemade-elements' ),
					'enlarge_image'   => __( 'Enlarge image', 'micemade-elements' ),
					'shrink_image'    => __( 'Shrink image', 'micemade-elements' ),
					'greyscale_image' => __( 'Greyscale image', 'micemade-elements' ),
				),
				'condition' => array(
					'image!' => '',
				),
			)
		);

		$this->add_control(
			'hover_style_box',
			array(
				'label'   => __( 'Category hover effect', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'               => __( 'None', 'micemade-elements' ),
					'box_enlarge_shadow' => __( 'Enlarge with shadow', 'micemade-elements' ),
					'box_shrink_shadow'  => __( 'Shrink with shadow', 'micemade-elements' ),
					'box_move_up'        => __( 'Float', 'micemade-elements' ),
					'box_move_down'      => __( 'Sink', 'micemade-elements' ),
					'box_move_right'     => __( 'Forward', 'micemade-elements' ),
					'box_move_left'      => __( 'Backward', 'micemade-elements' ),
				),
			)
		);

		// Prepare array of animations.
		$no_anim    = array( 'none' => __( 'None', 'micemade-elements' ) );
		$anim_array = $this->array_flatten( Control_Animation::get_animations() );
		$this->add_control(
			'item_anim',
			array(
				'label'       => __( 'Entrance Animation', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'none',
				'options'     => array_merge( $no_anim, $anim_array ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'item_anim_duration',
			array(
				'label'     => __( 'Animation Speed', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'slow' => __( 'Slow', 'elementor' ),
					''     => __( 'Normal', 'elementor' ),
					'fast' => __( 'Fast', 'elementor' ),
				),
				'condition' => array(
					'item_anim!' => 'none',
				),
			)
		);

		$this->add_control(
			'item_anim_delay',
			array(
				'label'     => __( 'Animation Delay', 'elementor' ) . ' (ms)',
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'step'      => 100,
				'title'     => esc_html__( 'animation delay between category items', 'micemade-elements' ),
				'condition' => array(
					'item_anim!' => 'none',
				),
			)
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		// Settings vars.
		// $categories__         = wp_list_pluck( $settings['categories__'], 'category' ); // if repeater.
		$categories           = $settings['categories'];
		$add_query_args       = $settings['add_query_args'];
		$add_query_text       = $settings['add_query_text'];
		$gr_or_sl             = $settings['grid_or_slider'];
		$cats_per_row         = (int) $settings['cats_per_row'];
		$cats_per_row_tab     = (int) $settings['cats_per_row_tab'];
		$cats_per_row_mob     = (int) $settings['cats_per_row_mob'];
		$posts_per_slide      = (int) $settings['posts_per_slide'];
		$posts_per_slide_tab  = (int) $settings['posts_per_slide_tab'];
		$posts_per_slide_mob  = (int) $settings['posts_per_slide_mob'];
		$space                = (int) $settings['space'];
		$space_tablet         = (int) $settings['space_tablet'];
		$space_mobile         = (int) $settings['space_mobile'];
		$pagination           = $settings['pagination'];
		$buttons              = $settings['buttons'];
		$arrow_icon           = $settings['arrow_icon'];
		$autoplay             = $settings['autoplay'];
		$autoplay_speed       = $settings['autoplay_speed'];
		$speed                = $settings['speed'];
		$pause_on_hover       = $settings['pause_on_hover'];
		$pause_on_interaction = $settings['pause_on_interaction'];
		$effect               = $settings['effect'];
		$infinite             = $settings['infinite'];
		$style                = $settings['style'];
		$hover_style          = $settings['hover_style'];
		$hover_style_box      = $settings['hover_style_box'];
		$image                = $settings['image'];
		$img_format           = $settings['img_format'];
		$prod_count           = $settings['prod_count'];
		$prod_count_pre       = $settings['prod_count_pre'];
		$prod_count_ape       = $settings['prod_count_ape'];
		$item_anim            = $settings['item_anim'];
		$item_anim_dur        = $settings['item_anim_duration'];
		$item_anim_delay      = $settings['item_anim_delay'];

		// This widget ID.
		$id = $this->get_id();
		// If no categories selected exit early.
		if ( empty( $categories ) ) {
			return;
		}

		// Grid item styles.
		$grid = micemade_elements_grid_class( intval( $cats_per_row ), intval( $cats_per_row_tab ), intval( $cats_per_row_mob ) );

		$swiper_settings = array(
			'posts_per_slide'      => $posts_per_slide,
			'posts_per_slide_tab'  => $posts_per_slide_tab,
			'posts_per_slide_mob'  => $posts_per_slide_mob,
			'space'                => $space,
			'space_tablet'         => $space_tablet,
			'space_mobile'         => $space_mobile,
			'pagination'           => $pagination,
			'autoplay'             => $autoplay,
			'delay'                => $autoplay_speed,
			'speed'                => $speed,
			'pause_on_hover'       => $pause_on_hover,
			'pause_on_interaction' => $pause_on_interaction,
			'infinite'             => $infinite,
			'effect'               => $effect,
		);

		// Add slider style selectors if slider instead of grid.
		$container_slider_css = ( 'slider' === $gr_or_sl ) ? 'swiper-container micemade-elements_slider' : 'mme-row';

		// All the styles for categories container.
		$this->add_render_attribute(
			array(
				'container' => array(
					'class' => array(
						$container_slider_css,
						'micemade-elements_product-categories',
						$style,
						$hover_style,
						$hover_style_box,
					),
				),
			)
		);

		// Additional query args.
		$args = '';
		if ( ! empty( $add_query_args ) ) {
			$args = '?';
			foreach ( $add_query_args as $arg ) {
				$args .= $arg . ( end( $add_query_args ) === $arg ? '' : '&amp;' );
			}
		}

		// Categories holder.
		echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';

		echo ( 'slider' === $gr_or_sl ) ? '<div class="swiper-wrapper">' : '';

		foreach ( $categories as $index => $cat ) {

			$count = $index + 1;

			$term_data = apply_filters( 'micemade_elements_term_data', 'product_cat', $cat, $img_format ); // hook in inc/helpers.php

			if ( empty( $term_data ) ) {
				continue;
			}

			$term_id    = isset( $term_data['term_id'] ) ? $term_data['term_id'] : '';
			$term_title = isset( $term_data['term_title'] ) ? $term_data['term_title'] : '';
			$term_link  = isset( $term_data['term_link'] ) ? $term_data['term_link'] . $args : '#';
			$image_url  = isset( $term_data['image_url'] ) ? $term_data['image_url'] : '';

			// Category item animation data.
			$anim_settings = array(
				'_enter_animation' => $item_anim,
				'_animation_delay' => $item_anim_delay * $count,
			);
			// Category item attributes.
			$this->add_render_attribute(
				array(
					'cat_item' . $count => array(
						'class'         => array(
							( 'slider' === $gr_or_sl ) ? 'swiper-slide category' : 'category ' . $grid, // grid or slider style.
							( 'none' !== $item_anim ) ? 'mm-enter-animate animated' : '', // animation styles.
							( 'none' !== $item_anim && $item_anim_dur ) ? 'animated-' . $item_anim_dur : '', // animation duration style.
						),
						'data-id'       => $id . '-' . $count,
						'data-anim'     => $item_anim,
						'data-delay'    => $item_anim_delay * $count,
						'data-settings' => wp_json_encode( $anim_settings ),
						'title'         => esc_attr( $term_title ),
						'href'          => esc_url( $term_link ),
					),
				)
			);

			echo '<a ' . $this->get_render_attribute_string( 'cat_item' . $count ) . '>';

				echo '<div class="category__inner-wrap">';

					echo '<div class="category__overlay"></div>';

			if ( $image ) {
				echo '<div class="category__image"><div class="image-inner" style="background-image: url(' . esc_url( $image_url ) . ')"></div></div>';
			}

					echo '<div class="category__text-wrap">';

			if ( ! empty( $add_query_args ) && $add_query_text ) {
				echo '<span class="category__add-query-text">' . esc_html( $add_query_text ) . '</span>';
			}

			if ( $term_title ) {
				echo '<h3 class="category__title">' . esc_html( $term_title ) . '</h3>';
			}

			if ( $prod_count && $term_id ) {
				do_action( 'micemade_elements_product_count', $term_id, $add_query_args, $prod_count_pre, $prod_count_ape );
			}

					echo '</div>';

				echo '</div>'; // .inner-wrap.

			echo '</a>';

		}

		// If slider add pagination and arrows.
		if ( 'slider' === $gr_or_sl ) {

			echo '</div>';// End slider wrapper div.

			if ( 'none' !== $pagination ) {
				echo '<div class="swiper-pagination"></div>';
			}

			if ( $buttons ) {
				echo '<div class="swiper-button-next ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Next', 'micemade-elements' ) . '</span></div>';
				echo '<div class="swiper-button-prev ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Previous', 'micemade-elements' ) . '</span></div>';
			}
		}

		echo '</div>';// End container div.

	}

	protected function content_template() {}

	public function render_plain_content( $instance = array() ) {}

	/**
	 * Flatten array
	 *
	 * @param array $arr - mulitd. array to be flatten
	 * @param array $out - output array.
	 * @return $out
	 */
	public function array_flatten( $arr, $out = array() ) {
		foreach ( $arr as $key => $item ) {
			if ( is_array( $item ) ) {
				$out = array_merge( $out, $this->array_flatten( $item ) );
			} else {
				$out[ $key ] = $item;
			}
		}
		return $out;
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Categories() );
