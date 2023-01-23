<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Typography;

class Micemade_Posts_Grid extends Widget_Base {
	
	public function get_name() {
		return 'micemade-posts-grid';
	}

	public function get_title() {
		return esc_html__( 'Micemade Posts Grid', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	protected function register_controls() {

		/***************************************************
		 * SHARED CONTROLS FOR POSTS QUERY
		 *
		 * "elementor/element/{$section_name}/{$section_id}/after_section_end"
		 * hooked in "class-micemade-elements" and file "includes/class-micemade-shared-controls.php
		 * https://code.elementor.com/php-hooks/#elementorelementsection_namesection_idbefore_section_start
		 * *************************************************
		*/

		// TAB 2 - STYLES FOR POSTS.
		$this->start_controls_section(
			'section_grid',
			array(
				'label' => esc_html__( 'Grid options', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$items_num = array(
			1 => esc_html__( 'One', 'micemade-elements' ),
			2 => esc_html__( 'Two', 'micemade-elements' ),
			3 => esc_html__( 'Three', 'micemade-elements' ),
			4 => esc_html__( 'Four', 'micemade-elements' ),
			6 => esc_html__( 'Six', 'micemade-elements' ),
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'           => esc_html__( 'Post columns (devices)', 'micemade-elements' ),
				'type'            => Controls_Manager::SELECT,
				'desktop_default' => 4,
				'tablet_default'  => 2,
				'mobile_default'  => 1,
				'options'         => $items_num,
			)
		);

		$this->add_responsive_control(
			'horiz_spacing',
			array(
				'label'     => esc_html__( 'Posts grid horizontal spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .post' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'       => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				),

			)
		);
		$this->add_responsive_control(
			'vert_spacing',
			array(
				'label'     => esc_html__( 'Posts grid vertical spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .post' => 'margin-bottom:{{SIZE}}px;',
					// '{{WRAPPER}} .mme-row'       => 'margin-bottom:-{{SIZE}}px;',
				),

			)
		);

		$this->end_controls_section();

		// TAB 2 - STYLES FOR POSTS.
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
				'label'   => esc_html__( 'Layout style', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style_1',
				'options' => array(
					'style_1' => esc_html__( 'Image above, text bellow', 'micemade-elements' ),
					'style_2' => esc_html__( 'Image left, text right', 'micemade-elements' ),
					'style_3' => esc_html__( 'Image as background', 'micemade-elements' ),
					'style_4' => esc_html__( 'Image and text overlapping', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'show_thumb',
			array(
				'label'     => esc_html__( 'Show featured image (thumbnail)', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'img_format',
			array(
				'label'     => esc_html__( 'Featured image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'thumbnail',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => array(
					'show_thumb!' => '',
				),
			)
		);

		$this->add_control(
			'excerpt',
			array(
				'label'     => esc_html__( 'Show excerpt', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'meta',
			array(
				'label'     => esc_html__( 'Show post meta', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'custom_meta',
			array(
				'label'     => esc_html__( 'Show custom meta (advanced)', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'no',
			)
		);

		$this->add_control(
			'custom_meta_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => '<small>' . sprintf( wp_kses_post( __( 'Add custom fields meta keys and edit settings bellow, under the "Custom meta" section. Only for advanced users. <a href="%s" target="_blank">Learn more</a>', 'micemade-elements' ) ), 'https://wordpress.org/support/article/custom-fields/' ) . '</small>',
				'content_classes' => 'your-class',
				'condition'       => array(
					'custom_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'hr_cm',
			array(
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => array(
					'custom_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'use_load_more',
			array(
				'label'     => esc_html__( 'Use load more', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'loadmore_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => '<small>' . esc_html__( 'If there are items in "Select items from [post type]", Load more feature will be disabled.', 'micemade-elements' ) . '</small>',
				'content_classes' => 'your-class',
				'condition'       => array(
					'use_load_more' => 'yes',
				),
			)
		);

		$this->add_control(
			'hr_loadmore',
			array(
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => array(
					'use_load_more' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'post_overal_padding',
			array(
				'label'      => esc_html__( 'Post overal padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .inner-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// CONTENT BOX STYLES.
		$this->start_controls_section(
			'section_items_style',
			array(
				'label' => esc_html__( 'Items style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Normal / Hover tabs.
		$this->start_controls_tabs( 'tabs_post_background' );
		$this->start_controls_tab(
			'tab_post_background_normal',
			array(
				'label' => esc_html__( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'post_text_background_color',
				'label'     => esc_html__( 'Post background', 'micemade-elements' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .style_1 .inner-wrap, {{WRAPPER}} .style_2 .inner-wrap',
				'condition' => array(
					'style' => array(
						'style_1',
						'style_2',
					),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'post_text_overlay_color',
				'label'     => esc_html__( 'Post overlay', 'micemade-elements' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .style_3 .inner-wrap .post-overlay, {{WRAPPER}} .style_4 .inner-wrap .post-overlay',
				'condition' => array(
					'style' => array(
						'style_3',
						'style_4',
					),
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

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'post_text_background_color_hover',
				'label'     => esc_html__( 'Post background on hover', 'micemade-elements' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .style_1 .inner-wrap:hover, {{WRAPPER}} .style_2 .inner-wrap:hover',
				'condition' => array(
					'style' => array(
						'style_1',
						'style_2',
					),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			array(
				'name'      => 'post_text_overlay_color_hover',
				'label'     => esc_html__( 'Post overlay on hover', 'micemade-elements' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .style_3 .inner-wrap:hover .post-overlay, {{WRAPPER}} .style_4 .inner-wrap:hover .post-overlay',
				'condition' => array(
					'style' => array(
						'style_3',
						'style_4',
					),
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'hr',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$this->add_responsive_control(
			'post_text_height',
			array(
				'label'     => esc_html__( 'Items height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
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
			'post_thumb_width',
			array(
				'label'     => esc_html__( 'Post thumb width (%)', 'micemade-elements' ),
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
					// thumb style 2.
					'{{WRAPPER}} .post-thumb'         => 'width:{{SIZE}}%;',
					'{{WRAPPER}} .style_2 .post-text' => 'width: calc( 100% - {{SIZE}}%);',
					// thumb style 4.
					'{{WRAPPER}} .inner-wrap .post-thumb-back' => 'right: calc( 100% - {{SIZE}}%); width: auto;',

				),
				'condition' => array(
					'style'       => array( 'style_2', 'style_4' ),
					'show_thumb!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'post_content_width',
			array(
				'label'     => esc_html__( 'Post content width (%)', 'micemade-elements' ),
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
					'{{WRAPPER}} .style_4 .post-overlay' => 'left: calc( 100% - {{SIZE}}%);',
					'{{WRAPPER}} .style_4 .post-text'    => 'left: calc( 100% - {{SIZE}}%);',
				),
				'condition' => array(
					'style' => array( 'style_4' ),
				),
			)
		);

		$this->add_responsive_control(
			'post_text_padding',
			array(
				'label'      => esc_html__( 'Post content padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_responsive_control(
			'post_text_align',
			array(
				'label'                => esc_html__( 'Horizontal align', 'micemade-elements' ),
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
				'default'              => 'center',
				'selectors'            => array(
					'{{WRAPPER}} .post-text, {{WRAPPER}} .post-text *' => '{{VALUE}};',
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
				'label'       => esc_html__( 'Vertical align', 'micemade-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => esc_html__( 'Start', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'End', 'micemade-elements' ),
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
			'post_elements_spacing',
			array(
				'label'     => esc_html__( 'Elements vertical spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .post-text > *:not(.micemade-elements-readmore)' => 'margin-bottom: {{SIZE}}px; padding-bottom: 0;',
					/*
					,
					 '{{WRAPPER}} .post-text .meta' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .post-text p' => 'margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .post-text a.micemade-elements-readmore  ' => 'margin-bottom: {{SIZE}}px;', */
				),

			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'border',
				'label'       => esc_html__( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .inner-wrap',
			)
		);

		$this->end_controls_section();

		// Title styles.
		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Title', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		// Hover tabs.
		$this->start_controls_tabs( 'tabs_button_style' );

		// Normal.
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
					'{{WRAPPER}} .post-text h4' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Hover.
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
					'{{WRAPPER}} .inner-wrap:hover .post-text h4 a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .inner-wrap:hover .post-text h4' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		// end tabs.

		// Title border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'postgrid_title_border',
				'label'       => esc_html__( 'Title Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text h4',
			)
		);

		$this->add_responsive_control(
			'postgrid_title_padding',
			array(
				'label'      => esc_html__( 'Title padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-text h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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

		// Meta controls.
		$this->start_controls_section(
			'section_meta',
			array(
				'label'     => esc_html__( 'Meta', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'meta!' => '',
				),
			)
		);

		// Meta hover tabs.
		$this->start_controls_tabs( 'tabs_meta_style' );

		// Normal.
		$this->start_controls_tab(
			'tab_meta_normal',
			array(
				'label' => esc_html__( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'meta_text_color',
			array(
				'label'     => esc_html__( 'Meta Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_links_color',
			array(
				'label'     => esc_html__( 'Meta Links Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta a, {{WRAPPER}} .post-text .meta a:active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_back_color',
			array(
				'label'     => esc_html__( 'Meta Back Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// Hover.
		$this->start_controls_tab(
			'tab_meta_hover',
			array(
				'label' => esc_html__( 'Hover', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'meta_text_hover_color',
			array(
				'label'     => esc_html__( 'Meta Text Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap:hover .post-text .meta span' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'meta_links_hover_color',
			array(
				'label'     => esc_html__( 'Meta Links Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap:hover .post-text .meta a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_back_hover_color',
			array(
				'label'     => esc_html__( 'Meta Back Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .post-text .meta' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'label'     => esc_html__( 'Meta typography', 'micemade-elements' ),
				'scheme'    => Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .post-text .meta',
				'condition' => array(
					'meta!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'meta_padding',
			array(
				'label'      => esc_html__( 'Meta padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-text .meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'meta_sorter_label',
			array(
				'label'      => esc_html__( 'Sort meta info', 'micemade-elements' ),
				'type'       => 'sorter_label', // Custom control - class Micemade_Control_Setting.
				'show_label' => false,
			)
		);
		$repeater->add_control(
			'meta_part_enabled',
			array(
				'label'     => esc_html__( 'Enable', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'meta_ordering',
			array(
				'label'        => esc_html__( 'Meta selection and ordering', 'micemade-elements' ),
				'type'         => \Elementor\Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'item_actions' => array(
					'duplicate' => false,
					'add'       => false,
					'remove'    => false,
				),
				'default'      => array(
					array(
						'meta_sorter_label' => 'Date',
						'meta_part_enabled' => 'yes',
					),
					array(
						'meta_sorter_label' => 'Author',
						'meta_part_enabled' => 'yes',
					),
					array(
						'meta_sorter_label' => 'Posted in',
						'meta_part_enabled' => 'yes',
					),
				),
				'title_field'  => '{{{ meta_sorter_label }}}',
				'condition'    => array(
					'meta!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Custom meta controls.
		$this->start_controls_section(
			'section_custom_meta',
			array(
				'label'     => esc_html__( 'Custom Meta', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'custom_meta' => 'yes',
				),
			)
		);
		/*
		$this->add_control(
			'cm_keys',
			[
				'label'       => esc_html__( 'Custom meta keys', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
				//'default'     => esc_html__( 'Default title', 'micemade-elements' ),
				'placeholder' => esc_html__( 'Separate meta keys with a comma (,)', 'micemade-elements' ),
			]
		);
		 */

		// Repeater controls for custom meta keys.
		$repeater_cm = new \Elementor\Repeater();
		$repeater_cm->add_control(
			'cm_key',
			array(
				'label'       => esc_html__( 'Custom meta field key', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'options'     => micemade_get_meta_keys(),
				'multiple'    => false,
			)
		);
		$repeater_cm->add_control(
			'cm_type',
			array(
				'label'   => esc_html__( 'Custom meta type', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => array(
					'timestamp' => esc_html__( 'Timestamp', 'micemade-elements' ),
					'string'    => esc_html__( 'String', 'micemade-elements' ),
				),
			)
		);
		$repeater_cm->add_control(
			'cm_label',
			array(
				'label'       => esc_html__( 'Label', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Add label for meta field here', 'micemade-elements' ),
			)
		);
		$repeater_cm->add_control(
			'cm_after',
			array(
				'label'       => esc_html__( 'Additional label after', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Label after custom meta value.', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'cm_fields',
			array(
				'label'        => esc_html__( 'Custom meta fields', 'micemade-elements' ),
				'type'         => \Elementor\Controls_Manager::REPEATER,
				'fields'       => $repeater_cm->get_controls(),
				'item_actions' => array(
					'duplicate' => true,
					'add'       => true,
					'remove'    => true,
				),
				'default'      => array(
					array(
						'cm_key'   => '',
						'cm_type'  => 'string',
						'cm_label' => '',
						'cm_after' => '',
					),
				),
				'title_field'  => '{{{ cm_label }}}',
			)
		);

		$this->add_control(
			'custom_meta_text_color',
			array(
				'label'     => esc_html__( 'Custom meta text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text .custom-meta span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'custom_meta_typography',
				'label'    => esc_html__( 'Custom meta typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text .custom-meta',
			)
		);
		$this->add_control(
			'cm_inline',
			array(
				'label'     => esc_html__( 'Show custom meta inline', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
			)
		);

		$this->end_controls_section();

		// Excerpt controls.
		$this->start_controls_section(
			'section_excerpt',
			array(
				'label'     => esc_html__( 'Excerpt', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'excerpt!' => '',
				),
			)
		);

		$this->add_control(
			'excerpt_limit',
			array(
				'label'   => esc_html__( 'Excerpt words limit', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '20',
				'title'   => esc_html__( 'Max. number of words in excerpt', 'micemade-elements' ),
			)
		);

		// EXCERPT HOVER TABS.
		$this->start_controls_tabs( 'tabs_excerpt_style' );
		// NORMAL.
		$this->start_controls_tab(
			'tab_excerpt_normal',
			array(
				'label' => esc_html__( 'Normal', 'micemade-elements' ),
			)
		);
		// Excerpt text color.
		$this->add_control(
			'excerpt_text_color',
			array(
				'label'     => esc_html__( 'Excerpt Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				),

			)
		);
		$this->end_controls_tab();

		// HOVER.
		$this->start_controls_tab(
			'tab_excerpt_hover',
			array(
				'label' => esc_html__( 'Hover', 'micemade-elements' ),
			)
		);
		// Excerpt text color.
		$this->add_control(
			'excerpt_text_hover_color',
			array(
				'label'     => esc_html__( 'Excerpt Text Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .inner-wrap:hover .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .inner-wrap:hover .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				),

			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Excerpt typohraphy.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Excerpt typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text p',

			)
		);

		// Excerpt padding.
		$this->add_responsive_control(
			'postgrid_excerpt_padding',
			array(
				'label'      => esc_html__( 'Excerpt padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .post-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				),
			)
		);
		// Excerpt border.
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'postgrid_excerpt_border',
				'label'       => esc_html__( 'Excerpt Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .post-text p',
			)
		);

		$this->add_control(
			'if_readmore',
			array(
				'label'     => esc_html__( 'Show "Read more"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'micemade-elements' ),
				'label_on'  => esc_html__( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
			)
		);

		$this->add_control(
			'readmore_title',
			array(
				'label'     => esc_html__( '"Read more" settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => array(
					'if_readmore!' => '',
				),
			)
		);

		$this->add_control(
			'readmore_text',
			array(
				'label'       => esc_html__( 'Replace "Read more" with custom text', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'type your custom Read more text here', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => array(
					'if_readmore!' => '',
				),
			)
		);

		$this->add_control(
			'css_class',
			array(
				'label'       => esc_html__( 'Enter css class(es) for "Read more"', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'title'       => esc_html__( 'Style the "Read more" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => array(
					'if_readmore!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Ajax LOAD MORE settings.
		$this->start_controls_section(
			'section_ajax_load_more',
			array(
				'label'     => esc_html__( 'Ajax LOAD MORE settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'use_load_more!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'load_more_padding',
			array(
				'label'      => esc_html__( '"LOAD MORE" button margin', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .micemade-elements_more-posts-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'loadmore_align',
			array(
				'label'     => esc_html__( 'Alignment', 'micemade-elements' ),
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
					'{{WRAPPER}} .micemade-elements_more-posts-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'loadmore_text',
			array(
				'label'       => esc_html__( '"Load more" with custom text', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'type your custom Load more text here', 'micemade-elements' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		// Reorder post elements.
		$this->start_controls_section(
			'section_elm_sorter_label',
			array(
				'label' => esc_html__( 'Sort post elements', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$repeater_0 = new \Elementor\Repeater();
		$repeater_0->add_control(
			'elm_sorter_label',
			array(
				'label'      => esc_html__( 'Sort post elements', 'micemade-elements' ),
				'type'       => 'sorter_label', // Custom control - class Micemade_Control_Setting.
				'show_label' => false,
			)
		);

		$this->add_control(
			'elm_ordering',
			array(
				'type'         => \Elementor\Controls_Manager::REPEATER,
				'fields'       => $repeater_0->get_controls(),
				'item_actions' => array(
					'duplicate' => false,
					'add'       => false,
					'remove'    => false,
				),
				'default'      => array(
					array(
						'elm_sorter_label' => 'Title',
					),
					array(
						'elm_sorter_label' => 'Meta',
					),
					array(
						'elm_sorter_label' => 'Excerpt',
					),
					array(
						'elm_sorter_label' => 'Custom meta',
					),
				),
				'title_field'  => '{{{ elm_sorter_label }}}',
			)
		);

		$this->add_control(
			'hr_elm',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);
		$this->end_controls_section();
	}

	protected function render() {

		// Get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		// ---- QUERY SETTINGS
		$post_type    = $settings['post_type'];
		$order        = $settings['order'];
		$orderby      = $settings['orderby'];
		$orderby_meta = $settings['orderby_meta'];
		$ppp          = (int) $settings['posts_per_page'];

		// "Dynamic" setting (taxonomies).
		$taxonomy = 'page' !== $post_type ? $settings[ $post_type . '_taxonomies' ] : '';
		// "Dynamic" setting (selected posts by post type).
		$posttype_in = $settings[ $post_type . '_post_in' ];
		$post__in    = ( is_array( $posttype_in ) && isset( $posttype_in ) ) ? $posttype_in : array();
		// "Dynamic" setting (terms from taxonomy).
		$categories = isset( $settings[ $taxonomy . '_terms_for_' . $post_type ] ) ? $settings[ $taxonomy . '_terms_for_' . $post_type ] : array();
		$sticky     = $settings['sticky'];
		$offset     = (int) $settings['offset'];
		// ---- end QUERY SETTINGS.

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
		$cm_fields     = ( $settings['custom_meta'] && $settings['cm_fields'] ) ? $settings['cm_fields'] : '';
		$cm_inline     = $settings['cm_inline'];
		$elm_ordering  = $settings['elm_ordering'];
		$readmore_text = $settings['readmore_text'];
		$if_readmore   = $settings['if_readmore'];
		$loadmore_text = $settings['loadmore_text'];

		global $post;

		// Columns - responsive settings.
		$columns = array();
		foreach ( $settings as $key => $value ) {
			if ( 'columns' === $key && $value ) {
				$columns[ $key . '_desktop' ] = (string) $value;
			} elseif ( strpos( $key, 'columns_' ) === 0 ) {
				$columns[ $key ] = (string) $value;
			}
		}

		// Create grid CSS selectors based on column settings.
		$grid = micemade_elements_grid( $columns );
		$grid .= $cm_inline ? ' custom-meta-inline' : '';

		// Query posts - "micemade_elements_query_args" hook in includes/helpers.php.
		$args  = apply_filters( 'micemade_elements_query_args', $post_type, $ppp, $order, $orderby, $orderby_meta, $taxonomy, $categories, $post__in, $sticky, $offset );
		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {

			// CSS classes for posts grid container.
			$this->add_render_attribute(
				array(
					'posts-grid-container' => array(
						'class' => array(
							'micemade-elements_posts-grid',
							'mme-row',
							$use_load_more ? 'micemade-elements-load-more' : '',
							esc_attr( $style ),
						),
					),
				)
			);

			// Posts grid container.
			?>
			<div <?php $this->print_render_attribute_string( 'posts-grid-container' ); ?> >

			<?php
			// If "Load more" is selected, turn on the arguments for Ajax call.
			if ( $use_load_more ) {

				$postoptions = wp_json_encode(
					array(
						'post_type'     => $post_type,
						'order'         => $order,
						'orderby'       => $orderby,
						'orderby_meta'  => $orderby_meta,
						'taxonomy'      => $taxonomy,
						'ppp'           => $ppp,
						'categories'    => $categories,
						'post__in'      => $post__in,
						'sticky'        => $sticky,
						'startoffset'   => $offset,
						'style'         => $style,
						'show_thumb'    => $show_thumb,
						'img_format'    => $img_format,
						'excerpt'       => $excerpt,
						'excerpt_limit' => $excerpt_limit,
						'meta'          => $meta,
						'meta_ordering' => $meta_ordering,
						'css_class'     => $css_class,
						'grid'          => $grid,
						'cm_fields'     => $cm_fields,
						'elm_ordering'  => $elm_ordering,
						'if_readmore'   => $if_readmore,
						'readmore_text' => $readmore_text,
					)
				);

				echo '<input type="hidden" class="posts-grid-settings" data-postoptions="' . esc_js( $postoptions ) . '">';
			}

			foreach ( $posts as $post ) {

				setup_postdata( $post );

				// Hook in includes/helpers.php.
				apply_filters( 'micemade_elements_loop_post', $style, $grid, $show_thumb, $img_format, $meta, $meta_ordering, $excerpt, $excerpt_limit, $css_class, $taxonomy, $cm_fields, $elm_ordering, $if_readmore, $readmore_text );

			}

			echo '</div>';
		}

		wp_reset_postdata();

		if ( $use_load_more ) {
			$loadmore = $loadmore_text ? $loadmore_text : esc_html__( 'Load more', 'micemade-elements' );
			echo '<div class="micemade-elements_more-posts-wrap"><a href="#" class="micemade-elements_more-posts more_posts button">' . esc_html( $loadmore ) . '</a></div>';
		}

	}

	protected function content_template() {}

	public function render_plain_content( $instance = array() ) {}

}
