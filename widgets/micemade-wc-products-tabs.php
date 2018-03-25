<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Micemade_WC_Products_Tabs extends Widget_Base {


	public function get_name() {
		return 'micemade-wc-products-tabs';
	}

	public function get_title() {
		return __( 'Micemade WC Products Tabs', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-tabs';
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
				//'prefix_class' => 'elementor-tabs-view-',
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
						'products_per_row'     => 4,
						'products_per_row_tab' => 2,
						'products_per_row_mob' => 2,
						'categories'           => '',
						'exclude_cats'         => '',
						'filters'              => '',
					],
				],
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
						'default' => '6',
						'title'   => __( 'Enter total number of products to show', 'micemade-elements' ),
					],

					[
						'name'     => 'categories',
						'label'    => esc_html__( 'Select product categories', 'micemade-elements' ),
						'type'     => Controls_Manager::SELECT2,
						'default'  => array(),
						'options'  => apply_filters( 'micemade_elements_terms', 'product_cat' ),
						'multiple' => true,
					],

					[
						'name'     => 'exclude_cats',
						'label'    => esc_html__( 'Exclude product categories', 'micemade-elements' ),
						'type'     => Controls_Manager::SELECT2,
						'default'  => array(),
						'options'  => apply_filters( 'micemade_elements_terms', 'product_cat' ),
						'multiple' => true,
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

				],
				'title_field' => '{{{ tab_title }}}',
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
				'default'   => [
					'size' => '',
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
				'label'     => __( 'Products bottom spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '20',
				],
				'range'     => [
					'px' => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.products > li' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'         => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);
		/*
		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'micemade-elements' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		*/
		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => __( 'Tabs', 'micemade-elements' ),
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
					'{{WRAPPER}} .tab-title, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tab-content, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .tab-title.active, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tab-content, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-color: {{VALUE}};',
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

		$this->add_responsive_control(
			'tab_padding',
			[
				'label'      => esc_html__( 'Tabs padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label'     => __( 'Title', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label'     => __( 'Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title' => 'color: {{VALUE}};',
				],
				/* discarded - color from Elementor pallete
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				 */
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label'     => __( 'Active Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tab-title.active' => 'color: {{VALUE}};',
				],
				/* discarded - color from Elementor pallete
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				], */
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .tab-title',
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
		$tabs = $this->get_settings( 'tabs' );
		$type = $this->get_settings( 'type' );
		?>

		<div class="micemade-elements_tabs <?php echo esc_attr( $type ); ?>" role="tablist">

			<?php
			$counter        = 1;
			$tab_status     = '';
			$content_status = '';
			?>

			<div class="tabs-wrapper" role="tab">
				<?php
				foreach ( $tabs as $item ) {
					$tab_status = ( 1 === $counter ) ? ' active' : '';

					echo '<div class="tab-title' . esc_attr( $tab_status ) . '" data-tab="' . esc_attr( $counter ) . '">' . esc_html( $item['tab_title'] ) . '</div>';

					$counter++;
				}
				?>
			</div>

			<?php $counter = 1; ?>
			<div class="tabs-content-wrapper" role="tabpanel">

				<?php
				foreach ( $tabs as $item ) {
					$content_status = ( 1 === $counter ) ? ' active' : '';
					$tab_title      = $item['tab_title'];

					echo '<span class="tab-title tab-mobile-title' . esc_attr( $content_status ) . '" data-tab="' . esc_attr( $counter ) . '">' . esc_html( $tab_title ) . '</span>';

					$posts_per_page = $item['posts_per_page'];
					$ppr            = $item['products_per_row'];
					$ppr_tab        = $item['products_per_row_tab'];
					$ppr_mob        = $item['products_per_row_mob'];
					$posts_per_page = $item['posts_per_page'];
					$categories     = $item['categories'];
					$exclude_cats   = $item['exclude_cats'];
					$filters        = $item['filters'];

					// Start WC products.
					global $post;

					$this->mm_products_grid = micemade_elements_grid_class( intval( $ppr ), intval( $ppr_tab ), intval( $ppr_mob ) );

					$args = apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $exclude_cats, $filters ); // hook in includes/wc-functions.php

					// Add (inject) grid classes to products in loop
					// ( in "content-product.php" template )
					// "item" class is to support Micemade Themes
					add_filter( 'post_class', function( $classes ) {
						$classes[] = $this->mm_products_grid;
						$classes[] = 'item';
						return $classes;
					}, 10 );

					$products = get_posts( $args );
					if ( ! empty( $products ) ) {

						echo '<div class="woocommerce woocommerce-page micemade-elements_wc-catalog tab-content elementor-clearfix tab-' . esc_attr( $counter . $content_status ) . '">';

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
					add_filter( 'post_class', function( $classes ) {
						$classes_to_clean = array( $this->mm_products_grid, 'item' );
						$classes          = array_diff( $classes, $classes_to_clean );
						return $classes;
					}, 10 );

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

	/**
	 * Render tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {}
}
Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products_Tabs() );
