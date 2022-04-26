<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Typography;

class Micemade_WC_Products extends Widget_Base {

	/**
	 * Item classes
	 *
	 * @var string $item_classes
	 *
	 * set here to use as "global" var in "post_class" hook
	 */
	public $item_classes;

	public function get_name() {
		return 'micemade-wc-products';
	}

	public function get_title() {
		return esc_html__( 'Micemade WC Products', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_main',
			array(
				'label' => esc_html__( 'Micemade WC Products', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'heading_product_query_basic',
			array(
				'label'     => esc_html__( 'Basic product query options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Total products', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6',
				'title'   => esc_html__( 'Enter total number of products to show', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'   => esc_html__( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => 0,
				'step'    => 1,
				'title'   => esc_html__( 'Offset is a number of skipped products', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'heading_product_query',
			array(
				'label'     => esc_html__( 'Additional query options', 'micemade-elements' ),
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
				'title'       => __( 'Enter total number of products to show', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'exclude_cats_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => '<small>' . esc_html__( 'Make sure not to exclude already included product category.', 'micemade-elements' ) . '</small>',
				'content_classes' => 'your-class',
				'separator'       => 'after',
			)
		);

		$this->add_control(
			'filters',
			array(
				'label'   => esc_html__( 'Products filters', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => array(
					'latest'       => esc_html__( 'Latest products', 'micemade-elements' ),
					'featured'     => esc_html__( 'Only featured products', 'micemade-elements' ),
					'on_sale'      => esc_html__( 'Only products on sale', 'micemade-elements' ),
					'random'       => esc_html__( 'Random products', 'micemade-elements' ),
					'best_sellers' => esc_html__( 'Order by best selling', 'micemade-elements' ),
					'best_rated'   => esc_html__( 'Order by best rated', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'products_in',
			array(
				'label'    => esc_html__( 'Select products', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => array(),
				'options'  => apply_filters( 'micemade_posts_array', 'product' ),
				'multiple' => true,
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
				'label'     => esc_html__( 'Order by', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => esc_html__( 'Date', 'micemade-elements' ),
					'name'       => esc_html__( 'Name', 'micemade-elements' ),
					'modified'   => esc_html__( 'Last date modified', 'micemade-elements' ),
					'menu_order' => esc_html__( 'Ordered in admin', 'micemade-elements' ),
				),
				'condition' => array(
					'filters!' => array( 'best_sellers', 'best_rated' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'micemade-elements' ),
					'ASC'  => esc_html__( 'Ascending', 'micemade-elements' ),
				),
				'condition' => array(
					'filters!' => array( 'best_sellers', 'best_rated' ),
				),
			)
		);

		$this->add_control(
			'heading_display_potions',
			array(
				'label'     => esc_html__( 'Display options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$items_num = array(
			1 => esc_html__( 'One', 'micemade-elements' ),
			2 => esc_html__( 'Two', 'micemade-elements' ),
			3 => esc_html__( 'Three', 'micemade-elements' ),
			4 => esc_html__( 'Four', 'micemade-elements' ),
			6 => esc_html__( 'Six', 'micemade-elements' ),
		);

		$this->add_control(
			'products_per_row',
			array(
				'label'   => esc_html__( 'Products per row', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 4,
				'options' => $items_num,
			)
		);

		$this->add_control(
			'products_per_row_tab',
			array(
				'label'   => esc_html__( 'Products per row (tablets)', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 2,
				'options' => $items_num,
			)
		);

		$this->add_control(
			'products_per_row_mob',
			array(
				'label'   => esc_html__( 'Products per row (mobiles)', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 2,
				'options' => $items_num,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => esc_html__( 'Products columns (devices)', 'micemade-elements' ),
				'type'            => Controls_Manager::SELECT,
				'desktop_default' => 4,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'options'         => $items_num,
			)
		);

		$this->end_controls_section();

		// TAB 2 - STYLES FOR POSTS.
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Basic settings', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Base style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => array(
					'catalog' => esc_html__( 'WC Catalog style', 'micemade-elements' ),
					'style_1' => esc_html__( 'Style one', 'micemade-elements' ),
					'style_2' => esc_html__( 'Style two', 'micemade-elements' ),
					'style_3' => esc_html__( 'Style three', 'micemade-elements' ),
					'style_4' => esc_html__( 'Style four', 'micemade-elements' ),
				),
			)
		);
		$this->add_control(
			'style_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => '<small>' . esc_html__( '"WC catalog style" inherits default WooCommerce layout from shop/catalog or category pages. Some themes or plugins may override this layout with their own layouts.', 'micemade-elements' ) . '</small>',
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
				'label'     => esc_html__( 'Elements alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Product height', 'micemade-elements' ),
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
			'horiz_spacing',
			array(
				'label'           => esc_html__( 'Products horizontal spacing', 'micemade-elements' ),
				'type'            => Controls_Manager::SLIDER,
				'desktop_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 10,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'           => array(
					'px' => array(
						'max'  => 50,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} ul.products > li, .mme-row > li' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				),

			)
		);

		$this->add_responsive_control(
			'vert_spacing',
			array(
				'label'           => esc_html__( 'Products bottom spacing', 'micemade-elements' ),
				'type'            => Controls_Manager::SLIDER,
				'desktop_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'tablet_default'  => array(
					'size' => 10,
					'unit' => 'px',
				),
				'mobile_default'  => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'           => array(
					'px' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors'       => array(
					'{{WRAPPER}} ul.products > li, {{WRAPPER}} .mme-row > li' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-bottom:-{{SIZE}}px;',
				),
			)
		);

		$this->end_controls_section();
		// end TAB 2 - STYLES FOR POSTS.

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
				'label'       => esc_html__( 'Show product categories (posted in)', 'micemade-elements' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => __( 'No', 'elementor' ),
				'label_on'    => __( 'Yes', 'elementor' ),
				'label_block' => true,
				'default'     => 'yes',
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
				'label' => esc_html__( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'post_info_background_color',
			array(
				'label'     => esc_html__( 'Background color', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Overlay color', 'micemade-elements' ),
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
				'label' => esc_html__( 'Hover', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'post_info_background_color_hover',
			array(
				'label'     => esc_html__( 'Hover product info background', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Hover product overlay color', 'micemade-elements' ),
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
				'label'                => esc_html__( 'Horizontal alignment', 'micemade-elements' ),
				'label_block'          => true,
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .post-text, {{WRAPPER}} .add-to-cart-wrap' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'   => 'text-align: left; justify-content: flex-start',
					'center' => 'text-align: center; justify-content: center',
					'right'  => 'text-align: right; justify-content: flex-end',
				),
			)
		);

		$this->add_responsive_control(
			'content_vertical_alignment',
			array(
				'label'     => esc_html__( 'Vertical Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'flex-start' => esc_html__( 'Top', 'micemade-elements' ),
					'center'     => esc_html__( 'Middle', 'micemade-elements' ),
					'flex-end'   => esc_html__( 'Bottom', 'micemade-elements' ),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .post-text' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'style' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'product_elements_spacing',
			array(
				'label'     => esc_html__( 'Vertical spacing for elements', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Product image settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'style' => array( 'style_3', 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_width',
			array(
				'label'       => esc_html__( 'Image container width', 'elementor' ),
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
				'label'     => esc_html__( 'Product title settings', 'micemade-elements' ),
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
				'label' => esc_html__( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'title_text_color',
			array(
				'label'     => esc_html__( 'Title Color', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Title Back Color', 'micemade-elements' ),
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
				'label' => esc_html__( 'Hover', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Title Color on Hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text h4:hover a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_back_color_hover',
			array(
				'label'     => esc_html__( 'Title Back Color on Hover', 'micemade-elements' ),
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
				'label'       => esc_html__( 'Title Border', 'micemade-elements' ),
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
				'label'    => esc_html__( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text h4',
			)
		);

		$this->end_controls_section();

		// PRODUCT DETAILS.
		$this->start_controls_section(
			'section_text',
			array(
				'label'     => esc_html__( 'Product info typography', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Categories', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'posted_in!' => '',
				),
			)
		);

		$this->add_control(
			'categories_text_color',
			array(
				'label'     => esc_html__( 'Categories Text Color', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Categories typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .meta',
				'condition' => array(
					'posted_in!' => '',
				),
			)
		);

		$this->add_control(
			'short_desc_options',
			array(
				'label'     => esc_html__( 'Short description', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Short description text Color', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Short description typography', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Price', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Price text Color', 'micemade-elements' ),
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
				'label'     => esc_html__( 'Price typography', 'micemade-elements' ),
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
				'label'       => esc_html__( 'Buttons typography', 'micemade-elements' ),
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
				'label'     => esc_html__( '"Product details" link', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'css_class',
			array(
				'label'   => esc_html__( 'CSS for "Product details"', 'micemade-elements' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'title'   => esc_html__( 'Style the "Product details" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_quickview',
			array(
				'label'     => esc_html__( 'Quick view settings', 'micemade-elements' ),
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
				'label'   => esc_html__( 'Button icon', 'micemade-elements' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-eye',
					'library' => 'solid',
				),
			)
		);

		$this->add_control(
			'quickview_overlay_color',
			array(
				'label'     => esc_html__( 'Quick view overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'#mmqv-{{ID}}' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'modal_margins',
			array(
				'label'       => esc_html__( 'Quick view modal side margins', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
				// 'default'     => [
				// 'size' => 50,
				// 'unit' => '%',
				// ],
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
		$categories     = $settings['categories'];
		$exclude_cats   = $settings['exclude_cats'];
		$filters        = $settings['filters'];
		$products_in    = $settings['products_in'];
		$no_outofstock  = $settings['no_outofstock'];
		$orderby        = $settings['orderby'];
		$order          = $settings['order'];
		$ppr            = (int) $settings['products_per_row'];
		$ppr_tab        = (int) $settings['products_per_row_tab'];
		$ppr_mob        = (int) $settings['products_per_row_mob'];
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

		// Columns - responsive settings.
		$columns = array();
		foreach ( $settings as $key => $value ) {
			if ( 'columns' === $key && $value ) {
				$columns[ (string) $value ] = $key . '_desktop';
			} elseif ( strpos( $key, 'columns_' ) === 0 ) {
				$columns[ (string) $value ] = $key;
			}
		}

		// Create grid CSS selectors based on column settings.
		if ( NEW_CSS ) {
			$this->item_classes = micemade_elements_grid( $columns );
		} else {
			$this->item_classes = micemade_elements_grid_class( intval( $ppr ), intval( $ppr_tab ), intval( $ppr_mob ) ); // To deprecate.

		}

		// Query args: ( hook in includes/wc-functions.php ).
		$args = apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $exclude_cats, $filters, $offset, $products_in, $no_outofstock, $orderby, $order ); // hook in includes/wc-functions.php.

		// Add (inject) grid classes to products in loop.
		// ( in "content-product.php" template ).
		// "item" class is to support Micemade Themes.
		add_filter(
			'woocommerce_post_class',
			function( $classes ) {
				// Remove the "first" and "last" added by wc_get_loop_class().
				$classes = array_diff( $classes, array( 'first', 'last' ) );
				// Add MM grid classes.
				return apply_filters( 'micemade_elements_product_item_classes', $classes, 'add', array( $this->item_classes, 'item' ) );
			},
			10
		);

		$products_query = new \WP_Query( $args );

		if ( $products_query->have_posts() ) {

			// Main products container - add CSS classes and data settings.
			$this->add_render_attribute(
				array(
					'container' => array(
						'class' => array(
							'woocommerce',
							'woocommerce-page',
							'micemade-elements_wc-catalog',
							$style,
						),
					),
				)
			);

			if ( $img_cont_pos ) {
				$this->add_render_attribute( 'container', 'class', 'container-' . $img_cont_pos );
			}
			?>

			<div <?php $this->print_render_attribute_string( 'container' ); ?> >

			<?php
			if ( 'catalog' === $style ) {
				// Start loop.
				$loop_start = woocommerce_product_loop_start();
				// "Inject" 'mme-row' class to loop start classes.
				$loop_start = str_replace( 'class="', 'class="mme-row ', $loop_start );
				echo wp_kses_post( $loop_start );
			} else {
				echo '<ul class="mme-row">';
			}

			while ( $products_query->have_posts() ) {

				$products_query->the_post();

				// If style as in WC content-product template.
				if ( 'catalog' === $style ) {
					wc_get_template_part( 'content', 'product' );
					// else, use plugin loop items.
				} else {
					apply_filters( 'micemade_elements_loop_product', $style, $this->item_classes, $img_format, $posted_in, $short_desc, $price, $add_to_cart, $css_class, $quickview, $selected_icon );// hook in includes/wc-functions.php.
				}
			}
			// end loop.

			if ( 'catalog' === $style ) {
				woocommerce_product_loop_end();
			} else {
				echo '</ul>'; // .swipper-wrapper.
			}

			echo '</div>';
		}

		// "Clean" or "reset" post_class
		// avoid conflict with other "post_class" functions
		add_filter(
			'woocommerce_post_class',
			function( $classes ) {
				return apply_filters( 'micemade_elements_product_item_classes', $classes, '', array( $this->item_classes, 'item' ) );
			},
			10
		);

		wp_reset_postdata();

		if ( $quickview ) {
			// Load WC single product JS scripts.
			do_action( 'micemade_elements_single_product_scripts' );
		}

	}

	protected function content_template() {}

	public function render_plain_content( $instance = array() ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products() );
