<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_WC_Products extends Widget_Base {

	// $mm_products_grid var - set here to use as "global" var in "post_class" hook
	public $mm_products_grid;
	
	public function get_name() {
		return 'micemade-wc-products';
	}

	public function get_title() {
		return __( 'Micemade WC Products', 'micemade-elements' );
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
				'label' => esc_html__( 'Micemade WC Products', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'posts_per_page',
			[
				'label'		=> __( 'Total products', 'micemade-elements' ),
				'type'		=> Controls_Manager::NUMBER,
				'default'	=> '6',
				'title'		=> __( 'Enter total number of products to show', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'products_per_row',
			[
				'label' => __( 'Products per row', 'micemade-elements' ),
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
			'products_per_row_mob',
			[
				'label' => __( 'Products per row (on mobiles)', 'micemade-elements' ),
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
		
		
		$this->add_responsive_control(
			'horiz_spacing',
			[
				'label' => __( 'Products horizontal spacing', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'range' => [
					'px' => [
						'max' => 50,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.products > li' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],

			]
		);
		
		$this->add_responsive_control(
			'vert_spacing',
			[
				'label' => __( 'Products bottom spacing', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '20',
				],
				'range' => [
					'px' => [
						'max' => 100,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} ul.products > li' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);
		
		
		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings		= $this->get_settings();
		
		$posts_per_page			= ! empty( $settings['posts_per_page'] )		? (int)$settings['posts_per_page'] : 6;
		$products_per_row		= ! empty( $settings['products_per_row'] )		? (int)$settings['products_per_row'] : 3;
		$products_per_row_mob	= ! empty( $settings['products_per_row_mob'] )	? (int)$settings['products_per_row_mob'] : 2;
		$categories				= ! empty( $settings['categories'] )			? $settings['categories'] : array();
		$filters				= ! empty( $settings['filters'] )				? $settings['filters'] : 'latest';
		
		global $post;
				
		$this->mm_products_grid = micemade_elements_grid_class( intval( $products_per_row ), intval( $products_per_row_mob ) );
		 
		$args = apply_filters( 'micemade_elements_wc_query_args', $posts_per_page, $categories, $filters ); // hook in includes/wc-functions.php

		// Add (inject) grid classes to products in loop ( in "content-product.php" template )
		add_filter( 'post_class', function( $classes ) {
			
			$classes[] = $this->mm_products_grid;
			$classes[] = 'item';
			return $classes;
		});
		
		$products = get_posts( $args );
		
		if( ! empty( $products ) ) {
			
			echo '<div class="woocommerce woocommerce-page micemade-elements_wc-catalog">';
			
				echo '<ul class="products mme-row">';
				
				foreach ( $products as $post ) {
					
					setup_postdata( $post );
					
					wc_get_template_part( 'content', 'product' ); 
					
				}
				
				echo '</ul>';
				
			echo '</div>';
		}
		
		// "Clean" or "reset" post_class to not conflict with other "post_class" functions
		add_filter( 'post_class', function( $classes ) { 
			
			$classes_to_clean = array( $this->mm_products_grid, 'item' );
			$classes = array_diff( $classes, $classes_to_clean );
			
			return $classes; 
		});
		
		wp_reset_postdata(); 

	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Products() );