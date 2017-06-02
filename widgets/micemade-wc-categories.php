<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_WC_Categories extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-categories';
	}

	public function get_title() {
		return __( 'Micemade WC Categories', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-woocommerce';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade WC Categories', 'micemade-elements' ),
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
			'cats_per_row',
			[
				'label' => __( 'Categories per row', 'micemade-elements' ),
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
			'cats_per_row_mob',
			[
				'label' => __( 'Categories per row (on mobiles)', 'micemade-elements' ),
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
		
		$this->add_responsive_control(
			'horiz_spacing',
			[
				'label' => __( 'Categories horizontal spacing', 'elementor' ),
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
					'{{WRAPPER}} .category' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],

			]
		);
		
		$this->add_responsive_control(
			'vert_spacing',
			[
				'label' => __( 'Categories bottom spacing', 'elementor' ),
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
					'{{WRAPPER}} .category' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-bottom:-{{SIZE}}px;',
				],

			]
		);
		
		
		
		$this->add_control(
			'style',
			[
				'label'		=> __( 'Base style', 'micemade-elements' ),
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
			'image',
			[
				'label'		=> esc_html__( 'Show category image', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'img_format',
			[
				'label' => esc_html__( 'Categories image format', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => apply_filters('micemade_elements_image_sizes',''),
				'condition' => [
					'image!' => ''
				],
			]
		);
		
		$this->add_control(
			'prod_count',
			[
				'label'		=> esc_html__( 'Show products count', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Micemade WC Categories', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		
		$this->add_responsive_control(
			'categories_height',
			[
				'label' => __( 'Categories height', 'elementor' ),
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
					'{{WRAPPER}} .category' => 'height: {{SIZE}}px;',
				],

			]
		);
		
		// HOVER TABS 
		$this->start_controls_tabs( 'tabs_button_style' );
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
					'{{WRAPPER}} .category' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'background_color',
			[
				'label' => __( 'Category overlay color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category__overlay' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .category:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'background_color_hover',
			[
				'label'		=> __( 'Category overlay color on hover', 'elementor' ),
				'type'		=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category:hover .category__overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'post_text_align',
			[
				'label' => __( 'Categories alignment', 'elementor' ),
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
					'{{WRAPPER}} .category__text-wrap' => 'text-align: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'cat_title_padding',
			[
				'label' => esc_html__( 'Categories title padding', 'micemade-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .category__text-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .category__title',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .category__inner-wrap',
			]
		);
		
		/* 
		$this->add_control(
			'background_opacity',
			[
				'label' => __( 'Category overlay opacity', 'elementor' ),
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
					'{{WRAPPER}} .category__overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'style' => ['style_3','style_4'],
				],
			]
		);
		
		$this->add_control(
			'background_opacity_hover',
			[
				'label' => __( 'Category overlay opacity hover', 'elementor' ),
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
					'{{WRAPPER}} .category:hover .category__overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'style' => ['style_3','style_4'],
				],
			]
		);
		
		 */
		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings		= $this->get_settings();
		
		$categories			= ! empty( $settings['categories'] )		? $settings['categories']	: array();
		$cats_per_row		= ! empty( $settings['cats_per_row'] )		? (int)$settings['cats_per_row'] : 3;
		$cats_per_row_mob	= ! empty( $settings['cats_per_row_mob'] )	? (int)$settings['cats_per_row_mob'] : 1;
		$style				= ! empty( $settings['style'] )				? $settings['style']		: 'style_1';
		$image				= ! empty( $settings['image'] )				? $settings['image']		: '';
		$img_format			= ! empty( $settings['img_format'] )		? $settings['img_format']	: 'thumbnail';
		$prod_count			= ! empty( $settings['prod_count'] )		? $settings['prod_count']	: '';
		
		$grid = micemade_elements_grid_class( intval( $cats_per_row ), intval( $cats_per_row_mob ) );
		
		
		if( empty( $categories ) ) return;
		
		echo '<div class="micemade-elements_product-categories mme-row '. esc_attr( $style ) .'">';
		
		foreach ( $categories as $cat ) {
			
			$term_data = apply_filters( 'micemade_elements_term_data', 'product_cat', $cat, $img_format ); // hook in inc/helpers.php
				
			$term_id	= $term_data['term_id'];
			$term_title	= $term_data['term_title'];
			$term_link	= $term_data['term_link'];
			$image_url	= $term_data['image_url'];
				
			echo '<a class="category '. esc_attr( $grid ).' mme-col-xs-12" href="'. esc_url( $term_link ) .'" title="'. esc_attr( $term_title ) .'">';
			
				echo '<div class="category__inner-wrap">';
				
					echo '<div class="category__overlay"></div>';
					
					if( $image ) {
						echo '<div class="category__image"><div class="image-inner" style="background-image: url('. esc_url( $image_url ) .')"></div></div>';
					}
					
					echo '<div class="category__text-wrap">';
					
						echo '<h3 class="category__title">' . esc_html( $term_title ). '</h3>';
						
						if( $prod_count ) {
							echo apply_filters( 'micemade_elements_product_count', $term_id );
						}
					
					echo '</div>';
				
				echo '</div>'; //.inner-wrap
			
			echo '</a>';
		}
		
		echo '</div>';

	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}