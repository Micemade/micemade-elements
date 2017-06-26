<?php
/**
 * Plugin Name: Micemade Elements
 * Description: Addon plugin with custom elements for Elementor, created by Micemade. Elementor plugin required.
 * Plugin URI: https://github.com/Micemade/micemade-elements/
 * Version: 0.2.9
 * Author: micemade
 * Author URI: http://micemade.com
 * Text Domain: micemade-elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Micemade_Elements {


	private static $instance = null;
	
	public static function get_instance() {
		if ( ! self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
	
	public function init() {
		
		if( self::$instance->Elementor_plugin_activation_check() ) {
			
			define('MICEMADE_ELEMENTS_DIR', plugin_dir_path( __FILE__ ) );
			define('MICEMADE_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );
			
			
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgets_registered' ) );
			//add_action( 'elementor/init', array( $this, 'widgets_registered' ) );
			
			add_action('plugins_loaded', array( self::$instance, 'load_plugin_textdomain') );
			
			self::$instance->activation_checks();
			
			self::$instance->includes();
			
			add_action( 'init', array( self::$instance, 'mega_menu_post_type' ) );
			
			// Enqueue script and styles for ADMIN
			//add_action( 'admin_enqueue_scripts', array( self::$instance, 'micemade_elements_admin_js_css' ) );
			
			// Enqueue scripts and styles for frontend
			add_action( 'wp_enqueue_scripts', array( self::$instance,'micemade_elements_styles') );
			add_action( 'wp_enqueue_scripts', array( self::$instance,'micemade_elements_scripts') );
			
			self::$instance->updater();
		
		}else {
			
			add_action( 'admin_notices', array( self::$instance ,'admin_notice') ); 
		}
	
		
	}
	

	private function Elementor_plugin_activation_check() {
		
		$micemade_elements_is_active = false;
		
		//if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
		if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			
			$micemade_elements_is_active = true; 
			define('ELEMENTOR_IS_ACTIVE', true );						
		}else{
			define('ELEMENTOR_IS_ACTIVE', false );	
		}
		
		return $micemade_elements_is_active;
		
	}
	
	
	private function activation_checks() {
		
		// VARIOUS PLUGINS ACTIVATION CHECK:
		// if WOOCOMMERCE activated:
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {
			define('MICEMADE_ELEMENTS_WOO_ACTIVE',true );								
		}else{
			define('MICEMADE_ELEMENTS_WOO_ACTIVE',false );	
		}
		// if YITH WC WISHLIST activated:
		if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {
			define('MICEMADE_ELEMENTS_WISHLIST_ACTIVE',true );
		}else{
			define('MICEMADE_ELEMENTS_WISHLIST_ACTIVE',false );
		}
		
		// if WPML activated:
		if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {
			define('MICEMADE_ELEMENTS_WPML_ON',true );										
		}else{
			define('MICEMADE_ELEMENTS_WPML_ON',false );	
		}
		
		// if REV. SLIDER activated:
		if ( in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )  {
			define('MICEMADE_ELEMENTS_REVSLIDER_ON',true );										
		}else{
			define('MICEMADE_ELEMENTS_REVSLIDER_ON',false );	
		}
		

	}
	
	public function widgets_registered() {

		// get our own widgets up and running:
		// copied from widgets-manager.php
		if ( class_exists( 'Elementor\Plugin' ) ) {
			
			if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
								
				$theElementor = Elementor\Plugin::instance();
				
				if ( isset( $theElementor->widgets_manager ) ) {
					
					if ( method_exists( $theElementor->widgets_manager, 'register_widget_type' ) ) {

						$widgets = self::$instance -> widgets_list();
						
						foreach( $widgets as $file => $class ) {
							$widget_file   = 'plugins/elementor/'.$file.'.php';
							$template_file = locate_template( $widget_file );
													
							if ( !$template_file || !is_readable( $template_file ) ) {
								$template_file = plugin_dir_path(__FILE__) . 'widgets/'.$file.'.php';
							}
							
							if ( $template_file && is_readable( $template_file ) ) {
								require_once $template_file;
								
								$widget_class = 'Elementor\\' . $class;
								
								$theElementor->widgets_manager->register_widget_type( new $widget_class );
							}
							
						} // end foreach
						
					} // end if( method_exists ...
					
				} // end if ( isset( $theElementor ...
				
			} // end if ( is_callable( 'Elementor\Plugin' ...
			
		} //if ( class_exists( 'Elementor\Plugin' ) )
		
	}
	
	public function widgets_list() {
		
		$widgets_list = array(
			'micemade-posts-grid'			=> 'Micemade_Posts_Grid'
		);
		
		if( MICEMADE_ELEMENTS_WOO_ACTIVE ) {
			$widgets_list['micemade-wc-products']			= 'Micemade_WC_Products';
			$widgets_list['micemade-wc-products-slider']	= 'Micemade_WC_Products_Slider';
			$widgets_list['micemade-wc-single-product']		= 'Micemade_WC_Single_Product';
			$widgets_list['micemade-wc-categories']			= 'Micemade_WC_Categories';
		}
		
		if( MICEMADE_ELEMENTS_REVSLIDER_ON ) {
			$widgets_list['micemade-rev-slider']			= 'Micemade_Rev_Slider';
		}
				
		return $widgets_list;
		
	}
	
	public function includes () {
		
		$plugin_path = plugin_dir_path( __FILE__ );
		include( $plugin_path . "/includes/Parsedown.php" );
		include( $plugin_path . "/includes/admin.php" );
		include( $plugin_path . "/includes/ajax_posts.php" );
		include( $plugin_path . "/includes/helpers.php" );
		include( $plugin_path . "/includes/wc-functions.php" );
		
	}
	
	
	/**
	 * Load Plugin Text Domain
	 *
	 * Looks for the plugin translation files in certain directories and loads
	 * them to allow the plugin to be localised
	 */
	public function load_plugin_textdomain() {
					
		$lang_dir = apply_filters('micemade_elements_lang_dir', trailingslashit( MICEMADE_ELEMENTS_DIR . 'languages') );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters('plugin_locale', get_locale(), 'micemade-elements');
		$mofile = sprintf('%1$s-%2$s.mo', 'micemade-elements', $locale);

		// Setup paths to current locale file
		$mofile_local = $lang_dir . $mofile;

		if ( file_exists( $mofile_local ) ) {
			// Look in the /wp-content/plugins/micemade-elements/languages/ folder
			load_textdomain('micemade-elements', $mofile_local);
		} else {
			// Load the default language files
			load_plugin_textdomain('micemade-elements', false, $lang_dir);
		}

		return false;
	}
	
	// ENQUEUE STYLES
	public function micemade_elements_styles () {
		
		// CSS styles:
		wp_register_style( 'micemade-elements', MICEMADE_ELEMENTS_URL . 'assets/css/micemade-elements.css' );
		wp_enqueue_style( 'micemade-elements' );
		
	}
	
	// ENQUEUE SCRIPTS
	public function micemade_elements_scripts () {
		
		// JS scripts:
		wp_register_script('micemade-elements-js', MICEMADE_ELEMENTS_URL .'assets/js/micemade-elements.min.js');
		wp_enqueue_script('micemade-elements-js', MICEMADE_ELEMENTS_URL .'assets/js/micemade-elements.min.js', array('jQuery'), '1.0', true);
		
		$ajaxurl = '';
		if( MICEMADE_ELEMENTS_WPML_ON ){
			$ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
		} else{
			$ajaxurl .= admin_url( 'admin-ajax.php');
		}
		 
		wp_localize_script( 'micemade-elements-js', 'micemadeJsLocalize', array(
			'ajaxurl'		=> $ajaxurl,
			'loadingposts'  => esc_html__('Loading posts ...', 'micemade-elements'),
			'noposts'		=> esc_html__('No more posts found', 'micemade-elements'),
			'loadmore'		=> esc_html__('Load more posts', 'micemade-elements')
		) );
		
	}
	
	public function ajax_url_var() {
		echo '<script type="text/javascript">var micemade_elements_ajaxurl = "'. admin_url("admin-ajax.php") .'"</script>';
	}
	
	public function admin_notice() {
		
		$class = "error updated settings-error notice is-dismissible";
		$message = __("Micemade elements is not effective without \"Elementor\" plugin activated. Either install and activate  \"Elementor\" plugin or deactivate Micemade elements. ","micemade-elements");
        echo"<div class=\"$class\"> <p>$message</p></div>"; 
		
	}
	
	public function mega_menu_post_type() {
		
		$micemade_themes	= array( 'natura', 'beautify', 'cloth'); // list of Micemade themes compatible with MM Mega menu CPT
		$active_theme		= get_option( 'template' );

		if( in_array( $active_theme, $micemade_themes ) ) {
			
			$labels = array(
				'name'			=> __( 'Mega Menus', 'micemade-elements' ),
				'singular_name'	=> __( 'Mega Menu', 'micemade-elements' ),
				'add_new'		=> __( 'New Mega Menu', 'micemade-elements' ),
				'add_new_item'	=> __( 'Add New Mega Menu', 'micemade-elements' ),
				'edit_item'		=> __( 'Edit Mega Menu', 'micemade-elements' ),
				'new_item'		=> __( 'New Mega Menu', 'micemade-elements' ),
				'view_item'		=> __( 'View Mega Menu', 'micemade-elements' ),
				'search_items'	=> __( 'Search Mega Menus', 'micemade-elements' ),
				'not_found'		=>  __( 'No Mega Menus Found', 'micemade-elements' ),
				'not_found_in_trash' => __( 'No Mega Menus found in Trash', 'micemade-elements' ),
				);
			$args = array(
				'labels'		=> $labels,
				'supports'              => array( 'title','editor' ),
				'public'                => true,
				'rewrite'               => false,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'show_in_nav_menus'     => false,
				'exclude_from_search'   => true,
				'capability_type'       => 'post',
				'hierarchical'          => false,
				'menu-icon'             => 'dashicon-tagcloud'
			);
			
			register_post_type( 'MM Mega menu', $args );
			
			// Automatically activate Elementor support for MM Mega menu CPT (always active)
			$elementor_cpt_support = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
			if( ! in_array( 'mmmegamenu', $elementor_cpt_support ) ) {
				$elementor_cpt_support[] = 'mmmegamenu';
				update_option( 'elementor_cpt_support',$elementor_cpt_support );
			}
			
		}
		
		return false;
		
	}
	
	function updater() {
			
		require_once( plugin_dir_path( __FILE__ ) . 'github_updater.php' );
		if ( is_admin() ) {
			new Micemade_GitHub_Plugin_Updater( __FILE__, 'Micemade', "micemade-elements" );
		}
	}

	
} // end class Micemade_Elements

Micemade_Elements::get_instance()->init();