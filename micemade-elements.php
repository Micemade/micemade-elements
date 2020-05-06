<?php
/**
 * Plugin Name: Micemade Elements
 * Description: Extension plugin with custom elements for Elementor, created by Micemade. Elementor plugin required.
 * Plugin URI: https://github.com/Micemade/micemade-elements/
 * Version: 0.7.1
 * Author: micemade
 * Author URI: http://micemade.com
 * Text Domain: micemade-elements
 *
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define MICEMADE_ELEMENTS_PLUGIN_FILE.
if ( ! defined( 'MICEMADE_ELEMENTS_PLUGIN_FILE' ) ) {
	define( 'MICEMADE_ELEMENTS_PLUGIN_FILE', __FILE__ );
}

// Define plugin dir path.
if ( ! defined( 'MICEMADE_ELEMENTS_DIR' ) ) {
	define( 'MICEMADE_ELEMENTS_DIR', plugin_dir_path( __FILE__ ) );
}

// Define plugin url.
if ( ! defined( 'MICEMADE_ELEMENTS_URL' ) ) {
	define( 'MICEMADE_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );
}

// Define plugin includes dir.
if ( ! defined( 'MICEMADE_ELEMENTS_INCLUDES' ) ) {
	define( 'MICEMADE_ELEMENTS_INCLUDES', MICEMADE_ELEMENTS_DIR . '/includes/' );
}

// Define plugin slug or a basename.
if ( ! defined( 'MICEMADE_ELEMENTS_SLUG' ) ) {
	define( 'MICEMADE_ELEMENTS_SLUG', plugin_basename( __FILE__ ) );
}

// Define plugin version.
if ( ! defined( 'MICEMADE_ELEMENTS_VERSION' ) ) {
	define( 'MICEMADE_ELEMENTS_VERSION', '0.7.1' );
}

require_once MICEMADE_ELEMENTS_DIR . 'includes/class-micemade-elements.php';
