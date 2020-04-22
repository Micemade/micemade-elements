<?php
/**
 * Main Micemade Elements Class
 *
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Micemade Elements Class
 */
class Micemade_Elements {

	/**
	 * This Class instance
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Get instance
	 *
	 * @return $instance
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public $debug;

	/**
	 * Initialize plugin
	 *
	 * @return void
	 */
	public function init() {

		if ( self::$instance->elementor_activation_check() ) {

			$this->includes();

			// Add "Micemade Elements" widget categories.
			add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );

			// Register "Micemade elements" widgets.
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'widgets_registered' ] );

			// Add custom controls to Elementor section.
			add_action( 'elementor/element/after_section_end', [ $this, 'custom_section_controls' ], 10, 5 );
			add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

			// Load textdomain.
			add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );
			// Check for plugin dependecies.
			add_action( 'plugins_loaded', [ $this, 'plugins_dependency_checks' ] );

			// Register CPT's and enabling WC functions in Elementor editor.
			add_action( 'init', [ $this, 'register_cpts' ] );
			add_action( 'init', [ $this, 'enable_wc_frontend_in_editor' ] );

			// Enqueue script and styles for Elementor editor.
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_scripts' ], 999 );
			// add_action( 'admin_enqueue_scripts', array( self::$instance, 'micemade_elements_admin_js_css' ) );

			// Enqueue scripts and styles for frontend.
			add_action( 'wp_enqueue_scripts', array( $this, 'micemade_elements_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'micemade_elements_scripts' ) );

			$this->debug = apply_filters( 'micemade_elements_debug', true );

			self::$instance->updater();

		} else {

			add_action( 'admin_notices', array( self::$instance, 'admin_notice' ) );
		}

	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Check for Elementor activation
	 *
	 * @return $micemade_elements_is_active
	 */
	private function elementor_activation_check() {

		$micemade_elements_is_active = false;

		//if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

			$micemade_elements_is_active = true;
			$this->define( 'ELEMENTOR_IS_ACTIVE', true );
		} else {
			$this->define( 'ELEMENTOR_IS_ACTIVE', false );
		}

		return $micemade_elements_is_active;

	}

	/**
	 * Activation checks for various plugins (dependencies)
	 *
	 * @return void
	 */
	public function plugins_dependency_checks() {
		// VARIOUS PLUGINS ACTIVATION CHECKS.
		require_once MICEMADE_ELEMENTS_DIR . 'includes/plugins.php';

	}

	/**
	 * Register categories for widgets (elements)
	 *
	 * @return void
	 */
	public function add_widget_categories() {

		$elements_manager = Elementor\Plugin::instance()->elements_manager;
		$elements_manager->add_category(
			'micemade_elements',
			[
				'title' => __( 'Micemade Elements', 'micemade-elements' ),
				'icon'  => 'eicon-font',
			],
			1
		);
		/* // Header CPT and header elements postponed for v. 1.0.0
		$elements_manager->add_category(
			'micemade_elements_header',
			[
				'title' => __( 'Micemade Header Elements', 'micemade-elements' ),
				'icon'  => 'eicon-font',
			],
			2
		);
		*/
	}

	/**
	 * Add new elementor group control
	 *
	 * @since v.0.7.0
	 */
	public function register_controls_group( $controls_manager ) {
		$controls_manager->add_group_control( 'mmposts', new Group_Control_Posts );
	}

	/**
	 * Register widgets (elements) for Elementor
	 *
	 * @return void
	 */
	public function widgets_registered() {

		// Widgets directory plus widget prefix "class-micemade".
		$prefix = MICEMADE_ELEMENTS_DIR . 'widgets/class-micemade-';

		require_once $prefix . 'posts-grid.php';
		require_once $prefix . 'buttons.php';

		// "Revolution Slider" plugin widget.
		if ( MICEMADE_ELEMENTS_REVSLIDER_ON ) {
			require_once $prefix . 'rev-slider.php';
		}
		// "WooCommerce" plugin widgets.
		if ( MICEMADE_ELEMENTS_WOO_ACTIVE ) {
			require_once $prefix . 'wc-categories.php';
			require_once $prefix . 'wc-products.php';
			require_once $prefix . 'wc-products-slider.php';
			require_once $prefix . 'wc-single-product.php';
			require_once $prefix . 'wc-products-tabs.php';
			require_once $prefix . 'wc-cat-menu.php';
		}

		// "Contact Form 7" plugin widget.
		if ( MICEMADE_ELEMENTS_CF7_ON ) {
			require_once $prefix . 'contact-form-7.php';
		}
		// "MailChimp 4 WP" plugin widget.
		if ( MICEMADE_ELEMENTS_MC4WP_ON ) {
			require_once $prefix . 'mailchimp.php';
		}

		// Instagram widget.
		require_once $prefix . 'instagram.php';
		// Micemade slider.
		require_once $prefix . 'slider.php';

		// Micemade header widgets - for v.1.0.0
		// require_once $prefix . 'header-logo.php';
		// require_once $prefix . 'nav.php';

	}

	/**
	 * Custom controls for section
	 *
	 * @param  object $element - element type.
	 * @param  integer $section_id - id of section element.
	 * @param  array $args - section argumets.
	 * @return void
	 */
	public function custom_section_controls( $element, $section_id, $args ) {

		if ( 'section' === $element->get_name() && 'section_typo' === $section_id ) {

			$element->start_controls_section(
				'micemade_elements_section_sticky',
				[
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
					'label' => __( 'Sticky section', 'micemade-elements' ),
				]
			);

			$element->add_control(
				'micemade_elements_sticky',
				[
					'label'        => __( 'Section sticky method', 'micemade-elements' ),
					'type'         => \Elementor\Controls_Manager::SELECT,
					'default'      => 'not-sticked',
					'options'      => array(
						'not-sticked'    => __( 'Not sticked', 'micemade-elements' ),
						'sticked-header' => __( 'Sticked header', 'micemade-elements' ),
						'sticked-inner'  => __( 'Sticked inside column', 'micemade-elements' ),
						'sticked-footer' => __( 'Sticked footer', 'micemade-elements' ),
					),
					'prefix_class' => 'selection-is-',
				]
			);

			$element->end_controls_section();
		}
	}

	public function register_controls() {

		require MICEMADE_ELEMENTS_DIR . '/includes/class-micemade-control-sorting.php';

		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'sorter_label', new Micemade_Control_Sorter() );
	}

	/**
	 * Plugin file inclusions (requirements)
	 *
	 * @return void
	 */
	public function includes() {

		require MICEMADE_ELEMENTS_DIR . '/includes/Parsedown.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/admin.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/ajax_posts.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/helpers.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/wc-functions.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/instagram.php';
		require MICEMADE_ELEMENTS_DIR . '/includes/class-micemade-nav-html.php';

	}

	/**
	 * Load Plugin Text Domain
	 *
	 * @return false
	 * Looks for the plugin translation files in certain directories and loads
	 * them to allow the plugin to be localised
	 */
	public function load_plugin_textdomain() {

		$lang_dir = apply_filters( 'micemade_elements_lang_dir', trailingslashit( MICEMADE_ELEMENTS_DIR . 'languages' ) );

		// Traditional WordPress plugin locale filter.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'micemade-elements' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'micemade-elements', $locale );

		// Setup paths to current locale file.
		$mofile_local = $lang_dir . $mofile;

		if ( file_exists( $mofile_local ) ) {
			// Look in the /wp-content/plugins/micemade-elements/languages/ folder.
			load_textdomain( 'micemade-elements', $mofile_local );
		} else {
			// Load the default language files.
			load_plugin_textdomain( 'micemade-elements', false, $lang_dir );
		}

		return false;
	}

	/**
	 * Enqueue plugin styles
	 *
	 * @return void
	 */
	public function micemade_elements_styles() {

		// CSS styles.
		wp_register_style( 'micemade-elements', MICEMADE_ELEMENTS_URL . 'assets/css/micemade-elements.css', array(), MICEMADE_ELEMENTS_VERSION );
		wp_enqueue_style( 'micemade-elements' );

		// Smartmenus styles - postponed until v.1.0.0
		// wp_register_style( 'micemade-elements-smartmenus', MICEMADE_ELEMENTS_URL . 'assets/css/smartmenus.css', array(), MICEMADE_ELEMENTS_VERSION  );
		// wp_enqueue_style( 'micemade-elements-smartmenus' );
	}

	/**
	 * Enqueue plugin JS scrips
	 *
	 * @return void
	 */
	public function micemade_elements_scripts() {

		$prefix = '.min';
		if ( $this->debug ) {
			$prefix = '';
		}

		// Register and enqueue custom plugin JS scripts.
		wp_register_script( 'micemade-elements-vendor-js', MICEMADE_ELEMENTS_URL . 'assets/js/vendor' . $prefix . '.js', '', MICEMADE_ELEMENTS_VERSION, true );
		wp_enqueue_script( 'micemade-elements-vendor-js', MICEMADE_ELEMENTS_URL . 'assets/js/vendor' . $prefix . '.js', array( 'jquery' ), MICEMADE_ELEMENTS_VERSION, true );

		// Register and enqueue vendor JS scripts.
		wp_register_script( 'micemade-element-js', MICEMADE_ELEMENTS_URL . 'assets/js/micemade-elements' . $prefix . '.js', '', MICEMADE_ELEMENTS_VERSION, true );
		wp_enqueue_script( 'micemade-elements-js', MICEMADE_ELEMENTS_URL . 'assets/js/micemade-elements' . $prefix . '.js', [ 'jquery', 'imagesloaded' ], MICEMADE_ELEMENTS_VERSION, true );

		// Smartmenus scripts - postponed until v.1.0.0
		//wp_register_script( 'smartmenus', MICEMADE_ELEMENTS_URL . 'assets/js/jquery.smartmenus.min.js' );

		$ajaxurl = '';
		if ( MICEMADE_ELEMENTS_WPML_ON ) {
			$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else {
			$ajaxurl .= admin_url( 'admin-ajax.php' );
		}

		wp_localize_script(
			'micemade-elements-js',
			'micemadeJsLocalize',
			array(
				'ajaxurl'      => esc_url( $ajaxurl ),
				'loadingposts' => esc_html__( 'Loading ...', 'micemade-elements' ),
				'noposts'      => esc_html__( 'No more items found', 'micemade-elements' ),
				'loadmore'     => esc_html__( 'Load more', 'micemade-elements' ),
			)
		);

	}

	/**
	 * Enqueue editor scritps
	 *
	 * @return void
	 */
	public function editor_scripts() {

		wp_enqueue_script(
			'micemade-elements-editor',
			MICEMADE_ELEMENTS_URL . 'assets/js/admin/editor.js',
			[
				'elementor-editor', // dependency.
			],
			'1.9.2',
			true // in_footer
		);
	}

	/**
	 * Admin notice for plugin activation
	 *
	 * @return void
	 */
	public function admin_notice() {

		$class   = 'error updated settings-error notice is-dismissible';
		$message = __( '"Micemade elements" plugin is not effective without "Elementor" plugin activated. Please, either install and activate  "Elementor" plugin or deactivate "Micemade elements".', 'micemade-elements' );
		echo '<div class="' . esc_attr( $class ) . '"><p>' . esc_html( $message ) . '</p></div>';
	}

	/**
	 * Register custom post types
	 *
	 * @return false
	 */
	public function register_cpts() {

		if ( current_theme_supports( 'micemade-elements-cpt' ) && ELEMENTOR_IS_ACTIVE ) {

			// include file with Custom Post Types registration
			// MM Mega Menu, MM Header, MM Footer.
			require MICEMADE_ELEMENTS_DIR . '/includes/cpt.php';

		}

		return false;

	}

	/**
	 * Make WooCommerce functions work in editor
	 *
	 * @return void
	 */
	public function enable_wc_frontend_in_editor() {
		// WooCommerce frontend functionalities in Elementor editor.
		if ( MICEMADE_ELEMENTS_WOO_ACTIVE ) {
			add_action( 'admin_action_elementor', array( self::$instance, 'wc_frontend_includes' ), 5 );
		}
	}

	/**
	 * WooCommerce frontend functions
	 *
	 * @return void
	 */
	public function wc_frontend_includes() {
		WC()->frontend_includes();
	}

	/**
	 * Github updater
	 *
	 * @return void
	 *
	 * class for GitHub automatic plugin updates - will be removed once
	 * the plugin will be available on WP.org repo
	 */
	public function updater() {

		require_once MICEMADE_ELEMENTS_DIR . 'github_updater.php';

		if ( is_admin() ) {
			new Micemade_GitHub_Plugin_Updater( MICEMADE_ELEMENTS_DIR . 'micemade-elements.php', 'Micemade', 'micemade-elements' );
		}
	}

} // end class Micemade_Elements

Micemade_Elements::get_instance()->init();
