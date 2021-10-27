<?php
namespace Elementor;

// use Elementor\Controls_Manager;
// use Elementor\Controls_Stack;
// use Elementor\Core\Files\CSS\Post;
// use Elementor\Element_Base;
// use Elementor\Widget_Base;
// use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes\Typography;

class Micemade_WC_Products_Tabs extends Widget_Base {
	/*
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$this->add_actions();
	}
	 */
	public function get_name() {
		return 'micemade-wc-products-tabs';
	}

	public function get_title() {
		return __( 'Micemade WC Products Tabs', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_script_depends() {
		return [ 'ace' ];
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	/**
	 * Register tabs widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_tabs',
			[
				'label' => __( 'Tabs', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'   => __( 'Type', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'micemade-elements' ),
					'vertical'   => __( 'Vertical', 'micemade-elements' ),
				],
				'prefix_class' => 'mm-wc-tabs-type-',
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'       => __( 'Tabs Items', 'micemade-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'tab_title'            => __( 'Tab #1', 'micemade-elements' ),
						'posts_per_page'       => 4,
						'offset'               => 0,
						'products_per_row'     => 4,
						'products_per_row_tab' => 2,
						'products_per_row_mob' => 2,
						'categories'           => '',
						'exclude_cats'         => '',
						'filters'              => 'latest',
						'products_in'          => [],
						'no_outofstock'        => 'yes',
						'orderby'              => 'date',
						'order'                => 'DESC',
					],
				],
				'title_field' => '<i class="{{ icon }}"></i> {{{ tab_title }}}',
				'fields'      => [
					[
						'name'        => 'tab_title',
						'label'       => __( 'Tab title', 'micemade-elements' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => __( 'Tab Title', 'micemade-elements' ),
						'placeholder' => __( 'Tab Title', 'micemade-elements' ),
						'label_block' => true,
					],

					[
						'name'    => 'posts_per_page',
						'label'   => __( 'Total products', 'micemade-elements' ),
						'type'    => Controls_Manager::NUMBER,
						'default' => '4',
						'title'   => __( 'Enter total number of products to show', 'micemade-elements' ),
					],

					[
						'name'    => 'offset',
						'label'   => __( 'Offset', 'micemade-elements' ),
						'type'    => Controls_Manager::NUMBER,
						'default' => '',
						'title'   => __( 'Offset is a number of skipped products', 'micemade-elements' ),
					],

					[
						'name'        => 'categories',
						'label'       => esc_html__( 'Select product categories', 'micemade-elements' ),
						'type'        => Controls_Manager::SELECT2,
						'default'     => array(),
						'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
						'multiple'    => true,
						'label_block' => true,
					],

					[
						'name'        => 'exclude_cats',
						'label'       => esc_html__( 'Exclude product categories', 'micemade-elements' ),
						'type'        => Controls_Manager::SELECT2,
						'default'     => array(),
						'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
						'multiple'    => true,
						'label_block' => true,
					],

					[
						'name'    => 'filters',
						'label'   => __( 'Products filters', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'latest',
						'options' => [
							'latest'       => __( 'Latest products', 'micemade-elements' ),
							'featured'     => __( 'Featured products', 'micemade-elements' ),
							'best_sellers' => __( 'Best selling products', 'micemade-elements' ),
							'best_rated'   => __( 'Best rated products', 'micemade-elements' ),
							'on_sale'      => __( 'Products on sale', 'micemade-elements' ),
							'random'       => __( 'Random products', 'micemade-elements' ),
						],
					],

					[
						'name'     => 'products_in',
						'label'    => esc_html__( 'Select products', 'micemade-elements' ),
						'type'     => Controls_Manager::SELECT2,
						'default'  => [],
						'options'  => apply_filters( 'micemade_posts_array', 'product' ),
						'multiple' => true,
					],

					[
						'name'      => 'no_outofstock',
						'label'     => esc_html__( 'Hide out of stock products', 'micemade-elements' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_off' => __( 'No', 'elementor' ),
						'label_on'  => __( 'Yes', 'elementor' ),
						'default'   => 'yes',
					],

					[
						'name'    => 'orderby',
						'label'   => __( 'Order by', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'date',
						'options' => [
							'date'       => __( 'Date', 'micemade-elements' ),
							'name'       => __( 'Name', 'micemade-elements' ),
							'modified'   => __( 'Last date modified', 'micemade-elements' ),
							'menu_order' => __( 'Ordered in admin', 'micemade-elements' ),
						],
					],

					[
						'name'    => 'order',
						'label'   => __( 'Order', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'DESC',
						'options' => [
							'DESC' => __( 'Descending', 'micemade-elements' ),
							'ASC'  => __( 'Ascending', 'micemade-elements' ),
						],
						'separator'=> 'after',
					],

					[
						'name'    => 'products_per_row',
						'label'   => __( 'Products per row', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 4,
						'options' => [
							1 => __( 'One', 'micemade-elements' ),
							2 => __( 'Two', 'micemade-elements' ),
							3 => __( 'Three', 'micemade-elements' ),
							4 => __( 'Four', 'micemade-elements' ),
							6 => __( 'Six', 'micemade-elements' ),
						],
					],

					[
						'name'    => 'products_per_row_tab',
						'label'   => __( 'Products per row (tablets)', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 2,
						'options' => [
							1 => __( 'One', 'micemade-elements' ),
							2 => __( 'Two', 'micemade-elements' ),
							3 => __( 'Three', 'micemade-elements' ),
							4 => __( 'Four', 'micemade-elements' ),
							6 => __( 'Six', 'micemade-elements' ),
						],
					],

					[
						'name'    => 'products_per_row_mob',
						'label'   => __( 'Products per row (mobiles)', 'micemade-elements' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 2,
						'options' => [
							1 => __( 'One', 'micemade-elements' ),
							2 => __( 'Two', 'micemade-elements' ),
							3 => __( 'Three', 'micemade-elements' ),
							4 => __( 'Four', 'micemade-elements' ),
							6 => __( 'Six', 'micemade-elements' ),
						],
					],

					[
						'name'        => 'icon',
						'label'       => __( 'Tab icon', 'micemade-elements' ),
						'type'        => Controls_Manager::ICON,
						'label_block' => true,
						'default'     => '',
					],

				],

			]
		);

		$this->add_control(
			'heading_items_spacing_settings',
			[
				'label'     => __( 'Items spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'horiz_spacing',
			[
				'label'     => __( 'Products horizontal spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'max'  => 50,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.products > li' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'         => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'vert_spacing',
			[
				'label'           => __( 'Products bottom spacing', 'micemade-elements' ),
				'type'            => Controls_Manager::SLIDER,
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors'       => [
					'{{WRAPPER}} ul.products > li' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'         => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => __( 'Tabs style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navigation_width',
			[
				'label'     => __( 'Navigation Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => '%',
				],
				'range'     => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tabs-wrapper'         => 'flex-basis: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tabs-content-wrapper' => 'flex-basis: calc( 100% - {{SIZE}}{{UNIT}} )',
				],
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Tabs Alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} .tabs-wrapper' => 'text-align: {{VALUE}};',
				],
				'prefix_class' => 'mm-wc-tabs-align-',
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label'      => esc_html__( 'Tab padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tab-title > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'vertical-align',
			[
				'label'     => __( 'Vertical Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Top', 'micemade-elements' ),
						'icon'  => 'fa fa-caret-down',
					],
					'center'     => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-unsorted',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'micemade-elements' ),
						'icon'  => 'fa fa-caret-up',
					],

				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .tabs-wrapper, {{WRAPPER}} .tabs-content-wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

		// Tab styles title
		$this->add_control(
			'heading_tab_styles',
			[
				'label'     => __( 'Tab styles', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// ACTIVE, HOVER, INACTIVE.
		$this->start_controls_tabs( 'tabstyles' );

		// Styles for active tab
		$this->start_controls_tab(
			'style_active',
			[
				'label' => __( 'Active', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label'     => __( 'Active tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title.active' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title.active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tab-content.active' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'active_tab_desc',
			[
				'raw'             => __( 'Active tab background also applies to active content. Content styles can be overriden bellow, under "Tab content style" settings.', 'micemade-elements' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->end_controls_tab();// End active tab styles.

		// Styles for tab hover.
		$this->start_controls_tab(
			'style_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);
		$this->add_control(
			'tab_color_hover',
			[
				'label'     => __( 'Hover tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title:not(.active):hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'background_color_hover',
			[
				'label'     => __( 'Hover background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title:not(.active):hover' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		// Styles for tab Inactive.
		$this->start_controls_tab(
			'style_inactive',
			[
				'label' => __( 'Inactive', 'micemade-elements' ),
			]
		);
		$this->add_control(
			'inacitve_tab_color',
			[
				'label'     => __( 'Inactive tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'inactive_background_color',
			[
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();// End inactive tab styles.

		$this->end_controls_tabs();
		// End of style control tabs.

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'label'    => __( 'Title typography', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .tab-title',
				'scheme'   => Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'border_width',
			[
				'label'     => __( 'Border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tab-title, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     => __( 'Border Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#999999',
				'selectors' => [
					'{{WRAPPER}} .tab-title.active, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_icon',
			[
				'label'     => __( 'Tab icon', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'     => __( 'Icon size', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tab-icon.fa' => 'font-size: {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'     => __( 'Tab icon spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tab-icon' => 'margin: 0 {{SIZE}}{{UNIT}};',
				],

			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'        => __( 'Icon position', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'left'  => __( 'Left', 'micemade-elements' ),
					'right' => __( 'Right', 'micemade-elements' ),
				],
				'default'      => 'left',
				'prefix_class' => 'mm-wc-tabs-icon-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_content',
			[
				'label' => __( 'Tab content style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_background_color',
			[
				'label'     => __( 'Content background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-content.active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Content padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_border_width',
			[
				'label'     => __( 'Content border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				/* 'default'   => [
					'size' => 1,
				], */
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tab-title.active:not(.tab-mobile-title):before, {{WRAPPER}} .tab-title.active:not(.tab-mobile-title):after' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		/*
		$this->add_control(
			'product_tabs_custom_css',
			[
				'type'        => Controls_Manager::CODE,
				'label'       => __( 'Custom CSS', 'micemade-elements' ),
				'language'    => 'css',
				'render_type' => 'ui',
				'show_label'  => false,
				'separator'   => 'none',
			]
		);
		*/
		$this->end_controls_section();

	}

	/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$tabs  = $settings['tabs'];
		$type  = $settings['type'];
		$idint = substr( $this->get_id_int(), 0, 3 );
		?>

		<div class="micemade-elements_tabs <?php echo esc_attr( $type ); ?> elementor-tabs" role="tablist">

			<?php
			$counter        = 1;
			$tab_status     = '';
			$content_status = '';
			?>

			<div class="tabs-wrapper elementor-tabs-wrapper" role="tab">
				<?php
				foreach ( $tabs as $item ) {

					$tab_status = ( 1 === $counter ) ? ' active' : '';

					if ( $item['icon'] ) {
						$icon_html = '<i class="tab-icon ' . esc_attr( $item['icon'] ) . '"></i>';
					} else {
						$icon_html = '';
					}

					echo '<div class="tab-title elementor-tab-title' . esc_attr( $tab_status ) . '" data-tab="' . esc_attr( $counter ) . '" aria-controls=" elementor-tab-content-' . esc_attr( $idint . $counter ) . '" role="tab"><span>' . wp_kses_post( $icon_html . $item['tab_title'] ) . '</span></div>';

					$counter++;
				}
				?>
			</div>

			<?php $counter = 1; ?>
			<div class="tabs-content-wrapper elementor-tabs-content-wrapper" role="tabpanel">

				<?php
				foreach ( $tabs as $item ) {
					$content_status = ( 1 === $counter ) ? ' active' : '';

					if ( $item['icon'] ) {
						$icon_html = '<i class="tab-icon ' . esc_attr( $item['icon'] ) . '"></i>';
					} else {
						$icon_html = '';
					}

					echo '<div class="tab-title tab-mobile-title' . esc_attr( $content_status ) . '" data-tab="' . esc_attr( $counter ) . '"><span>' . wp_kses_post( $icon_html . $item['tab_title'] ) . '</span></div>';

					$ppp           = $item['posts_per_page'];
					$offset        = $item['offset'];
					$ppr           = $item['products_per_row'];
					$ppr_tab       = $item['products_per_row_tab'];
					$ppr_mob       = $item['products_per_row_mob'];
					$categories    = $item['categories'];
					$exclude_cats  = $item['exclude_cats'];
					$filters       = $item['filters'];
					$products_in   = $item['products_in'];
					$no_outofstock = $item['no_outofstock'];
					$orderby       = $item['orderby'];
					$order         = $item['order'];

					// Start WC products.
					global $post;

					$this->mm_products_grid = micemade_elements_grid_class( intval( $ppr ), intval( $ppr_tab ), intval( $ppr_mob ) );

					// Hook in includes/wc-functions.php.
					$args = apply_filters( 'micemade_elements_wc_query_args', $ppp, $categories, $exclude_cats, $filters, $offset, $products_in, $no_outofstock, $orderby, $order );

					// Add (inject) grid classes to products in loop
					// ( in "content-product.php" template )
					// "item" class is to support Micemade Themes
					add_filter(
						'woocommerce_post_class',
						function( $classes ) {
							// Remove the "first" and "last" added by wc_get_loop_class().
							$classes = array_diff( $classes, array( 'first', 'last' ) );
							return apply_filters( 'micemade_elements_product_item_classes', $classes, 'add', [ $this->mm_products_grid, 'item'] );
						},
						10
					);

					$products = get_posts( $args );
					if ( ! empty( $products ) ) {

						echo '<div class="woocommerce woocommerce-page micemade-elements_wc-catalog tab-content elementor-tab-content elementor-clearfix tab-' . esc_attr( $counter . $content_status ) . '">';
						echo '<ul class="products mme-row">';

						foreach ( $products as $post ) {

							setup_postdata( $post );
							wc_get_template_part( 'content', 'product' );
						}

						echo '</ul>';
						echo '</div>';
					}

					// "Clean" or "reset" post_class
					// avoid conflict with other "post_class" functions
					add_filter(
						'woocommerce_post_class',
						function( $classes ) {
							return apply_filters( 'micemade_elements_product_item_classes', $classes, '', [ $this->mm_products_grid, 'item'] );
						},
						10
					);

					wp_reset_postdata();
					?>

					<?php
					$counter++;
				} // end foreach.
				?>
			</div>
		</div>
		<?php
	}

	public function add_post_css( $post_css, Element_Base $element ) {

		$settings = $element->get_settings();

		if ( empty( $settings['product_tabs_custom_css'] ) ) {
			return;
		}

		$css = trim( $settings['product_tabs_custom_css'] );

		if ( empty( $css ) ) {
			return;
		}
		$css = str_replace( 'selector', $post_css->get_element_unique_selector( $element ), $css );

		// Add a css comment.
		$css = sprintf( '/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector() ) . $css;

		$post_css->get_stylesheet()->add_raw_css( $css );
	}
	/*
	protected function add_actions() {
		add_action( 'elementor/element/parse_css', [ $this, 'add_post_css' ], 10, 2 );
	}
	 */
	/**
	 * Render tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	// protected function content_template() {}
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products_Tabs() );
