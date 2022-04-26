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
			array(
				'label' => __( 'Posts query settings', 'micemade-elements' ),
			)
		);

		$element->add_control(
			'post_type',
			array(
				'label'    => __( 'Post type', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => 'post',
				'options'  => $post_types,
				'multiple' => false,
			)
		);

		$element->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'Total posts', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '6',
				'title'   => __( 'Enter total number of posts to show', 'micemade-elements' ),
			)
		);

		$element->add_control(
			'offset',
			array(
				'label'   => __( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
				'title'   => __( 'Offset is number of skipped posts', 'micemade-elements' ),
			)
		);

		$element->add_control(
			'order',
			array(
				'label'       => __( 'Post ordering', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => 'DESC',
				'label_block' => true,
				'options'     => array(
					'ASC'  => 'Ascending (older first)',
					'DESC' => 'Descending (newer first)',
				),
				'multiple'    => false,
			)
		);

		$element->add_control(
			'orderby',
			array(
				'label'       => __( 'Order posts by', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => 'date',
				'label_block' => true,
				'options'     => array(
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
				),
				'multiple'    => false,
			)
		);

		$element->add_control(
			'orderby_meta',
			array(
				'label'       => __( 'Meta key for ordering', 'micemade-elements' ),
				'label_block' => true,
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter meta field key here', 'micemade-elements' ),
				'condition'   => array(
					'orderby' => array(
						'meta_value',
						'meta_value_num',
					),
				),
			)
		);

		$element->add_control(
			'heading_filtering',
			array(
				'label'     => __( 'Posts filtering options', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'post_type!' => 'page',
				),
			)
		);

		$element->add_control(
			'sticky',
			array(
				'label'     => esc_html__( 'Show only sticky posts', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'micemade-elements' ),
				'label_on'  => __( 'Yes', 'micemade-elements' ),
				'condition' => array(
					'post_type' => 'post',
				),
			)
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
				array(
					'label'       => esc_html__( 'Select taxonomy', 'micemade-elements' ),
					'type'        => Controls_Manager::SELECT2,
					'default'     => '',
					'options'     => $tax_options,
					'multiple'    => false,
					'label_block' => true,
					'condition'   => array(
						'post_type' => $post_type,
					),
				)
			);

			foreach ( $tax_options as $taxonomy => $label ) {
				$element->add_control(
					$taxonomy . '_terms_for_' . $post_type,
					array(
						'label'       => esc_html__( 'Select ', 'micemade-elements' ) . strtolower( $label ),
						'type'        => Controls_Manager::SELECT2,
						'default'     => array(),
						'options'     => apply_filters( 'micemade_elements_terms', $taxonomy ),
						'multiple'    => true,
						'label_block' => true,
						'condition'   => array(
							$taxonomy_control => $taxonomy,
							'post_type'       => $post_type,
						),
					)
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
				array(
					// translators: $label for post type label.
					'label'       => sprintf( esc_html__( 'Select items from %s', 'micemade-elements' ), strtolower( $label ) ),
					'type'        => Controls_Manager::SELECT2,
					'default'     => 3,
					'options'     => apply_filters( 'micemade_posts_array', $post_type, 'id' ),
					'multiple'    => true,
					'label_block' => true,
					'condition'   => array(
						'post_type' => $post_type,
						'sticky!'   => 'yes',
					),
				)
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
		// $element->start_controls_section(
		// 'section_slider_settings',
		// [
		// 'label'     => esc_html__( 'Slider settings', 'micemade-elements' ),
		// 'tab'       => Controls_Manager::TAB_STYLE,
		// ]
		// );

		$element->add_control(
			'heading_slider',
			array(
				'label'     => __( 'Slider settings', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// Choices for number of slides.
		$slides_no = array(
			1 => __( 'One', 'micemade-elements' ),
			2 => __( 'Two', 'micemade-elements' ),
			3 => __( 'Three', 'micemade-elements' ),
			4 => __( 'Four', 'micemade-elements' ),
			6 => __( 'Six', 'micemade-elements' ),
		);
		// Slides to show.
		$element->add_responsive_control(
			'slides_to_show',
			array(
				'label'              => __( 'Items per slide', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => $slides_no,
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}}' => '--e-image-carousel-slides-to-show: {{VALUE}}',
				),
			)
		);

		// Slides to scroll.
		$element->add_responsive_control(
			'slides_to_scroll',
			array(
				'label'              => __( 'Items to scroll', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 3,
				'options'            => $slides_no,
				'frontend_available' => true,
			)
		);
		$element->add_control(
			'scroll_slides_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Only divisible values to "Items per slide" can be selected.</small>', 'micemade-elements' ),
				'content_classes' => 'your-class',
				'separator'       => 'after',
			)
		);
		// End slides to scroll.

		// Space between the slides.
		$element->add_control(
			'space',
			array(
				'label'              => __( 'Space between', 'micemade-elements' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 30,
				'min'                => 0,
				'step'               => 10,
				'frontend_available' => true,
				'separator'          => 'before',
			)
		);

		// Pagination.
		$element->add_control(
			'pagination',
			array(
				'label'              => __( 'Slider pagination', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bullets',
				'options'            => array(
					'none'        => __( 'None', 'micemade-elements' ),
					'bullets'     => __( 'Bullets', 'micemade-elements' ),
					'progressbar' => __( 'Progress bar', 'micemade-elements' ),
					'fraction'    => __( 'Fraction', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		// Slider navigation.
		$element->add_control(
			'buttons',
			array(
				'label'              => esc_html__( 'Show navigation arrows', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_off'          => __( 'No', 'elementor' ),
				'label_on'           => __( 'Yes', 'elementor' ),
				'default'            => 'yes',
				'frontend_available' => true,

			)
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
				'condition'          => array(
					'slides_to_show' => '1',
					// 'slides_to_show_tab' => '1',
					// 'slides_to_show_mob' => '1',
				),
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

		// $element->end_controls_section();
	}

	public function slider_styles( $element, $arg ) {

		// Section elements style.
		$element->start_controls_section(
			'section_slider_elements_style',
			array(
				'label' => esc_html__( 'Slider elements style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$element->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Pagination color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-progressbar-fill'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .swiper-pagination-fraction'      => 'color: {{VALUE}};',
				),
				'default'   => '#333333',
				'separator' => 'after',
				'condition' => array(
					'pagination!' => 'none',
				),
			)
		);

		$element->add_control(
			'buttons_color',
			array(
				'label'     => __( 'Arrows icon color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-next:before' => 'color: {{VALUE}};',
				),
				'default'   => '#2F2E2EC7',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);
		$element->add_control(
			'buttons_bckcolor',
			array(
				'label'     => __( 'Arrows button color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'background-color: {{VALUE}};',
				),
				'default'   => '#FFFFFF7A',
				'separator' => 'after',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);

		$element->add_responsive_control(
			'nav_arrows_vert_position',
			array(
				'label'       => __( 'Arrows vertical position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
				'default'     => array(
					'size' => 50,
					'unit' => '%',
				),
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
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$element->add_control(
			'arrow_icon',
			array(
				'label'       => __( 'Arrow icons', 'micemade-elements' ),
				'type'        => \Elementor\Controls_Manager::ICON,
				'label_block' => true,
				'include'     => array(
					'fa fa-arrow-left',
					'fa fa-angle-left',
					'fa fa-angle-double-left',
					'fa fa-arrow-circle-left',
					'fa fa-caret-left',
					'fa fa-chevron-left',
					'fa fa-chevron-circle-left',
					'fa fa-long-arrow-left',
				),
				'default'     => 'fa fa-chevron-left',
			)
		);

		$element->add_responsive_control(
			'nav_arrows_size',
			array(
				'label'       => __( 'Arrows icon size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 12,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-next:before, {{WRAPPER}} .swiper-button-prev:before' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$element->add_responsive_control(
			'nav_arrows_back_size',
			array(
				'label'       => __( 'Arrows button size', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 32,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next, {{WRAPPER}} .swiper-button-next:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$element->add_control(
			'nav_arrows_back_roundness',
			array(
				'label'       => __( 'Arrows button roundness', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 12,
					'unit' => 'px',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!' => '',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'label'     => __( 'Arrow button border' ),
				'name'      => 'arrow_button_border',
				'selector'  => '{{WRAPPER}} .swiper-button-prev:after,{{WRAPPER}} .swiper-button-next:after',
				'separator' => 'after',
				'condition' => array(
					'buttons!' => '',
				),
			)
		);

		// Slider navigation.
		$element->add_control(
			'show_icon_fix',
			array(
				'label'     => esc_html__( 'Fix icon position', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on'  => __( 'Yes', 'elementor' ),
				'default'   => 'no',
			)
		);
		$element->add_control(
			'fix_icon_note',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<small>Some icons may misalign with it\'s button. Enable the control above to fix the arrow icon position.' ),
				'content_classes' => 'your-class',
			)
		);

		$element->add_responsive_control(
			'nav_arrows_fix_position',
			array(
				'label'       => __( 'Fix arrow position', 'micemade-elements' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'size_units'  => array( '%', 'px' ),
				'default'     => array(
					'size' => 0,
					'unit' => '%',
				),
				'range'       => array(
					'%' => array(
						'max'  => 50,
						'min'  => -50,
						'step' => 1,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev:before' => 'margin-top: {{SIZE}}{{UNIT}};',
					' {{WRAPPER}} .swiper-button-next:before' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'buttons!'      => '',
					'show_icon_fix' => 'yes',
				),
			)
		);

		$element->end_controls_section();
	}

}
