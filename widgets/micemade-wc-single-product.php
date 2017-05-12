<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_WC_Single_Product extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-single-product';
	}

	public function get_title() {
		return __( 'Micemade WC Single Product', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Micemade WC Single Product', 'micemade-elements' ),
			]
		);
		 
		 
		$this->add_control(
			'single_product',
			[
				'label'		=> esc_html__( 'Select a product', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT2,
				'default'	=> 3,
				'options'	=> apply_filters("micemade_posts_array" ,"product"),
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout base', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'images_left',
				'options' => [
					'images_left'		=> 'Image left',
					'images_right'		=> 'Image right',
					'vertical'			=> 'Vertical',
					'vertical_reversed'	=> 'Vertical reversed',
				]
			]
		);
		
		$this->add_control(
			'img_format',
			[
				'label' => esc_html__( 'Featured image format', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => apply_filters('micemade_elements_image_sizes','')
			]
		);
		
		
		$this->add_control(
			'heading_content',
			[
				'label' => __( 'Additional layout options', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		
		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Product info padding', 'micemade-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'summary_width',
			[
				'label' => esc_html__( 'Product info width (%)', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout' => ['images_left', 'images_right'],
				],
			]
		);
		$this->add_responsive_control(
			'thumb_width',
			[
				'label' => esc_html__( 'Image width (%)', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .product-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
					'layout' => ['images_left', 'images_right'],
				],
			]
		);
		
		$this->add_responsive_control(
			'thumb_height',
			[
				'label' => esc_html__( 'Image height', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .product-thumb' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
			]
		);

		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Micemade WC Single Product', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'background_color',
			[
				'label' => __( 'Product info background', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		
		/*
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);
		 */
	
		$this->end_controls_section();
		
		//Plugin::$instance->controls_manager->add_custom_css_controls( $this );
 
	}

	protected function render() {

		// get our input from the widget settings.
		$settings			= $this->get_settings();
		
		
		$single_product		= ! empty( $settings['single_product'] )	? (int)$settings['single_product'] : '';
		$layout				= ! empty( $settings['layout'] )			? $settings['layout'] : 'images_left';
		$img_format			= ! empty( $settings['img_format'] )		? $settings['img_format'] : 'thumbnail';
		
		
		
		global $post, $woocommerce_loop;
		
		$args = array( 
			'posts_per_page'	=> 1,
			'post_type'			=> 'product',
			'no_found_rows'		=> 1,
			'post_status'		=> 'publish',
			'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'numberposts'		=> 1,
			'include'			=> $single_product
		);
		
		$woocommerce_loop['columns'] = $product_per_row;
		
		$product = get_posts( $args );
		
		if( ! empty( $product ) ) { ?>
		
			<div class="woocommerce woocommerce-page micemade-elements_single-product">	
			
			<?php foreach ( $product as $post ) { ?>
			
			<?php setup_postdata( $post ); ?>
			
			<div <?php post_class( esc_attr( $layout . ' single-product-container') ); ?>>
			
				<?php 
				$post_thumb_id = get_post_thumbnail_id( $post_id );
				$thum_src = wp_get_attachment_image_src( $post_thumb_id , $img_format );
				$img_url = $thum_src[0]; 
				
				echo '<div class="product-thumb" style="background-image: url( '.esc_url( $img_url) .' );"></div>';
				?>
				
				<?php remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' , 40 ); ?>
				
				<div class="summary entry-summary">

					<div class="thumb-summary-link fa fa-angle-left"></div>
					
					<?php do_action( 'woocommerce_single_product_summary' ); ?>
				
				</div>
			
			</div>
			
			<?php } // end foreach ?>
			
			</div>

		<?php } // endif ?>
		
		
		<?php wp_reset_postdata(); 

	}

	// protected function content_template() {}
	
	// public function render_plain_content( $instance = [] ) {}

}