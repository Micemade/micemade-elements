<?php
namespace Elementor;

use Elementor\Controls_Stack;
// use Elementor\Controls_Manager;
// use Elementor\Core\Files\CSS\Post;
// use Elementor\Element_Base;
// use Elementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

class Micemade_WC_Products_Tabs extends \Elementor\Widget_Base {

	/**
	 * Products grid CSS classes
	 *
	 * @var string
	 */
	public $mm_products_grid;

	public function get_name() {
		return 'micemade-wc-products-tabs';
	}

	public function get_title() {
		return __( 'Micemade WC Products Tabs', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_script_depends() {
		return array( 'ace' );
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	/**
	 * Register tabs widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_tabs',
			array(
				'label' => __( 'Tabs', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'type',
			array(
				'label'        => __( 'Type', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'horizontal',
				'options'      => array(
					'horizontal' => __( 'Horizontal', 'micemade-elements' ),
					'vertical'   => __( 'Vertical', 'micemade-elements' ),
				),
				'prefix_class' => 'mm-wc-tabs-type-',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			array(
				'label'       => esc_html__( 'Tab title', 'micemade-elements' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Tab Title', 'micemade-elements' ),
				'placeholder' => esc_html__( 'Tab Title', 'micemade-elements' ),
				'label_block' => true,
			),
		);
		$repeater->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Total products', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '4',
				'title'   => esc_html__( 'Enter total number of products to show', 'micemade-elements' ),
			)
		);
		$repeater->add_control(
			'offset',
			array(
				'label'   => esc_html__( 'Offset', 'micemade-elements' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '',
				'title'   => esc_html__( 'Offset is a number of skipped products', 'micemade-elements' ),
			),
		);
		$repeater->add_control(
			'categories',
			array(
				'label'       => esc_html__( 'Select product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			),
		);
		$repeater->add_control(
			'exclude_cats',
			array(
				'label'       => esc_html__( 'Exclude product categories', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => array(),
				'options'     => apply_filters( 'micemade_elements_terms', 'product_cat' ),
				'multiple'    => true,
				'label_block' => true,
			),
		);
		$repeater->add_control(
			'filters',
			array(
				'label'   => __( 'Products filters', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => array(
					'latest'       => esc_html__( 'Latest products', 'micemade-elements' ),
					'featured'     => esc_html__( 'Featured products', 'micemade-elements' ),
					'best_sellers' => esc_html__( 'Best selling products', 'micemade-elements' ),
					'best_rated'   => esc_html__( 'Best rated products', 'micemade-elements' ),
					'on_sale'      => esc_html__( 'Products on sale', 'micemade-elements' ),
					'random'       => esc_html__( 'Random products', 'micemade-elements' ),
				),
			),
		);
		$repeater->add_control(
			'products_in',
			array(
				'label'    => esc_html__( 'Select products', 'micemade-elements' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => array(),
				'options'  => apply_filters( 'micemade_posts_array', 'product' ),
				'multiple' => true,
			),
		);
		$repeater->add_control(
			'no_outofstock',
			array(
				'label'     => esc_html__( 'Hide out of stock products', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'elementor' ),
				'label_on'  => esc_html__( 'Yes', 'elementor' ),
				'default'   => 'yes',
			),
		);
		$repeater->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order by', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'micemade-elements' ),
					'name'       => esc_html__( 'Name', 'micemade-elements' ),
					'modified'   => esc_html__( 'Last date modified', 'micemade-elements' ),
					'menu_order' => esc_html__( 'Ordered in admin', 'micemade-elements' ),
				),
			),
		);
		$repeater->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'DESC' => esc_html__( 'Descending', 'micemade-elements' ),
					'ASC'  => esc_html__( 'Ascending', 'micemade-elements' ),
				),
				'separator' => 'after',
			),
		);
		$items_num = array(
			1 => esc_html__( 'One', 'micemade-elements' ),
			2 => esc_html__( 'Two', 'micemade-elements' ),
			3 => esc_html__( 'Three', 'micemade-elements' ),
			4 => esc_html__( 'Four', 'micemade-elements' ),
			6 => esc_html__( 'Six', 'micemade-elements' ),
		);

		$repeater->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Products columns (devices)', 'micemade-elements' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => 4,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'options'        => $items_num,
				'separator'      => 'after',
			)
		);
		$repeater->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Tab icon', 'micemade-elements' ),
				'type'        => Controls_Manager::ICON,
				'label_block' => true,
				'default'     => '',
			),
		);

		$this->add_control(
			'tabs',
			array(
				'label'       => esc_html__( 'Tab Items', 'micemade-elements' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'tab_title' => esc_html__( 'Tab #1', 'micemade-elements' ),
					),
				),
				'title_field' => '<i class="{{ icon }}"></i> {{{ tab_title }}}',
				'fields'      => $repeater->get_controls(),

			)
		);

		$this->add_control(
			'heading_items_spacing_settings',
			array(
				'label'     => esc_html__( 'Items spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'horiz_spacing',
			array(
				'label'     => esc_html__( 'Products horizontal spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => array( 'desktop', 'tablet', 'mobile' ),
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'max'  => 50,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ul.products > li' => 'padding-left:{{SIZE}}px;padding-right:{{SIZE}}px;',
					'{{WRAPPER}} .mme-row'         => 'margin-left:-{{SIZE}}px; margin-right:-{{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'vert_spacing',
			array(
				'label'     => esc_html__( 'Products bottom spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => array( 'desktop', 'tablet', 'mobile' ),
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} ul.products > li' => 'margin-bottom:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mme-row'         => 'margin-bottom:-{{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_style',
			array(
				'label' => esc_html__( 'Tabs style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'navigation_width',
			array(
				'label'     => esc_html__( 'Tabs width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => '%',
				),
				'range'     => array(
					'%' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tabs-wrapper'         => 'flex-basis: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .tabs-content-wrapper' => 'flex-basis: calc( 100% - {{SIZE}}{{UNIT}} )',
				),
				'condition' => array(
					'type' => 'vertical',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => esc_html__( 'Tabs align', 'micemade-elements' ),
				'type'         => Controls_Manager::CHOOSE,
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
				'default'      => '',
				'selectors'    => array(
					'{{WRAPPER}} .tabs-wrapper' => 'text-align: {{VALUE}};',
				),
				'prefix_class' => 'mm-wc-tabs-align-',
			)
		);

		$this->add_responsive_control(
			'tab_padding',
			array(
				'label'      => esc_html__( 'Tab padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tab-title > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'vertical-align',
			array(
				'label'     => esc_html__( 'Vertical align', 'micemade-elements' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),

				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .tabs-wrapper, {{WRAPPER}} .tabs-content-wrapper' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'type' => 'vertical',
				),
			)
		);

		// Tab styles title.
		$this->add_control(
			'heading_tab_styles',
			array(
				'label'     => esc_html__( 'Tab styles', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		// ACTIVE, HOVER, INACTIVE.
		$this->start_controls_tabs( 'tabstyles' );

		// Styles for active tab.
		$this->start_controls_tab(
			'style_active',
			array(
				'label' => esc_html__( 'Active', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => esc_html__( 'Active tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title.active' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title.active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .tab-content.active' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'active_tab_desc',
			array(
				'raw'             => esc_html__( 'Active tab background also applies to active content. Content styles can be overriden bellow, under "Tab content style" settings.', 'micemade-elements' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			)
		);

		$this->end_controls_tab();// End active tab styles.

		// Styles for tab hover.
		$this->start_controls_tab(
			'style_hover',
			array(
				'label' => esc_html__( 'Hover', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'tab_color_hover',
			array(
				'label'     => esc_html__( 'Hover tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title:not(.active):hover' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'background_color_hover',
			array(
				'label'     => esc_html__( 'Hover background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title:not(.active):hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		// Styles for tab Inactive.
		$this->start_controls_tab(
			'style_inactive',
			array(
				'label' => esc_html__( 'Inactive', 'micemade-elements' ),
			)
		);
		$this->add_control(
			'inacitve_tab_color',
			array(
				'label'     => esc_html__( 'Inactive tab title color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'inactive_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();// End inactive tab styles.

		$this->end_controls_tabs();
		// End of style control tabs.

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'label'    => esc_html__( 'Title typography', 'micemade-elements' ),
				'selector' => '{{WRAPPER}} .tab-title',
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'     => esc_html__( 'Border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 1,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tab-title, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#999999',
				'selectors' => array(
					'{{WRAPPER}} .tab-title, {{WRAPPER}} .tab-title:before, {{WRAPPER}} .tab-title:after, {{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'heading_icon',
			array(
				'label'     => esc_html__( 'Tab icon', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Icon size', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tab-icon.fa' => 'font-size: {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => esc_html__( 'Tab icon spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tab-icon' => 'margin: 0 {{SIZE}}{{UNIT}};',
				),

			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'        => esc_html__( 'Icon position', 'micemade-elements' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'left'  => __( 'Left', 'micemade-elements' ),
					'right' => __( 'Right', 'micemade-elements' ),
				),
				'default'      => 'left',
				'prefix_class' => 'mm-wc-tabs-icon-',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_tabs_content',
			array(
				'label' => esc_html__( 'Tab content style', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => esc_html__( 'Content background Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-content.active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Content padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_border_width',
			array(
				'label'     => esc_html__( 'Content border Width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .tabs-content-wrapper, {{WRAPPER}} .tabs-content-wrapper:before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .tab-title.active:not(.title-showing):before, {{WRAPPER}} .tab-title.active:not(.title-showing):after' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$tabs  = $settings['tabs'];
		$type  = $settings['type'];
		$idint = substr( $this->get_id_int(), 0, 3 );
		?>

<div class="micemade-elements_tabs <?php echo esc_attr( $type ); ?>" role="tablist">

	<?php
		$counter        = 1;
		$tab_status     = '';
		$content_status = '';
		?>

	<div class="tabs-wrapper" role="tab">
		<?php
				foreach ( $tabs as $item ) {

					$tab_status = ( 1 === $counter ) ? ' active' : '';

					if ( $item['icon'] ) {
						$icon_html = '<i class="tab-icon ' . esc_attr( $item['icon'] ) . '"></i>';
					} else {
						$icon_html = '';
					}

					echo '<div class="tab-title elementor-tab-title' . esc_attr( $tab_status ) . '" data-tab="' . esc_attr( $counter ) . '" aria-controls=" elementor-tab-content-' . esc_attr( $idint . $counter ) . '" role="tab"><span>' . wp_kses_post( $icon_html . $item['tab_title'] ) . '</span></div>';

					$counter++;
				}
				?>
	</div>

	<?php $counter = 1; ?>
	<div class="tabs-content-wrapper" role="tabpanel">

		<?php
				foreach ( $tabs as $item ) {
					$content_status = ( 1 === $counter ) ? ' active' : '';

					$ppp           = $item['posts_per_page'];
					$offset        = $item['offset'];
					$categories    = $item['categories'];
					$exclude_cats  = $item['exclude_cats'];
					$filters       = $item['filters'];
					$products_in   = $item['products_in'];
					$no_outofstock = $item['no_outofstock'];
					$orderby       = $item['orderby'];
					$order         = $item['order'];

					// Columns - responsive settings.
					$columns = array();
					foreach ( $item as $key => $value ) {
						if ( 'columns' === $key && $value ) {
							$columns[ $key . '_desktop' ] = (string) $value;
						} elseif ( strpos( $key, 'columns_' ) === 0 ) {
							$columns[ $key ] = (string) $value;
						}
					}

					// Create grid CSS selectors based on column settings.
					$this->mm_products_grid = micemade_elements_grid( $columns );

					// Query args: ( includes/wc-functions.php ).
					$qa = micemade_elements_wc_query( $ppp, $categories, $exclude_cats, $filters, $offset, $products_in, $no_outofstock, $orderby, $order );

					$products = wc_get_products( $qa );

					if ( ! empty( $products ) ) {

						// Add grid classes to products in loop ( in "content-product.php" template )
						// "item" class is to support Micemade Themes.
						add_filter(
							'woocommerce_post_class',
							function ( $classes ) {
								// Remove the "first" and "last" added by wc_get_loop_class().
								$classes = array_diff( $classes, array( 'first', 'last' ) );
								return apply_filters( 'micemade_elements_product_item_classes', $classes, 'add', array( $this->mm_products_grid, 'item' ) );
							},
							10
						);

						echo '<div class="woocommerce woocommerce-page micemade-elements_wc-catalog tab-content elementor-tab-content elementor-clearfix tab-' . esc_attr( $counter . $content_status ) . '">';

						echo '<div class="title-showing tab-' . esc_attr( $counter . $content_status ) . '" data-tab="' . esc_attr( $counter ) . '"><span><i>' . esc_html__( 'Showing: ', 'micemade-elements' ) . '</i>' . esc_html( $item['tab_title'] ) . '</span></div>';

						// Start loop (get loop start as string, no echo).
						$loop_start = woocommerce_product_loop_start( false );
						// "Inject" 'mme-row' class to loop start classes.
						$loop_start = str_replace( 'class="', 'class="mme-row ', $loop_start );
						echo wp_kses_post( $loop_start );

						foreach ( $products as $product ) {

							$post_object = get_post( $product->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object );// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found );
							wc_get_template_part( 'content', 'product' );
						}

						woocommerce_product_loop_end();

						echo '</div>';
					}

					// "Clean" or "reset" post_class
					// avoid conflict with other "post_class" functions
					add_filter(
						'woocommerce_post_class',
						function ( $classes ) {
							return apply_filters( 'micemade_elements_product_item_classes', $classes, '', array( $this->mm_products_grid, 'item' ) );
						},
						10
					);

					wp_reset_postdata();
					?>

		<?php
					$counter++;
				} // end foreach tab.
				?>
	</div>
</div>
<?php
	}

	/**
	 * Render tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	// protected function content_template() {}
}
