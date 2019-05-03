( function($) {
	"use strict";

	$(document).ready(function() {
	
		// Example of Elementor editor JS hooks
		// run code when "Micemade WC categories" settings are opened
		elementor.hooks.addAction( 'panel/open_editor/widget/micemade-wc-products-slider', function( panel, model, view ) {
			console.log("Micemade WC Products Slider");
		} );

	} );

	
} )( jQuery );