<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_Posts_Grid extends Widget_Base {

	public function get_name() {
		return 'micemade-posts-grid';
	}

	public function get_title() {
		return __( 'Micemade posts grid', 'micemade-elements' );
	}

	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'eicon-posts-grid';
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_main',
			[
				'label' => esc_html__( 'Micemade posts grid', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'posts_per_page',
			[
				'label'		=> __( 'Number of posts', 'micemade-elements' ),
				'type'		=> Controls_Manager::TEXT,
				'default'	=> '6',
				'title'		=> __( 'Enter total number of products to show', 'micemade-elements' ),
			]
		);
		
		$this->add_control(
			'posts_per_row',
			[
				'label' => __( 'Posts per row', 'micemade-elements' ),
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
			'posts_per_row_mob',
			[
				'label' => __( 'Posts per row (on mobiles)', 'micemade-elements' ),
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
			'heading_filtering',
			[
				'label' => __( 'Posts filtering options', 'micemade-elements' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'categories',
			[
				'label'		=> esc_html__( 'Select post categories', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT2,
				'default'	=> array(),
				'options'	=> apply_filters('micemade_elements_terms','category'),
				'multiple'	=> true
			]
		);
		
		
		$this->add_control(
			'sticky',
			[
				'label'		=> esc_html__( 'Only sticky posts', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> false,
			]
		);
		
		$this->end_controls_section();
		
		// TAB 2 - STYLES FOR POSTS :
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Micemade posts grid', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'style',
			[
				'label'		=> __( 'Post base style', 'micemade-elements' ),
				'type'		=> Controls_Manager::SELECT,
				'default'	=> 'style_1',
				'options'	=> [
					'style_1'		=> __( 'Style one', 'micemade-elements' ),
					'style_2'		=> __( 'Style two', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'img_format',
			[
				'label' => esc_html__( 'Post image format', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => apply_filters('micemade_elements_image_sizes','')
			]
		);
		
		$this->add_control(
			'excerpt',
			[
				'label'		=> esc_html__( 'Show excerpt', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'meta',
			[
				'label'		=> esc_html__( 'Show post meta', 'micemade-elements' ),
				'type'		=> Controls_Manager::CHECKBOX,
				'default'	=> true,
			]
		);
		
		$this->add_control(
			'post_text_background_color',
			[
				'label' => __( 'Post text background', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .inner-wrap' => 'background-color: {{VALUE}};',
				],
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

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
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
				],
			]
		);
		
		// Title typohraphy
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .post-text p',
			]
		);

		
		$this->end_controls_section();
		

	}

	protected function render() {

		// get our input from the widget settings.
		$settings		= $this->get_settings();
		
		$posts_per_page		= ! empty( $settings['posts_per_page'] )	? (int)$settings['posts_per_page'] : 6;
		$posts_per_row		= ! empty( $settings['posts_per_row'] )		? (int)$settings['posts_per_row'] : 3;
		$posts_per_row_mob	= ! empty( $settings['posts_per_row_mob'] )	? (int)$settings['posts_per_row_mob'] : 1;
		$categories			= ! empty( $settings['categories'] )		? $settings['categories'] : array();
		$img_format			= ! empty( $settings['img_format'] )		? $settings['img_format'] : 'thumbnail';
		$style				= ! empty( $settings['style'] )				? $settings['style'] : 'style_1';
		$excerpt			= ! empty( $settings['excerpt'] )			? $settings['excerpt'] : '';
		$meta				= ! empty( $settings['meta'] )				? $settings['meta'] : '';
		$sticky				= ! empty( $settings['sticky'] )			? $settings['sticky'] : '';
		
		global $post;
		
		$grid_selector = micemade_elemets_grid_class( intval( $posts_per_row ), intval( $posts_per_row_mob ) );
		
		// Query posts:
		$base_args = array( 
			'posts_per_page'	=> $posts_per_page,
			'post_type'			=> 'post',
		);
		$filter_args	= apply_filters( 'micemade_elements_query_args', $categories, $sticky ); // hook in includes/admin.php
		$args			= array_merge( $base_args, $filter_args );
		$posts			= get_posts( $args );
		
		if( ! empty( $posts ) ) {
			
			echo '<div class="micemade-elements_posts-grid mme-row '.esc_attr( $style ) .'">';
						
			foreach ( $posts as $post ) {
				
				setup_postdata( $post ); 
				?>
				
				<div class="post <?php echo esc_attr( $grid_selector ); ?> mme-col-md-6 mme-col-xs-12">
				
					<div class="inner-wrap">
					
						<?php do_action( 'micemade_elements_thumb', $img_format );  ?>

						<div class="post-text">
							
							<h4><a href="<?php the_permalink();?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h4>
						
							<?php if( $meta ) { ?>
							<div class="meta">
							
								<?php echo apply_filters( 'micemade_elements_date','' ); ?>
								<?php echo apply_filters( 'micemade_elements_author','' ); ?>
								<br>
								<?php echo apply_filters( 'micemade_elements_posted_in','category' ); ?>
							
							</div>
							<?php } ?>

							<?php 
							if( $excerpt ) {
								the_excerpt(); 
								echo '<a href="'. get_permalink() .'" title="'.the_title_attribute("echo=0").'">'.esc_html__( 'Read more','micemade-elements' ) .'</a>';
							}
							?>
							 
							
						
						</div>
					
					</div>
				
				</div>
				
				<?php 
			}
			
			echo '</div>';
		}
		
		wp_reset_postdata(); 

	}

	protected function content_template() {}
	
	public function render_plain_content( $instance = [] ) {}

}