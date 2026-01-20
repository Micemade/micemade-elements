<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Widget_Base;
use Elementor\Controls_Stack;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

class Micemade_Posts_Slider extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$today = gmdate( 'YmdGi', time() );

		if ( ! wp_script_is( 'micemade-slider-js', 'registered' ) ) {
			wp_register_script( 'micemade-slider-js', MICEMADE_ELEMENTS_URL . 'assets/js/custom/handlers/slider.js', array( 'elementor-frontend' ), $today, true );
		}
	}

	public function get_script_depends() {
		return [ 'micemade-slider-js' ];
	}

	public function get_name() {
		return 'micemade-posts-slider';
	}

	public function get_title() {
		return __( 'Micemade Posts Slider', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-carousel';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function register_controls() {

		/***************************************************
		 * SHARED CONTROLS FOR POSTS QUERY AND SLIDER SETTINGS
		 *
		 * "elementor/element/{$section_name}/{$section_id}/after_section_end"
		 * hooked in "class-micemade-elements" and file "includes/class-micemade-shared-controls.php
		 * https://code.elementor.com/php-hooks/#elementorelementsection_namesection_idbefore_section_start
		 * *************************************************
		*/

		// STYLES FOR POSTS.
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Layout general', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => __( 'Layout style', 'micemade-elements' ),
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

		$this->add_control(
			'custom_meta',
			[
				'label'     => esc_html__( 'Show custom meta (advanced)', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			]
		);

		$this->add_control(
			'custom_meta_note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Add custom fields meta keys and edit settings bellow, under the "Custom meta" section. Only for advanced users. <a href="https://wordpress.org/support/article/custom-fields/" target="_blank">Learn more</a></small>', 'micemade-elements' ),
				'content_classes' => 'your-class',
				'condition'       => [
					'custom_meta' => 'yes',
				],
			]
		);
		$this->add_control(
			'hr_cm',
			[
				'type'      => Controls_Manager::DIVIDER,
				'condition' => [
					'custom_meta' => 'yes',
				],
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
			'section_items_style',
			[
				'label' => __( 'Items style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// Normal / Hover tabs.
		$this->start_controls_tabs( 'tabs_post_background' );
		$this->start_controls_tab(
			'tab_post_background_normal',
			[
				'label' => __( 'Normal', 'micemade-elements' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_background_color',
				'label'     => __( 'Post background', 'micemade-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .style_1 .inner-wrap, {{WRAPPER}} .style_2 .inner-wrap',
				'condition' => [
					'style' => [
						'style_1',
						'style_2',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_overlay_color',
				'label'     => __( 'Post overlay', 'micemade-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .style_3 .inner-wrap .post-overlay, {{WRAPPER}} .style_4 .inner-wrap .post-overlay',
				'condition' => [
					'style' => [
						'style_3',
						'style_4',
					],
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

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_background_color_hover',
				'label'     => __( 'Post background on hover', 'micemade-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .style_1 .inner-wrap:hover, {{WRAPPER}} .style_2 .inner-wrap:hover',
				'condition' => [
					'style' => [
						'style_1',
						'style_2',
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'post_text_overlay_color_hover',
				'label'     => __( 'Post overlay on hover', 'micemade-elements' ),
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .style_3 .inner-wrap:hover .post-overlay, {{WRAPPER}} .style_4 .inner-wrap:hover .post-overlay',
				'condition' => [
					'style' => [
						'style_3',
						'style_4',
					],
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'post_text_height',
			[
				'label'     => __( 'Items height', 'micemade-elements' ),
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
					'{{WRAPPER}} .inner-wrap .post-thumb-back' => 'right: calc( 100% - {{SIZE}}%); width: auto;',

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
				'label'      => esc_html__( 'Post content padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'post_text_align',
			[
				'label'     => __( 'Horizontal align', 'micemade-elements' ),
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
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .post-text, {{WRAPPER}} .post-text *' => '{{VALUE}};',
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
					'{{WRAPPER}} .inner-wrap, {{WRAPPER}} .post-text' => 'justify-content: {{VALUE}};',
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

		$this->add_control(
			'meta_back_color',
			[
				'label'     => __( 'Meta Back Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .post-text .meta' => 'background-color: {{VALUE}};',
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

		$this->add_control(
			'meta_back_hover_color',
			[
				'label'     => __( 'Meta Back Hover Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .post-text .meta' => 'background-color: {{VALUE}};',
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

		$this->add_responsive_control(
			'meta_padding',
			[
				'label'      => esc_html__( 'Meta padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-text .meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'meta_sorter_label',
			[
				'label'      => esc_html__( 'Sort meta info', 'micemade-elements' ),
				'type'       => 'sorter_label', // Custom control - class Micemade_Control_Setting.
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
				'type'         => Controls_Manager::REPEATER,
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

		// Custom meta controls.
		$this->start_controls_section(
			'section_custom_meta',
			[
				'label'     => __( 'Custom Meta', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'custom_meta' => 'yes',
				],
			]
		);
		/*
		$this->add_control(
			'cm_keys',
			[
				'label'       => __( 'Custom meta keys', 'micemade-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				//'default'     => __( 'Default title', 'micemade-elements' ),
				'placeholder' => __( 'Separate meta keys with a comma (,)', 'micemade-elements' ),
			]
		);
		 */

		// Repeater controls for custom meta keys.
		$repeater_cm = new Repeater();
		$repeater_cm->add_control(
			'cm_key',
			[
				'label'       => __( 'Custom meta field key', 'micemade-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => micemade_get_meta_keys(),
				'multiple' => false,
			]
		);
		$repeater_cm->add_control(
			'cm_type',
			[
				'label'   => __( 'Custom meta type', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					'timestamp' => __( 'Timestamp', 'micemade-elements' ),
					'string'    => __( 'String', 'micemade-elements' ),
				],
			]
		);
		$repeater_cm->add_control(
			'cm_label',
			[
				'label'       => __( 'Label', 'micemade-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Add label for meta field here', 'micemade-elements' ),
			]
		);
		$repeater_cm->add_control(
			'cm_after',
			[
				'label'       => __( 'Additional label after', 'micemade-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Label after custom meta value.', 'micemade-elements' ),
			]
		);
		$this->add_control(
			'cm_fields',
			[
				'label'       => __( 'Custom meta fields', 'micemade-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_cm->get_controls(),
				'item_actions' => [
					'duplicate' => true,
					'add'       => true,
					'remove'    => true,
				],
				'default'     => [
					[
						'cm_key'   => '',
						'cm_type'  => 'string',
						'cm_label' => '',
						'cm_after' => '',
					],
				],
				'title_field' => '{{{ cm_label }}}',
			]
		);

		$this->add_control(
			'custom_meta_text_color',
			[
				'label'     => __( 'Custom meta text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .custom-meta span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'custom_meta_typography',
				'label'    => __( 'Custom meta typography', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .post-text .custom-meta',
			]
		);
		$this->add_control(
			'cm_inline',
			[
				'label'     => esc_html__( 'Show custom meta inline', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
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
				'label'   => __( 'Excerpt words limit', 'micemade-elements' ),
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
					'{{WRAPPER}} .post-text p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
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
			'if_readmore',
			[
				'label'     => esc_html__( 'Show "Read more"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'readmore_title',
			[
				'label'     => __( '"Read more" settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'if_readmore!' => '',
				],
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label'       => __( 'Replace "Read more" with custom text', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'type your custom Read more text here', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => [
					'if_readmore!' => '',
				],
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
				'condition' => [
					'if_readmore!' => '',
				],
			]
		);

		$this->end_controls_section();

		// Reorder post elements.
		$this->start_controls_section(
			'section_elm_sorter_label',
			[
				'label'     => __( 'Sort post elements', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$repeater_0 = new Repeater();
		$repeater_0->add_control(
			'elm_sorter_label',
			[
				'label'      => esc_html__( 'Sort post elements', 'micemade-elements' ),
				'type'       => 'sorter_label', // Custom control - class Micemade_Control_Setting.
				'show_label' => false,
			]
		);

		$this->add_control(
			'elm_ordering',
			[
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater_0->get_controls(),
				'item_actions' => [
					'duplicate' => false,
					'add'       => false,
					'remove'    => false,
				],
				'default'      => [
					[
						'elm_sorter_label' => 'Title',
					],
					[
						'elm_sorter_label' => 'Meta',
					],
					[
						'elm_sorter_label' => 'Excerpt',
					],
					[
						'elm_sorter_label' => 'Custom meta',
					],
				],
				'title_field'  => '{{{ elm_sorter_label }}}',
			]
		);

		$this->add_control(
			'hr_elm',
			[
				'type'      => Controls_Manager::DIVIDER,
			]
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
		$posttype_in = $settings[ $post_type . '_post_in'];
		$post__in    = ( is_array( $posttype_in ) && isset( $posttype_in ) ) ? $posttype_in : [];
		// "Dynamic" setting (terms from taxonomy).
		$categories = isset( $settings[ $taxonomy . '_terms_for_' . $post_type ] ) ? $settings[ $taxonomy . '_terms_for_' . $post_type ] : [];
		$sticky     = $settings['sticky'];
		$offset     = (int) $settings['offset'];
		// ---- end QUERY SETTINGS.

		// Slider settings.
		$pagination           = $settings['pagination'];
		$buttons              = $settings['buttons'];
		$arrow_icon           = $settings['arrow_icon'];
		// Layout and style settings.
		$show_thumb    = $settings['show_thumb'];
		$img_format    = $settings['img_format'];
		$style         = $settings['style'];
		$excerpt       = $settings['excerpt'];
		$excerpt_limit = $settings['excerpt_limit'];
		$meta          = $settings['meta'];
		$meta_ordering = $settings['meta_ordering'];
		$css_class     = $settings['css_class'];
		$cm_fields     = ( $settings['custom_meta'] && $settings['cm_fields'] ) ? $settings['cm_fields'] : '';
		$cm_inline     = $settings['cm_inline'];
		$elm_ordering  = $settings['elm_ordering'];
		$if_readmore   = $settings['if_readmore'];
		$readmore_text = $settings['readmore_text'];

		global $post;

		$grid  = 'swiper-slide';
		$grid .= $cm_inline ? ' custom-meta-inline' : '';

		// Query posts - "micemade_elements_query_args" hook in includes/helpers.php.
		$args  = apply_filters( 'micemade_elements_query_args', $post_type, $ppp, $order, $orderby, $orderby_meta, $taxonomy, $categories, $post__in, $sticky, $offset );
		$posts = get_posts( $args );

		if ( ! empty( $posts ) ) {

			// CSS classes for posts slider container.
			$this->add_render_attribute(
				[
					'posts-slider-container' => [
						'class'           => [
							'swiper-container',
							'micemade-elements_slider',
							'micemade-elements_posts-slider',
							esc_attr( $style ),
						],
						'id'              => 'slider_' . $this->get_id(),
						'data-mme-widget' => 'micemade_slider_posts',
					],
				]
			);

			// Posts slider container.
			?>
<div <?php $this->print_render_attribute_string( 'posts-slider-container' ); ?>>

	<?php
			echo '<div class="swiper-wrapper">';

			foreach ( $posts as $post ) {

				setup_postdata( $post );

				// Hook in includes/helpers.php.
				apply_filters( 'micemade_elements_loop_post', $style, $grid, $show_thumb, $img_format, $meta, $meta_ordering, $excerpt, $excerpt_limit, $css_class, $taxonomy, $cm_fields, $elm_ordering, $if_readmore, $readmore_text );

			}

			echo '</div>'; // end .swiper-wrapper.

			if ( 'none' !== $pagination ) {
				echo '<div class="swiper-pagination"></div>';
			}

			if ( $buttons ) {
				echo '<div class="swiper-button-next ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Next', 'micemade-elements' ) . '</span></div>';
				echo '<div class="swiper-button-prev ' . esc_attr( $arrow_icon ) . '" screen-reader><span>' . esc_html__( 'Previous', 'micemade-elements' ) . '</span></div>';
			}

			echo '</div>';// end posts slider container.
		}

		wp_reset_postdata();

	}

	protected function content_template() {}

	public function render_plain_content( $instance = [] ) {}

}
