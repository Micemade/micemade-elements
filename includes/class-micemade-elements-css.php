<?php
/**
 * Class to create CSS for elements.
 * Using SCSSPHP compiler.
 *
 * @since 0.0.1
 * @package WordPress
 * @subpackage Micemade Elements
 *
 * Uses elementor/document/before_save hook : https://developers.elementor.com/docs/hooks/php/
 * Breakpoints manager: elementor/core/breakpoints/manager.php
 * Dependencies (composer): deliciousbrains/wp-filesystem  scssphp/scssphp.
 * Dropped padaliyajay/php-autoprefixer (performance issues - https://github.com/padaliyajay/php-autoprefixer)
 */

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;

/**
 * Class for creating CSS files.
 */
class Micemade_Elements_CSS {

	/**
	 * DBI filesystem.
	 *
	 * @var object
	 */
	private $filesys;

	/**
	 * Main SCSS file.
	 *
	 * Get all styles from SCSS file.
	 *
	 * @var string
	 */
	private $scss_file_path;

	/**
	 * Main CSS file.
	 *
	 * Final file to be generated.
	 *
	 * @var string
	 */
	private $css_file_path;

	/**
	 * The constructor
	 */
	public function __construct() {

		// DBI_Filesystem autoloaded.
		$this->filesys = new DBI_Filesystem();

		$this->scss_file_path = MICEMADE_ELEMENTS_DIR . 'assets/css/scss/micemade-elements.scss';

		$upload_dir          = wp_upload_dir();
		$this->css_file_path = $upload_dir['basedir'] . '/micemade-elements.css';

		// Create CSS file in "wp-content/uploads" dir, on plugin register, if file doesn't exist.
		register_activation_hook( MICEMADE_ELEMENTS_PLUGIN_FILE, array( $this, 'init_css_on_activation' ) );
		// Create CSS file on before_save elementor hook (only on settings update - check the method).
		add_action( 'elementor/document/before_save', array( $this, 'create_css' ), 10, 2 );

		// For debug only.
		add_action( 'elementor/frontend/before_get_builder_content', array( $this, 'before_builder_content' ) );
	}

	/**
	 * Create CSS file upon plugin activation.
	 *
	 * @return void
	 */
	public function init_css_on_activation() {
		if ( ! file_exists( $this->css_file_path ) ) {
			// "Dummy" data, for array checking in 'create_css' method.
			$data['elements'] = array();
			$this->create_css( null, $data );
		}
	}

	/**
	 * Start compiling CSS.
	 *
	 * @param object $document - document object with num of methods.
	 * @param array  $data - array of settings in 'elementor/document/before_save' hook.
	 *
	 * Run only on plugin activation, or after Elements doc. settings is updated.
	 * Doc. settings contain active breakpoints, so this is run only on this change.
	 * See in elementor/modules/usage/module.php
	 *
	 * @return void
	 */
	public function create_css( $document = null, $data = array() ) {

		// Check if $data from 'elementor/document/before_save'.
		// On document settings (not page elements) saving. If $data['elements'] is
		// populated, the page elements are saving, not settings, therefore, abort.
		if ( isset( $data['elements'] ) && ! empty( $data['elements'] ) ) {
			return;
		}

		$import_path = MICEMADE_ELEMENTS_DIR . 'assets/css/scss';
		// Get contents of SCSS file(s).
		$get_scss = $this->get_scss();

		try {
			// ScssPhp compiler. use ScssPhp\ScssPhp\Compiler.
			$compiler = new Compiler();
			// Set paths for scss imports.
			$compiler->setImportPaths( $import_path );
			// Set output style.
			$compiler->setOutputStyle( OutputStyle::COMPRESSED ); // use ScssPhp\ScssPhp\OutputStyle.

			// Complile CSS string.
			$css_content = $compiler->compileString( $get_scss )->getCss();

			// Write the CSS file.
			if ( ! $this->filesys->file_exists( $this->css_file_path ) ) {
				$this->filesys->touch( $this->css_file_path );
			}
			$this->filesys->put_contents( $this->css_file_path, $css_content );

		} catch ( \Exception $e ) {
			error_log( $e->getMessage() . "\n", 3, WP_CONTENT_DIR . '/uploads/mm-errors.log' );
		}
	}

	/**
	 * Get active Elementor Breakpoints in array.
	 *
	 * @return array $active_bps
	 */
	public function elementor_active_breakpoints() {
		// Get Elementor breakpoints. use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager.
		$bp_manager = new Breakpoints_Manager();
		$bp_array   = $bp_manager->get_active_breakpoints(); // get_breakpoints_config() for ALL breakponits.

		// Value and direction settings for each breakpoint.
		foreach ( $bp_array as $name => $settings ) {
			$active_bps[ $name ] = array(
				'value'     => $settings->get_value(),
				'direction' => $settings->get_direction(),
			);
		}
		// Add value and direction to default "Desktop" breakpoint and append to breakpoints.
		$nearest_value         = isset( $active_bps['laptop'] ) ? (int) $bp_array['laptop']->get_value() : (int) $bp_array['tablet']->get_value();
		$active_bps['desktop'] = array(
			'value'     => $nearest_value + 1,
			'direction' => 'min',
		);

		// Reverse array because of usage max-width in media queries (CSS overriedes).
		$active_bps = array_reverse( $active_bps, true );

		return $active_bps;
	}

	/**
	 * Create SCSS mixin from active Elementor Breakpoints array.
	 *
	 * @return string $bp_scss
	 */
	public function create_breakpoints_mixin() {

		$active_bps = $this->elementor_active_breakpoints();

		// Create breakpoints mixin.
		$bp_scss = ' @mixin breakpoint($point) {';
		foreach ( $active_bps as $bp_name => $bp_config ) {
			$bp_scss .= '@if $point == ' . $bp_name . ' {';
			$bp_scss .= '@media (' . $bp_config['direction'] . '-width: ' . $bp_config['value'] . 'px) { @content ; }';
			$bp_scss .= '}';
		}
		$bp_scss .= '}';

		return $bp_scss;

	}

	/**
	 * Create SCSS vars from active Elementor Breakpoints array.
	 *
	 * @return string $bp_scss
	 */
	public function create_breakpoint_vars() {

		$active_bps = $this->elementor_active_breakpoints();

		// Create flex-grid breakpoints variables.
		if ( is_array( $active_bps ) ) {
			$last    = array_key_last( $active_bps );
			$bp_scss = '$fg-breakpoints: (';
			foreach ( $active_bps as $bp_name => $bp_config ) {
				$comma    = $bp_name === $last ? '' : ',';
				$bp_scss .= '(' . $bp_name . ', ' . $bp_config['value'] . 'px, ' . $bp_config['direction'] . ')' . $comma;
			}
			$bp_scss .= ') !default;';

			return $bp_scss;
		}

	}

	/**
	 * SCSS code
	 *
	 * Get all the SCSS, starting with mixins and vars created using Elementor
	 * breakpoint settings.
	 *
	 * @return $content
	 */
	private function get_scss() {

		// SCSS with breakpoint mixin, generated from Elementor settings.
		$content = $this->create_breakpoints_mixin();
		// SCSS with breakpoint vars (for flex-grid).
		$content .= $this->create_breakpoint_vars();

		// SCSS from .scss file(s).
		if ( $this->filesys->file_exists( $this->scss_file_path ) ) {
			$content .= $this->filesys->get_contents( $this->scss_file_path );
		}

		return $content;
	}

	/**
	 * Before content on Elementor Frontend.
	 *
	 * @return void
	 */
	public function before_builder_content() {}

}

$micemade_css = new Micemade_Elements_CSS();
