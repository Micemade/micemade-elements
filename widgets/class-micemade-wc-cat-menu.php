<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

	protected function _register_controls() {

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
				'default' => '',
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
				'type'        => Controls_Manager::SELECT2,
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

				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .micemade-elements_wc_cat_menu.horizontal' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'orient' => 'horizontal',
				],
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

		$this->add_control(
			'image',
			[
				'label'     => esc_html__( 'Show category image', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'img_format',
			[
				'label'     => esc_html__( 'Categories image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => [
					'image!' => '',
				],
			]
		);

		$this->add_control(
			'prod_count',
			[
				'label'     => esc_html__( 'Show products count', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
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
						'max' => 10,
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
				'default'   => 'yes',
			]
		);

		/*
		$this->add_responsive_control(
			'cat_image_width',
			[
				'label'     => __( 'Image width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '',
				],
				'range'     => [
					'px' => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category .category__image' => 'width: {{SIZE}}%;',
				],
				'condition' => [
					'style' => 'style_2',
				],
			]
		);

		$this->add_responsive_control(
			'post_text_align',
			[
				'label'     => __( 'Categories alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} .category__text-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'cat_vertical_alignment',
			[
				'label'     => __( 'Vertical Align Product Info', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'flex-start' => __( 'Top', 'micemade-elements' ),
					'center'     => __( 'Middle', 'micemade-elements' ),
					'flex-end'   => __( 'Bottom', 'micemade-elements' ),
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .category__text-wrap' => 'align-self: {{VALUE}};',
				],
				'condition' => [
					'style' => [ 'style_1', 'style_2', 'style_4' ],
				],
			]
		);
		*/
		$this->end_controls_section();

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
			'cat_item_border',
			[
				'label'     => __( 'Category item border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'cat_border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .cat-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toplevel_box_shadow',
				'label'    => __( 'Box Shadow', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .product-categories',
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
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
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
			'submenu_cat_item_border',
			[
				'label'     => __( 'Category item border', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'submenu_cat_border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .children .cat-item',
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
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .children > .cat-item a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_subicon',
			[
				'label' => esc_html__( 'Submenu icon', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sub_icon',
			[
				'label'     => __( 'Choose icon', 'micemade-elements' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-plus',
				'separator' => 'before',
				'include'   => [
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

		/*
		// Animation effects
		$this->start_controls_section(
			'section_anim_effects',
			[
				'label' => __( 'Animation and hover effects', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_style_box',
			[
				'label'   => __( 'Category hover effect', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'               => __( 'None', 'micemade-elements' ),
					'box_enlarge_shadow' => __( 'Enlarge with shadow', 'micemade-elements' ),
					'box_shrink_shadow'  => __( 'Shrink with shadow', 'micemade-elements' ),
					'box_move_up'        => __( 'Float', 'micemade-elements' ),
					'box_move_down'      => __( 'Sink', 'micemade-elements' ),
					'box_move_right'     => __( 'Forward', 'micemade-elements' ),
					'box_move_left'      => __( 'Backward', 'micemade-elements' ),
				],
			]
		);

		$this->add_control(
			'item_anim',
			[
				'label'       => __( 'Category Entering Animation', 'elementor' ),
				'type'        => Controls_Manager::ANIMATION,
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'item_anim_duration',
			[
				'label'     => __( 'Animation Speed', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					'slow' => __( 'Slow', 'elementor' ),
					''     => __( 'Normal', 'elementor' ),
					'fast' => __( 'Fast', 'elementor' ),
				],
				'condition' => [
					'item_anim!' => '',
				],
			]
		);

		$this->add_control(
			'item_anim_delay',
			[
				'label'     => __( 'Animation Delay', 'elementor' ) . ' (ms)',
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'min'       => 0,
				'step'      => 100,
				'title'     => esc_html__( 'animation delay between category items', 'micemade-elements' ),
				'condition' => [
					'item_anim!' => '',
				],
			]
		);

		$this->end_controls_section();
		*/
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
		include_once MICEMADE_ELEMENTS_DIR . 'includes/class-micemade-custom-walker-category.php';

		// Args for wp_list_categories function.
		$list_args['walker']           = new Micemade_Custom_Walker_Category();
		$list_args['title_li']         = '';
		$list_args['pad_counts']       = 1;
		$list_args['sub_icon']         = ( $sub_icon && $hierarchical ) ? $sub_icon : '';
		$list_args['show_option_none'] = __( 'No product categories exist.', 'micemade-elements' );

		// Add classes to menu wrapper ( <ul> element ).
		$this->add_render_attribute( 'wrapper', 'class', 'product-categories micemade-elements_wc_cat_menu' );
		$this->add_render_attribute( 'wrapper', 'class', $last_border );
		$this->add_render_attribute( 'wrapper', 'class', $orient );

		// Render the menu.
		echo '<ul ' . $this->get_render_attribute_string( 'wrapper' ) . ' >';

		wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );

		echo '</ul>';

		/*
		// *
		if ( empty( $categories ) ) {
			return;
		}

		// Categories holder.
		echo '<div ' . $this->get_render_attribute_string( 'categories-holder-css' ) . '>';

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

			// Category item data-id / data-delay.
			$this->add_render_attribute( 'data-id' . $count, 'data-id', $id . '-' . $count );
			$this->add_render_attribute( 'data-delay' . $count, 'data-delay', $item_anim_delay * $count );

			echo '<a ' . $this->get_render_attribute_string( 'data-id' . $count ) . ' ' . $this->get_render_attribute_string( 'category-css' ) . ' href="' . esc_url( $term_link ) . '" title="' . esc_attr( $term_title ) . '" ' . $this->get_render_attribute_string( 'item-anim-data' ) . ' ' . $this->get_render_attribute_string( 'data-delay' . $count ) . '>';

				echo '<div class="category__inner-wrap">';

					echo '<div class="category__overlay"></div>';

			if ( $image ) {
				echo '<div class="category__image"><div class="image-inner" style="background-image: url(' . esc_url( $image_url ) . ')"></div></div>';
			}

					echo '<div class="category__text-wrap">';

			if ( $term_title ) {
				echo '<h3 class="category__title">' . esc_html( $term_title ) . '</h3>';
			}

			if ( $prod_count && $term_id ) {
				do_action( 'micemade_elements_product_count', $term_id );
			}

					echo '</div>';

				echo '</div>'; //.inner-wrap.

			echo '</a>';

		}

		echo '</div>';
		*/
	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Cat_Menu() );
