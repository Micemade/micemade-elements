<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_WC_Products extends Widget_Base {

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
				'label'		=> __( 'Number of products', 'micemade-elements' ),
				'type'		=> Controls_Manager::TEXT,
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
		
		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings		= $this->get_settings();
		
		$posts_per_page		= ! empty( $settings['posts_per_page'] )	? (int)$settings['posts_per_page'] : 6;
		$products_per_row	= ! empty( $settings['products_per_row'] )	? (int)$settings['products_per_row'] : 3;
		$categories			= ! empty( $settings['categories'] )		? $settings['categories'] : array();
		$filters			= ! empty( $settings['filters'] )			? $settings['filters'] : 'latest';
		
		global $post, $woocommerce_loop;
		
		$woocommerce_loop['columns'] = $products_per_row;
		
		$base_args = array( 
			'posts_per_page'	=> $posts_per_page,
			'post_type'			=> 'product',
		);
		$filter_args = apply_filters( 'micemade_elements_wc_query_args', $filters, $categories ); // hook in includes/wc-functions.php
		
	
		$args = array_merge( $base_args, $filter_args );
		
		$products = get_posts( $args );
		
		if( ! empty( $products ) ) {
			
			echo '<div class="woocommerce woocommerce-page">';
			
			woocommerce_product_loop_start();
			
			foreach ( $products as $post ) {
				
				setup_postdata( $post ); 

				wc_get_template_part( 'content', 'product' ); 
				
			}
			woocommerce_product_loop_end();
			
			echo '</div>';
		}
		
		wp_reset_postdata(); 

	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}