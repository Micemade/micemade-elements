<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Micemade_Header_Logo extends Widget_Base {

	public function get_name() {
		return 'micemade-header-logo';
	}

	public function get_title() {
		return __( 'Micemade Header Logo', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-logo';
	}

	public function get_categories() {
		return [ 'micemade_elements_header' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Header Logo', 'micemade-elements' ),   //section name for controler view
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => __( 'Choose logo image', 'micemade-elements' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'no_logo!' => 'yes',
				],
			]
		);

		$this->add_control(
			'no_logo',
			[
				'label'     => esc_html__( 'Hide logo (show site title)', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'micemade-elements' ),
				'label_on'  => __( 'Yes', 'micemade-elements' ),
				'default'   => '',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					],

				],
				'selectors' => [
					'{{WRAPPER}} .micemade-elements_header-logo' => 'justify-content: {{VALUE}};',
				],
				/* 'condition' => [
					'no_logo!' => 'yes',
				] */
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label'     => __( 'Logo width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '180',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .micemade-elements_header-logo .logo-image.logo' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'no_logo!' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'logo_height',
			[
				'label'     => __( 'Logo height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '60',
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .micemade-elements_header-logo .logo-image.logo' => 'height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'no_logo!' => 'yes',
				],
			]
		);

		$this->add_control(
			'logo_text_color',
			[
				'label'     => __( 'Title Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .micemade-elements_header-logo .logo-image.no-logo .site-title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'no_logo' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'typography',
				'label'     => __( 'Typography', 'micemade-elements' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .micemade-elements_header-logo .logo-image.no-logo .site-title',
				'condition' => [
					'no_logo' => 'yes',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings   = $this->get_settings();
		$back_image = $settings['image']['url'];
		$no_logo    = $settings['no_logo'];

		$this->add_render_attribute( 'logo-image', 'style', ! $no_logo ? 'background-image: url(' . $back_image . ');' : '' );
		$this->add_render_attribute( 'logo-class', 'class', 'logo-image' );
		$this->add_render_attribute( 'logo-class', 'class', $no_logo ? 'no-logo' : 'logo' );
		$this->add_render_attribute( 'site-title', 'class', $no_logo ? 'site-title text' : 'site-title text-hidden' );

		echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . ' / ' . get_bloginfo( 'description' ) . '" rel="home" class="micemade-elements_header-logo">';
		?>

			<?php
			// Render <h1> if on home/front page, else render <div>.
			echo '<' . ( ( is_home() || is_front_page() ) ? 'h1 ' : 'div ' ) . $this->get_render_attribute_string( 'logo-class' ) . $this->get_render_attribute_string( 'logo-image' ) . '>';
			?>

				<span <?php echo $this->get_render_attribute_string( 'site-title' ); ?>>
					<?php bloginfo( 'name' ); ?>
				</span>

			<?php
			// Render </h1> if on home/front page, else render </div>.
			echo '</' . ( ( is_home() || is_front_page() ) ? 'h1' : 'div' ) . '>';
			?>

		<?php
		echo '</a>';

	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Header_Logo() );
