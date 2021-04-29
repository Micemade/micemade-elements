<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Core\Schemes;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Schemes\Typography;

/**
 * Micemade Elements WC products slider.
 *
 * Elementor widget that displays a set of WC products in a rotating carousel or
 * slider.
 *
 * @since 0.3.0
 */
class Micemade_WC_Products_Slider extends \Elementor\Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		if ( ! wp_script_is( 'micemade-slider-js', 'registered' ) ) {
			wp_register_script( 'micemade-slider-js', MICEMADE_ELEMENTS_URL . 'assets/js/custom/handlers/slider.js', [ 'elementor-frontend' ], '1.0.0', true );
		}
		if ( ! wp_script_is( 'micemade-slider-js', 'enqueued' ) ) {
			wp_enqueue_script( 'micemade-slider-js' );
		}
	}

	public function get_script_depends() {
		return [ 'micemade-slider-js', 'jquery-swiper' ];
	}

	public function get_name() {
		return 'micemade-wc-products-slider';
	}

	public function get_title() {
		return __( 'Micemade WC products slider', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
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
		return [ 'micemade', 'woocommerce', 'products', 'carousel', 'slider' ];
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade products slider', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'heading_product_query_basic',
			[
				'label'     => __( 'Basic product query options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Total products', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 0,
				'step'    => 1,
				'title'   => __( 'Enter total number of products to show', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => __( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'step'    => 1,
				'title'   => __( 'Offset is number of skipped products', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'heading_filtering',
			[
				'label'     => __( 'Additional query options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'exclude_cats',
			[
				'label'       => esc_html__( 'Exclude product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'exclude_cats_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Make sure not to exclude already included product category.</small>' ),
				'content_classes' => 'your-class',
				'separator'       => 'after',
			)
		);

		$this->add_control(
			'filters',
			[
				'label'   => __( 'Products filters', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => [
					'latest'       => __( 'Latest products', 'micemade-elements' ),
					'featured'     => __( 'Only featured products', 'micemade-elements' ),
					'on_sale'      => __( 'Only products on sale', 'micemade-elements' ),
					'random'       => __( 'Random products', 'micemade-elements' ),
					'best_sellers' => __( 'Order by best selling', 'micemade-elements' ),
					'best_rated'   => __( 'Order by best rated', 'micemade-elements' ),
				],
			]
		);

		$this->add_control(
			'products_in',
			[
				'label'       => esc_html__( 'Select products', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => [],
				'options'     => apply_filters( 'micemade_posts_array', 'product' ),
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'no_outofstock',
			[
				'label'     => esc_html__( 'Hide out of stock products', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => __( 'Order by', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => [
					'date'       => __( 'Date', 'micemade-elements' ),
					'name'       => __( 'Name', 'micemade-elements' ),
					'modified'   => __( 'Last date modified', 'micemade-elements' ),
					'menu_order' => __( 'Ordered in admin', 'micemade-elements' ),
				],
				'condition' => [
					'filters!' => [ 'best_sellers', 'best_rated' ],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => __( 'Order', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => [
					'DESC' => __( 'Descending', 'micemade-elements' ),
					'ASC'  => __( 'Ascending', 'micemade-elements' ),
				],
				'condition' => [
					'filters!' => [ 'best_sellers', 'best_rated' ],
				],
			]
		);

		$this->add_control(
			'heading_slider',
			[
				'label'     => __( 'Slider settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Slides to show.
		$slides_no = [
			1 => __( 'One', 'micemade-elements' ),
			2 => __( 'Two', 'micemade-elements' ),
			3 => __( 'Three', 'micemade-elements' ),
			4 => __( 'Four', 'micemade-elements' ),
			6 => __( 'Six', 'micemade-elements' ),
		];

		$this->add_control(
			'posts_per_slide',
			[
				'label'              => __( 'Items per slide', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'posts_per_slide_tab',
			[
				'label'              => __( 'Items per slide (tablets)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 2,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'posts_per_slide_mob',
			[
				'label'              => __( 'Items per slide (mobiles)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 1,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);
		// end slides to show.

		// Space between the slides.
		$this->add_responsive_control(
			'space',
			[
				'label'              => __( 'Space between', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 30,
				'min'                => 0,
				'step'               => 10,
				'frontend_available' => true,
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

		// Slider navigation.
		$this->add_control(
			'buttons',
			[
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'elementor' ),
				'label_on'           => __( 'Yes', 'elementor' ),
				'default'            => 'yes',
				'frontend_available' => true,

			]
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

		$this->end_controls_section();

		// Section elements style.
		$this->start_controls_section(
			'section_slider_elements_style',
			[
				'label'     => esc_html__( 'Slider elements style', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
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
				'default'   => '#333333',
				'separator' => 'after',
				'condition' => [
					'pagination!' => 'none',
				],
			]
		);

		$this->add_control(
			'buttons_color',
			[
				'label'     => __( 'Arrows icon color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-next:before' => 'color: {{VALUE}};',
				],
				'default'   => '#2F2E2EC7',
				'condition' => [
					'buttons!' => '',
				],
			]
		);
		$this->add_control(
			'buttons_bckcolor',
			[
				'label'     => __( 'Arrows button color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'background-color: {{VALUE}};',
				],
				'default'   => '#FFFFFF7A',
				'separator' => 'after',
				'condition' => [
					'buttons!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'nav_arrows_vert_position',
			[
				'label'       => __( 'Arrows vertical position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px' ],
				'default'     => [
					'size' => 50,
					'unit' => '%',
				],
				'range'       => [
					'%'  => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$this->add_control(
			'arrow_icon',
			[
				'label'       => __( 'Arrow icons', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'include' => [
					'fa fa-arrow-left',
					'fa fa-angle-left',
					'fa fa-angle-double-left',
					'fa fa-arrow-circle-left',
					'fa fa-caret-left',
					'fa fa-chevron-left',
					'fa fa-chevron-circle-left',
					'fa fa-long-arrow-left',
				],
				'default'     => 'fa fa-chevron-left',
			]
		);

		$this->add_responsive_control(
			'nav_arrows_size',
			[
				'label'       => __( 'Arrows icon size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 12,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-next:before, {{WRAPPER}} .swiper-button-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'nav_arrows_back_size',
			[
				'label'       => __( 'Arrows button size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 32,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-next:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$this->add_control(
			'nav_arrows_back_roundness',
			[
				'label'       => __( 'Arrows button roundness', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 12,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'     => __( 'Arrow button border' ),
				'name'      => 'arrow_button_border',
				'selector'  => '{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after',
				'separator' => 'after',
				'condition' => [
					'buttons!' => '',
				],
			]
		);

		// Slider navigation.
		$this->add_control(
			'show_icon_fix',
			[
				'label'     => esc_html__( 'Fix icon position', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			]
		);
		$this->add_control(
			'fix_icon_note',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Some icons may misalign with it\'s button. Enable the control above to fix the arrow icon position.' ),
				'content_classes' => 'your-class',
			]
		);

		$this->add_responsive_control(
			'nav_arrows_fix_position',
			[
				'label'       => __( 'Fix arrow position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px' ],
				'default'     => [
					'size' => 0,
					'unit' => '%',
				],
				'range'       => [
					'%'  => [
						'max'  => 50,
						'min'  => -50,
						'step' => 1,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev:before' => 'margin-top: {{SIZE}}{{UNIT}};',
					' {{WRAPPER}} .swiper-button-next:before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!'      => '',
					'show_icon_fix' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// TAB 2 - STYLES FOR POSTS.
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Basic settings', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => __( 'Base style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'catalog' => __( 'WC Catalog style', 'micemade-elements' ),
					'style_1' => __( 'Style one', 'micemade-elements' ),
					'style_2' => __( 'Style two', 'micemade-elements' ),
					'style_3' => __( 'Style three', 'micemade-elements' ),
					'style_4' => __( 'Style four', 'micemade-elements' ),
				],
			]
		);
		$this->add_control(
			'style_note',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>"WC catalog style" inherits default WooCommerce layout from shop/catalog or category pages. Some themes or plugins may override this layout with their own layouts.</small>', 'micemade-elements' ),
				'content_classes' => 'your-class',
				'separator' => 'after',
				'condition' => [
					'style' => [ 'catalog' ],
				],
			]
		);

		$this->add_responsive_control(
			'wc_catalog_align',
			[
				'label'     => __( 'Elements alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} ul.products' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'style' => [ 'catalog' ],
				],
			]
		);

		$this->add_control(
			'img_format',
			[
				'label'     => esc_html__( 'Product image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_responsive_control(
			'product_text_height',
			[
				'label'     => __( 'Product height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 400,
				],
				'range'     => [
					'px' => [
						'max'  => 800,
						'min'  => 0,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .inner-wrap' => 'height: {{SIZE}}px;',
				],
				'condition' => [
					'style' => [ 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'product_bottom_margin',
			[
				'label'     => __( 'Product bottom margin', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'margin-bottom: {{SIZE}}px;',
				],
				/* 'condition' => [
					'style' => [ 'style_3', 'style_4' ],
				], */
			]
		);

		$this->add_control(
			'slider_items_padding_border',
			[
				'label'     => __( 'Slider items padding and border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_responsive_control(
			'slider_items_padding',
			[
				'label'      => esc_html__( 'Slider items padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'     => __( 'Slider items border' ),
				'name'      => 'productslider_post_border',
				'selector'  => '{{WRAPPER}} .inner-wrap',
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->end_controls_section();

		// Section product info elements.
		$this->start_controls_section(
			'section_info_elements',
			[
				'label'     => esc_html__( 'Toggle elements', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label'     => esc_html__( 'Show price', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'add_to_cart',
			[
				'label'     => esc_html__( 'Show "Add to cart"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'quickview',
			[
				'label'     => esc_html__( 'Show "Quick view"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_control(
			'short_desc',
			[
				'label'     => esc_html__( 'Show product short description', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				//'default' => 'yes',
			]
		);

		$this->add_control(
			'posted_in',
			[
				'label'       => esc_html__( 'Show posted in categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => __( 'No', 'elementor' ),
				'label_on'    => __( 'Yes', 'elementor' ),
				'default'     => 'yes',
			]
		);

		$this->end_controls_section();
		// end product info elements.

		// Section product info box styling.
		$this->start_controls_section(
			'section_info_styling',
			[
				'label'     => esc_html__( 'Elements styling', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_product_background' );
		$this->start_controls_tab(
			'tab_product_background_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'post_info_background_color',
			[
				'label'     => __( 'Background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style!' => [ 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_control(
			'post_overlay_color',
			[
				'label'     => __( 'Overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style' => [ 'style_3', 'style_4' ],
				],
			]
		);

		$this->end_controls_tab();
		// HOVER.
		$this->start_controls_tab(
			'tab_product_background_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);
		$this->add_control(
			'post_info_background_color_hover',
			[
				'label'     => __( 'Hover product info background', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style!' => [ 'style_3' ],
				],
			]
		);

		$this->add_control(
			'post_overlay_hover_color',
			[
				'label'     => __( 'Hover product overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover .post-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style' => [ 'style_3' ],
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'product_info_align',
			[
				'label'                => __( 'Horizontal align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'              => 'center',
				'selectors'            => [
					'{{WRAPPER}} .post-text, {{WRAPPER}} .post-text > *:not(.button)' => '{{VALUE}};',
				],
				'selectors_dictionary' => array(
					'left'   => 'text-align: left; align-items: flex-start',
					'center' => 'text-align: center; align-items: center',
					'right'  => 'text-align: right; align-items: flex-end',
				),
			]
		);

		$this->add_responsive_control(
			'content_vertical_alignment',
			[
				'label'       => __( 'Vertical align', 'micemade-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'flex-start' => [
						'title' => __( 'Start', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'     => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'   => [
						'title' => __( 'End', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .inner-wrap, {{WRAPPER}} .post-text' => 'justify-content: {{VALUE}};',
				],
				'condition'   => [
					'style' => [ 'style_2', 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'product_elements_spacing',
			[
				'label'     => __( 'Elements vertical spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '',
				],
				'range'     => [
					'px' => [
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .post-text h4'          => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .meta'       => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .price-wrap' => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .add-to-cart-wrap' => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text p'           => 'padding: 0 0 {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'product_info_padding',
			[
				'label'      => esc_html__( 'Product info box padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'before',
				'selectors'  => [
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'buttons_padding',
			[
				'label'      => esc_html__( 'Product buttons padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .add_to_cart_button, {{WRAPPER}} .mme-quick-view' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->end_controls_section();
		// end product info styling.

		$this->start_controls_section(
			'section_product_thumb_settings',
			[
				'label'     => __( 'Product image settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => [ 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'thumb_width',
			[
				'label'       => __( 'Image container width', 'elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 100,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => [
					'{{WRAPPER}} .post-thumb-back' => 'width: {{VALUE}}%;',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_as_back_height',
			[
				'label'       => esc_html__( 'Image container height', 'micemade-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 100,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => [
					'{{WRAPPER}} .post-thumb-back' => 'height: {{VALUE}}%;',
				],
			]
		);
		$this->add_responsive_control(
			'img_container_pos',
			[
				'label'       => esc_html__( 'Image container position', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'top__left',
				'label_block' => true,
				'options'     => [
					'top__left'      => esc_html__( 'Top left', 'micemade-elements' ),
					'top__center'    => esc_html__( 'Top center', 'micemade-elements' ),
					'top__right'     => esc_html__( 'Top right', 'micemade-elements' ),
					'middle__left'   => esc_html__( 'Middle Left ', 'micemade-elements' ),
					'middle'         => esc_html__( 'Middle', 'micemade-elements' ),
					'middle__right'  => esc_html__( 'Middle right ', 'micemade-elements' ),
					'bottom__left'   => esc_html__( 'Bottom left ', 'micemade-elements' ),
					'bottom__center' => esc_html__( 'Bottom center ', 'micemade-elements' ),
					'bottom__right'  => esc_html__( 'Bottom right ', 'micemade-elements' ),
				],
			]
		);
		$this->add_responsive_control(
			'back_image_position',
			[
				'label'       => esc_html__( 'Image background position', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'label_block' => true,
				'options'     => [
					'left top'      => esc_html__( 'Top left', 'micemade-elements' ),
					'center top'    => esc_html__( 'Top center', 'micemade-elements' ),
					'right top'     => esc_html__( 'Top right', 'micemade-elements' ),
					'left center'   => esc_html__( 'Middle Left ', 'micemade-elements' ),
					'center'        => esc_html__( 'Middle', 'micemade-elements' ),
					'right center'  => esc_html__( 'Middle right ', 'micemade-elements' ),
					'left bottom'   => esc_html__( 'Bottom left ', 'micemade-elements' ),
					'center bottom' => esc_html__( 'Bottom center ', 'micemade-elements' ),
					'right bottom'  => esc_html__( 'Bottom right ', 'micemade-elements' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .post-thumb-back' => 'background-position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_background_size',
			[
				'label'       => esc_html__( 'Image background size', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cover',
				'label_block' => true,
				'options'     => [
					'auto'    => esc_html__( 'Auto', 'micemade-elements' ),
					'cover'   => esc_html__( 'Cover', 'micemade-elements' ),
					'contain' => esc_html__( 'Contain', 'micemade-elements' ),
					'custom'  => esc_html__( 'Custom', 'micemade-elements' ),
				],
				'selectors'   => [
					'{{WRAPPER}} .post-thumb-back' => 'background-size: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_background_size_custom',
			[
				'label'       => esc_html__( 'Custom image size', 'micemade-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 200,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => [
					'{{WRAPPER}} .post-thumb-back' => 'background-size: {{VALUE}}% !important;',
				],
				'condition'   => [
					//'layout' => 'image_background',
					'thumb_background_size' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		// TITLE STYLES.
		$this->start_controls_section(
			'section_title',
			[
				'label'     => __( 'Product title settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'tabs_button_style' );
		// NORMAL.
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'title_text_color',
			[
				'label'     => __( 'Title Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .post-text h4 a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_back_color',
			[
				'label'     => __( 'Title Back Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .post-text h4' => 'background-color: {{VALUE}};padding-left:0.5em;padding-right:0.5em;',
				],
			]
		);

		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => __( 'Title Color on Hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text h4:hover a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_back_color_hover',
			[
				'label'     => __( 'Title Back Color on Hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .post-text h4:hover' => 'background-color: {{VALUE}};padding-left:0.5em;padding-right:0.5em;',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		// end tabs.

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'productslider_title_border',
				'label'       => __( 'Title Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text h4',
			]
		);

		$this->add_responsive_control(
			'productslider_title_padding',
			[
				'label'      => esc_html__( 'Title padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Title typohraphy.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text h4',
			]
		);

		$this->end_controls_section();

		// PRODUCT DETAILS.
		$this->start_controls_section(
			'section_text',
			[
				'label'     => __( 'Product info typography', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style!' => [ 'catalog' ],
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'categories_typo',
			[
				'label'     => __( 'Categories', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'posted_in!' => '',
				],
			]
		);

		$this->add_control(
			'categories_text_color',
			[
				'label'     => __( 'Categories Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .meta a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'posted_in!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'categories_typography',
				'label'     => __( 'Categories typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .meta',
				'condition' => [
					'posted_in!' => '',
				],
			]
		);

		$this->add_control(
			'short_desc_options',
			[
				'label'     => __( 'Short description', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'short_desc!' => '',
				],
			]
		);

		$this->add_control(
			'excerpt_text_color',
			[
				'label'     => __( 'Short description text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				],
				'condition' => [
					'short_desc!' => '',
				],
			]
		);

		// Short desc typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'text_typography',
				'label'     => __( 'Short description typography', 'micemade-elements' ),
				'scheme'    => Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .post-text p',
				'condition' => [
					'short_desc!' => '',
				],
			]
		);

		// Price controls.
		$this->add_control(
			'price_options',
			[
				'label'     => __( 'Price', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'price!' => '',
				],
			]
		);

		$this->add_control(
			'price_text_color',
			[
				'label'     => __( 'Price text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .price' => 'color: {{VALUE}};',
				],
				'condition' => [
					'price!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'price_typography',
				'label'     => __( 'Price typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .price',
				'condition' => [
					'price!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'        => 'product_button_typography',
				'label'       => __( 'Buttons typography', 'micemade-elements' ),
				'label_block' => true,
				'selector'    => '{{WRAPPER}} .add_to_cart_button',
				'separator'   => 'before',
				'condition'   => [
					'style!' => [ 'catalog' ],
				],
			]
		);

		$this->add_control(
			'prod_det_button_options',
			[
				'label'     => __( '"Product details" link', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'css_class',
			[
				'label'   => __( 'CSS for "Product details"', 'micemade-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'title'   => __( 'Style the "Product details" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_quickview',
			[
				'label'     => __( 'Quick view settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'quickview!' => '',
					'style!'     => 'catalog',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'   => __( 'Button icon', 'micemade-elements' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-eye',
					'library' => 'solid',
				],
			]
		);

		$this->add_control(
			'quickview_overlay_color',
			[
				'label'     => __( 'Quick view overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#mmqv-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'modal_margins',
			[
				'label'       => __( 'Quick view modal side margins', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px' ],
				'range'       => [
					'%'  => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'   => [
					'#mmqv-{{ID}} .mmqv-holder' => 'left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// end of section_quickview.
	}

	protected function render() {

		// Get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$posts_per_page = (int) $settings['posts_per_page'];
		$offset         = (int) $settings['offset'];
		$pps            = (int) $settings['posts_per_slide'];
		$pps_tab        = (int) $settings['posts_per_slide_tab'];
		$pps_mob        = (int) $settings['posts_per_slide_mob'];
		$space          = (int) $settings['space'];
		$space_tablet   = (int) $settings['space_tablet'];
		$space_mobile   = (int) $settings['space_mobile'];
		$pagination     = $settings['pagination'];
		$autoplay       = $settings['autoplay'];
		$infinite       = $settings['infinite'];
		$effect         = $settings['effect'];
		$buttons        = $settings['buttons'];
		$arrow_icon     = $settings['arrow_icon'];
		$categories     = $settings['categories'];
		$exclude_cats   = $settings['exclude_cats'];
		$filters        = $settings['filters'];
		$products_in    = $settings['products_in'];
		$no_outofstock  = $settings['no_outofstock'];
		$orderby        = $settings['orderby'];
		$order          = $settings['order'];
		$img_format     = $settings['img_format'];
		$style          = $settings['style'];
		$short_desc     = $settings['short_desc'];
		$price          = $settings['price'];
		$quickview      = $settings['quickview'];
		$selected_icon  = $settings['selected_icon'];
		$add_to_cart    = $settings['add_to_cart'];
		$posted_in      = $settings['posted_in'];
		$css_class      = $settings['css_class'];
		$img_cont_pos   = $settings['img_container_pos'];

		global $post;

		// $grid = micemade_elements_grid_class( intval( $pps ), intval( $pps_tab ), intval( $pps_mob ) );

		// Query args: ( hook in includes/wc-functions.php ).
		$args = apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $exclude_cats, $filters, $offset, $products_in, $no_outofstock, $orderby, $order );

		$products_query = new \WP_Query( $args );

		if ( $products_query->have_posts() ) {

			$swiper_settings = [
				'posts_per_slide'     => $pps,
				'posts_per_slide_tab' => $pps_tab,
				'posts_per_slide_mob' => $pps_mob,
				'space'               => $space,
				'space_tablet'        => $space_tablet,
				'space_mobile'        => $space_mobile,
				'pagination'          => $pagination,
				'autoplay'            => $autoplay,
				'infinite'            => $infinite,
				'effect'              => $effect,
			];

			// Main slider container - add CSS classes and data settings.
			$this->add_render_attribute(
				[
					'container' => [
						'class'         => [
							'swiper-container',
							'micemade-elements_slider',
							'micemade-elements_products_slider',
							'woocommerce',
							$style,
						],
						//'data-settings' => wp_json_encode( $swiper_settings ),
					],
				]
			);

			if ( $img_cont_pos ) {
				$this->add_render_attribute( 'container', 'class', 'container-' . $img_cont_pos );
			}

			echo '<div ' . $this->get_render_attribute_string( 'container' ) . '>';

			// Pick up catalog loop start, or MME styles loop start.
			if ( 'catalog' === $style ) {
				$loop_start = woocommerce_product_loop_start( false );
				// "Inject" 'swiper-wrapper' class to loop start classes.
				$loop_start = str_replace( 'class="', 'class="swiper-wrapper ', $loop_start );
				echo wp_kses_post( $loop_start );
			} else {
				echo '<ul class="swiper-wrapper products">';
			}
			// If style as in WC content-product template.
			if ( 'catalog' === $style ) {
				add_filter(
					'woocommerce_post_class',
					function( $classes ) {
						// Remove the "first" and "last" added by wc_get_loop_class().
						$classes = array_diff( $classes, array( 'first', 'last' ) );
						// Add the slides classes.
						return apply_filters( 'micemade_elements_product_item_classes', $classes, 'add', [ 'swiper-slide', 'item' ] );
					},
					10
				);
			}

			while ( $products_query->have_posts() ) {

				$products_query->the_post();

				// If style as in WC content-product template.
				if ( 'catalog' === $style ) {
					//wc_get_template_part( 'content', 'product' );
					// else, use plugin loop items.
				} else {
					$item_classes = apply_filters( 'micemade_elements_product_item_class', 'swiper-slide post product' );
					apply_filters( 'micemade_elements_loop_product', $style, $item_classes, $img_format, $posted_in, $short_desc, $price, $add_to_cart, $css_class, $quickview, $selected_icon );// hook in includes/wc-functions.php.
				}
			}

			if ( 'catalog' === $style ) {
				woocommerce_product_loop_end();
			} else {
				echo '</ul>'; // .swipper-wrapper.
			}

			// If style as in WC content-product template.
			if ( 'catalog' === $style ) {
				// "Clean" or "reset" post_class
				// avoid conflict with other "post_class" functions.
				add_filter(
					'woocommerce_post_class',
					function( $classes ) {
						return apply_filters( 'micemade_elements_product_item_classes', $classes, '', [ 'swiper-slide', 'item' ] );
					},
					10
				);
			}

			if ( 'none' !== $pagination ) {
				echo '<div class="swiper-pagination"></div>';
			}

			if ( $buttons ) {
				echo '<div class="swiper-button-next ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Next', 'micemade-elements' ) . '</span></div>';
				echo '<div class="swiper-button-prev ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Previous', 'micemade-elements' ) . '</span></div>';
			}
			echo '</div>';
		}

		wp_reset_postdata();

		if ( $quickview ) {
			// Load WC single product JS scripts.
			do_action( 'micemade_elements_single_product_scripts' );
		}

	}

	//protected function content_template() {}

	// public function render_plain_content( $instance = [] ) {}
	/*
	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
	*/

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products_Slider() );
