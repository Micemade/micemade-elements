<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Color;

class Micemade_Nav extends Widget_Base {

	public function get_name() {
		return 'micemade-nav';
	}

	public function get_title() {
		return __( 'Micemade Nav Menu', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-select';
	}

	public function get_categories() {
		return array( 'micemade_elements_header' );
	}

	public function get_script_depends() {
		return array( 'smartmenus' );
	}

	private $selector = '.micemade-elements-nav-menu';

	private function get_menus() {
		$menus = wp_get_nav_menus();

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_layout',
			array(
				'label' => __( 'General', 'micemade-elements' ),
			)
		);

		$menus = $this->get_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				array(
					'label'       => __( 'Select menu', 'micemade-elements' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => $menus,
					'default'     => array_keys( $menus )[0],
					'separator'   => 'after',
					'description' => sprintf( __( 'To manage nav menus, navigate to <a href="%s" target="_blank">Menus admin</a>.', 'micemade-elements' ), admin_url( 'nav-menus.php' ) ),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( '<strong>It seems no menus are created.</strong><br>Navigate to <a href="%s" target="_blank">Menus admin</a> and create one.', 'micemade-elements' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'layout',
			array(
				'label'              => __( 'Layout', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'horizontal',
				'options'            => array(
					'horizontal' => __( 'Horizontal', 'micemade-elements' ),
					'vertical'   => __( 'Vertical', 'micemade-elements' ),
					'dropdown'   => __( 'Dropdown', 'micemade-elements' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'align_items',
			array(
				'label'        => __( 'Menu align', 'micemade-elements' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'mme-menu-align-',
				'condition'    => array(
					'layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'hover_style',
			array(
				'label'        => __( 'Hover style', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'background',
				'options'      => array(
					'none'        => __( 'None', 'micemade-elements' ),
					'underline'   => __( 'Underline', 'micemade-elements' ),
					'overline'    => __( 'Overline', 'micemade-elements' ),
					'double-line' => __( 'Double Line', 'micemade-elements' ),
					'framed'      => __( 'Framed', 'micemade-elements' ),
					'background'  => __( 'Background', 'micemade-elements' ),
					'text'        => __( 'Text', 'micemade-elements' ),
				),
				'prefix_class' => 'mme-hover-style-',
				'condition'    => array(
					'layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'animation_line',
			array(
				'label'     => __( 'Animation', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'     => 'Fade',
					'slide'    => 'Slide',
					'grow'     => 'Grow',
					'drop-in'  => 'Drop In',
					'drop-out' => 'Drop Out',
				),
				'condition' => array(
					'layout!'     => 'dropdown',
					'hover_style' => array( 'underline', 'overline', 'double-line' ),
				),
			)
		);

		$this->add_control(
			'animation_framed',
			array(
				'label'     => __( 'Animation', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'    => 'Fade',
					'grow'    => 'Grow',
					'shrink'  => 'Shrink',
					'draw'    => 'Draw',
					'corners' => 'Corners',
				),
				'condition' => array(
					'layout!'     => 'dropdown',
					'hover_style' => 'framed',
				),
			)
		);

		$this->add_control(
			'animation_background',
			array(
				'label'     => __( 'Animation', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'                   => 'Fade',
					'grow'                   => 'Grow',
					'shrink'                 => 'Shrink',
					'sweep-left'             => 'Sweep Left',
					'sweep-right'            => 'Sweep Right',
					'sweep-up'               => 'Sweep Up',
					'sweep-down'             => 'Sweep Down',
					'shutter-in-vertical'    => 'Shutter In Vertical',
					'shutter-out-vertical'   => 'Shutter Out Vertical',
					'shutter-in-horizontal'  => 'Shutter In Horizontal',
					'shutter-out-horizontal' => 'Shutter Out Horizontal',
				),
				'condition' => array(
					'layout!'     => 'dropdown',
					'hover_style' => 'background',
				),
			)
		);

		$this->add_control(
			'animation_text',
			array(
				'label'     => __( 'Animation', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => array(
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
				),
				'condition' => array(
					'layout!'     => 'dropdown',
					'hover_style' => 'text',
				),
			)
		);

		$this->add_control(
			'indicator',
			array(
				'label'        => __( 'Submenu Indicator', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'classic',
				'options'      => array(
					'none'    => __( 'None', 'micemade-elements' ),
					'classic' => __( 'Classic', 'micemade-elements' ),
					'chevron' => __( 'Chevron', 'micemade-elements' ),
					'angle'   => __( 'Angle', 'micemade-elements' ),
					'plus'    => __( 'Plus', 'micemade-elements' ),
				),
				'prefix_class' => 'elementor-nav-menu--indicator-',
			)
		);

		$this->add_control(
			'heading_mobile_dropdown',
			array(
				'label'     => __( 'Mobile Dropdown', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'dropdown',
			array(
				'label'        => __( 'Breakpoint', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => array(
					'mobile' => __( 'Mobile (767px >)', 'micemade-elements' ),
					'tablet' => __( 'Tablet (1023px >)', 'micemade-elements' ),
				),
				'prefix_class' => 'elementor-nav-menu--dropdown-',
				'condition'    => array(
					'layout!' => 'dropdown',
				),
			)
		);

		$this->add_control(
			'full_width',
			array(
				'label'              => __( 'Full Width', 'micemade-elements' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => __( 'Stretch the dropdown of the menu to full width.', 'micemade-elements' ),
				'prefix_class'       => 'elementor-nav-menu--',
				'return_value'       => 'stretch',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'text_align',
			array(
				'label'        => __( 'Align', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'aside',
				'options'      => array(
					'aside'  => __( 'Aside', 'micemade-elements' ),
					'center' => __( 'Center', 'micemade-elements' ),
				),
				'prefix_class' => 'elementor-nav-menu__text-align-',
			)
		);

		$this->add_control(
			'toggle',
			array(
				'label'              => __( 'Toggle Button', 'micemade-elements' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'burger',
				'options'            => array(
					''       => __( 'None', 'micemade-elements' ),
					'burger' => __( 'Hamburger', 'micemade-elements' ),
				),
				'prefix_class'       => 'elementor-nav-menu--toggle elementor-nav-menu--',
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'toggle_align',
			array(
				'label'                => __( 'Toggle Align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'center',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right'  => 'margin-left: auto',
				),
				'selectors'            => array(
					'{{WRAPPER}} .elementor-menu-toggle' => '{{VALUE}}',
				),
				'condition'            => array(
					'toggle!' => '',
				),
				'label_block'          => false,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main_items',
			array(
				'label'     => __( 'Menu Main Items', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!' => 'dropdown',
				),

			)
		);

		$this->add_control(
			'menu_main_items_desc',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Main items are first (or top) level items.', 'micemade-elements' ),
				'separator'       => 'after',
				'content_classes' => 'elementor-control-field-description',
			)
		);

		$this->add_control(
			'heading_items_style',
			array(
				'type'  => Controls_Manager::HEADING,
				'label' => __( 'Items Style', 'micemade-elements' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'main_items_typography',
				'label'     => __( 'Items typography', 'micemade-elements' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} ' . $this->selector . ' > .menu-item > .mme-navmenu-link',
			)
		);

		$this->start_controls_tabs( 'tabs_item_style' );

		$this->start_controls_tab(
			'tab_item_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'textcolor_menu_item',
			array(
				'label'     => __( 'Items text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_3,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' .menu-item .mme-navmenu-link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_item_hover',
			array(
				'label' => __( 'Hover', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'textcolor_item_hover',
			array(
				'label'     => __( 'Items text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_4,
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' .menu-item .mme-navmenu-link:hover,
					{{WRAPPER}} .elementor-nav-menu--main .elementor-item.elementor-item-active,
					{{WRAPPER}} .elementor-nav-menu--main .elementor-item.highlighted,
					{{WRAPPER}} .elementor-nav-menu--main .elementor-item:focus' => 'color: {{VALUE}}',
				),
				/*
				 'condition' => [
					'hover_style!' => 'background',
				], */
			)
		);

		$this->add_control(
			'style_color_item_hover',
			array(
				'label'     => __( 'Hover style color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_4,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' .menu-item .mme-navmenu-link:hover:before' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'hover_style!' => array( 'none', 'text' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_active',
			array(
				'label' => __( 'Active', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'color_item_active',
			array(
				'label'     => __( 'Items text color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' .menu-item .mme-navmenu-link:hover:before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'style_color_item_active',
			array(
				'label'     => __( 'Active style color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' .menu-item .mme-navmenu-link:active:before' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'hover_style!' => array( 'none', 'text' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pointer_width',
			array(
				'label'     => __( 'Hover style lines width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => array( self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ),
				'range'     => array(
					'px' => array(
						'max' => 30,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' > .menu-item > .mme-navmenu-link:before' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'hover_style' => array( 'underline', 'overline', 'double-line', 'framed' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'padding_horizontal_menu_item',
			array(
				'label'     => __( 'Horizontal items padding', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'devices'   => array( 'desktop', 'tablet' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' > .menu-item > .mme-navmenu-link' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'padding_vertical_menu_item',
			array(
				'label'     => __( 'Vertical items padding', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'devices'   => array( 'desktop', 'tablet' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' > .menu-item > .mme-navmenu-link' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'items_spacing',
			array(
				'label'     => __( 'Items spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'devices'   => array( 'desktop', 'tablet' ),
				'selectors' => array(
					'{{WRAPPER}} ' . $this->selector . ' > .menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius_menu_item',
			array(
				'label'      => __( 'Border Radius', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'devices'    => array( 'desktop', 'tablet' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--animation-shutter-in-horizontal .elementor-item:before' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .e--animation-shutter-in-horizontal .elementor-item:after' => 'border-radius: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--animation-shutter-in-vertical .elementor-item:before' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .e--animation-shutter-in-vertical .elementor-item:after' => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'hover_style' => 'background',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_submenus',
			array(
				'label' => __( 'Sub menus', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'submenus_description',
			array(
				'raw'             => __( 'On desktop, this will affect the submenu. On mobile, this will affect the entire menu.', 'micemade-elements' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->start_controls_tabs( 'tabs_submenu_item_style' );

		$this->start_controls_tab(
			'tab_submenu_item_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'color_submenu_item',
			array(
				'label'     => __( 'Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown a, {{WRAPPER}} .elementor-menu-toggle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'background_color_submenu_item',
			array(
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown' => 'background-color: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_item_hover',
			array(
				'label' => __( 'Hover', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'color_submenu_item_hover',
			array(
				'label'     => __( 'Text Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown a:hover, {{WRAPPER}} .elementor-menu-toggle:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'background_color_submenu_item_hover',
			array(
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown a:hover,
					{{WRAPPER}} .elementor-nav-menu--dropdown a.highlighted' => 'background-color: {{VALUE}}',
				),
				'separator' => 'none',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'submenu_typography',
				'label'     => __( 'Typography', 'micemade-elements' ),
				'exclude'   => array( 'line_height' ),
				'selector'  => '{{WRAPPER}} .elementor-nav-menu--dropdown',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'submenu_border',
				'label'     => __( 'Border', 'micemade-elements' ),
				'selector'  => '{{WRAPPER}} .elementor-nav-menu--dropdown',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'submenu_border_radius',
			array(
				'label'      => __( 'Border Radius', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-nav-menu--dropdown li:first-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-nav-menu--dropdown li:last-child' => 'border-radius: {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submenu_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .elementor-nav-menu--main .elementor-nav-menu--dropdown, {{WRAPPER}} .elementor-nav-menu__container.elementor-nav-menu--dropdown',
			)
		);

		$this->add_responsive_control(
			'padding_horizontal_submenu_item',
			array(
				'label'     => __( 'Horizontal Padding', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',

			)
		);

		$this->add_responsive_control(
			'padding_vertical_submenu_item',
			array(
				'label'     => __( 'Vertical Padding', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_submenu_divider',
			array(
				'label'     => __( 'Divider', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'submenu_divider',
				'label'    => __( 'Divider', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .elementor-nav-menu--dropdown li:not(:last-child)',
				'exclude'  => array( 'width' ),
			)
		);

		$this->add_control(
			'submenu_divider_width',
			array(
				'label'     => __( 'Border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--dropdown li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'dropdown_divider_border!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'submenu_top_distance',
			array(
				'label'     => __( 'Distance', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-nav-menu--main > .elementor-nav-menu > li > .elementor-nav-menu--dropdown, {{WRAPPER}} .elementor-nav-menu__container.elementor-nav-menu--dropdown' => 'margin-top: {{SIZE}}{{UNIT}} !important',
				),
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			array(
				'label'     => __( 'Toggle Button', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'toggle!' => '',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_style_normal',
			array(
				'label' => __( 'Normal', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => __( 'Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.elementor-menu-toggle' => 'color: {{VALUE}}', // Harder selector to override text color control
				),
			)
		);

		$this->add_control(
			'toggle_background_color',
			array(
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-toggle' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_style_hover',
			array(
				'label' => __( 'Hover', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'toggle_color_hover',
			array(
				'label'     => __( 'Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.elementor-menu-toggle:hover' => 'color: {{VALUE}}', // Harder selector to override text color control
				),
			)
		);

		$this->add_control(
			'toggle_background_color_hover',
			array(
				'label'     => __( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-toggle:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_size',
			array(
				'label'     => __( 'Size', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'toggle_border_width',
			array(
				'label'     => __( 'Border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .elementor-menu-toggle' => 'border-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'toggle_border_radius',
			array(
				'label'      => __( 'Border Radius', 'micemade-elements' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-menu-toggle' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {

		$available_menus = $this->get_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_active_settings();

		new Micemade_Nav_Html( $settings['menu'], $settings['layout'] );

	}

	public function render_plain_content() {}
}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_Nav() );
