<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Settings\Manager;

/**
 * Elementor custom controls for Swiper slider.
 *
 * Used in Micemade Products Slider and Micemade Posts Slider.
 *
 * @since 1.0.0
 */
class Micemade_Shared_Controls {

	public function __construct() {}

	/**
	 * Common controls to query posts - posts slider and posts grid.
	 *
	 * @param Element_Base $element - The edited element.
	 * @param array        $args - The $args that sent to $element->start_controls_section.
	 * @return void
	 */
	public function query_controls( $element, $args ) {

		// We'll need this var only in this method.
		$post_types = apply_filters( 'micemade_elements_post_types', '' );

		$element->start_controls_section(
			'section_content',
			[
				'label' => __( 'Posts query settings', 'micemade-elements' ),
			]
		);

		$element->add_control(
			'post_type',
			[
				'label'    => __( 'Post type', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => $post_types,
				'multiple' => false,
			]
		);

		$element->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Total posts', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6',
				'title'   => __( 'Enter total number of posts to show', 'micemade-elements' ),
			]
		);

		$element->add_control(
			'offset',
			[
				'label'   => __( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
				'title'   => __( 'Offset is number of skipped posts', 'micemade-elements' ),
			]
		);

		$element->add_control(
			'order',
			[
				'label'    => __( 'Post ordering', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => 'DESC',
				'label_block' => true,
				'options'  => [
					'ASC'  => 'Ascending (older first)',
					'DESC' => 'Descending (newer first)',
				],
				'multiple' => false,
			]
		);

		$element->add_control(
			'orderby',
			[
				'label'       => __( 'Order posts by', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => 'date',
				'label_block' => true,
				'options'     => [
					'none'           => __( 'No ordering specified', 'micemade-elements' ),
					'ID'             => __( 'Order by post id.', 'micemade-elements' ),
					'author'         => __( 'Order by author.', 'micemade-elements' ),
					'title'          => __( 'Order by title.', 'micemade-elements' ),
					'name'           => __( 'Order by name (post slug).', 'micemade-elements' ),
					'date'           => __( 'Order by date.', 'micemade-elements' ),
					'modified'       => __( 'Order by last modified date.', 'micemade-elements' ),
					'parent'         => __( 'Order by post/page parent id.', 'micemade-elements' ),
					'rand'           => __( 'Random order.', 'micemade-elements' ),
					'comment_count'  => __( 'Order by number of comments', 'micemade-elements' ),
					'menu_order'     => __( 'Order by sorting in admin (pages, attachments)', 'micemade-elements' ),
					'meta_value'     => __( 'Order by meta value (meta key must be added)', 'micemade-elements' ),
					'meta_value_num' => __( 'Order by meta numeric value (meta key must be added)', 'micemade-elements' ),
				],
				'multiple'    => false,
			]
		);

		$element->add_control(
			'orderby_meta',
			[
				'label'       => __( 'Meta key for ordering', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter meta field key here', 'micemade-elements' ),
				'condition' => [
					'orderby' => [
						'meta_value',
						'meta_value_num',
					],
				],
			]
		);

		$element->add_control(
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

		$element->add_control(
			'sticky',
			[
				'label'     => esc_html__( 'Show only sticky posts', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'micemade-elements' ),
				'label_on'  => __( 'Yes', 'micemade-elements' ),
				'condition' => [
					'post_type' => 'post',
				],
			]
		);

		/**
		 * Get all the post types with its taxonomies in array.
		 */
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

			if ( empty( $tax_options ) ) {
				continue;
			}

			// Controls for selection of taxonomies per post type.
			// -- correct "-" to "_" in taxonomies, JS issue in Elementor-conditional hiding.
			$taxonomy_control = str_replace( '-', '_', $post_type ) . '_taxonomies';
			$element->add_control(
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
				$element->add_control(
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

		/**
		 * For every post type create post__in selection.
		 */
		foreach ( $post_types as $post_type => $label ) {
			$element->add_control(
				$post_type . '_post_in',
				[
					'label'    => esc_html__( 'Select items from ' . strtolower( $label ) , 'micemade-elements' ),
					'type'     => Controls_Manager::SELECT2,
					'default'  => 3,
					'options'  => apply_filters( 'micemade_posts_array', $post_type, 'id' ),
					'multiple' => true,
					'label_block' => true,
					'condition'   => [
						'post_type' => $post_type,
						'sticky!'   => 'yes',
					],
				]
			);
		}

		$element->end_controls_section();

	}

	/**
	 * Common controls in products and posts slider
	 *
	 * @param Element_Base $element - The edited element.
	 * @param array        $args - The $args that sent to $element->start_controls_section.
	 * @return void
	 */
	public function slider_controls( $element, $args ) {

		// Section elements style.
		$element->start_controls_section(
			'section_slider_settings',
			[
				'label'     => esc_html__( 'Slider settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'heading_slider',
			[
				'label'     => __( 'Slider settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		// Slides to show.
		$slides_no = [
			1 => __( 'One', 'micemade-elements' ),
			2 => __( 'Two', 'micemade-elements' ),
			3 => __( 'Three', 'micemade-elements' ),
			4 => __( 'Four', 'micemade-elements' ),
			6 => __( 'Six', 'micemade-elements' ),
		];

		$element->add_control(
			'posts_per_slide',
			[
				'label'              => __( 'Items per slide', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'posts_per_slide_tab',
			[
				'label'              => __( 'Items per slide (tablets)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 2,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'posts_per_slide_mob',
			[
				'label'              => __( 'Items per slide (mobiles)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 1,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);
		// end slides to show.

		// Slides to scroll.
		$element->add_control(
			'scroll_slides_note',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small><strong>"Items to scroll"</strong> will fallback to <strong>"Items per slide"</strong>, if the scroll number is higher than per slide number.</small>', 'micemade-elements' ),
				'content_classes' => 'your-class',
				'separator'       => 'before',
			]
		);
		$element->add_control(
			'posts_to_scroll',
			[
				'label'              => __( 'Items to scroll', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);
		$element->add_control(
			'posts_to_scroll_tab',
			[
				'label'              => __( 'Items to scroll (tablets)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 2,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);
		$element->add_control(
			'posts_to_scroll_mob',
			[
				'label'              => __( 'Items to scroll (mobile)', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 1,
				'options'            => $slides_no,
				'frontend_available' => true,
			]
		);
		// End slides to scroll.

		// Space between the slides.
		$element->add_responsive_control(
			'space',
			[
				'label'              => __( 'Space between', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 30,
				'min'                => 0,
				'step'               => 10,
				'frontend_available' => true,
				'separator'          => 'before',
			]
		);

		// Pagination.
		$element->add_control(
			'pagination',
			[
				'label'              => __( 'Slider pagination', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => [
					'none'        => __( 'None', 'micemade-elements' ),
					'bullets'     => __( 'Bullets', 'micemade-elements' ),
					'progressbar' => __( 'Progress bar', 'micemade-elements' ),
					'fraction'    => __( 'Fraction', 'micemade-elements' ),
				],
				'frontend_available' => true,
			]
		);

		// Slider navigation.
		$element->add_control(
			'buttons',
			[
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'elementor' ),
				'label_on'           => __( 'Yes', 'elementor' ),
				'default'            => 'yes',
				'frontend_available' => true,

			]
		);

		$element->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'autoplay_speed',
			array(
				'label'              => __( 'Autoplay Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5000,
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'autplay_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>No value under 1000 (ms) will be accepted.</small>' ),
				'content_classes' => 'your-class',
				'condition'       => array(
					'autoplay' => 'yes',
				),
				'separator'       => 'after',
			)
		);

		$element->add_control(
			'pause_on_hover',
			array(
				'label'              => __( 'Pause on Hover', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'pause_on_interaction',
			array(
				'label'              => __( 'Pause on Interaction', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'condition'          => array(
					'autoplay' => 'yes',
				),
				'frontend_available' => true,
			)
		);

		// Loop the slider.
		$element->add_control(
			'infinite',
			array(
				'label'              => __( 'Infinite Loop', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'yes',
				'options'            => array(
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no'  => __( 'No', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'effect',
			array(
				'label'              => __( 'Effect', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => array(
					'slide' => __( 'Slide', 'micemade-elements' ),
					'fade'  => __( 'Fade', 'micemade-elements' ),
				),
				'frontend_available' => true,
				'condition'          => [
					'posts_per_slide'     => '1',
					'posts_per_slide_tab' => '1',
					'posts_per_slide_mob' => '1',
				]
			)
		);

		$element->add_control(
			'speed',
			array(
				'label'              => __( 'Animation Speed', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 500,
				'frontend_available' => true,
			)
		);

		$element->end_controls_section();

		// Section elements style.
		$element->start_controls_section(
			'section_slider_elements_style',
			[
				'label'     => esc_html__( 'Slider elements style', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'pagination_color',
			[
				'label'     => __( 'Pagination color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-progressbar-fill'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-fraction'      => 'color: {{VALUE}};',
				],
				'default'   => '#333333',
				'separator' => 'after',
				'condition' => [
					'pagination!' => 'none',
				],
			]
		);

		$element->add_control(
			'buttons_color',
			[
				'label'     => __( 'Arrows icon color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-next:before' => 'color: {{VALUE}};',
				],
				'default'   => '#2F2E2EC7',
				'condition' => [
					'buttons!' => '',
				],
			]
		);
		$element->add_control(
			'buttons_bckcolor',
			[
				'label'     => __( 'Arrows button color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'background-color: {{VALUE}};',
				],
				'default'   => '#FFFFFF7A',
				'separator' => 'after',
				'condition' => [
					'buttons!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'nav_arrows_vert_position',
			[
				'label'       => __( 'Arrows vertical position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px' ],
				'default'     => [
					'size' => 50,
					'unit' => '%',
				],
				'range'       => [
					'%'  => [
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$element->add_control(
			'arrow_icon',
			[
				'label'       => __( 'Arrow icons', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'include' => [
					'fa fa-arrow-left',
					'fa fa-angle-left',
					'fa fa-angle-double-left',
					'fa fa-arrow-circle-left',
					'fa fa-caret-left',
					'fa fa-chevron-left',
					'fa fa-chevron-circle-left',
					'fa fa-long-arrow-left',
				],
				'default'     => 'fa fa-chevron-left',
			]
		);

		$element->add_responsive_control(
			'nav_arrows_size',
			[
				'label'       => __( 'Arrows icon size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 12,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-next:before, {{WRAPPER}} .swiper-button-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'nav_arrows_back_size',
			[
				'label'       => __( 'Arrows button size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 32,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-next:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$element->add_control(
			'nav_arrows_back_roundness',
			[
				'label'       => __( 'Arrows button roundness', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ 'px' ],
				'default'     => [
					'size' => 12,
					'unit' => 'px',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!' => '',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'label'     => __( 'Arrow button border' ),
				'name'      => 'arrow_button_border',
				'selector'  => '{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after',
				'separator' => 'after',
				'condition' => [
					'buttons!' => '',
				],
			]
		);

		// Slider navigation.
		$element->add_control(
			'show_icon_fix',
			[
				'label'     => esc_html__( 'Fix icon position', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			]
		);
		$element->add_control(
			'fix_icon_note',
			[
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Some icons may misalign with it\'s button. Enable the control above to fix the arrow icon position.' ),
				'content_classes' => 'your-class',
			]
		);

		$element->add_responsive_control(
			'nav_arrows_fix_position',
			[
				'label'       => __( 'Fix arrow position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => [ '%', 'px' ],
				'default'     => [
					'size' => 0,
					'unit' => '%',
				],
				'range'       => [
					'%'  => [
						'max'  => 50,
						'min'  => -50,
						'step' => 1,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .swiper-button-prev:before' => 'margin-top: {{SIZE}}{{UNIT}};',
					' {{WRAPPER}} .swiper-button-next:before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'buttons!'      => '',
					'show_icon_fix' => 'yes',
				],
			]
		);

		$element->end_controls_section();

	}

}
