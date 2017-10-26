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

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Micemade WC Single Product', 'micemade-elements' ),
			]
		);
		 
		 
		$this->add_control(
			'post_name',
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
					'images_left'		=> esc_html__( 'Image left', 'micemade-elements' ),
					'images_right'		=> esc_html__( 'Image right', 'micemade-elements' ),
					'vertical'			=> esc_html__( 'Vertical', 'micemade-elements' ),
					'vertical_reversed'	=> esc_html__( 'Vertical reversed', 'micemade-elements' ),
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
			'product_data',
			[
				'label' => esc_html__( 'Product data', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'full'		=> esc_html__( 'Full - single product page', 'micemade-elements' ),
					'reduced'	=> esc_html__( 'Reduced - catalog product', 'micemade-elements' ),
				],
				
			]
		);
		$this->add_control(
			'short_desc',
			[
				'label'		=> esc_html__( 'Show "Product Short Description"', 'micemade-elements' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'elementor' ),
				'label_on' => __( 'Yes', 'elementor' ),
				'default' => 'yes',
				'condition' => [
					'product_data' => 'reduced',
				],
			]
		);
		
		

		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Micemade WC Single Product', 'micemade-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'background_color',
			[
				'label' => __( 'Product info background', 'micemade-elements' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		
		
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'micemade-elements' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		/* 
		 * SET OPTIONS FOR TITLE PRICE AND DESCRIPTION
		 */
		$this->start_controls_section(
			'section_title_price_desc',
			[
				'label' => __( 'Title, price, description options', 'micemade-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		// Title Options
		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label' => __( 'Title Color', 'micemade-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .entry-summary h3, {{WRAPPER}} .entry-summary h4' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'micemade-elements' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary h3, {{WRAPPER}} .entry-summary h4',
			]
		);
		
		// Price options
		$this->add_control(
			'heading_price',
			[
				'label' => __( 'Price', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Price Color', 'micemade-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .entry-summary .price' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'label' => __( 'Typography', 'micemade-elements' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary .price',
			]
		);
		// Description options
		$this->add_control(
			'heading_desc',
			[
				'label' => __( 'Short description', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'desc_color',
			[
				'label' => __( 'Short description Color', 'micemade-elements' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .entry-summary .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Typography', 'micemade-elements' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary .woocommerce-product-details__short-description',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_additional',
			[
				'label' => __( 'Additional layout options', 'micemade-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'product_elements_spacing',
			[
				'label' => __( 'Elements vertical spacing', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 0,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce div.product div.summary>*' => 'margin-top: {{SIZE}}px;margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce div.product div.summary form.cart >*' => 'margin-bottom: {{SIZE}}px;',
				],
				/* 'condition' => [
					'style' => ['style_3','style_4']
				], */
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
				'label' => __( 'Info width (%)', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'max' => 100,
				'step' => 5,
				'selectors' => [
					'{{WRAPPER}} .entry-summary' => 'width: {{VALUE}}%;',
				],
				/* 'condition' => [
					'layout' => ['images_left', 'images_right'],
				], */
			]
		);
		
		
		$this->add_responsive_control(
			'thumb_width',
			[
				'label' => __( 'Image width (%)', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'max' => 100,
				'step' => 5,
				'selectors' => [
					'{{WRAPPER}} .product-thumb' => 'width: {{VALUE}}%;',
				],
			]
		);
		
		
		$this->add_responsive_control(
			'thumb_height',
			[
				'label' => esc_html__( 'Image height', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step'=> 10
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product-thumb' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->end_controls_section();
		
		
		
		//Plugin::$instance->controls_manager->add_custom_css_controls( $this );
 
	}

	protected function render() {

		// get our input from the widget settings.
		$settings			= $this->get_settings();
		
		$this->add_render_attribute( 'summary', 'class', $settings['align'] . ' summary entry-summary' );
		
		$post_name		= ! empty( $settings['post_name'] )		? $settings['post_name'] : '';
		$layout			= ! empty( $settings['layout'] )		? $settings['layout'] : 'images_left';
		$img_format		= ! empty( $settings['img_format'] )	? $settings['img_format'] : 'thumbnail';
		$product_data	= ! empty( $settings['product_data'] )	? $settings['product_data'] : 'full';
		$short_desc 	= ! empty( $settings['short_desc'] )	? ( $settings['short_desc'] == 'yes' ) : '';
		
		if( is_array($post_name) ) {
			$post_name = $post_name;
		}else{
			$post_name = array( $post_name );
		}
		
		global $post;
		
		$args = array( 
			'posts_per_page'	=> 1,
			'post_type'			=> 'product',
			'no_found_rows'		=> 1,
			'post_status'		=> 'publish',
			'post_parent'		=> 0,
			'suppress_filters'	=> false,
			'post_name__in'		=> $post_name
		);
				
		$product = get_posts( $args );
		
		if( ! empty( $product ) ) { ?>
		
			<div class="woocommerce woocommerce-page micemade-elements_single-product">	

			<?php foreach ( $product as $post ) { ?>
			
			<?php setup_postdata( $post ); ?>
			
			<div <?php post_class( esc_attr( $layout . ' single-product-container') ); ?>>
			
				<?php
				$post_id		= get_the_ID();
				
				if( has_post_thumbnail( $post_id ) ) {
					
					$post_thumb_id	= get_post_thumbnail_id( $post_id );
					$img_src		= wp_get_attachment_image_src( $post_thumb_id , $img_format );
					$img_url		= $img_src[0]; 
					
					echo '<div class="product-thumb" style="background-image: url( '. esc_url(  $img_url ) .' );"></div>';
				}
				?>

				<div <?php echo $this->get_render_attribute_string( 'summary' ); ?>>
					<?php 
					if( $product_data == 'full' ) { 
						// display full single prod. summary
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' , 40 );
						do_action( 'woocommerce_single_product_summary' ); 

					}else{
						// display price / short desc. / button
						apply_filters( 'micemade_elements_simple_prod_data', $short_desc );
					}
					?>
				
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

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Single_Product() );