<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Stack;
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

		$today = gmdate( 'YmdGi', time() );

		if ( ! wp_script_is( 'micemade-slider-js', 'registered' ) ) {
			wp_register_script( 'micemade-slider-js', MICEMADE_ELEMENTS_URL . 'assets/js/custom/handlers/slider.js', array( 'elementor-frontend' ), $today, true );
		}
		if ( ! wp_script_is( 'micemade-slider-js', 'enqueued' ) ) {
			wp_enqueue_script( 'micemade-slider-js' );
		}
	}

	/**
	 * Get script dependencies.
	 *
	 * @access public
	 *
	 * @return array Scripts.
	 */
	public function get_script_depends() {
		return array( 'micemade-slider-js', 'jquery-swiper' );
	}

	/**
	 * Get widget name.
	 *
	 * @access public
	 *
	 * @return string widget name.
	 */
	public function get_name() {
		return 'micemade-wc-products-slider';
	}

	/**
	 * Widget title.
	 *
	 * @access public
	 *
	 * @return string widget title.
	 */
	public function get_title() {
		return __( 'Micemade WC products slider', 'micemade-elements' );
	}

	/**
	 * Get widget icon (in editor).
	 *
	 * @access public
	 *
	 * @return string widget icon.
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since  2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return array( 'micemade', 'woocommerce', 'products', 'carousel', 'slider' );
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	/**
	 * Register all widget controls.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Micemade products slider', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'heading_product_query_basic',
			array(
				'label'     => __( 'Basic product query options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Total products', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 0,
				'step'    => 1,
				'title'   => __( 'Enter total number of products to show', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'   => __( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'step'    => 1,
				'title'   => __( 'Offset is number of skipped products', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'heading_filtering',
			array(
				'label'     => __( 'Additional query options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_control(
			'exclude_cats',
			array(
				'label'       => esc_html__( 'Exclude product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			)
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
			array(
				'label'   => __( 'Products filters', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => array(
					'latest'       => __( 'Latest products', 'micemade-elements' ),
					'featured'     => __( 'Only featured products', 'micemade-elements' ),
					'on_sale'      => __( 'Only products on sale', 'micemade-elements' ),
					'random'       => __( 'Random products', 'micemade-elements' ),
					'best_sellers' => __( 'Order by best selling', 'micemade-elements' ),
					'best_rated'   => __( 'Order by best rated', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'products_in',
			array(
				'label'       => esc_html__( 'Select products', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_posts_array', 'product' ),
				'multiple'    => true,
				'label_block' => true,
			)
		);

		$this->add_control(
			'no_outofstock',
			array(
				'label'     => esc_html__( 'Hide out of stock products', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => __( 'Order by', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => __( 'Date', 'micemade-elements' ),
					'name'       => __( 'Name', 'micemade-elements' ),
					'modified'   => __( 'Last date modified', 'micemade-elements' ),
					'menu_order' => __( 'Ordered in admin', 'micemade-elements' ),
				),
				'condition' => array(
					'filters!' => array( 'best_sellers', 'best_rated' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => __( 'Order', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => __( 'Descending', 'micemade-elements' ),
					'ASC'  => __( 'Ascending', 'micemade-elements' ),
				),
				'condition' => array(
					'filters!' => array( 'best_sellers', 'best_rated' ),
				),
			)
		);

		$this->end_controls_section();
		// End "Content" tab with products query settings.

		/***************************************************
		 * SHARED CONTROLS FOR SLIDER SETTINGS
		 *
		 * "elementor/element/{$section_name}/{$section_id}/after_section_end"
		 * hooked in "class-micemade-elements" and file "includes/class-micemade-shared-controls.php
		 * https://code.elementor.com/php-hooks/#elementorelementsection_namesection_idbefore_section_start
		 * *************************************************
		*/

		// TAB 2 - STYLES FOR PRODUCTS.
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Layout general', 'micemade-elements' ),
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
					'catalog' => __( 'WC Catalog style', 'micemade-elements' ),
					'style_1' => __( 'Style one', 'micemade-elements' ),
					'style_2' => __( 'Style two', 'micemade-elements' ),
					'style_3' => __( 'Style three', 'micemade-elements' ),
					'style_4' => __( 'Style four', 'micemade-elements' ),
				),
			)
		);
		$this->add_control(
			'style_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>"WC catalog style" inherits default WooCommerce layout from shop/catalog or category pages. Some themes or plugins may override this layout with their own layouts.</small>', 'micemade-elements' ),
				'content_classes' => 'your-class',
				'separator'       => 'after',
				'condition'       => array(
					'style' => array( 'catalog' ),
				),
			)
		);

		$this->add_responsive_control(
			'wc_catalog_align',
			array(
				'label'     => __( 'Elements alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} ul.products' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'catalog' ),
				),
			)
		);

		$this->add_control(
			'img_format',
			array(
				'label'     => esc_html__( 'Product image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_responsive_control(
			'product_text_height',
			array(
				'label'     => __( 'Product height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 400,
				),
				'range'     => array(
					'px' => array(
						'max'  => 800,
						'min'  => 0,
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap' => 'height: {{SIZE}}px;',
				),
				'condition' => array(
					'style' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'product_bottom_margin',
			array(
				'label'     => __( 'Product bottom margin', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
				),
				'range'     => array(
					'px' => array(
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .swiper-slide' => 'margin-bottom: {{SIZE}}px;',
				),
			/*
			'condition' => [
				'style' => [ 'style_3', 'style_4' ],
			],
			*/
			)
		);

		$this->add_control(
			'slider_items_padding_border',
			array(
				'label'     => __( 'Slider items padding and border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_responsive_control(
			'slider_items_padding',
			array(
				'label'      => esc_html__( 'Slider items padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'     => __( 'Slider items border' ),
				'name'      => 'productslider_post_border',
				'selector'  => '{{WRAPPER}} .inner-wrap',
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->end_controls_section();

		// Section product info elements.
		$this->start_controls_section(
			'section_info_elements',
			array(
				'label'     => esc_html__( 'Toggle elements', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'     => esc_html__( 'Show price', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'add_to_cart',
			array(
				'label'     => esc_html__( 'Show "Add to cart"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'quickview',
			array(
				'label'     => esc_html__( 'Show "Quick view"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_control(
			'short_desc',
			array(
				'label'     => esc_html__( 'Show product short description', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				// 'default' => 'yes',
			)
		);

		$this->add_control(
			'posted_in',
			array(
				'label'     => esc_html__( 'Show posted in categories', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			)
		);

		$this->end_controls_section();
		// end product info elements.

		// Section product info box styling.
		$this->start_controls_section(
			'section_info_styling',
			array(
				'label'     => esc_html__( 'Elements styling', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_product_background' );
		$this->start_controls_tab(
			'tab_product_background_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'post_info_background_color',
			array(
				'label'     => __( 'Background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'style!' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_control(
			'post_overlay_color',
			array(
				'label'     => __( 'Overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-overlay' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->end_controls_tab();
		// HOVER.
		$this->start_controls_tab(
			'tab_product_background_hover',
			array(
				'label' => __( 'Hover', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'post_info_background_color_hover',
			array(
				'label'     => __( 'Hover product info background', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'style!' => array( 'style_3' ),
				),
			)
		);

		$this->add_control(
			'post_overlay_hover_color',
			array(
				'label'     => __( 'Hover product overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap:hover .post-overlay' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_3' ),
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'product_info_align',
			array(
				'label'                => __( 'Horizontal align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
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
				'default'              => 'center',
				'selectors'            => array(
					'{{WRAPPER}} .post-text, {{WRAPPER}} .post-text > *:not(.button)' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'   => 'text-align: left; align-items: flex-start',
					'center' => 'text-align: center; align-items: center',
					'right'  => 'text-align: right; align-items: flex-end',
				),
			)
		);

		$this->add_responsive_control(
			'content_vertical_alignment',
			array(
				'label'       => __( 'Vertical align', 'micemade-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Start', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .inner-wrap, {{WRAPPER}} .post-text' => 'justify-content: {{VALUE}};',
				),
				'condition'   => array(
					'style' => array( 'style_2', 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'product_elements_spacing',
			array(
				'label'     => __( 'Elements vertical spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .post-text h4'          => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .meta'       => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .price-wrap' => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text .add-to-cart-wrap' => 'padding: 0 0 {{SIZE}}px;',
					'{{WRAPPER}} .post-text p'           => 'padding: 0 0 {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'product_info_padding',
			array(
				'label'      => esc_html__( 'Product info box padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_padding',
			array(
				'label'      => esc_html__( 'Product buttons padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .add_to_cart_button, {{WRAPPER}} .mme-quick-view' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
				'separator'  => 'before',
				'condition'  => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->end_controls_section();
		// end product info styling.

		$this->start_controls_section(
			'section_product_thumb_settings',
			array(
				'label'     => __( 'Product image settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_width',
			array(
				'label'       => __( 'Image container width', 'elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 100,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .post-thumb-back' => 'width: {{VALUE}}%;',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_as_back_height',
			array(
				'label'       => esc_html__( 'Image container height', 'micemade-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 100,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .post-thumb-back' => 'height: {{VALUE}}%;',
				),
			)
		);
		$this->add_responsive_control(
			'img_container_pos',
			array(
				'label'       => esc_html__( 'Image container position', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'top__left',
				'label_block' => true,
				'options'     => array(
					'top__left'      => esc_html__( 'Top left', 'micemade-elements' ),
					'top__center'    => esc_html__( 'Top center', 'micemade-elements' ),
					'top__right'     => esc_html__( 'Top right', 'micemade-elements' ),
					'middle__left'   => esc_html__( 'Middle Left ', 'micemade-elements' ),
					'middle'         => esc_html__( 'Middle', 'micemade-elements' ),
					'middle__right'  => esc_html__( 'Middle right ', 'micemade-elements' ),
					'bottom__left'   => esc_html__( 'Bottom left ', 'micemade-elements' ),
					'bottom__center' => esc_html__( 'Bottom center ', 'micemade-elements' ),
					'bottom__right'  => esc_html__( 'Bottom right ', 'micemade-elements' ),
				),
			)
		);
		$this->add_responsive_control(
			'back_image_position',
			array(
				'label'       => esc_html__( 'Image background position', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center',
				'label_block' => true,
				'options'     => array(
					'left top'      => esc_html__( 'Top left', 'micemade-elements' ),
					'center top'    => esc_html__( 'Top center', 'micemade-elements' ),
					'right top'     => esc_html__( 'Top right', 'micemade-elements' ),
					'left center'   => esc_html__( 'Middle Left ', 'micemade-elements' ),
					'center'        => esc_html__( 'Middle', 'micemade-elements' ),
					'right center'  => esc_html__( 'Middle right ', 'micemade-elements' ),
					'left bottom'   => esc_html__( 'Bottom left ', 'micemade-elements' ),
					'center bottom' => esc_html__( 'Bottom center ', 'micemade-elements' ),
					'right bottom'  => esc_html__( 'Bottom right ', 'micemade-elements' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .post-thumb-back' => 'background-position: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_background_size',
			array(
				'label'       => esc_html__( 'Image background size', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'cover',
				'label_block' => true,
				'options'     => array(
					'auto'    => esc_html__( 'Auto', 'micemade-elements' ),
					'cover'   => esc_html__( 'Cover', 'micemade-elements' ),
					'contain' => esc_html__( 'Contain', 'micemade-elements' ),
					'custom'  => esc_html__( 'Custom', 'micemade-elements' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .post-thumb-back' => 'background-size: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_background_size_custom',
			array(
				'label'       => esc_html__( 'Custom image size', 'micemade-elements' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '',
				'min'         => 0,
				'max'         => 200,
				'step'        => 5,
				'label_block' => true,
				'selectors'   => array(
					'{{WRAPPER}} .post-thumb-back' => 'background-size: {{VALUE}}% !important;',
				),
				'condition'   => array(
					// 'layout' => 'image_background',
					'thumb_background_size' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		// TITLE STYLES.
		$this->start_controls_section(
			'section_title',
			array(
				'label'     => __( 'Product title settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'tabs_button_style' );
		// NORMAL.
		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'title_text_color',
			array(
				'label'     => __( 'Title Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .post-text h4 a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'title_back_color',
			array(
				'label'     => __( 'Title Back Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .post-text h4' => 'background-color: {{VALUE}};padding-left:0.5em;padding-right:0.5em;',
				),
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
					'{{WRAPPER}} .post-text h4:hover a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_back_color_hover',
			array(
				'label'     => __( 'Title Back Color on Hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .post-text h4:hover' => 'background-color: {{VALUE}};padding-left:0.5em;padding-right:0.5em;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		// end tabs.

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'productslider_title_border',
				'label'       => __( 'Title Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text h4',
			)
		);

		$this->add_responsive_control(
			'productslider_title_padding',
			array(
				'label'      => esc_html__( 'Title padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-text h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Title typohraphy.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text h4',
			)
		);

		$this->end_controls_section();

		// PRODUCT DETAILS.
		$this->start_controls_section(
			'section_text',
			array(
				'label'     => __( 'Product info typography', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style!' => array( 'catalog' ),
				),
				'separator' => 'after',
			)
		);

		$this->add_control(
			'categories_typo',
			array(
				'label'     => __( 'Categories', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'posted_in!' => '',
				),
			)
		);

		$this->add_control(
			'categories_text_color',
			array(
				'label'     => __( 'Categories Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .meta a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'posted_in!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'categories_typography',
				'label'     => __( 'Categories typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .meta',
				'condition' => array(
					'posted_in!' => '',
				),
			)
		);

		$this->add_control(
			'short_desc_options',
			array(
				'label'     => __( 'Short description', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'short_desc!' => '',
				),
			)
		);

		$this->add_control(
			'excerpt_text_color',
			array(
				'label'     => __( 'Short description text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'short_desc!' => '',
				),
			)
		);

		// Short desc typography.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'text_typography',
				'label'     => __( 'Short description typography', 'micemade-elements' ),
				'scheme'    => Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .post-text p',
				'condition' => array(
					'short_desc!' => '',
				),
			)
		);

		// Price controls.
		$this->add_control(
			'price_options',
			array(
				'label'     => __( 'Price', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'price!' => '',
				),
			)
		);

		$this->add_control(
			'price_text_color',
			array(
				'label'     => __( 'Price text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .price' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'price!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'price_typography',
				'label'     => __( 'Price typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .price',
				'condition' => array(
					'price!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'product_button_typography',
				'label'       => __( 'Buttons typography', 'micemade-elements' ),
				'label_block' => true,
				'selector'    => '{{WRAPPER}} .add_to_cart_button',
				'separator'   => 'before',
				'condition'   => array(
					'style!' => array( 'catalog' ),
				),
			)
		);

		$this->add_control(
			'prod_det_button_options',
			array(
				'label'     => __( '"Product details" link', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'css_class',
			array(
				'label'   => __( 'CSS for "Product details"', 'micemade-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'title'   => __( 'Style the "Product details" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_quickview',
			array(
				'label'     => __( 'Quick view settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'quickview!' => '',
					'style!'     => 'catalog',
				),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'   => __( 'Button icon', 'micemade-elements' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fa fa-eye',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'quickview_overlay_color',
			array(
				'label'     => __( 'Quick view overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#mmqv-{{ID}}' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'modal_margins',
			array(
				'label'       => __( 'Quick view modal side margins', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
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
					'#mmqv-{{ID}} .mmqv-holder' => 'left: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
		// end of section_quickview.
	}

	protected function render() {

		// Get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$posts_per_page = (int) $settings['posts_per_page'];
		$offset         = (int) $settings['offset'];
		$pagination     = $settings['pagination'];
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

		// Query args: ( hook in includes/wc-functions.php ).
		$args = apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $exclude_cats, $filters, $offset, $products_in, $no_outofstock, $orderby, $order );

		$products_query = new \WP_Query( $args );

		if ( $products_query->have_posts() ) {

			// Main slider container - add CSS classes and data settings.
			$this->add_render_attribute(
				array(
					'container' => array(
						'class'           => array(
							'swiper-container',
							'micemade-elements_slider',
							'micemade-elements_products_slider',
							'woocommerce',
							$style,
						),
						'id'              => 'slider_' . $this->get_id(),
						'data-mme-widget' => 'micemade_slider_wc_products',
					),
				)
			);

			if ( $img_cont_pos ) {
				$this->add_render_attribute( 'container', 'class', 'container-' . $img_cont_pos );
			}
			?>
			<div <?php $this->print_render_attribute_string( 'container' ); ?>>

			<?php
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
					function ( $classes ) {
						// Remove the "first" and "last" added by wc_get_loop_class().
						$classes = array_diff( $classes, array( 'first', 'last' ) );
						// Add the slides classes.
						return apply_filters( 'micemade_elements_product_item_classes', $classes, 'add', array( 'swiper-slide', 'item' ) );
					},
					10
				);
			}

			while ( $products_query->have_posts() ) {

				$products_query->the_post();

				// If style as in WC content-product template.
				if ( 'catalog' === $style ) {
					wc_get_template_part( 'content', 'product' );
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
					function ( $classes ) {
						return apply_filters( 'micemade_elements_product_item_classes', $classes, '', array( 'swiper-slide', 'item' ) );
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

	// protected function content_template() {}

	// public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products_Slider() );
