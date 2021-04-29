jQuery.noConflict();
( function( $ ) {
	"use strict";
	
	var isEditMode = false;
	
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


	( function( $ ){
		/**
		 * Detect Micemade Elements widgets when in viewport
		 */
		window.micemadeInViewport = function micemadeInViewportFunc() {
			$('.mm-enter-animate').whenInViewport( function( $item ) {

				var settings = $item.data('settings'),
					animType = $item.data('anim'),
					delay = $item.data('delay');

				setTimeout(
					function() {
						$item.addClass( animType ).addClass( 'anim-done' );
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
		
		window.megaMenuWatch = function() {
			
			var mutationObserver = new MutationObserver( function(mutations) {
				mutations.forEach(function(mutation) {
					var target = mutation.target,
						name = mutation.attributeName,
						value = target.getAttribute(name);
					
					if( value == 'true' ) {
						// var restartMicemadeElementSwiper = micemadeElementsSwiper('.mega-menu');
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

	/**
	 * Micemade Tabs - display WC products in tabs
	 */
	var micemadeElementsTabs = function() {
		
		var tabs = $('.micemade-elements_tabs');
		if( ! tabs.length ) { return; }
		tabs.each( function(){
			
			var $_Tabs = $(this); // Single tabs holder
			
			var tabsWrapper = $_Tabs.find('.tabs-wrapper'),
				tabTitles   = tabsWrapper.find('.tab-title'),
				tabsContent = $_Tabs.find('.tabs-content-wrapper'),
				content     = tabsContent.find('.tab-content');
			
			tabTitles.on( 'click touchstart', function(event) {

				$(this).addClass('active');
				$(this).siblings().removeClass('active');
				
				// Hide inactive tab titles and show active one and content
				var tab = $(this).data('tab');
				content.not('.tab-'+ tab).css('display', 'none').removeClass('active');
				tabsContent.find('.tab-' + tab).fadeIn().addClass('active');
			});
			
		});
	}

	/**
	 * Micemade WC Categories Menu.
	 */
	var wcCategoriesMenu = function() {
		
		var wcCatMenuToggle = $( '.micemade-elements_wc_cat_menu' ).find( '.sub-toggler' );
		wcCatMenuToggle.each( function() {

			var $_this   = $(this),
				liItem   = $_this.parent().parent(),
				submenu  = liItem.find(".children"),
				siblings = liItem.siblings().find(".sub-toggler");
	
			$_this.on( 'click', function(e) {
				
				e.preventDefault();
	
				siblings.removeClass("activeparent");
				siblings.parent().next(".children").removeClass("active");
	
				if( ! $_this.hasClass("activeparent") ) {
					submenu.addClass("active");
					$_this.addClass("activeparent");
	
				}else{
					submenu.removeClass("active").delay(200);
					$_this.removeClass("activeparent");
				}
	
			});
		});

	}

	var mmWcGalleryJS = function() {
		//console.log( wc_single_product_params );
		
		// wc_single_product_params and wc_product_gallery are required to continue.
		if ( typeof wc_single_product_params === 'undefined' || ! jQuery().wc_product_gallery ) {
			return false;
		}
		

		$( '.woocommerce-product-gallery' ).each( function() {
			$( this ).trigger( 'wc-product-gallery-before-init', [ this, wc_single_product_params ] );
			$( this ).wc_product_gallery( wc_single_product_params );
			$( this ).trigger( 'wc-product-gallery-after-init', [ this, wc_single_product_params ] );
			console.log(this);
		} );
	}

	//( function( $ ){})( jQuery );

	// Hook into Elementor JS hooks.
	$( window ).on( 'elementor/frontend/init', function () {
		if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}

		// Start Micemade Products Tab
		elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-products-tabs.default', micemadeElementsTabs );

		// Start Micemade Product Categories Menu
		elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-cat-menu.default', wcCategoriesMenu );

		// Start Micemade Single product
		elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-single-product.default', mmWcGalleryJS );

		// Restart micemadeInViewport when element changes
		// Elementor editor only
		// var isEditor = $('.elementor-editor-active');
		// if( isEditor.length ) {
			
			elementorFrontend.hooks.addAction( 'frontend/element_ready/micemade-wc-categories.default',
			function( $scope ) {
				var restartVieportAfterReload = window.micemadeInViewport();
				
			} );
			
		//}

	});
	
	( function( $ ){
		window.initMicemadeElements = function() {
			
			var startMicemadeInViewport    = window.micemadeInViewport();
			var startMicemadeMenuNav       = window.initMicemadeMenuNav();
			var startMicemadeMegaMenuWatch = window.megaMenuWatch();

		}

	})( jQuery );

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
			
			var $loader       = $_posts_holder.next('.micemade-elements_more-posts-wrap').find('.more_posts'),
				postoptions   = $_posts_holder.find('.posts-grid-settings' ).data( 'postoptions' ),
				post_type     = postoptions.post_type,
				order         = postoptions.order,
				orderby       = postoptions.orderby,
				orderby_meta  = postoptions.orderby_meta,
				taxonomy      = postoptions.taxonomy,
				ppp           = postoptions.ppp,
				categories    = postoptions.categories,
				post__in      = postoptions.post__in,
				sticky        = postoptions.sticky,
				offset        = $_posts_holder.find('.post').length,
				startoffset   = postoptions.startoffset,
				style         = postoptions.style,
				show_thumb    = postoptions.show_thumb,
				img_format    = postoptions.img_format,
				excerpt       = postoptions.excerpt,
				excerpt_limit = postoptions.excerpt_limit,
				meta          = postoptions.meta,
				meta_ordering = postoptions.meta_ordering,
				css_class     = postoptions.css_class,
				grid          = postoptions.grid,
				cm_fields     = postoptions.cm_fields,
				elm_ordering  = postoptions.elm_ordering,
				if_readmore   = postoptions.if_readmore,
				readmore_text = postoptions.readmore_text;
			
			$loader.on( 'click', load_ajax_posts );

			function load_ajax_posts(e) {

				e.preventDefault();
				
				if ( !($loader.hasClass('post_loading_loader') || $loader.hasClass('no_more_posts')) ) {

					$.ajax({
						type: 'POST',
						dataType: 'html',
						url: micemadeJsLocalize.ajaxurl,
						data: {
							'post_type': post_type,
							'order': order,
							'orderby': orderby,
							'orderby_meta': orderby_meta,
							'taxonomy': taxonomy,
							'ppp': ppp,
							'categories': categories,
							'post__in': post__in,
							'sticky': sticky,
							'offset': offset + startoffset,
							'style': style,
							'show_thumb': show_thumb,
							'img_format': img_format,
							'excerpt': excerpt,
							'excerpt_limit': excerpt_limit,
							'meta': meta,
							'meta_ordering': meta_ordering,
							'css_class': css_class,
							'grid': grid,
							'cm_fields': cm_fields,
							'elm_ordering': elm_ordering,
							'if_readmore': if_readmore,
							'readmore_text': readmore_text,
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
		
		
		

		/**
		 * AJAX - QUICK VIEW.
		 *
		 */
		$(document).on("click", "a.mme-quick-view", function(e) {

			e.preventDefault();
			
			// QV button attributes.
			var qvLink  = $(this),
				prod_ID = qvLink.attr("data-id"),
				modalID = qvLink.closest( '.elementor-element' ).data( 'id' );
			
			// Append Quickview element to body.
			$( 'body' ).append( '<div class="micemade-elements-quickview" id="mmqv-' + modalID + '"><div class="mmqv-holder woocommerce"><div class="mmqv-loading"><i class="fa fa-spinner fa-spin"></i><span>' + micemadeJsLocalize.loadingposts + ' </span></div></div></div>' )
			
			// Vars to popuate QV HOLDER with product data.
			var lang           = qvLink.attr("data-lang"),
				qvOverlay      = $( '.micemade-elements-quickview' ),
				qvHolder       = $( '.mmqv-holder ' );

			// Show Quick view (with loader, first)
			qvOverlay.addClass('mmqv-active');

			var xhr = $.ajax({
			
				type: "POST",
				dataType: 'html',
				// contentType: "application/json; charset=utf-8",
				url: micemadeJsLocalize.ajaxurl,
				data: { 
					'action': 'mme_quick_view',
					productID: prod_ID,
					lang: lang
				},
				success: function( response ) {

					qvHolder.html( response );
					
					$( window ).delay(200).trigger( 'resize' );
					$( ".mmqv-wrapper" ).animate( { 'opacity': 1 },{ duration:1500 } );

					// Append X (close) button for remove action.
					qvHolder.append( '<div class="remove fa fa-times"></div>' );

					// Fade in the product images
					qvHolder.find( '.woocommerce-product-gallery' ).fadeTo( 0.2, 1 );
					
					// Remove Quick view with click on appended X.
					qvHolder.find( '.remove' ).on( 'click', function(e) {
						qvOverlay.removeClass( 'mmqv-active' );
						setTimeout( function() {
							$( '.micemade-elements-quickview' ).remove();
						} ,300 );
					});

					// Remove Quick view with click on overlay.
					qvOverlay.on( 'click', function(e) {
						if( e.target == this ) {
							$(this).removeClass( 'mmqv-active' );
							setTimeout( function() {
								$( '.micemade-elements-quickview' ).remove();
							} ,300 );
						}
					});

					// Load WC single product gallery JS scripts.
					mmWcGalleryJS();

					// Load single product JS and resize, center QV modal.
					mmAfterQVModal();

				},
				error: function( xhr, status, error ){
					var errorMessage = xhr.status + ': ' + xhr.statusText
					alert( micemadeJsLocalize.ajaxerror + ': ' + errorMessage );
				}
			});

		});

		function mmAfterQVModal(event, request, settings) {

			// MAIN vars.
			var qvHolder  = $( '.mmqv-holder' ),
				qvOverlay = $( '.micemade-elements-quickview' );

			// Get those WC variations forms to work ;).
			$( function() {
				if ( typeof wc_add_to_cart_variation_params !== "undefined" ) {
					$( ".variations_form" ).each( function() {
						$( this ).wc_variation_form().find(".variations select:eq(0)").change();
					});
				}
			});

			// QUICK VIEW WINDOW VERTICAL CENTER POSITION.
			$(window).resize(function() {
				
				var qvWrap      = qvHolder.find(".mmqv-wrapper"),
					qvWrap_h    = qvWrap.outerHeight(),
					qvOverlay_H = qvOverlay.outerHeight(true);
						
				var	qv_top   = (qvOverlay_H / 2) - (qvWrap_h/2);
				// if modal goes off the top.
				if ( qv_top <=  55 ) {
					qv_top = 75;
				}
				
				qvHolder.stop(true,false).animate({'top': qv_top },{ 
					duration:400,
					complete: function() {

						qvWrap.stop(true,false).animate({'opacity': 1 },{ duration:200 });
						qvHolder.stop(true,false).delay(200).animate({'height': qvWrap_h },
						{
							duration:400,
						});
					}
				});
					
			});
			// end resize.
			
			$( window ).on( "load", qvHolder, function(){
				$( window ).trigger( "resize" );
			});
			
			var imgLoad = qvHolder.find( "img" );
			
			imgLoad.on( "load",function() {
				$( window ).trigger( "resize" );
			});
			
		} // end mmAfterQVModal

		

	}); // end doc ready
	
})(jQuery);
