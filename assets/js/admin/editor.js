( function($) {
	"use strict";

	$(document).ready(function() {
	
		// Example of Elementor editor JS hooks
		// Run code when "Micemade WC Products Slider" settings are opened
		elementor.hooks.addAction( 'panel/open_editor/widget/micemade-wc-products-slider', function( panel, model, view ) {
			console.log('Settings for MM Products Slider accessed');
		} );

	} );

	
} )( jQuery );