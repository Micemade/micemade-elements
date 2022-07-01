<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Typography;

class Micemade_WC_Single_Product extends Widget_Base {

	public function get_name() {
		return 'micemade-wc-single-product';
	}

	public function get_title() {
		return __( 'Micemade WC Single Product', 'micemade-elements' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return array( 'micemade_elements' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_my_custom',
			array(
				'label' => esc_html__( 'Micemade WC Single Product', 'micemade-elements' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => esc_html__( 'Select a product', 'micemade-elements' ),
				'type'        => Controls_Manager::SELECT2,
				'default'     => 3,
				'options'     => apply_filters( 'micemade_posts_array', 'product', 'id' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout base', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'images_left',
				'options' => array(
					'images_left'       => esc_html__( 'Image left', 'micemade-elements' ),
					'images_right'      => esc_html__( 'Image right', 'micemade-elements' ),
					'vertical'          => esc_html__( 'Vertical', 'micemade-elements' ),
					'vertical_reversed' => esc_html__( 'Vertical reversed', 'micemade-elements' ),
					'image_background'  => esc_html__( 'Featured image background', 'micemade-elements' ),
					'no_image'          => esc_html__( 'No product image', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'wc_image_gallery',
			array(
				'label'       => esc_html__( 'Use WC image gallery', 'micemade-elements' ),
				'label_block' => true,
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => __( 'No', 'elementor' ),
				'label_on'    => __( 'Yes', 'elementor' ),
				'condition'   => array(
					'layout!' => array( 'image_background', 'no_image' ),
				),
			)
		);

		$this->add_control(
			'img_format',
			array(
				'label'     => esc_html__( 'Featured image format', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'shop_single',
				'options'   => apply_filters( 'micemade_elements_image_sizes', '' ),
				'condition' => array(
					'layout!' => 'no_image',
				),
			)
		);

		$this->add_control(
			'product_data',
			array(
				'label'   => esc_html__( 'Product data', 'micemade-elements' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'full',
				'options' => array(
					'full'    => esc_html__( 'Full - single product page', 'micemade-elements' ),
					'reduced' => esc_html__( 'Reduced - catalog product', 'micemade-elements' ),
				),
			)
		);

		$this->add_control(
			'short_desc',
			array(
				'label'     => esc_html__( 'Show "Product Short Description"', 'micemade-elements' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => __( 'No', 'micemade-elements' ),
				'label_on'  => __( 'Yes', 'micemade-elements' ),
				'default'   => 'yes',
				'condition' => array(
					'product_data' => 'reduced',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_height',
			array(
				'label'     => esc_html__( 'Product height', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_back_product_height',
			array(
				'label'     => __( 'Product height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '450',
				),
				'range'     => array(
					'px' => array(
						'max'  => 1600,
						'min'  => 0,
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce div.single-product-container ' => 'height: {{SIZE}}px;',
				),
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Product info settings', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Product info background', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .entry-summary' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'product_elements_spacing',
			array(
				'label'     => __( 'Product elements spacing', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'max'  => 200,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce div.single-product-container div.summary>*' => 'margin-top: {{SIZE}}px;margin-bottom: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce div.single-product-container div.summary form.cart >*' => 'margin-bottom: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'                => __( 'Horizontal align', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'              => 'left',
				'selectors'            => array(
					'{{WRAPPER}} .entry-summary' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .star-rating, {{WRAPPER}} .woocommerce-review-link' => 'float: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'   => 'text-align: left; align-items: flex-start',
					'center' => 'text-align: center; align-items: center',
					'right'  => 'text-align: right; align-items: flex-end',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_align',
			array(
				'label'       => __( 'Vertical Align', 'micemade-elements' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'flex-start' => array(
						'title' => __( 'Start', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'micemade-elements' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .entry-summary' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'product_info_position',
			array(
				'label'                => esc_html__( 'Product info position', 'micemade-elements' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'micemade-elements' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'micemade-elements' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'micemade-elements' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .woocommerce div.single-product-container div.entry-summary' => '{{VALUE}};',
				),
				'selectors_dictionary' => array(
					'left'   => 'left: 0; transform: none; right: auto',
					'center' => 'left: 50%; transform: translateX(-50%)',
					'right'  => 'right: 0; transform: none; left: auto',
				),
				'condition'            => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'product_info__width',
			array(
				'label'     => __( 'Product info width (%)', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '100',
				),
				'range'     => array(
					'%' => array(
						'max'  => 100,
						'min'  => 0,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .woocommerce div.single-product-container div.entry-summary' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Product info padding', 'micemade-elements' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} div.entry-summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_thumb_settings',
			array(
				'label'     => __( 'Product image settings', 'micemade-elements' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!' => 'no_image',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_width',
			array(
				'label'     => __( 'Image container width', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'unit' => '%',
					'size' => 100,
				),
				'range'     => array(
					'%' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} div.single-product-container .entry-summary' => 'width: calc(100% - {{SIZE}}{{UNIT}} )',
					'{{WRAPPER}} div.single-product-container .product-thumb' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} div.single-product-container .mm-wc-gallery' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'layout' => array( 'image_left', 'image_right' ),
				),
			)
		);

		$this->add_responsive_control(
			'thumb_height',
			array(
				'label'     => esc_html__( 'Image container height', 'micemade-elements' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 400,
				),
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-thumb' => 'height: {{SIZE}}{{UNIT}} !important;',
				),
				'condition' => array(
					'layout!' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_as_back_height',
			array(
				'label'     => esc_html__( 'Image container height', 'micemade-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 100,
				'min'       => 0,
				'max'       => 100,
				'step'      => 5,
				'selectors' => array(
					'{{WRAPPER}} .product-thumb' => 'height: {{VALUE}}%;',
				),
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'back_image_position',
			array(
				'label'     => esc_html__( 'Image background position', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'center',
				'options'   => array(
					'left top'      => esc_html__( 'Top left', 'micemade-elements' ),
					'center top'    => esc_html__( 'Top center', 'micemade-elements' ),
					'right top'     => esc_html__( 'Top right', 'micemade-elements' ),
					'left center'   => esc_html__( 'Center Left ', 'micemade-elements' ),
					'center'        => esc_html__( 'Center', 'micemade-elements' ),
					'right center'  => esc_html__( 'Center right ', 'micemade-elements' ),
					'left bottom'   => esc_html__( 'Bottom left ', 'micemade-elements' ),
					'center bottom' => esc_html__( 'Bottom center ', 'micemade-elements' ),
					'right bottom'  => esc_html__( 'Bottom right ', 'micemade-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-thumb' => 'background-position: {{VALUE}};',
				),
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_background_size',
			array(
				'label'     => esc_html__( 'Image background size', 'micemade-elements' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => array(
					'auto'    => esc_html__( 'Auto', 'micemade-elements' ),
					'cover'   => esc_html__( 'Cover', 'micemade-elements' ),
					'contain' => esc_html__( 'Contain', 'micemade-elements' ),
					'custom'  => esc_html__( 'Custom', 'micemade-elements' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-thumb' => 'background-size: {{VALUE}};',
				),
				'condition' => array(
					'layout' => 'image_background',
				),
			)
		);

		$this->add_responsive_control(
			'thumb_background_size_custom',
			array(
				'label'     => esc_html__( 'Custom image size', 'micemade-elements' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'min'       => 0,
				'max'       => 200,
				'step'      => 5,
				'selectors' => array(
					'{{WRAPPER}} .product-thumb' => 'background-size: {{VALUE}}% !important;',
				),
				'condition' => array(
					'layout'                => 'image_background',
					'thumb_background_size' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		/*
		 * SET OPTIONS FOR TITLE PRICE AND DESCRIPTION
		 */
		$this->start_controls_section(
			'section_title_price_desc',
			array(
				'label' => __( 'Title, price, description options', 'micemade-elements' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		// Title Options.
		$this->add_control(
			'heading_title',
			array(
				'label'     => __( 'Title', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'title_text_color',
			array(
				'label'     => __( 'Title Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry-summary h3 a, {{WRAPPER}} .entry-summary h4 a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary h3, {{WRAPPER}} .entry-summary h4',
			)
		);

		// Price options.
		$this->add_control(
			'heading_price',
			array(
				'label'     => __( 'Price', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Price Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry-summary .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary .woocommerce-Price-amount',
			)
		);
		// Description options.
		$this->add_control(
			'heading_desc',
			array(
				'label'     => __( 'Short description', 'micemade-elements' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => __( 'Short description Color', 'micemade-elements' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .entry-summary .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => __( 'Typography', 'micemade-elements' ),
				'scheme'   => Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .entry-summary .woocommerce-product-details__short-description',
			)
		);

		$this->end_controls_section();

		// Plugin::$instance->controls_manager->add_custom_css_controls( $this );.
	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'summary', 'class', $settings['align'] . ' summary entry-summary' );

		$post_name        = ! empty( $settings['post_name'] ) ? $settings['post_name'] : ''; // fallback.
		$product_id       = ! empty( $settings['product_id'] ) ? $settings['product_id'] : '';
		$product_id       = $product_id ? $product_id : $post_name;
		$layout           = ! empty( $settings['layout'] ) ? $settings['layout'] : 'images_left';
		$wc_image_gallery = ! empty( $settings['wc_image_gallery'] ) ? $settings['wc_image_gallery'] : '';
		$img_format       = ! empty( $settings['img_format'] ) ? $settings['img_format'] : 'thumbnail';
		$product_data     = ! empty( $settings['product_data'] ) ? $settings['product_data'] : 'full';
		$short_desc       = ! empty( $settings['short_desc'] ) ? ( 'yes' === $settings['short_desc'] ) : '';

		// if ( is_array( $product_id ) ) {
		// 	$product_id = $product_id;
		// } else {
		// 	$product_id = array( $product_id );
		// }

		global $post;

		// $args = array(
		// 	'posts_per_page'   => 1,
		// 	'post_type'        => 'product',
		// 	'no_found_rows'    => 1,
		// 	'post_status'      => 'publish',
		// 	'post_parent'      => 0,
		// 	'suppress_filters' => false,
		// 	'post_name__in'    => $post_name,
		// );
		// $product = get_posts( $args );

		$product = wc_get_product( $product_id );

		if ( ! empty( $product ) ) {
			?>

			<div class="woocommerce woocommerce-page micemade-elements_single-product">	

			<?php
			// $post = $product[0];
			// setup_postdata( $post );
			$post_object = get_post( $product->get_id() );
			setup_postdata( $GLOBALS['post'] =& $post_object );// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found );
			?>

			<div <?php post_class( esc_attr( $layout . ' single-product-container' ) ); ?>>

				<?php
				// $product_id = get_the_ID();
				if ( ( 'no_image' !== $layout ) && has_post_thumbnail( $product_id ) ) {

					$post_thumb_id = get_post_thumbnail_id( $product_id );
					$img_src       = wp_get_attachment_image_src( $post_thumb_id, $img_format );
					$img_url       = $img_src[0];

					if ( ! $wc_image_gallery || 'image_background' === $layout ) {
						echo '<div class="product-thumb" style="background-image: url( ' . esc_url( $img_url ) . ' );"></div>';
					} else {
						echo '<div class="mm-wc-gallery">';
						wc_get_template( 'product-image.php', false, false, MICEMADE_ELEMENTS_DIR . '/includes/templates/' );
						echo '</div>';
					}
				}
				?>

				<div <?php $this->print_render_attribute_string( 'summary' ); ?>>
					<?php
					if ( 'full' == $product_data ) {
						// display full single prod. summary.
						remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
						do_action( 'woocommerce_single_product_summary' );

					} else {
						// display price/short desc./button.
						apply_filters( 'micemade_elements_simple_prod_data', $short_desc );
					}
					?>

				</div>

			</div>

			<?php
			// Load WC single product JS scripts.
			if ( $wc_image_gallery ) {
				do_action( 'micemade_elements_single_product_scripts' );
			}
			?>

			</div>

			<?php
		} // endif

		wp_reset_postdata();

	}

	// protected function content_template() {}

	// public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Micemade_WC_Single_Product() );
