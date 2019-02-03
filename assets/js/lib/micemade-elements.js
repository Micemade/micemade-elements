jQuery.noConflict();
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
					if( $.waypoints ) {
						$.waypoints('refresh');
					}
				});
			
			});
		};
		
	})( jQuery );
	
	/**
	 *  SLICK SLIDER
	 */
	( function( $ ){
			
		window.micemade_elements_slider = function( rootElement ) {
			
			var root = rootElement || $('body');

			console.log( root );

			var sliderContainers = root.find( '.slick-slider' );
		
			sliderContainers.each( function(index, element) {
				
				var $_this   = $(this),
					config   = $_this.parent().find('.slider-config'),
					pps      = config.data('pps'),
					ppst     = config.data('ppst'),
					ppsm     = config.data('ppsm'),
					space    = config.data('space'),
					pagin    = config.data('pagin'),
					autoPlay = config.data('autoplay'),
					loop     = config.data('loop');
				
				$_this.not(".slick-initialized").slick({
					autoplay: autoPlay,
					infinite: loop,
					//centerMode: true,
					//centerPadding: '60px',
					slidesToShow: parseInt(pps),
					settings: "unslick",
					responsive: [
						{
							breakpoint: 1025,
							settings: {
								arrows: true,
								//centerMode: true,
								//centerPadding: space + 'px',
								slidesToShow: parseInt(ppsm)
							}
						},
						{
							breakpoint: 769,
							settings: {
								arrows: false,
								//centerMode: true,
								//centerPadding: space + 'px',
								slidesToShow: parseInt(ppst)
							}
						}
						
					]
				});

			});

		};

	})( jQuery );
	
	
	/**
	 *  SWIPER SLIDER
	 */
	( function( $ ){
			
		window.micemade_elements_swiper = function( rootElement ) {
			
			var root = $('body');
			if( rootElement ) {
				root = $( rootElement );
			}

			var swiperContainers = root.find( '.swiper-container' );

			swiperContainers.each( function(index, element) {
				
				var $_this   = $(this),
					config   = $_this.find('.slider-config'),
					pps      = config.data('pps'),
					ppst     = config.data('ppst'),
					ppsm     = config.data('ppsm'),
					space    = config.data('space'),
					pagin    = config.data('pagin'),
					autoplay = config.data('autoplay'),
					loop     = config.data('loop');

				var mainSwiperArgs = {
					effect: 'slide',
					slidesPerView: pps,
					spaceBetween: space,
					grabCursor: true,
					loop: loop,
					pagination: {
						el: '.swiper-pagination',
						type: pagin,
						clickable: true
					},
					navigation: {
						prevEl: $_this.find('.swiper-button-prev')[0],
						nextEl: $_this.find('.swiper-button-next')[0]
					},
					breakpoints: {
						768: {
							slidesPerView: ppst,
						},
						480: {
							slidesPerView: ppsm,
						},

					}
				}
				// If autoplay is enabled.
				var autoplayArgs = {}
				if( autoplay != '0' ) {
					var autoplayArgs = {
						autoplay: {
							delay: autoplay
						}
					}
				}
				// Join arg objects.
				var swiperArgs = $.extend( mainSwiperArgs, autoplayArgs );
				// Initialize Swiper.
				var swiper = new Swiper( $_this, swiperArgs );

				//swiper.on('init', function() { /* do something */});
				//swiper.init();

			});

		};

	})( jQuery );
	
	( function( $ ){
		/**
		 * Make ID - create random ID
		 */
		window.mmMakeId = function mmMakeId() {
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

			for( var i=0; i < 5; i++ )
				text += possible.charAt(Math.floor(Math.random() * possible.length));

			return text;
		}
	})( jQuery );

	( function( $ ) {
		/**
		 * Micemade Tabs - display WC products in tabs
		 */
		window.initMicemadeElementsTabs =function micemadeElementsTabs() {
			
			var tabs = $('.micemade-elements_tabs');
			tabs.each( function(){
				
				var $_Tabs = $(this); // Single tabs holder
				
				var tabsWrapper = $_Tabs.find('.tabs-wrapper'),
					tabTitles   = tabsWrapper.find('.tab-title'),
					tabsContent = $_Tabs.find('.tabs-content-wrapper'),
					content     = tabsContent.find('.tab-content');

				// clear old random "id"'s
				// "randomid" - unique identifier for tab elements
				tabsWrapper.data('randomid',null);
				
				// create new random "id"
				var randId = window.mmMakeId();
				tabsWrapper.data('randomid', randId );
				
				//tabTitles.click( function(event) {
				$( document ).on( 'click', '.tab-title', function(event) {

					if( tabsWrapper.data('randomid') !== randId ) {
						return;
					}

					$(this).addClass('active');
					$(this).siblings().removeClass('active');
					
					// Hide inactive tab titles and show active one and content
					var tab = $(this).data('tab');
					content.not('.tab-'+ tab).css('display', 'none').removeClass('active');
					tabsContent.find('.tab-' + tab).fadeIn().addClass('active');
				});
				
			});
		}
	})( jQuery );

	( function( $ ){
		/**
		 * Detect Micemade Elements widgets when in viewport
		 */
		window.micemadeInViewport = function micemadeInViewportFunc() {
			$('.mm-enter-animate').whenInViewport( function( $paragraph ) {
				var animType = $paragraph.data('anim');
				var delay = $paragraph.data('delay');
				setTimeout(
					function() {
						$paragraph.addClass( animType ).addClass( 'anim-done' );
					}
					, delay
				);
				
			});
		}
	})( jQuery );

	( function( $ ){
		window.initMicemadeMenuNav = function micemadeMenuNav() {
			
			var navMenuObj = $( '.micemade-elements-nav-menu' );
			navMenuObj.each( function() {
				
				// Start Smartmenus plugin
				var $_this = $(this).smartmenus({
					showTimeout: 0,
					hideDuration: 0
				});

				jQuery.SmartMenus.prototype.isCSSOn = function() {
					return true;
				};
			});
		}
	})( jQuery );


	( function( $ ){
		window.initMicemadeElements = function() {
			
			var startMicemadeElementsTabs  = window.initMicemadeElementsTabs();
			var startMicemadeElementSwiper = window.micemade_elements_swiper();
			var startMicemadeInViewport    = window.micemadeInViewport();
			var startMicemadeMenuNav       = window.initMicemadeMenuNav();
			var startMicemadeMegaMenuWatch = window.megaMenuWatch();
		}

	})( jQuery );

	( function( $ ){
		
		window.megaMenuWatch = function() {
			
			var mutationObserver = new MutationObserver( function(mutations) {
				mutations.forEach(function(mutation) {
					var target = mutation.target,
						name = mutation.attributeName,
						value = target.getAttribute(name);
					
					if( value == 'true' ) {
						var restartMicemadeElementSwiper = window.micemade_elements_swiper('.mega-menu');
					}else{
						mutationObserver.disconnect();
					}
				});
			});
			
			var megaMenus = document.getElementsByClassName('mega-menu');
			Array.prototype.forEach.call ( megaMenus, function(el) {
				mutationObserver.observe( el, {
					attributes: true,
					attributeFilter: ['aria-expanded'],
				});
			})
		}

	})( jQuery );

	//( function( $ ){})( jQuery );
	
	$(document).ready(function() {
	
		
		var initMicemadeElements = window.initMicemadeElements();
		
		/**
		 * Test for mobile broswers
		 */
		var testMobile;
		var isMobile = {
			Android: function() {
				return navigator.userAgent.match(/Android/i);
			},
			BlackBerry: function() {
				return navigator.userAgent.match(/BlackBerry/i);
			},
			iOS: function() {
				return navigator.userAgent.match(/iPhone|iPad|iPod/i);
			},
			Opera: function() {
				return navigator.userAgent.match(/Opera Mini/i);
			},
			Windows: function() {
				return navigator.userAgent.match(/IEMobile/i);
			},
			any: function() {
				return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
			}
		};

		/**
		 *  Add class for mobile browsers
		 */
		if( isMobile.any() ) {
			$('body').addClass( 'micemade-elements-is-mobile' );
		}
		
		/**
		 *  Parallax for all "parallax" sections/columns selectors
		 */
		var parallax = function() {
			testMobile = isMobile.any();
			if( testMobile == null ) {
				$(".parallax").parallax("50%", 0.3);
			}
		};

		// Dom Ready
		$( function() {
			parallax();
		});

		/**
		 * AJAX LOADING POSTS
		 *
		 */
		var $posts_holder = $('.micemade-elements-load-more');
			
		$posts_holder.each( function() {
			
			var $_posts_holder = $(this);
			
			var	$loader       = $_posts_holder.next('.micemade-elements_more-posts-wrap').find('.more_posts'),
				postoptions   = $_posts_holder.find('.posts-grid-settings' ).data( 'postoptions' ),
				ppp           = postoptions.ppp,
				sticky        = postoptions.sticky,
				categories    = postoptions.categories,
				style         = postoptions.style,
				show_thumb    = postoptions.show_thumb,
				img_format    = postoptions.img_format,
				excerpt       = postoptions.excerpt,
				excerpt_limit = postoptions.excerpt,
				meta          = postoptions.meta,
				meta_ordering = postoptions.meta_ordering,
				css_class     = postoptions.css_class,
				grid          = postoptions.grid,
				startoffset   = postoptions.startoffset,
				offset        = $_posts_holder.find('.post').length;
			
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
							'sticky': sticky,
							'categories': categories,
							'style': style,
							'show_thumb': show_thumb,
							'img_format': img_format,
							'excerpt': excerpt,
							'excerpt_limit': excerpt_limit,
							'meta': meta,
							'meta_ordering': meta_ordering,
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
						},
					});
					
				}
				offset += ppp;
				return false;
			}

		
		}); // end $posts_holder.each
		
		
		// Restart micemadeInViewport when element changes
		// Elementor editor only
		var isEditor = $('.elementor-editor-active');
		if( isEditor.length ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-categories.default',
			function( $scope ) {
				var restartVieportAfterReload = window.micemadeInViewport();
			} );


		}
		

	}); // end doc ready
	
})(jQuery);