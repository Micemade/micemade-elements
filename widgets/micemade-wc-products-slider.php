<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_WC_Products_Slider extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-products-slider';
	}

	public function get_title() {
		return __( 'Micemade WC products slider', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade products slider', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'posts_per_page',
			[
				'label'		=> __( 'Number of products', 'micemade-elements' ),
				'type'		=> Controls_Manager::NUMBER,
				'default'	=> 6,
				'min'		=> 0,
				'step'		=> 1,
				'title'		=> __( 'Enter total number of products to show', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'offset',
			[
				'label'		=> __( 'Offset', 'micemade-elements' ),
				'type'		=> Controls_Manager::NUMBER,
				'default'	=> 0,
				'min'		=> 0,
				'step'		=> 1,
				'title'		=> __( 'Offset is number of skipped posts', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'heading_filtering',
			[
				'label' => __( 'Products filtering options', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'categories',
			[
				'label'		=> esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT2,
				'default'	=> array(),
				'options'	=> apply_filters('micemade_elements_terms','product_cat'),
				'multiple'	=> true
			]
		);
		
		
		$this->add_control(
			'filters',
			[
				'label'		=> __( 'Products filters', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> 'latest',
				'options'	=> [
					'latest'		=> __( 'Latest products', 'micemade-elements' ),
					'featured'		=> __( 'Featured products', 'micemade-elements' ) ,
					'best_sellers'	=> __( 'Best selling products', 'micemade-elements' ),
					'best_rated'	=> __( 'Best rated products', 'micemade-elements' ),
					'on_sale'		=> __( 'Products on sale', 'micemade-elements' ),
					'random'		=> __( 'Random products', 'micemade-elements' )
				]
			]
		);
		
		$this->add_control(
			'heading_slider',
			[
				'label' => __( 'Slider options', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'posts_per_slide',
			[
				'label' => __( 'Products per slide', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'posts_per_slide_tab',
			[
				'label' => __( 'Products per slide (on tablets)', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 2,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'posts_per_slide_mob',
			[
				'label' => __( 'Products per slide (on mobiles)', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'space',
			[
				'label' => __( 'Space between slides', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 30,
				'min' => 0,
				'step' => 10,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'		=> __( 'Slider pagination', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> 'bullets',
				'options'	=> [
					'none'		=> __( 'None', 'micemade-elements' ),
					'bullets'	=> __( 'Bullets', 'micemade-elements' ),
					'progress'	=> __( 'Progress bar', 'micemade-elements' ),
					'fraction'	=> __( 'Fraction', 'micemade-elements' ),
				]
			]
		);
		
		$this->end_controls_section();
		
		// TAB 2 - STYLES FOR POSTS :
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Micemade products slider', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'style',
			[
				'label'		=> __( 'Product base style', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> 'style_1',
				'options'	=> [
					'style_1'		=> __( 'Style one', 'micemade-elements' ),
					'style_2'		=> __( 'Style two', 'micemade-elements' ),
					'style_3'		=> __( 'Style three', 'micemade-elements' ),
					'style_4'		=> __( 'Style four', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'img_format',
			[
				'label' => esc_html__( 'Product image format', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => apply_filters('micemade_elements_image_sizes','')
			]
		);
		
		$this->add_control(
			'price',
			[
				'label'		=> esc_html__( 'Show price', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'add_to_cart',
			[
				'label'		=> esc_html__( 'Show "Add to cart"', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'excerpt',
			[
				'label'		=> esc_html__( 'Show product short description', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> false,
			]
		);
		
		$this->add_control(
			'meta',
			[
				'label'		=> esc_html__( 'Show product meta', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'post_text_background_color',
			[
				'label' => __( 'Product text background', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap, {{WRAPPER}} .post-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'post_text_background_opacity',
			[
				'label' => __( 'Product text background opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.8,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .post-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'style' => ['style_3','style_4'],
				],
			]
		);
		
		$this->add_control(
			'post_text_background_opacity_hover',
			[
				'label' => __( 'Product text background opacity hover', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .inner-wrap:hover .post-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'style' => ['style_3','style_4']
				],
			]
		);
		
		$this->add_responsive_control(
			'product_text_height',
			[
				'label' => __( 'Product text height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'range' => [
					'px' => [
						'max' => 800,
						'min' => 0,
						'step' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .post-text' => 'height: {{SIZE}}px;',
				],
				'condition' => [
					'style' => ['style_3','style_4']
				],
			]
		);
		
		$this->add_responsive_control(
			'post_text_padding',
			[
				'label' => esc_html__( 'Product text padding', 'micemade-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .post-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'post_text_align',
			[
				'label' => __( 'Product text alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .post-text' => 'text-align: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .inner-wrap',
			]
		);
		
		$this->end_controls_section();

		
		// TITLE STYLES
		
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		// HOVER TABS 
		$this->start_controls_tabs( 'tabs_button_style' );
		// NORMAL
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'title_text_color',
			[
				'label' => __( 'Title Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .post-text h4 a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// HOVER
		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Title Color on Hover', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text h4:hover a' => 'color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		// end tabs
		
		// Title typohraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text h4',
			]
		);
		
		$this->end_controls_section();
		
		//
		// POST EXCERPT AND META
		$this->start_controls_section(
			'section_text',
			[
				'label' => __( 'Excerpt and Meta', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'meta_text_color',
			[
				'label' => __( 'Meta Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .meta a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'meta_font_size',
			[
				'label' => esc_html__( 'Meta font size (%)', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				]

			]
		);
		
		$this->add_control(
			'excerpt_text_color',
			[
				'label' => __( 'Excerpt Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .post-text .micemade-elements-readmore' => 'color: {{VALUE}};',
				],
				
			]
		);
		
		// Title typohraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Excerpt typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text p',
				
			]
		);

		
		$this->end_controls_section();
		
	
		
		//
		// POST EXCERPT AND META
		$this->start_controls_section(
			'section_price',
			[
				'label' => __( 'Price', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'price_text_color',
			[
				'label' => __( 'Price text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-text .meta span, {{WRAPPER}} .post-text .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'price_font_size',
			[
				'label' => esc_html__( 'Price font size (%)', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .post-text .price' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				]

			]
		);
		
		$this->end_controls_section();
		
		// Add custom css style selector
		$this->start_controls_section(
			'section_css_class',
			[
				'label' => __( '"Product details" custom css', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'css_class',
			[
				'label'		=> __( 'CSS class(es) for "Product details"', 'micemade-elements' ),
				'type'		=> Controls_Manager::TEXT,
				'default'	=> '',
				'title'		=> __( 'Style the "Read more" with css class(es) defined in your theme, plugin or customizer', 'micemade-elements' ),
			]
		);
		
		$this->end_controls_section();
		

	}

	protected function render() {

		// get our input from the widget settings.
		$settings		= $this->get_settings();
		
		$posts_per_page		= ! empty( $settings['posts_per_page'] )		? (int)$settings['posts_per_page'] : 6;
		$offset				= ! empty( $settings['offset'] )				? (int)$settings['offset'] : 0;
		$posts_per_slide	= ! empty( $settings['posts_per_slide'] )		? (int)$settings['posts_per_slide'] : 3;
		$posts_per_slide_tab= ! empty( $settings['posts_per_slide_tab'] )	? (int)$settings['posts_per_slide_tab'] : 2;
		$posts_per_slide_mob= ! empty( $settings['posts_per_slide_mob'] )	? (int)$settings['posts_per_slide_mob'] : 1;
		$space				= ! empty( $settings['space'] )					? (int)$settings['space'] : 0;
		$pagination			= ! empty( $settings['pagination'] )			? $settings['pagination'] : 'bullets';
		$categories			= ! empty( $settings['categories'] )			? $settings['categories'] : array();
		$filters			= ! empty( $settings['filters'] )				? $settings['filters'] : '';
		$img_format			= ! empty( $settings['img_format'] )			? $settings['img_format'] : 'thumbnail';
		$style				= ! empty( $settings['style'] )					? $settings['style'] : 'style_1';
		$excerpt			= ! empty( $settings['excerpt'] )				? $settings['excerpt'] : '';
		$price				= ! empty( $settings['price'] )					? $settings['price'] : '';
		$add_to_cart		= ! empty( $settings['add_to_cart'] )			? $settings['add_to_cart'] : '';
		$meta				= ! empty( $settings['meta'] )					? $settings['meta'] : '';
		$css_class			= ! empty( $settings['css_class'] )				? $settings['css_class'] : '';
		
		global $post;
		
		$grid = micemade_elements_grid_class( intval( $posts_per_slide ), intval( $posts_per_slide_mob ) );
		
		// Query posts: ( hook in includes/wc-functions.php )
		$args 	= apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $filters, $offset ); 
		$products	= get_posts( $args );
		
		if( ! empty( $products ) ) {
			
			echo '<div class="micemade-elements_products_slider mme-row swiper-container '.esc_attr( $style ) .'">';
			
			echo '<input type="hidden" data-pps="'. $posts_per_slide .'" data-ppst="'. $posts_per_slide_tab .'" data-ppsm="'. $posts_per_slide_mob .'" data-space="'.$space.'" data-pagin="'. $pagination .'" class="slider-config">';
			
			
			echo '<div class="swiper-wrapper">';
						
			
			foreach ( $products as $post ) {
				
				setup_postdata( $post ); 
				
				apply_filters( 'micemade_elements_loop_product', $style, $img_format , $meta , $excerpt, $price, $add_to_cart, $css_class );// hook in includes/helpers.php
				
			}
			
			echo '</div>'; // .swipper-wrapper
			
			if( $pagination !== 'none' ) {
				echo '<div class="swiper-pagination"></div>';
			}
			
			echo '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>';
			
			echo '</div>';
		}
		
		wp_reset_postdata(); 
		
		echo '
		<script>
		(function( $ ){
			"use strict";
			jQuery(document).ready( function($) {
				var swiper = window.micemade_elements_swiper();
			});
		})( jQuery );
		</script>';
	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}