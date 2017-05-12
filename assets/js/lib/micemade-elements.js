(function( $ ) {
"use strict";
/**
 *	FILTER ITEMS (Isotope) :
 *	var function filterItems
 *	var arg container
 *	var arg filter
 */
(function( $ ){
	
	window.filterItems = function( container, filter ) {
		
		container.imagesLoaded( function(){
		// init Isotope
			container.isotope({
				itemSelector: '.item',
				layoutMode: 'masonry',
				containerStyle: {
					position: 'relative',
					overflow: 'hidden'
				},
				transitionDuration: '0.5s'
			});
			// filter items on button click
			if( filter ) {
				filter.on( 'click', 'a', function() {
					var filterValue = $(this).attr('data-filter');
					container.isotope({ filter: filterValue });
				});				
			}
			
			container.on( 'arrangeComplete', function() { 
				// $(document).foundation('equalizer', 'reflow');
				// $.waypoints('refresh');
				} );
		
		});
	};
	
})( jQuery );

})(jQuery);