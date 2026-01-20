<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Stack;

class Micemade_WC_Cat_Menu extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-cat-menu';
	}

	public function get_title() {
		return __( 'Micemade WC Cat Menu', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade WC Cat Menu', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => esc_html__( 'Order by', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => 'order',
				'options'     => [
					'order' => esc_html__( 'Category order', 'micemade-elements' ),
					'name'  => esc_html__( 'Name', 'micemade-elements' ),
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'count',
			[
				'label'     => esc_html__( 'Show product counts', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'hierarchical',
			[
				'label'     => esc_html__( 'Show hierarchy', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'     => esc_html__( 'Hide empty categories', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'exclude',
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
			'max_depth',
			[
				'label'   => __( 'Maximum depth', 'elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0',
				'min'     => 0,
				'step'    => 1,
				'title'   => esc_html__( 'depth of submenus', 'micemade-elements' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout and style general', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'orient',
			[
				'label'       => esc_html__( 'Orientation', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'vertical',
				'options'     => [
					'vertical'   => esc_html__( 'Vertical', 'micemade-elements' ),
					'horizontal' => esc_html__( 'Horizontal', 'micemade-elements' ),
				],
				'label_block' => true,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Menu alignment (top level)', 'micemade-elements' ),
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
					'space-between' => array(
						'title' => __( 'Justified', 'micemade-elements' ),
						'icon'  => 'fa fa-align-justify',
					),

				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} > .elementor-widget-container' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .product-categories' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'orient' => 'horizontal',
				],
				'prefix_class'       => 'wc_cat-menu-align-',
			]
		);

		/*
		$this->add_control(
			'style',
			[
				'label'   => __( 'Base style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1' => __( 'Style one', 'micemade-elements' ),
					'style_2' => __( 'Style two', 'micemade-elements' ),
					'style_3' => __( 'Style three', 'micemade-elements' ),
					'style_4' => __( 'Style four', 'micemade-elements' ),
				],
			]
		);

		*/

		$this->add_responsive_control(
			'cat_item_padding',
			[
				'label'      => esc_html__( 'Category items padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .cat-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'menu_width',
			[
				'label'          => __( 'Menu width', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'em', '%', 'vw' ],
				'default'        => [
					'unit' => '%',
					'size' => 100,
				],
				'tablet_default' => [
					'unit' => '%',
					'size' => 100,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .product-categories' => 'width: {{SIZE}}{{UNIT}};',
				],
				// 'condition' => [
				// 	'align' => 'space-between',
				// ],
			]
		);

		$this->add_responsive_control(
			'submenus_width',
			[
				'label'          => __( 'Submenus width', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'em', '%', 'vw' ],
				'default'        => [
					'unit' => 'px',
					'size' => 300,
				],
				'tablet_default' => [
					'unit' => '%',
					'size' => 100,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 100,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .children' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'last_border',
			[
				'label'     => esc_html__( 'Remove last item border', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			]
		);

		$this->end_controls_section();

		// Styles for both main and sub level menus.
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Menu styles (top level & sub)', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'tabs_button_style' );
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'cat_text_color',
			[
				'label'     => __( 'Category text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cat-item > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cat_background_color',
			[
				'label'     => __( 'Category item background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cat-item' => 'background-color: {{VALUE}};',
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
			'cat_text_color_hover',
			[
				'label'     => __( 'Category text color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-item:hover > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cat_background_color_hover',
			[
				'label'     => __( 'Category item background color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'menu_border',
			[
				'label'     => __( 'Menu (and submenu) border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'menu_border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .product-categories, {{WRAPPER}} .children',
				'separator'   => 'after',
			]
		);

		$this->add_responsive_control(
			'main_menu_items_sep',
			[
				'label'          => __( 'Items separator', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'default'        => [
					'unit' => 'px',
					'size' => 0,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .product-categories.horizontal > .cat-item' => 'border-right-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-categories.horizontal .children > .cat-item' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .product-categories.vertical .cat-item' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'toplevel_box_shadow',
				'label'     => __( 'Box Shadow', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .product-categories',
			]
		);

		$this->add_control(
			'typo_heading',
			[
				'label'     => __( 'Typography', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'top_level_typography',
				'label'    => __( 'Top level typography', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .product-categories > .cat-item a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submenu',
			[
				'label' => esc_html__( 'Submenu styles only', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'tabs_submenu_style' );
		$this->start_controls_tab(
			'tab_submenu_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'submenu_cat_text_color',
			[
				'label'     => __( 'Category text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .children > .cat-item > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submenu_cat_background_color',
			[
				'label'     => __( 'Category item background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .children > .cat-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'tab_submenu_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'submenu_cat_text_color_hover',
			[
				'label'     => __( 'Category text color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .children > .cat-item:hover > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submenu_cat_background_color_hover',
			[
				'label'     => __( 'Category item background color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .children > .cat-item:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'submenu_border',
			[
				'label'     => __( 'Submenu border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'submenu_border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .product-categories .children',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'submenu_box_shadow',
				'label'    => __( 'Box Shadow', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .children',
			]
		);

		$this->add_control(
			'submenu_typo_heading',
			[
				'label'     => __( 'Typography', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'submenu__typography',
				'label'    => __( 'Submenu typography', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .children > .cat-item a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_subicon',
			[
				'label' => esc_html__( 'Submenu indicator icon', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sub_icon',
			[
				'label'       => __( 'Choose icon', 'micemade-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => 'fa fa-plus',
				'separator'   => 'before',
				'include'     => [
					'fa fa-plus',
					'fa fa-plus-circle',
					'fa fa-plus-square',
					'fa fa-plus-square-o',
					'fa fa-minus',
					'fa fa-minus-circle',
					'fa fa-minus-square',
					'fa fa-minus-square-o',
					'fa fa-angle-right',
					'fa fa-angle-double-right',
					'fa fa-arrow-circle-o-right',
					'fa fa-arrow-circle-right',
					'fa fa-arrow-right',
					'fa fa-caret-right',
					'fa fa-caret-square-o-right',
					'fa fa-chevron-circle-right',
					'fa fa-chevron-right',
					'fa fa-long-arrow-right',
					'fa fa-toggle-right',
				],
			]
		);

		$this->add_responsive_control(
			'sub_icon_size',
			[
				'label'          => __( 'Submenu icon size', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'em' ],
				'default'        => [
					'unit' => 'px',
					'size' => 12,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .cat-item .fa' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_icon_box_size',
			[
				'label'          => __( 'Submenu icon box size', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'em' ],
				'default'        => [
					'unit' => 'px',
					'size' => 20,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .cat-item .fa' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_icon_radius',
			[
				'label'          => __( 'Submenu icon box radius', 'micemade-elements' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', 'em' ],
				'default'        => [
					'unit' => 'px',
					'size' => 10,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .cat-item .fa' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_icon_spacing',
			[
				'label'      => __( 'Submenu icon spacing', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default'    => [
					'unit' => 'px',
					'size' => 40,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .cat-item.has-children > a' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'sub_icon!'     => '',
					'hierarchical!' => '',
				],
			]
		);

		// HOVER TABS.
		$this->start_controls_tabs( 'submenu_icon_style' );
		$this->start_controls_tab(
			'subicon_tab_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'subicon_icon_color',
			[
				'label'     => __( 'Icon color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .cat-item > a i.fa' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subicon_back_color',
			[
				'label'     => __( 'Category item background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-item > a i.fa' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'subicon_tab_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'subicon_icon_color_hover',
			[
				'label'     => __( 'Icon color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-item > a i.fa:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'subicon_icon_back_color_hover',
			[
				'label'     => __( 'Category item background color (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-item > a i.fa:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		// Settings vars.
		$orderby      = $settings['orderby'];
		$count        = $settings['count'];
		$hierarchical = $settings['hierarchical'];
		$hide_empty   = $settings['hide_empty'];
		$exclude      = $settings['exclude'];
		$max_depth    = $settings['max_depth'];
		$orient       = $settings['orient'];
		$sub_icon     = $settings['sub_icon'];
		$last_border  = $settings['last_border'] ? ' no-last-border' : '';

		/**
		 * From plugins\woocommerce\includes\widgets\class-wc-widget-product-categories.php
		 */
		$list_args = array(
			'show_count'   => $count,
			'hierarchical' => $hierarchical,
			'taxonomy'     => 'product_cat',
			'hide_empty'   => $hide_empty,
			'depth'        => $max_depth,
		);
		if ( 'order' === $orderby ) {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby'] = 'title';
		}

		// If some category should be excluded
		// Get term ID using term slugs and micemade_elements_term_data filter.
		if ( ! empty( $exclude ) ) {
			$exclude_array = [];
			foreach ( $exclude as $cat ) {
				$term_data = apply_filters( 'micemade_elements_term_data', 'product_cat', $cat );
				if ( isset( $term_data ) ) {
					$exclude_array[] = $term_data['term_id'];
				}
			}
			$list_args['exclude'] = $exclude_array;
		}

		// Custom walker to change menu items output.
		include_once MICEMADE_ELEMENTS_INCLUDES . 'class-micemade-custom-walker-category.php';

		// Args for wp_list_categories function.
		$list_args['walker']           = new Micemade_Custom_Walker_Category();
		$list_args['title_li']         = '';
		$list_args['pad_counts']       = 1;
		$list_args['sub_icon']         = ( $sub_icon && $hierarchical ) ? $sub_icon : '';
		$list_args['show_option_none'] = __( 'No product categories exist.', 'micemade-elements' );

		// Add classes to menu wrapper ( <ul> element ).
		$this->add_render_attribute(
			array(
				'wrapper' => array(
					'class' => array(
						'micemade-elements_wc_cat_menu',
						'product-categories',
						$last_border,
						$orient,
					),
				),
			)
		);

		// Render the menu.
		?>
		<ul <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

		<?php wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) ); ?>

		</ul>
		<?php
	}
}
