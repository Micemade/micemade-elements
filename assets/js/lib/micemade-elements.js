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
	
	/**
	 * AJAX LOADING POSTS
	 *
	 */
	var $content	= $('.micemade-elements_posts-grid'),
		$loader		= $content.next('.more_posts'),
		$settings	= $content.find('.posts-grid-settings'),
		ppp			= $settings.data('ppp'),
		categories	= $settings.data('categories'),
		img_format	= $settings.data('img_format'),
		excerpt		= $settings.data('excerpt'),
		meta		= $settings.data('meta'),
		css_class	= $settings.data('css_class'),
		grid		= $settings.data('grid'),
		offset		= $content.find('.post').length;
	 
	$loader.on( 'click', load_ajax_posts );
	 
	function load_ajax_posts(e) {
				
		e.preventDefault();
		
		if ( !($loader.hasClass('post_loading_loader') || $loader.hasClass('no_more_posts')) ) {
			
			$.ajax({
				type: 'POST',
				dataType: 'html',
				url: screenReaderText.ajaxurl,
				data: {
					'ppp': ppp,
					'categories': categories,
					'img_format': img_format,
					'excerpt': excerpt,
					'meta': meta,
					'css_class': css_class,
					'grid': grid,
					'offset': offset,
					'action': 'micemade_elements_more_post_ajax'
				},
				beforeSend : function () {
					$loader.addClass('post_loading_loader').html(screenReaderText.loadingposts);
				},
				success: function (data) {
					
					var $data = $(data);
					if ($data.length) {
						var $newElements = $data.css({ opacity: 0 });
						$content.append($newElements);
						$newElements.animate({ opacity: 1 });
						$loader.removeClass('post_loading_loader').html(screenReaderText.loadmore);
					} else {
						$loader.removeClass('post_loading_loader').addClass('no_more_posts disabled').html(screenReaderText.noposts);
					}
				},
				error : function (jqXHR, textStatus, errorThrown) {
					$loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
					console.log(jqXHR);
				},
			});
			
		}
		offset += ppp;
		return false;
	}

})(jQuery);