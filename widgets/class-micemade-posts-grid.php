<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Micemade_Posts_Grid extends Widget_Base {

	public function get_name() {
		return 'micemade-posts-grid';
	}

	public function get_title() {
		return __( 'Micemade Posts Grid', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function _register_controls() {

		// We'll need this var only in this method.
		$post_types = apply_filters( 'micemade_elements_post_types', '' );

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade posts grid', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'post_type',
			[
				'label'    => esc_html__( 'Post type', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => $post_types,
				'multiple' => false,
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Total posts', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6',
				'title'   => __( 'Enter total number of posts to show', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => __( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
				'title'   => __( 'Offset is number of skipped posts', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'heading_filtering',
			[
				'label'     => __( 'Posts filtering options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'post_type!' => 'page',
				],
			]
		);

		// Get all the post types with its taxonomies in array.
		foreach ( $post_types as $post_type => $label ) {
			$posttypes_and_its_taxonomies[ $post_type ] = get_cpt_taxonomies( $post_type );
		}

		foreach ( $posttypes_and_its_taxonomies as $post_type => $taxes ) {

			if ( 'page' === $post_type || empty( $taxes ) ) {
				continue;
			}

			// Create options from all publicly queryable taxonomies.
			foreach ( $taxes as $tax ) {
				$tax_object = get_taxonomy( $tax );
				if ( ! $tax_object->publicly_queryable ) {
					continue;
				}
				$tax_options[ $tax ] = $tax_object->label;
			}

			// Controls for selection of taxonomies per post type.
			// -- correct "-" to "_" in taxonomies, JS issue in Elementor-conditional hiding.
			$taxonomy_control = str_replace( '-', '_', $post_type ) . '_taxonomies';
			$this->add_control(
				$taxonomy_control,
				[
					'label'       => esc_html__( 'Select taxonomy', 'micemade-elements' ),
					'type'        => Controls_Manager::SELECT2,
					'default'     => '',
					'options'     => $tax_options,
					'multiple'    => false,
					'label_block' => true,
					'condition'   => [
						'post_type' => $post_type,
					],
				]
			);

			foreach ( $tax_options as $taxonomy => $label ) {
				$this->add_control(
					$taxonomy . '_terms_for_' . $post_type,
					[
						'label'       => esc_html__( 'Select ', 'micemade-elements' ) . strtolower( $label ),
						'type'        => Controls_Manager::SELECT2,
						'default'     => array(),
						'options'     => apply_filters( 'micemade_elements_terms', $taxonomy ),
						'multiple'    => true,
						'label_block' => true,
						'condition'   => [
							$taxonomy_control => $taxonomy,
							'post_type'       => $post_type,
						],
					]
				);

			}

			unset( $tax_options );
		}

		$this->add_control(
			'sticky',
			[
				'label'     => esc_html__( 'Show only sticky posts', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'condition' => [
					'post_type' => 'post',
				],
			]
		);

		$this->add_control(
			'heading_grid_options',
			[
				'label'     => __( 'Grid options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'posts_per_row',
			[
				'label'   => __( 'Posts per row', 'micemade-elements' ),
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
			'posts_per_row_tab',
			[
				'label'   => __( 'Posts per row (on tablets)', 'micemade-elements' ),
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
			'posts_per_row_mob',
			[
				'label'   => __( 'Posts per row (on mobiles)', 'micemade-elements' ),
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
				'label'     => __( 'Posts grid horizontal spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .post' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'       => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],

			]
		);
		$this->add_responsive_control(
			'vert_spacing',
			[
				'label'     => __( 'Posts grid vertical spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .post' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'       => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);

		$this->end_controls_section();

		// TAB 2 - STYLES FOR POSTS.
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'General style and layout', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => __( 'Style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => [
					'style_1' => __( 'Image above, text bellow', 'micemade-elements' ),
					'style_2' => __( 'Image left, text right', 'micemade-elements' ),
					'style_3' => __( 'Image as background', 'micemade-elements' ),
					'style_4' => __( 'Image and text overlapping', 'micemade-elements' ),
				],
			]
		);

		$this->add_control(
			'show_thumb',
			[
				'label'     => esc_html__( 'Show featured image (thumbnail)', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'img_format',
			[
				'label'     => esc_html__( 'Featured image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => [
					'show_thumb!' => '',
				],
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label'     => esc_html__( 'Show excerpt', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'meta',
			[
				'label'     => esc_html__( 'Show post meta', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_responsive_control(
			'post_overal_padding',
			[
				'label'      => esc_html__( 'Post overal padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .inner-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// CONTENT BOX STYLES.
		$this->start_controls_section(
			'section_post_text',
			[
				'label' => __( 'Post text styling', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'background_type',
			[
				'label'       => __( 'Post background type', 'micemade-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'solid',
				'options'     => [
					'solid'    => [
						'title' => __( 'Solid color', 'micemade-elements' ),
						'icon'  => 'fa fa-eyedropper',
					],
					'gradient' => [
						'title' => __( 'Gradient', 'micemade-elements' ),
						'icon'  => 'fa fa-list-ul',
					],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_post_background' );

		$this->start_controls_tab(
			'tab_post_background_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'post_text_background_color',
			[
				'label'     => __( 'Background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .post-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'background_type' => 'solid',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_background_gradient',
				'label'     => __( 'Background gradient', 'micemade-elements' ),
				'types'     => [ 'gradient' ],
				'selector'  => '{{WRAPPER}} .inner-wrap, {{WRAPPER}} .post-overlay',
				'condition' => [
					'background_type' => 'gradient',
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
			'post_text_background_color_hover',
			[
				'label'     => __( 'Post text background (hover)', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .inner-wrap:hover .post-overlay' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'background_type' => 'solid',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_background_gradient_hover',
				'label'     => __( 'Background gradient', 'micemade-elements' ),
				'types'     => [ 'gradient' ],
				'selector'  => '{{WRAPPER}} .inner-wrap:hover, {{WRAPPER}} .inner-wrap:hover .post-overlay',
				'condition' => [
					'background_type' => 'gradient',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'post_text_height',
			[
				'label'     => __( 'Post height', 'micemade-elements' ),
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
					'{{WRAPPER}} .inner-wrap' => 'height: {{SIZE}}px;',
				],
				'condition' => [
					'style' => [ 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'post_thumb_width',
			[
				'label'     => __( 'Post thumb width (%)', 'micemade-elements' ),
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
					// thumb style 2.
					'{{WRAPPER}} .post-thumb'         => 'width:{{SIZE}}%;',
					'{{WRAPPER}} .style_2 .post-text' => 'width: calc( 100% - {{SIZE}}%);',
					// thumb style 4.
					'{{WRAPPER}} .post-thumb-back'    => 'right: calc( 100% - {{SIZE}}%); width: auto;',

				],
				'condition' => [
					'style'       => [ 'style_2', 'style_4' ],
					'show_thumb!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'post_content_width',
			[
				'label'     => __( 'Post content width (%)', 'micemade-elements' ),
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
					'{{WRAPPER}} .style_4 .post-overlay' => 'left: calc( 100% - {{SIZE}}%);',
					'{{WRAPPER}} .style_4 .post-text'    => 'left: calc( 100% - {{SIZE}}%);',
				],
				'condition' => [
					'style' => [ 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'post_text_padding',
			[
				'label'      => esc_html__( 'Post text padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_text_align',
			[
				'label'     => __( 'Post text alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} .post-text' => 'text-align: {{VALUE}};',
				],

			]
		);
		/*
		$this->add_responsive_control(
			'content_vertical_alignment',
			[
				'label'     => __( 'Vertical align', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'flex-start' => __( 'Top', 'micemade-elements' ),
					'center'     => __( 'Middle', 'micemade-elements' ),
					'flex-end'   => __( 'Bottom', 'micemade-elements' ),
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .post-text'  => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .inner-wrap' => 'align-items: {{VALUE}};',
				],
				'condition' => [
					'style' => [ 'style_2', 'style_3', 'style_4' ],
				],
			]
		);
		*/
		$this->add_responsive_control(
			'content_vertical_alignment',
			[
				'label'       => __( 'Vertical Align', 'micemade-elements' ),
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
					'{{WRAPPER}} .post-text'  => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .inner-wrap' => 'align-items: {{VALUE}};',
				],
				'condition'   => [
					'style' => [ 'style_2', 'style_3', 'style_4' ],
				],
			]
		);

		$this->add_responsive_control(
			'post_elements_spacing',
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
					'{{WRAPPER}} .post-text > *:not(.micemade-elements-readmore)' => 'margin-bottom: {{SIZE}}px; padding-bottom: 0;',
					/*,
					 '{{WRAPPER}} .post-text .meta' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .post-text p' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .post-text a.micemade-elements-readmore  ' => 'margin-bottom: {{SIZE}}px;', */
				],

			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .inner-wrap',
			]
		);

		$this->end_controls_section();

		// Title styles.
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Hover tabs.
		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal.
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
					'{{WRAPPER}} .post-text h4' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		// Hover.
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
					'{{WRAPPER}} .inner-wrap:hover .post-text h4 a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .inner-wrap:hover .post-text h4' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		// end tabs.

		// Title border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'postgrid_title_border',
				'label'       => __( 'Title Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text h4',
			]
		);

		$this->add_responsive_control(
			'postgrid_title_padding',
			[
				'label'      => esc_html__( 'Title padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		// Title typohraphy.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text h4',
			]
		);

		$this->end_controls_section();

		// Meta controls.
		$this->start_controls_section(
			'section_meta',
			[
				'label'     => __( 'Meta', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'meta!' => '',
				],
			]
		);

		// Meta hover tabs.
		$this->start_controls_tabs( 'tabs_meta_style' );

		// Normal.
		$this->start_controls_tab(
			'tab_meta_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'meta_text_color',
			[
				'label'     => __( 'Meta Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_links_color',
			[
				'label'     => __( 'Meta Links Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta a, {{WRAPPER}} .post-text .meta a:active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'tab_meta_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);
		$this->add_control(
			'meta_text_hover_color',
			[
				'label'     => __( 'Meta Text Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover .post-text .meta span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'meta_links_hover_color',
			[
				'label'     => __( 'Meta Links Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover .post-text .meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'meta_typography',
				'label'     => __( 'Meta typography', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .post-text .meta',
				'condition' => [
					'meta!' => '',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'meta_sorter_label',
			[
				'label'      => esc_html__( 'Sort meta info', 'micemade-elements' ),
				'type'       => 'sorter_label', // Custom type - class Micemade_Control_Setting.
				'show_label' => false,
			]
		);
		$repeater->add_control(
			'meta_part_enabled',
			[
				'label'     => esc_html__( 'Enable', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'meta_ordering',
			[
				'label'        => __( 'Meta selection and ordering', 'micemade-elements' ),
				'type'         => \Elementor\Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => [
					'duplicate' => false,
					'add'       => false,
					'remove'    => false,
				],
				'default'      => [
					[
						'meta_sorter_label' => 'Date',
						'meta_part_enabled' => 'yes',
					],
					[
						'meta_sorter_label' => 'Author',
						'meta_part_enabled' => 'yes',
					],
					[
						'meta_sorter_label' => 'Posted in',
						'meta_part_enabled' => 'yes',
					],
				],
				'title_field'  => '{{{ meta_sorter_label }}}',
				'condition'    => [
					'meta!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Excerpt controls.
		$this->start_controls_section(
			'section_excerpt',
			[
				'label'     => __( 'Excerpt', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'excerpt!' => '',
				],
			]
		);

		$this->add_control(
			'excerpt_limit',
			[
				'label'   => __( 'Excerpt Limit (words)', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '20',
				'title'   => __( 'Max. number of words in excerpt', 'micemade-elements' ),
			]
		);

		// EXCERPT HOVER TABS.
		$this->start_controls_tabs( 'tabs_excerpt_style' );
		// NORMAL.
		$this->start_controls_tab(
			'tab_excerpt_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);
		// Excerpt text color.
		$this->add_control(
			'excerpt_text_color',
			[
				'label'     => __( 'Excerpt Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				],

			]
		);
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'tab_excerpt_hover',
			[
				'label' => __( 'Hover', 'micemade-elements' ),
			]
		);
		// Excerpt text color.
		$this->add_control(
			'excerpt_text_hover_color',
			[
				'label'     => __( 'Excerpt Text Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .inner-wrap:hover .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				],

			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Excerpt typohraphy.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'label'    => __( 'Excerpt typography', 'micemade-elements' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text p',

			]
		);

		// Excerpt padding.
		$this->add_responsive_control(
			'postgrid_excerpt_padding',
			[
				'label'      => esc_html__( 'Excerpt padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		// Excerpt border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'postgrid_excerpt_border',
				'label'       => __( 'Excerpt Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text p',
			]
		);

		$this->add_control(
			'readmore_title',
			[
				'label'     => __( '"Read more" button custom css', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'css_class',
			[
				'label'       => __( 'Enter css class(es) for "Read more"', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'title'       => __( 'Style the "Read more" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		// Ajax LOAD MORE settings.
		$this->start_controls_section(
			'section_ajax_load_more',
			[
				'label' => __( 'Ajax LOAD MORE settings', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'use_load_more',
			[
				'label'     => esc_html__( 'Use load more', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_responsive_control(
			'load_more_padding',
			[
				'label'      => esc_html__( '"LOAD MORE" margin', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .micemade-elements_more-posts-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'use_load_more!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'loadmore_align',
			[
				'label'     => __( '"LOAD MORE"  alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} .micemade-elements_more-posts-wrap' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'use_load_more!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		// Get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		// ---- QUERY SETTINGS
		$post_type = $settings['post_type'];

		// "Dynamic" setting.
		$taxonomy = 'page' !== $post_type ? $settings[ $post_type . '_taxonomies' ] : '';

		$ppp      = (int) $settings['posts_per_page'];
		// "Dynamic" setting.
		$categories = isset( $settings[ $taxonomy . '_terms' ] ) ? $settings[ $taxonomy . '_terms' ] : [];
		$sticky     = $settings['sticky'];
		$offset     = (int) $settings['offset'];
		// ---- end QUERY SETTINGS.

		// Grid settings.
		$ppr     = (int) $settings['posts_per_row'];
		$ppr_tab = (int) $settings['posts_per_row_tab'];
		$ppr_mob = (int) $settings['posts_per_row_mob'];

		// Layout and style settings.
		$show_thumb    = $settings['show_thumb'];
		$img_format    = $settings['img_format'];
		$style         = $settings['style'];
		$excerpt       = $settings['excerpt'];
		$excerpt_limit = $settings['excerpt_limit'];
		$meta          = $settings['meta'];
		$meta_ordering = $settings['meta_ordering'];
		$css_class     = $settings['css_class'];
		$use_load_more = $settings['use_load_more'];

		global $post;

		$grid = micemade_elements_grid_class( intval( $ppr ), intval( $ppr_tab ), intval( $ppr_mob ) );

		// Query posts - "micemade_elements_query_args" hook in includes/helpers.php.
		$args  = apply_filters( 'micemade_elements_query_args', $post_type, $taxonomy, $ppp, $categories, $sticky, $offset );
		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {

			echo '<div class="micemade-elements_posts-grid' . ( $use_load_more ? ' micemade-elements-load-more' : '' ) . ' mme-row ' . esc_attr( $style ) . '">';

			// If "Load more" is selected, turn on the arguments for Ajax call.
			if ( $use_load_more ) {

				$postoptions = wp_json_encode(
					array(
						'post_type'     => $post_type,
						'taxonomy'      => $taxonomy,
						'ppp'           => $ppp,
						'sticky'        => $sticky,
						'categories'    => $categories,
						'style'         => $style,
						'show_thumb'    => $show_thumb,
						'img_format'    => $img_format,
						'excerpt'       => $excerpt,
						'excerpt_limit' => $excerpt_limit,
						'meta'          => $meta,
						'meta_ordering' => $meta_ordering,
						'css_class'     => $css_class,
						'grid'          => $grid,
						'startoffset'   => $offset,
					)
				);

				echo '<input type="hidden" class="posts-grid-settings" data-postoptions="' . esc_js( $postoptions ) . '">';
			}

			foreach ( $posts as $post ) {

				setup_postdata( $post );

				// Hook in includes/helpers.php.
				apply_filters( 'micemade_elements_loop_post', $style, $grid, $show_thumb, $img_format, $meta, $meta_ordering, $excerpt, $excerpt_limit, $css_class );

			}

			echo '</div>';
		}

		wp_reset_postdata();

		if ( $use_load_more ) {
			echo '<div class="micemade-elements_more-posts-wrap"><a href="#" class="micemade-elements_more-posts more_posts button">' . __( 'Load more', 'micemade-elements' ) . '</a></div>';
		}

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Posts_Grid() );
