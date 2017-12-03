<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}

	class Micemade_Buttons extends Widget_Base {

		public function get_name() {
			return 'micemade-buttons';
		}

		public function get_title() {
			return __( 'Micemade Buttons', 'micemade-elements' );
		}

		public function get_icon() {
			return 'eicon-dual-button';
		}

		public function get_categories() {
			return [ 'micemade_elements' ];
		}


		protected function _register_controls() {
			$this->start_controls_section(
				'section_button',
				[
					'label' => __( 'Buttons', 'micemade-elements' ),
				]
			);

			$this->add_control(
				'inherit',
				[
					'label' => __( 'Inherit theme buttons style', 'micemade-elements' ),
					'type' => Controls_Manager::SWITCHER,
					'label_off' => __( 'No', 'micemade-elements' ),
					'label_on' => __( 'Yes', 'micemade-elements' ),
				]
			);

			$this->add_control(
				'style_selectors',
				[
					'label' => __( 'Add theme button style selectors', 'micemade-elements' ),
					'type' => Controls_Manager::TEXT,
					'placeholder' => __( 'Add theme style selector(s) here', 'micemade-elements' ),
					'default' => __( 'button', 'micemade-elements' ),
					'label_block' => true,
					'condition' => [
						'inherit!' => '',
					]
				]
			);
			
			$this->add_control(
				'orientation',
				[
					'label' => __( 'Orientation', 'micemade-elements' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'horizontal' => __( 'Horizontal', 'micemade-elements' ),
						'vertical' => __( 'Vertical', 'micemade-elements' ),
					],
					'default' => 'horizontal',
				
				]
			);

			$this->add_control(
				'button_list',
				[
					'label' => '',
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'text' => __( 'Button #1', 'micemade-elements' ),
							'icon' => 'fa fa-check',
						],
					],
					'fields' => [
						[
							'name' => 'text',
							'label' => __( 'Button text', 'micemade-elements' ),
							'type' => Controls_Manager::TEXT,
							'label_block' => true,
							'placeholder' => __( 'Button Text', 'micemade-elements' ),
							'default' => __( 'Button Text', 'micemade-elements' ),
						],
						[
							'name' => 'icon',
							'label' => __( 'Button icon', 'micemade-elements' ),
							'type' => Controls_Manager::ICON,
							'label_block' => true,
							'default' => 'fa fa-check',
						],
						[
							'name' => 'link',
							'label' => __( 'Button link', 'micemade-elements' ),
							'type' => Controls_Manager::URL,
							'label_block' => true,
							'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
						],
						
						[
							'name' =>'text_color',
							'label' => __( 'Text color', 'micemade-elements' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
							],
						],
						[
							'name' =>'back_color',
							'label' => __( 'Background color', 'micemade-elements' ),
							'type' => Controls_Manager::COLOR,
							'scheme' => [
								'type' => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
							],
						],
						
						[
							'name' =>'text_color_hover',
							'label' => __( 'Text hover color', 'micemade-elements' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
							],
						],
						[
							'name' =>'back_color_hover',
							'label' => __( 'Background hover color', 'micemade-elements' ),
							'type' => Controls_Manager::COLOR,
							'scheme' => [
								'type' => Scheme_Color::get_type(),
								'value' => Scheme_Color::COLOR_4,
							],
							'selectors' => [
								'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}};',
							],
						],
					],
					'title_field' => '<i class="{{ icon }}"></i> {{{ text }}}',
				]
			);



			$this->add_control(
				'view',
				[
					'label' => __( 'View', 'micemade-elements' ),
					'type' => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Buttons settings', 'micemade-elements' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_responsive_control(
				'align',
				[
					'label' => __( 'Alignment', 'micemade-elements' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'flex-start'    => [
							'title' => __( 'Left', 'micemade-elements' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'micemade-elements' ),
							'icon' => 'fa fa-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'micemade-elements' ),
							'icon' => 'fa fa-align-right',
						],
						'space-between' => [
							'title' => __( 'Justified', 'micemade-elements' ),
							'icon' => 'fa fa-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-elements_buttons' => 'justify-content: {{VALUE}};',
					],
					'condition' => [
						'orientation' => 'horizontal',
					],
				]
			);
			
			$this->add_responsive_control(
				'align_vertical',
				[
					'label' => __( 'Alignment', 'micemade-elements' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'flex-start'    => [
							'title' => __( 'Left', 'micemade-elements' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'micemade-elements' ),
							'icon' => 'fa fa-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'micemade-elements' ),
							'icon' => 'fa fa-align-right',
						],
						'stretch' => [
							'title' => __( 'Justified', 'micemade-elements' ),
							'icon' => 'fa fa-align-justify',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-elements_buttons' => 'align-items: {{VALUE}};',
					],
					'condition' => [
						'orientation' => 'vertical',
					],
				]
			);
			
			$this->add_responsive_control(
				'space_between',
				[
					'label' => __( 'Buttons Horizontal Spacing', 'micemade-elements' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => 'px',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step'=> 1
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-button' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
					],
					'condition' => [
						'orientation' => 'horizontal',
					],
				]
			);
			
			$this->add_responsive_control(
				'space_between_vert',
				[
					'label' => __( 'Buttons Vertical Spacing', 'micemade-elements' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => 'px',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step'=> 1
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
					],
				]
			);
			
			$this->add_responsive_control(
				'icon_size',
				[
					'label' => __( 'Icon size', 'micemade-elements' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => 'px',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step'=> 1
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-button .button-icon .fa' => 'font-size: {{SIZE}}{{UNIT}};',
					],

				]
			);
			
			$this->add_responsive_control(
				'icon_spacing',
				[
					'label' => __( 'Icon Spacing', 'micemade-elements' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => 'px',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step'=> 1
						],
					],
					'selectors' => [
						'{{WRAPPER}} .micemade-button .button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					],

				]
			);



			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'typography',
					'label' => __( 'Typography', 'micemade-elements' ),
					'scheme' => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} a.micemade-button .button-text',
				]
			);


			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', 'micemade-elements' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .micemade-button',
					'condition' => [
						'inherit!' => 'yes',
					]
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label' => __( 'Border Radius', 'micemade-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.micemade-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition' => [
						'inherit!' => 'yes',
					]
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .micemade-button',
					'condition' => [
						'inherit!' => 'yes',
					]
				]
			);

			$this->add_responsive_control(
				'button_padding',
				[
					'label' => __( 'Button Padding', 'micemade-elements' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} a.micemade-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
					'condition' => [
						'inherit!' => 'yes',
					]
				]
			);
			
			$this->add_control(
				'hover_animation',
				[
					'label' => __( 'Hover animation', 'micemade-elements' ),
					'type' => Controls_Manager::HOVER_ANIMATION,
				]
			);
			$this->end_controls_section();

			
		}

		protected function render() {
			$settings = $this->get_settings();

			// Buttons wrapper classes
			$this->add_render_attribute( 'wrapper', 'class', 'micemade-elements_buttons' );
			$this->add_render_attribute( 'wrapper', 'class', $settings['orientation'] );

			// Add animation class to all buttons
			$this->add_render_attribute( 'buttons', 'class', 'elementor-button' );
			
			?>

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				
				<?php
				$buttons = $settings['button_list'];
				$this->add_render_attribute( 'icon', 'class', 'button-icon' );
				$this->add_render_attribute( 'text', 'class', 'button-text' );
				foreach ( $buttons as $index => $item ) {
					
					$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'button_list', $index );
					
					$link = !empty( $item['link']['url'] ) ? $item['link']['url'] : '#';
					$link_key = 'link_' . $index;
					
					// Add general class attribute
					$this->add_render_attribute( $link_key, 'class', 'micemade-button' );
					// Add item specific class attribute
					$this->add_render_attribute( $link_key, 'class', 'elementor-repeater-item-'. $item['_id'] );
					// Add style selectors if theme inheritance is on
					if( $settings['inherit'] && $settings['style_selectors'] ) {
						$this->add_render_attribute( $link_key, 'class', $settings['style_selectors'] );
					}
					// Add hover animation class
					if ( $settings['hover_animation'] ) {
						$this->add_render_attribute( $link_key, 'class', 'elementor-animation-' . $settings['hover_animation'] );
					}
					// Add link attribute
					$this->add_render_attribute( $link_key, 'href', $link );
					// add target attribute
					if ( $item['link']['is_external'] ) {
						$this->add_render_attribute( $link_key, 'target', '_blank' );
					}
					// add nofollow attribute
					if ( $item['link']['nofollow'] ) {
						$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
					}

					echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
					
					
					if ( $item['icon'] ) {
					
						echo '<span ' . $this->get_render_attribute_string( 'icon' ) . '>';
							echo '<i class="'. esc_attr( $item['icon'] ) .'"></i>';
						echo '</span>';

					} ?>
					
					<?php if( $item['text']) { ?>
						<span <?php echo $this->get_render_attribute_string( $repeater_setting_key ) . $this->get_render_attribute_string( 'text' ) ; ?>><?php echo $item['text']; ?></span>
					<?php } ?>


					<?php echo '</a>';
					
				?>
				
				<?php } // end foreach ?>
			
			</div>
			
			<?php
		}

		protected function _content_template() { ?>
			
			<div class="micemade-elements_buttons {{ settings.orientation }}">
				<#
				var theme_style_inherit = hover_anim = '';
				if( settings.inherit && settings.style_selectors ) {
					theme_style_inherit = settings.style_selectors;
				}
				if( settings.hover_animation ) {
					hover_anim = 'elementor-animation-' + settings.hover_animation;
				}
				
				if ( settings.button_list ) {
					_.each( settings.button_list, function( item, index ) { #>
						<a class="micemade-button elementor-repeater-item-{{{ item._id }}} {{{ theme_style_inherit }}} {{{ hover_anim }}}"
						<# if ( item.link && item.link.url ) { #>
						href="{{ item.link.url }}"
						<# } #>
						>
							<span class="button-icon">
								<i class="{{ item.icon }}"></i>
							</span>
							<span class="button-text elementor-inline-editing" data-elementor-setting-key="button_list.{{{ index }}}.text">{{{ item.text }}}</span>

						</a>
					<#
					} );
				} #>
			</div>

		<?php	
		}
	}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Buttons() );
