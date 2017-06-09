//jQuery.noConflict();
( function( $ ) {
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
	
	( function( $ ){
			
			window.micemade_elements_swiper = function() {
				
				var swiperContainer = $( '.swiper-container' );
				
				swiperContainer.each( function() {
					
					var $_this		= $(this),
						config		= $_this.find('.slider-config'),
						pps			= config.data('pps'),
						ppst		= config.data('ppst'),
						ppsm		= config.data('ppsm'),
						space		= config.data('space'),
						pagin		= config.data('pagin'),
						autoplay	= config.data('autoplay');
					
					
					var swiper = new Swiper( $_this, {
						pagination: '.swiper-pagination',
						//effect: 'flip',
						//paginationClickable: true,
						slidesPerView: pps,
						paginationClickable: true,
						spaceBetween: space,
						grabCursor: true,
						pagination: '.swiper-pagination',
						paginationType: pagin,
						autoplay: autoplay,
						autoplayDisableOnInteraction: true,
						//centeredSlides: true,
						//loop: true,
						nextButton: '.swiper-button-next',
						prevButton: '.swiper-button-prev',
						breakpoints: {
					
							768: {
								slidesPerView: ppst,
								//spaceBetween: 30
							},
							480: {
								slidesPerView: ppsm,
								//spaceBetween: 20
							},

						}
					});	
					
				});
			};
					
		})( jQuery );
	
	$(document).ready(function() {
	
		/**
		 * AJAX LOADING POSTS
		 *
		 */
		var $posts_holder	= $('.micemade-elements-load-more');
			
		$posts_holder.each( function() {
			
			var $_posts_holder = $(this);
			
			var	$loader		= $_posts_holder.next('.micemade-elements_more-posts-wrap').find('.more_posts'),
				$settings	= $_posts_holder.find('.posts-grid-settings'),
				ppp			= $settings.data('ppp'),
				categories	= $settings.data('categories'),
				style		= $settings.data('style'),
				img_format	= $settings.data('img_format'),
				excerpt		= $settings.data('excerpt'),
				meta		= $settings.data('meta'),
				css_class	= $settings.data('css_class'),
				grid		= $settings.data('grid'),
				startoffset	= $settings.data('startoffset'),
				offset		= $_posts_holder.find('.post').length;
			 
			$loader.on( 'click', load_ajax_posts );
			 
			function load_ajax_posts(e) {
						
				e.preventDefault();
				
				if ( !($loader.hasClass('post_loading_loader') || $loader.hasClass('no_more_posts')) ) {
					
					$.ajax({
						type: 'POST',
						dataType: 'html',
						url: micemadeJsLocalize.ajaxurl,
						data: {
							'ppp': ppp,
							'categories': categories,
							'style': style,
							'img_format': img_format,
							'excerpt': excerpt,
							'meta': meta,
							'css_class': css_class,
							'grid': grid,
							'offset': offset + startoffset,
							'action': 'micemade_elements_more_post_ajax'
						},
						beforeSend : function () {
							$loader.addClass('post_loading_loader').html(micemadeJsLocalize.loadingposts);
						},
						success: function (data) {
							
							var $data = $(data);
							if ($data.length) {
								var $newElements = $data.css({ opacity: 0 });
								$_posts_holder.append($newElements);
								$newElements.animate({ opacity: 1 });
								$loader.removeClass('post_loading_loader').html(micemadeJsLocalize.loadmore);
							} else {
								$loader.removeClass('post_loading_loader').addClass('no_more_posts disabled').html(micemadeJsLocalize.noposts);
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

		
		}); // end $posts_holder.each
		/* 
		var swiper = new Swiper('.swiper-container', {
			pagination: '.swiper-pagination',
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
			paginationClickable: true,
			spaceBetween: 30,
			centeredSlides: true,
			autoplay: 2500,
			autoplayDisableOnInteraction: false
		});
		 */
		

	});
	
})(jQuery);