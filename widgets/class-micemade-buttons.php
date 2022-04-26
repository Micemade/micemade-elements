<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

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
		return array( 'micemade_elements' );
	}


	protected function register_controls() {
		$this->start_controls_section(
			'section_button',
			array(
				'label' => __( 'Buttons', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'inherit',
			array(
				'label'     => __( 'Inherit theme buttons style', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'micemade-elements' ),
				'label_on'  => __( 'Yes', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'style_selectors',
			array(
				'label'       => __( 'Add theme button style selectors', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Add theme style selector(s) here', 'micemade-elements' ),
				'default'     => __( 'button', 'micemade-elements' ),
				'label_block' => true,
				'condition'   => array(
					'inherit!' => '',
				),
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => __( 'Orientation', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'horizontal' => __( 'Horizontal', 'micemade-elements' ),
					'vertical'   => __( 'Vertical', 'micemade-elements' ),
				),
				'default' => 'horizontal',
			)
		);

		$this->add_control(
			'stacked',
			array(
				'label'        => __( 'Stacked', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'not-stacked' => __( 'Not', 'micemade-elements' ),
					'stacked'     => __( 'Stacked', 'micemade-elements' ),
				),
				'default'      => 'not-stacked',
				'prefix_class' => 'stacking-',
				'condition'    => array(
					'orientation' => 'horizontal',
				),
			)
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Button text', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Button text', 'micemade-elements' ),
				'default'     => __( 'Button text', 'micemade-elements' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'selected_icon',
			array(
				'label'            => __( 'Button icon', 'micemade-elements' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fa fa-check',
					'library' => 'fa-solid',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'micemade-elements' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'http://your-link.com', 'micemade-elements' ),
			)
		);

		$repeater->add_control(
			'text_color',
			array(
				'label'     => __( 'Text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'back_color',
			array(
				'label'     => __( 'Background color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'text_color_hover',
			array(
				'label'     => __( 'Text hover color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'back_color_hover',
			array(
				'label'     => __( 'Background hover color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} a.micemade-button{{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_list',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'text'          => __( 'Button #1', 'micemade-elements' ),
						'selected_icon' => array(
							'value'   => 'fa fa-check',
							'library' => 'fa-solid',
						),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => __( 'View', 'micemade-elements' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Buttons settings', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start'    => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'        => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'      => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
					'space-between' => array(
						'title' => __( 'Justified', 'micemade-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .micemade-elements_buttons' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'orientation' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'align_vertical',
			array(
				'label'     => __( 'Alignment', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
					'stretch'    => array(
						'title' => __( 'Justified', 'micemade-elements' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .micemade-elements_buttons' => 'align-items: {{VALUE}};',
				),
				'condition' => array(
					'orientation' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'     => __( 'Buttons Horizontal Spacing', 'micemade-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .micemade-button' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'orientation' => 'horizontal',
				),
			)
		);

		$this->add_responsive_control(
			'space_between_vert',
			array(
				'label'     => __( 'Buttons Vertical Spacing', 'micemade-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .micemade-button' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Icon size', 'micemade-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 18,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .micemade-button .button-icon [class*=" fa-"]' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => __( 'Icon Spacing', 'micemade-elements' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}.micemade-button-icon-position-before .micemade-button .button-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.micemade-button-icon-position-after .micemade-button .button-icon'  => 'margin-left: {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'                => __( 'Icon position', 'micemade-elements' ),
				'type'                 => \Elementor\Controls_Manager::SELECT,
				'options'              => array(
					'before' => __( 'Before', 'micemade-elements' ),
					'after'  => __( 'After', 'micemade-elements' ),
				),
				'default'              => 'before',
				'selectors'            => array(
					'{{WRAPPER}} .micemade-button' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'before' => 'flex-direction: row',
					'after'  => 'flex-direction: row-reverse',
				),
				'prefix_class'         => 'micemade-button-icon-position-',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a.micemade-button .button-text',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'border',
				'label'       => __( 'Border', 'micemade-elements' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .micemade-button',
				'condition'   => array(
					'inherit!' => 'yes',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} a.micemade-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'inherit!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .micemade-button',
				'condition' => array(
					'inherit!' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Button Padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => '5',
					'right'    => '15',
					'bottom'   => '5',
					'left'     => '15',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} a.micemade-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => array(
					'inherit!' => 'yes',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label' => __( 'Hover animation', 'micemade-elements' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);
		$this->end_controls_section();

	}

	protected function render() {

		$settings          = $this->get_settings();
		$fallback_defaults = array(
			'fa fa-check',
		);
		// Buttons wrapper classes.
		$this->add_render_attribute(
			array(
				'wrapper' => array(
					'class' => array(
						'micemade-elements_buttons',
						$settings['orientation'],
					),
				),
			)
		);

		// Add animation class to all buttons.
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

			<?php
			$buttons = $settings['button_list'];
			$this->add_render_attribute( 'icon', 'class', 'button-icon' );
			$this->add_render_attribute( 'text', 'class', 'button-text' );
			foreach ( $buttons as $index => $item ) {

				$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'button_list', $index );
				$migration_allowed    = Icons_Manager::is_migration_allowed();

				$link     = ! empty( $item['link']['url'] ) ? $item['link']['url'] : '#';
				$link_key = 'link_' . $index;

				// Add general class attribute.
				$this->add_render_attribute( $link_key, 'class', 'micemade-button' );
				// Add item specific class attribute.
				$this->add_render_attribute( $link_key, 'class', 'elementor-repeater-item-' . $item['_id'] );
				// Add style selectors if theme inheritance is on.
				if ( $settings['inherit'] && $settings['style_selectors'] ) {
					$this->add_render_attribute( $link_key, 'class', $settings['style_selectors'] );
				}
				// Add hover animation class.
				if ( $settings['hover_animation'] ) {
					$this->add_render_attribute( $link_key, 'class', 'elementor-animation-' . $settings['hover_animation'] );
				}
				// Add link attribute.
				$this->add_render_attribute( $link_key, 'href', $link );
				// Add target attribute.
				if ( $item['link']['is_external'] ) {
					$this->add_render_attribute( $link_key, 'target', '_blank' );
				}
				// Add nofollow attribute.
				if ( $item['link']['nofollow'] ) {
					$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
				}

				// Migration to newer version of ICON(s) control.
				// Add old default.
				if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
					$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
				}
				$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
				$is_new   = ! isset( $item['icon'] ) && $migration_allowed;
				?>
				<a <?php $this->print_render_attribute_string( $link_key ); ?> >

				<?php if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) { ?>

					<span <?php $this->print_render_attribute_string( 'icon' ); ?> >
					<?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
					} else {
						echo '<i class="' . esc_attr( $item['icon'] ) . '" aria-hidden="true"></i>';
					}
					echo '</span>';
				}
				?>

				<?php if ( $item['text'] ) { ?>
					<span <?php $this->print_render_attribute_string( 'text' ); ?>>
						<?php echo esc_html( $item['text'] ); ?>
					</span>
				<?php } ?>

				<?php
				echo '</a>';

			} // end foreach.
			?>

		</div>

		<?php
	}

	protected function content_template() {
		?>
		<#
			var iconsHTML = {},
				migrated = {};
		#>
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
							<#
								iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
								migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
								if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
									{{{ iconsHTML[ index ].value }}}
								<# } else { #>
									<i class="{{ item.icon }}" aria-hidden="true"></i>
								<# }
							#>
						</span>
						<span class="button-text elementor-inline-editing" data-elementor-setting-key="button_list.{{{ index }}}.text">{{{ item.text }}}</span>

					</a>
				<#
				} );
			} #>
		</div>

		<?php
	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
	}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Buttons() );
