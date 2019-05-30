<?php
/**
 * Plugin activation checks
 *
 * @since 0.0.1
 * @package WordPress
 * @subpackage Micemade Elements
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If WOOCOMMERCE activated.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && defined( 'WC_PLUGIN_FILE' ) ) {
	define( 'MICEMADE_ELEMENTS_WOO_ACTIVE', true );
} else {
	define( 'MICEMADE_ELEMENTS_WOO_ACTIVE', false );
}
// if YITH WC WISHLIST activated.
if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'MICEMADE_ELEMENTS_WISHLIST_ACTIVE', true );
} else {
	define( 'MICEMADE_ELEMENTS_WISHLIST_ACTIVE', false );
}

// If WPML activated.
if ( in_array( 'sitepress-multilingual-cms/sitepress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'MICEMADE_ELEMENTS_WPML_ON', true );
} else {
	define( 'MICEMADE_ELEMENTS_WPML_ON', false );
}

// if REV. SLIDER activated:
if ( in_array( 'revslider/revslider.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'MICEMADE_ELEMENTS_REVSLIDER_ON', true );
} else {
	define( 'MICEMADE_ELEMENTS_REVSLIDER_ON', false );
}

// if CF7 activated:
if ( in_array( 'contact-form-7/wp-contact-form-7.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'MICEMADE_ELEMENTS_CF7_ON', true );
} else {
	define( 'MICEMADE_ELEMENTS_CF7_ON', false );
}

// if MailChimp for WP activated:
if ( in_array( 'mailchimp-for-wp/mailchimp-for-wp.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'MICEMADE_ELEMENTS_MC4WP_ON', true );
} else {
	define( 'MICEMADE_ELEMENTS_MC4WP_ON', false );
}
