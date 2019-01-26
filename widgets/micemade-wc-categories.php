<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Micemade_WC_Categories extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-categories';
	}

	public function get_title() {
		return __( 'Micemade WC Categories', 'micemade-elements' );
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
				'label' => esc_html__( 'Micemade WC Categories', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'categories',
			[
				'label'    => esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => array(),
				'options'  => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple' => true,
			]
		);

		$this->add_control(
			'add_query_args',
			[
				'label'    => __( 'Additional filters (per category)', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => array(),
				'options'  => [
					'on_sale'      => esc_html__( 'On sale', 'micemade-elements' ),
					'featured'     => esc_html__( 'Featured', 'micemade-elements' ),
					'best_sellers' => esc_html__( 'Best sellers', 'micemade-elements' ),
					'best_rated'   => esc_html__( 'Best rated', 'micemade-elements' ),
				],
				'multiple' => true,
			]
		);

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
			'cats_per_row',
			[
				'label'   => __( 'Categories per row', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 3,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				],
			]
		);

		$this->add_control(
			'cats_per_row_tab',
			[
				'label'   => __( 'Categories per row (tablets)', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				],
			]
		);

		$this->add_control(
			'cats_per_row_mob',
			[
				'label'   => __( 'Categories per row (mobiles)', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				],
			]
		);

		$this->add_responsive_control(
			'horiz_spacing',
			[
				'label'     => __( 'Grid horizontal spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .category' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'  => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],

			]
		);

		$this->add_responsive_control(
			'vert_spacing',
			[
				'label'     => __( 'Grid bottom spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .category' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'  => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);

		$this->add_responsive_control(
			'inner_spacing',
			[
				'label'     => __( 'Inner spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '0',
				],
				'range'     => [
					'px' => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category__inner-wrap' => 'margin:{{SIZE}}px;',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Category item layout', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'categories_height',
			[
				'label'     => __( 'Categories height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '',
				],
				'range'     => [
					'px' => [
						'max'  => 800,
						'min'  => 0,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .category' => 'height: {{SIZE}}px;',
				],
			]
		);

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
			'cat_title_padding',
			[
				'label'      => esc_html__( 'Category box padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .category__text-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Colors, typography, border', 'micemade-elements' ),
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
			'title_text_color',
			[
				'label'     => __( 'Title Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .category' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => __( 'Category overlay color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category__overlay' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .category:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_hover',
			[
				'label'     => __( 'Category overlay color on hover', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category:hover .category__overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .category__title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .category__inner-wrap',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_anim_effects',
			[
				'label' => __( 'Animation and hover effects', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover_style',
			[
				'label'     => __( 'Image hover effect', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'blur_image',
				'options'   => [
					'no_image_effect' => __( 'No image hover effect', 'micemade-elements' ),
					'blur_image'      => __( 'Blur image', 'micemade-elements' ),
					'enlarge_image'   => __( 'Enlarge image', 'micemade-elements' ),
					'shrink_image'    => __( 'Shrink image', 'micemade-elements' ),
					'greyscale_image' => __( 'Greyscale image', 'micemade-elements' ),
				],
				'condition' => [
					'image!' => '',
				],
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

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings();
		// Settings vars.
		$categories       = ! empty( $settings['categories'] ) ? $settings['categories'] : array();
		$add_query_args   = ! empty( $settings['add_query_args'] ) ? $settings['add_query_args'] : array();
		$cats_per_row     = ! empty( $settings['cats_per_row'] ) ? (int) $settings['cats_per_row'] : 3;
		$cats_per_row_tab = ! empty( $settings['cats_per_row_tab'] ) ? (int) $settings['cats_per_row_tab'] : 1;
		$cats_per_row_mob = ! empty( $settings['cats_per_row_mob'] ) ? (int) $settings['cats_per_row_mob'] : 1;
		$style            = ! empty( $settings['style'] ) ? $settings['style'] : 'style_1';
		$hover_style      = ! empty( $settings['hover_style'] ) ? $settings['hover_style'] : '';
		$hover_style_box  = ! empty( $settings['hover_style_box'] ) ? $settings['hover_style_box'] : 'none';
		$image            = ! empty( $settings['image'] ) ? $settings['image'] : '';
		$img_format       = ! empty( $settings['img_format'] ) ? $settings['img_format'] : 'thumbnail';
		$prod_count       = ! empty( $settings['prod_count'] ) ? $settings['prod_count'] : '';
		$item_anim        = ! empty( $settings['item_anim'] ) ? $settings['item_anim'] : '';
		$item_anim_dur    = ! empty( $settings['item_anim_duration'] ) ? $settings['item_anim_duration'] : '';
		$item_anim_delay  = ! empty( $settings['item_anim_delay'] ) ? $settings['item_anim_delay'] : 0;

		$id = $this->get_id();

		// All the styles for categories holder.
		$this->add_render_attribute( 'categories-holder-css', 'class', 'micemade-elements_product-categories mme-row' );
		$this->add_render_attribute( 'categories-holder-css', 'class', $style );
		$this->add_render_attribute( 'categories-holder-css', 'class', $hover_style );
		$this->add_render_attribute( 'categories-holder-css', 'class', $hover_style_box );

		// Each singular category styles.
		$grid = micemade_elements_grid_class( intval( $cats_per_row ), intval( $cats_per_row_tab ), intval( $cats_per_row_mob ) );
		$this->add_render_attribute( 'category-css', 'class', 'category ' . $grid );
		$this->add_render_attribute( 'category-css', 'class', $item_anim ? 'mm-enter-animate animated' : '' );
		$this->add_render_attribute( 'category-css', 'class', 'animated-' . $item_anim_dur );
		$this->add_render_attribute( 'item-anim-data', 'data-anim', $item_anim );

		// Additional query args.
		$args = '';
		if ( ! empty( $add_query_args ) ) {
			$args = '?';
			foreach ( $add_query_args as $arg ) {
				$args .= $arg . ( end( $add_query_args ) === $arg ? '' : '&amp;' );
			}
		}

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
				echo apply_filters( 'micemade_elements_product_count', $term_id );
			}

					echo '</div>';

				echo '</div>'; //.inner-wrap

			echo '</a>';

		}

		echo '</div>';

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Categories() );
