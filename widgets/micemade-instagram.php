<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 

class Micemade_Instagram extends Widget_Base {

	public function get_name() {
		return 'micemade-instagram';
	}

	public function get_title() {
		return __( 'Micemade Instagram', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-social-icons';
	}

	public function get_categories() {
		return [ 'micemade_elements' ];
	}

    protected function _register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Instagram settings', 'micemade-elements' ),   //section name for controler view
			]
        );
        
        $this->add_control(
			'username',
			[
				'label' => __( '@username or #tag: ', 'micemade-elements' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'example: @elementor or #micemade', 'micemade-elements' ),
				'default' => '',
				'label_block' => true,
			]
		);

        $this->add_control(
			'number',
			[
				'label'		=> __( 'Number of photographs', 'micemade-elements' ),
				'type'		=> Controls_Manager::NUMBER,
				'default'	=> 6,
				'title'		=> __( 'total number of photographs to show', 'micemade-elements' ),
			]
		);

		$this->add_control(
			'size',
			[
				'label' => esc_html__( 'Photograph size', 'micemade-elements' ),
                'description' => '',
                'type' => Controls_Manager::SELECT,
                'default' => 'thumbnail',
				'options' => [
                    'thumbnail' => esc_html__( 'Thumbnail', 'micemade-elements' ),
                    'small' => esc_html__( 'Small', 'micemade-elements' ),
                    'large' => esc_html__( 'Large', 'micemade-elements' ),
                    'original' => esc_html__( 'Original', 'micemade-elements' ),
                    
                ],
            ]
        );
        
        $this->add_control(
			'open_lightbox',
			[
				'label' => __( 'Lightbox', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'micemade-elements' ),
					'yes' => __( 'Yes', 'micemade-elements' ),
					'no' => __( 'No', 'micemade-elements' ),
				],
			]
		);

        $this->end_controls_section();
        

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'micemade-elements' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );


		$this->add_control(
			'images_per_row',
			[
				'label' => __( 'Images per row', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
					6 => __( 'Six', 'micemade-elements' ),
					8 => __( 'Eight', 'micemade-elements' ),
					9 => __( 'Nine', 'micemade-elements' ),
					12 => __( 'Twelwe', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_control(
			'images_per_row_mob',
			[
				'label' => __( 'Images per row (on mobiles)', 'micemade-elements' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'options' => [
					1 => __( 'One', 'micemade-elements' ),
					2 => __( 'Two', 'micemade-elements' ),
					3 => __( 'Three', 'micemade-elements' ),
					4 => __( 'Four', 'micemade-elements' ),
                    6 => __( 'Six', 'micemade-elements' ),
                    8 => __( 'Eight', 'micemade-elements' ),
					9 => __( 'Nine', 'micemade-elements' ),
					12 => __( 'Twelwe', 'micemade-elements' ),
				]
			]
		);
		
		$this->add_responsive_control(
			'horiz_spacing',
			[
				'label' => __( 'Horizontal spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .pic' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				],

			]
		);
		$this->add_responsive_control(
			'vert_spacing',
			[
				'label' => __( 'Vertical spacing', 'micemade-elements' ),
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
					'{{WRAPPER}} .mme-row .pic' => 'margin-bottom:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row' => 'margin-bottom:-{{SIZE}}px;',
				],
			]
		);
        
        $this->add_responsive_control(
			'space',
			[
				'label' => __( 'Inner spacing (px)', 'micemade-elements' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
                        'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mme-row .image-link' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
        );
        
        $this->add_control(
			'stretch',
			[
				'label' => __( 'Stretch images', 'micemade-elements' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
			]
		);

        $this->end_controls_section();
	}

	protected function render() {
        
        $settings = $this->get_settings();
        $username = $settings['username'];
        $number = $settings['number'];
        $size = $settings['size'];
        $images_per_row = $settings['images_per_row'];
        $images_per_row_mob = $settings['images_per_row_mob'];
        $lightbox = $settings['open_lightbox'];
        $stretch = $settings['stretch'];

        $grid = micemade_elements_grid_class( intval( $images_per_row ), intval( $images_per_row_mob ) );

        if ( '' !== $username ) {

			$media_array = micemade_elements_instagram( $username ); // inc/instagram.php

			if ( is_wp_error( $media_array ) ) {

				echo wp_kses_post( $media_array->get_error_message() );

			} else {

				// Slice list down to required total number of pics.
				$media_array = array_slice( $media_array, 0, $number );

                // Element attributes
                $this->add_render_attribute( 'ul-class', 'class', 'micemade-elements_instagram mme-row instagram-size-' . $size );
                $this->add_render_attribute( 'li-class', 'class', 'pic ' . $grid );
				?>
                
                <ul <?php echo $this->get_render_attribute_string( 'ul-class' ); ?>>
                <?php
				foreach( $media_array as $index => $item ) {
					
                    $i = $index + 1;
                    // Link attributes
                    $this->add_render_attribute( 'link' . $i, 
                        [
                            'href' => ( $lightbox == 'yes') ? $item['original'] : $item['link'],
                            'class' => 'image-link elementor-clickable',
                            'data-elementor-open-lightbox' => $settings['open_lightbox'],
                            'data-elementor-lightbox-slideshow' => $this->get_id(),
                        ]
                    );
                    // Thumb attributes
                    $this->add_render_attribute( 'img'. $i,
                        [
                            'src' => $item[$size],
                            'alt' => $item['description'],
                            'title' => $item['description'],
                            'class' => ( $stretch == 'yes' ) ? 'stretch' : '',

                        ]
                    );

                    $this->add_render_attribute( 'title'. $i, 'title', $item['description'] );
                    
                    echo '<li '. $this->get_render_attribute_string( 'li-class' ) .'>';
                    
                    echo '<a ' . $this->get_render_attribute_string( 'link' . $i ) .'>';
                    
                    echo '<img '. $this->get_render_attribute_string( 'img' . $i ) . '/>';
                   
                    echo '</a>';
                    echo '</li>';
					
				}
				?>
                </ul>
                <?php
			}
		}

    }
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Instagram() );